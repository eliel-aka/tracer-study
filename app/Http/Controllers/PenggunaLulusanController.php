<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use App\Models\PenggunaLulusan;
use App\Models\Lulusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel;
use App\Exports\PenggunaLulusanExport;
use App\Imports\PenggunaLulusanImport;

class PenggunaLulusanController extends Controller
{
    public function index(Request $request)
    {
        $query = PenggunaLulusan::with('user:id,email');

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nip', 'like', '%' . $searchTerm . '%');
            });
        }

        $dataPenggunaLulusan = $query->paginate(10)->appends($request->query());

        return view('admin.views.pengguna_lulusan.index', compact('dataPenggunaLulusan'));
    }

    public function create()
    {
        return view('admin.views.pengguna_lulusan.create');
    }

    public function store(Request $request, PenggunaLulusan $penggunaLulusan)
    {
        // ----------------
        // VALIDASI MANUAL
        // ----------------
        $errors = [];

        if (empty(trim($request->nama))) {
            $errors['nama'] = 'Nama wajib diisi.';
        }

        if (empty(trim($request->nip))) {
            $errors['nip'] = 'NIP wajib diisi.';
        } elseif (PenggunaLulusan::where('nip', $request->nip)->where('id', '!=', $penggunaLulusan->id)->exists()) {
            $errors['nip'] = 'NIP sudah digunakan oleh pengguna lulusan lain, gunakan NIP yang berbeda.';
        }

        if (empty(trim($request->email))) {
            $errors['email'] = 'Email wajib diisi.';
        } elseif (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid.';
        } elseif (User::where('email', $request->email)->where('id', '!=', $penggunaLulusan->user_id)->exists()) {
            $errors['email'] = 'Email sudah terdaftar di sistem.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->withErrors($errors);
        }

        // -------------------------
        // PROSES SIMPAN
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
                'role'     => 'penggunaLulusan',
                'password' => bcrypt($password),
            ]);
            $user->assignRole('penggunaLulusan');

            PenggunaLulusan::create([
                'user_id'       => $user->id,
                'nama'          => $request->nama,
                'nip'           => $request->nip,
                'email'         => $request->email,
                'jabatan'       => $request->jabatan,
                'satuan_kerja'  => $request->satuan_kerja,
                'unit_kerja'    => $request->unit_kerja,
                'no_hp'         => $request->no_hp,
            ]);

            DB::commit();

            $emailSent = $this->sendCredentialEmail($request->nama, $request->email, $password);

            if (!$emailSent) {
                return redirect()->route('admin.penggunaLulusan.index')
                    ->with('warning', 'Data Pengguna Lulusan berhasil ditambahkan, tetapi email kredensial gagal dikirim.');
            }

            return redirect()->route('admin.penggunaLulusan.index')
                ->with('success', 'Data Pengguna Lulusan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function show(PenggunaLulusan $penggunaLulusan)
    {
        //
    }

    public function edit(PenggunaLulusan $penggunaLulusan)
    {
        return view('admin.views.pengguna_lulusan.edit', compact('penggunaLulusan'));
    }

    public function update(Request $request, PenggunaLulusan $penggunaLulusan)
    {
        // -------------------------
        // VALIDASI MANUAL
        // -------------------------
        $errors = [];

        if (empty(trim($request->nama))) {
            $errors['nama'] = 'Nama wajib diisi.';
        }

        if (empty(trim($request->nip))) {
            $errors['nip'] = 'NIP wajib diisi.';
        } elseif (PenggunaLulusan::where('nip', $request->nip)->where('id', '!=', $penggunaLulusan->id)->exists()) {
            $errors['nip'] = 'NIP sudah digunakan oleh pengguna lulusan lain, gunakan NIP yang berbeda.';
        }

        if (empty(trim($request->email))) {
            $errors['email'] = 'Email wajib diisi.';
        } elseif (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid.';
        } elseif (User::where('email', $request->email)
            ->where('id', '!=', $penggunaLulusan->user_id)->exists()) {
            $errors['email'] = 'Email sudah digunakan oleh pengguna lain.';
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->withErrors($errors);
        }

        // -------------------------
        // PROSES UPDATE
        // -------------------------
        try {
            DB::beginTransaction();

            $penggunaLulusan->user->update([
                'name'  => $request->nama,
                'email' => $request->email,
            ]);

            $penggunaLulusan->update([
                'nama'          => $request->nama,
                'nip'           => $request->nip,
                'email'         => $request->email,
                'jabatan'       => $request->jabatan,
                'satuan_kerja'  => $request->satuan_kerja,
                'unit_kerja'    => $request->unit_kerja,
                'no_hp'         => $request->no_hp,
            ]);

            DB::commit();

            return redirect()->route('admin.penggunaLulusan.index')
                ->with('success', 'Data Pengguna Lulusan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function destroy(PenggunaLulusan $penggunaLulusan)
    {
        $penggunaLulusan->user->delete();
        $penggunaLulusan->delete();

        return redirect()->route('admin.pengguna_lulusan.index')
            ->with('success', 'Pengguna Lulusan deleted successfully.');
    }

    public function export(Excel $excel)
    {
        return $excel->download(new PenggunaLulusanExport, 'penggunaLulusan.xlsx');
    }

    public function import(Request $request, Excel $excel)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
            ]);

            $excel->import(new PenggunaLulusanImport, $request->file('file'));

            return redirect()->route('admin.penggunaLulusan.index')
                ->with('success', 'Pengguna Lulusan imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.penggunaLulusan.index')
                ->with('error', 'Failed to import pengguna lulusan.');
        }
    }

    public function details($id)
    {
        $penggunaLulusan = PenggunaLulusan::findOrFail($id);

        $lulusan = Lulusan::where(
            'nip_pengguna_lulusan',
            $penggunaLulusan->nip
        )->paginate(10);

        return view('admin.views.pengguna_lulusan.details',
            compact('penggunaLulusan', 'lulusan')
        );
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
            Log::error('Gagal mengirim email kredensial pengguna lulusan.', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}