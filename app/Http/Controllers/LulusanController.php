<?php

namespace App\Http\Controllers;

use App\Exports\LulusanExport;
use App\Imports\LulusanImport;
use App\Mail\SendEmail;
use App\Models\Lulusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel;

class LulusanController extends Controller
{
    public function index(Request $request)
    {
        $query = Lulusan::with('user:id,email');

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nip', 'like', '%' . $searchTerm . '%');
            });
        }

        $daftarLulusan = $query->paginate(10)->appends($request->query());

        return view('admin.views.lulusan.index', compact('daftarLulusan'));
    }

    public function create()
    {
        return view('admin.views.lulusan.create');
    }

    public function store(Request $request)
    {
        // -------------------------
        // Validasi field required & format
        // -------------------------
        $errors = [];

        if (empty(trim($request->nama))) {
            $errors['nama'] = 'Nama wajib diisi.';
        }

        if (empty(trim($request->nip))) {
            $errors['nip'] = 'NIP wajib diisi.';
        } elseif (Lulusan::where('nip', $request->nip)->exists()) {
            $errors['nip'] = 'NIP sudah terdaftar di sistem, gunakan NIP lain.';
        }

        if (empty(trim($request->email))) {
            $errors['email'] = 'Email wajib diisi.';
        } elseif (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid.';
        } elseif (User::where('email', $request->email)->exists()) {
            $errors['email'] = 'Email sudah terdaftar di sistem, gunakan email lain.';
        }

        if (empty(trim($request->prodi))) {
            $errors['prodi'] = 'Program Studi wajib diisi.';
        }

        if (empty(trim($request->tanggal_lahir))) {
            $errors['tanggal_lahir'] = 'Tanggal Lahir wajib diisi.';
        } else {
            $d = \DateTime::createFromFormat('Y-m-d', $request->tanggal_lahir);
            if (!$d || $d->format('Y-m-d') !== $request->tanggal_lahir) {
                $errors['tanggal_lahir'] = 'Format tanggal lahir tidak valid (YYYY-MM-DD).';
            }
        }

        if (empty(trim($request->tahun_lulus))) {
            $errors['tahun_lulus'] = 'Tahun Lulus wajib diisi.';
        }

        if (empty(trim($request->nip_pengguna_lulusan))) {
            $errors['nip_pengguna_lulusan'] = 'NIP Pengguna Lulusan wajib diisi.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->withErrors($errors);
        }

        // -------------------------
        // Proses simpan data
        // -------------------------
        try {
            $nipLast2     = substr($request->nip, -2);
            $namaLast2    = strtolower(substr(preg_replace('/[^A-Za-z]/', '', $request->nama), -2));
            $tanggalLahir = str_replace('-', '', $request->tanggal_lahir ?? '01');
            $tanggalLast2 = substr($tanggalLahir, -2);
            $password     = $nipLast2 . $namaLast2 . $tanggalLast2;

            DB::beginTransaction();

            $user = User::create([
                'name'     => $request->nama,
                'email'    => $request->email,
                'role'     => 'lulusan',
                'password' => bcrypt($password),
            ]);
            $user->assignRole('lulusan');

            Lulusan::create([
                'user_id'              => $user->id,
                'nama'                 => $request->nama,
                'nip'                  => $request->nip,
                'email'                => $request->email,
                'prodi'                => $request->prodi,
                'jabatan'              => $request->jabatan,
                'satuan_kerja'         => $request->satuan_kerja,
                'unit_kerja'           => $request->unit_kerja,
                'no_hp'                => $request->no_hp,
                'nip_pengguna_lulusan' => $request->nip_pengguna_lulusan,
                'tanggal_lahir'        => $request->tanggal_lahir,
                'tahun_lulus'          => $request->tahun_lulus,
            ]);

            DB::commit();

            $emailSent = $this->sendCredentialEmail($request->nama, $request->email, $password);

            if (!$emailSent) {
                return redirect()->route('admin.lulusan.index')
                    ->with('warning', 'Data Lulusan berhasil ditambahkan, tetapi email kredensial gagal dikirim.');
            }

            return redirect()->route('admin.lulusan.index')->with('success', 'Data Lulusan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function show(Lulusan $lulusan)
    {
        //
    }

    public function edit(Lulusan $lulusan)
    {
        return view('admin.views.lulusan.edit', compact('lulusan'));
    }

    public function update(Request $request, Lulusan $lulusan)
    {
        // -------------------------
        // Validasi field required & format
        // -------------------------
        $errors = [];

        if (empty(trim($request->nama))) {
            $errors['nama'] = 'Nama wajib diisi.';
        }

        if (empty(trim($request->nip))) {
            $errors['nip'] = 'NIP wajib diisi.';
        } elseif (Lulusan::where('nip', $request->nip)->where('id', '!=', $lulusan->id)->exists()) {
            $errors['nip'] = 'NIP sudah digunakan oleh lulusan lain, gunakan NIP yang berbeda.';
        }

        if (empty(trim($request->email))) {
            $errors['email'] = 'Email wajib diisi.';
        } elseif (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid.';
        } elseif (User::where('email', $request->email)->where('id', '!=', $lulusan->user_id)->exists()) {
            $errors['email'] = 'Email sudah digunakan oleh pengguna lain, gunakan email yang berbeda.';
        }

        if (empty(trim($request->prodi))) {
            $errors['prodi'] = 'Program Studi wajib diisi.';
        }

        if (empty(trim($request->tanggal_lahir))) {
            $errors['tanggal_lahir'] = 'Tanggal Lahir wajib diisi.';
        } else {
            $d = \DateTime::createFromFormat('Y-m-d', $request->tanggal_lahir);
            if (!$d || $d->format('Y-m-d') !== $request->tanggal_lahir) {
                $errors['tanggal_lahir'] = 'Format tanggal lahir tidak valid (YYYY-MM-DD).';
            }
        }

        if (empty(trim($request->tahun_lulus))) {
            $errors['tahun_lulus'] = 'Tahun Lulus wajib diisi.';
        }

        if (empty(trim($request->nip_pengguna_lulusan))) {
            $errors['nip_pengguna_lulusan'] = 'NIP Pengguna Lulusan wajib diisi.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->withErrors($errors);
        }

        // -------------------------
        // Proses update data
        // -------------------------
        try {
            DB::beginTransaction();

            $lulusan->user->update([
                'name'  => $request->nama,
                'email' => $request->email,
            ]);

            $lulusan->update([
                'nama'                 => $request->nama,
                'nip'                  => $request->nip,
                'email'                => $request->email,
                'prodi'                => $request->prodi,
                'jabatan'              => $request->jabatan,
                'satuan_kerja'         => $request->satuan_kerja,
                'unit_kerja'           => $request->unit_kerja,
                'no_hp'                => $request->no_hp,
                'nip_pengguna_lulusan' => $request->nip_pengguna_lulusan,
                'tanggal_lahir'        => $request->tanggal_lahir,
                'tahun_lulus'          => $request->tahun_lulus,
            ]);

            DB::commit();

            return redirect()->route('admin.lulusan.index')->with('success', 'Data Lulusan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function destroy(Lulusan $lulusan)
    {
        $lulusan->user->delete();
        $lulusan->delete();

        return redirect()->route('admin.lulusan.index')->with('success', 'Lulusan deleted successfully.');
    }

    public function export(Excel $excel)
    {
        return $excel->download(new LulusanExport, 'lulusan.xlsx');
    }

    public function import(Request $request, Excel $excel)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
            ]);

            $excel->import(new LulusanImport, $request->file('file'));

            return redirect()->route('admin.lulusan.index')->with('success', 'Lulusan imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.lulusan.index')->with('error', 'Failed to import lulusan.');
        }
    }

    private function sendCredentialEmail(string $name, string $email, string $password): bool
    {
        try {
            $emailData = [
                'nama' => $name,
                'email' => $email,
                'password' => $password,
                'link' => route('login'),
                'subject' => 'Akun Tracer Study Anda Telah Dibuat',
                'body' => '<h2>Halo ' . e($name) . '!</h2><p>Akun Tracer Study Anda telah berhasil dibuat/diaktifkan kembali.</p><p>Berikut kredensial login terbaru Anda:</p><p><strong>Email:</strong> ' . e($email) . '<br><strong>Password:</strong> ' . e($password) . '</p><p>Silakan login dan segera ubah password setelah berhasil masuk.</p>',
            ];

            Mail::to($email)->send(new SendEmail($emailData));
            return true;
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email kredensial lulusan.', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}