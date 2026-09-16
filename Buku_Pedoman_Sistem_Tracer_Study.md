# BUKU PEDOMAN SISTEM TRACER STUDY

---

**Judul Sistem:** Sistem Informasi Tracer Study — Politeknik Statistika STIS

**Versi Dokumen:** 1.0

**Tanggal Penyusunan:** Juni 2026

**Nama Instansi:** Politeknik Statistika STIS — Unit Sistem Penjaminan Mutu (SPM)

**Logo:**

![Logo Politeknik Statistika STIS — Sisipkan logo instansi di sini](placeholder-logo.png)

---

## Kata Pengantar

Puji syukur kehadirat Tuhan Yang Maha Esa, atas berkat dan rahmat-Nya Buku Pedoman Sistem Informasi Tracer Study Politeknik Statistika STIS ini dapat diselesaikan dengan baik. Dokumen ini disusun sebagai panduan operasional terpadu bagi seluruh pemangku kepentingan yang terlibat dalam pelaksanaan kegiatan Tracer Study, mulai dari Administrator sistem, Supervisor, Lulusan (alumni), hingga Pengguna Lulusan (atasan/instansi tempat alumni bekerja).

Buku pedoman ini dirancang agar seorang pengguna yang sama sekali belum pernah menggunakan sistem dapat langsung mengoperasikan seluruh fitur yang tersedia tanpa memerlukan bantuan tambahan. Setiap langkah operasional dijelaskan secara rinci, disertai penjelasan validasi sistem, kemungkinan error, serta solusi penanganannya.

Kami menyadari bahwa dokumen ini masih dapat disempurnakan seiring perkembangan sistem. Oleh karena itu, masukan dan saran dari seluruh pengguna sangat kami harapkan demi peningkatan kualitas sistem dan dokumentasi ini di masa mendatang.

**[PERLU DIISI: Nama dan Jabatan Pejabat Berwenang]**

---

## Riwayat Revisi Dokumen

| Versi | Tanggal | Perubahan | Penanggung Jawab |
|---|---|---|---|
| 1.0 | Juni 2026 | Rilis pertama dokumen — mencakup seluruh modul sistem | Unit SPM, Politeknik Statistika STIS |

---

## Daftar Isi

*(Daftar isi akan di-generate otomatis setelah dokumen dikonversi ke format DOCX melalui fitur Table of Contents pada Microsoft Word)*

---

## Daftar Gambar

*(Daftar gambar akan di-generate otomatis setelah dokumen dikonversi ke format DOCX. Setiap placeholder gambar bertanda **[SCREENSHOT: ...]** perlu diganti dengan tangkapan layar aktual dari sistem.)*

---

## Daftar Tabel

*(Daftar tabel akan di-generate otomatis setelah dokumen dikonversi ke format DOCX.)*

---

# BAB 1 PENDAHULUAN

## 1.1 Latar Belakang

Tracer Study merupakan studi pelacakan jejak alumni yang bertujuan untuk mengetahui kondisi lulusan setelah menyelesaikan pendidikan, termasuk status pekerjaan, keselarasan bidang kerja dengan bidang studi, tingkat kompetensi yang dirasakan, serta umpan balik dari instansi tempat lulusan bekerja. Politeknik Statistika STIS sebagai institusi pendidikan kedinasan memiliki kebutuhan khusus dalam memantau daya serap lulusan di berbagai instansi pemerintahan dan swasta.

Sistem Informasi Tracer Study ini dikembangkan oleh Unit Sistem Penjaminan Mutu (SPM) Politeknik Statistika STIS untuk mengotomatisasi proses penyebaran kuesioner, pengumpulan data, monitoring progres pengisian, serta analisis dan visualisasi hasil tracer study secara digital dan terintegrasi.

## 1.2 Tujuan Sistem

1. Memfasilitasi proses penyusunan dan distribusi kuesioner tracer study secara digital kepada lulusan dan pengguna lulusan.
2. Menyediakan mekanisme pengisian kuesioner yang mudah, dengan dukungan fitur autosave dan navigasi per pertanyaan.
3. Menyajikan dashboard visualisasi dan analitik yang komprehensif untuk mendukung pengambilan keputusan berbasis data.
4. Memungkinkan monitoring tingkat partisipasi (response rate) secara real-time berdasarkan berbagai dimensi filter.
5. Mendukung pengelolaan master data lulusan, pengguna lulusan, dan struktur satuan kerja secara terpusat.

## 1.3 Ruang Lingkup Sistem

Sistem ini mencakup:
- Pengelolaan master data: Lulusan, Pengguna Lulusan, Jabatan, Satuan Kerja, dan Unit Kerja.
- Pembuatan survei dinamis dengan Form Builder yang mendukung berbagai tipe pertanyaan, blok navigasi, dan mode kompetensi.
- Distribusi survei kepada responden berdasarkan tahun lulus atau penugasan individual.
- Pengiriman email undangan, reminder, dan ucapan terima kasih.
- Pengisian survei oleh responden dengan fitur autosave dan navigasi satu pertanyaan per halaman.
- Monitoring response rate dengan visualisasi grafik.
- Dashboard visualisasi grafik (Pie Chart, Bar Chart) dan tabel rangkuman per pertanyaan.
- Dashboard analitik dengan tabel tabulasi silang (cross-tabulation).
- Ekspor data ke format Excel (.xlsx).

## 1.4 Definisi dan Istilah

| Istilah | Definisi |
|---|---|
| **Tracer Study** | Studi pelacakan jejak lulusan/alumni yang dilakukan setelah mereka menyelesaikan pendidikan. |
| **Dashboard** | Halaman utama panel admin yang menampilkan ringkasan statistik jumlah Survei, Pengguna Lulusan, Lulusan, serta persentase pengerjaan survei aktif dengan tautan ke visualisasi Grafik dan Analitik. |
| **Dashboard Grafik** | Halaman visualisasi yang menampilkan grafik (Pie Chart/Bar Chart) dan tabel rangkuman dari jawaban responden per pertanyaan survei, lengkap dengan filter Tahun Lulus dan Program Studi. |
| **Dashboard Analitik** | Halaman yang menampilkan tabel tabulasi silang (cross-tabulation) dari jawaban responden, mempersilangkan jawaban pertanyaan dengan dimensi Program Studi dan/atau Tahun Lulus. |
| **Survei** | Instrumen kuesioner digital yang terdiri dari satu atau lebih blok pertanyaan, dibuat oleh Administrator untuk diisi oleh responden (Lulusan atau Pengguna Lulusan). |
| **Responden** | Pengguna yang mengisi survei, yaitu Lulusan atau Pengguna Lulusan. |
| **Lulusan** | Alumni Politeknik Statistika STIS yang menjadi responden utama tracer study. |
| **Pengguna Lulusan** | Atasan langsung, HRD, atau pimpinan di instansi tempat lulusan bekerja yang memberikan penilaian terhadap kinerja lulusan. |
| **NIP** | Nomor Induk Pegawai, digunakan sebagai identitas unik lulusan dan pengguna lulusan dalam sistem. |
| **Program Studi (Prodi)** | Jurusan asal lulusan. Dalam sistem Politeknik Statistika STIS, terdapat tiga program studi: DIV Komputasi Statistik, DIV Statistika, dan DIII Statistika. |
| **Tahun Lulus** | Tahun seorang lulusan secara resmi menyelesaikan pendidikannya. |
| **Satuan Kerja (Satker)** | Instansi atau lembaga tempat lulusan/pengguna lulusan bekerja. |
| **Unit Kerja** | Bagian atau divisi di dalam satuan kerja. |
| **Jabatan** | Posisi atau peran pekerjaan yang dijabat oleh lulusan/pengguna lulusan. |
| **Blok (Block)** | Satuan pengelompokan pertanyaan dalam satu survei. Setiap blok memiliki nama, deskripsi, dan aturan navigasi. |
| **Form Builder** | Antarmuka pembuat survei di halaman Edit Survey yang memungkinkan admin menambah blok, pertanyaan, dan pilihan jawaban secara dinamis. |
| **Tipe Pertanyaan** | Jenis input jawaban: Text Input, Text Area, Radio Button, Checkbox, Dropdown, Multiple Choice Grid, Date, File Upload, dan Gaji. |
| **Visualisasi** | Representasi grafis dari data jawaban responden, berupa Pie Chart atau Bar Chart pada dashboard. |
| **Tabel Rangkuman** | Tabel yang muncul di Dashboard Grafik di bawah setiap grafik, menampilkan frekuensi dan persentase per pilihan jawaban. |
| **Tabel Analitik** | Tabel tabulasi silang di Dashboard Analitik yang menampilkan distribusi jawaban berdasarkan persilangan dengan Program Studi dan/atau Tahun Lulus. |
| **Response Rate** | Persentase responden yang telah menyelesaikan (submit) survei dibandingkan total responden yang ditugaskan. |
| **Autosave** | Fitur penyimpanan jawaban otomatis saat responden mengisi survei (per pertanyaan), sehingga progress pengisian tidak hilang meskipun responden menutup browser. |
| **Status Data** | Indikator kelengkapan profil lulusan/pengguna lulusan. "Data Lengkap" berarti semua field wajib terisi; "Data Tidak Lengkap" berarti ada field wajib yang masih kosong. |
| **Mode Kompetensi** | Mode khusus pada blok survei yang mengonfigurasi seluruh pertanyaan dalam blok tersebut sebagai indikator penilaian kompetensi, diolah menjadi satu kesatuan tabel analitik dan visualisasi. |
| **Navigasi Blok** | Pengaturan alur lanjutan setelah responden menyelesaikan satu blok: lanjut ke blok berikutnya atau akhiri survei. |
| **Template Email** | Template pesan email yang dapat dikustomisasi oleh admin untuk keperluan pengiriman undangan, reminder, dan ucapan terima kasih kepada responden. |
| **Supervisor** | Peran pimpinan (Dekan, Direktur, Wakil Direktur) yang dapat melihat dashboard dan monitoring tanpa hak kelola data. |

## 1.5 Hak Akses Pengguna

### Administrator

**Hak Akses:** Akses penuh ke seluruh modul sistem.

Administrator memiliki wewenang untuk:
- Mengakses dan mengelola **Dashboard** (melihat statistik, visualisasi grafik, dan analitik).
- Mengelola **Monitoring** (melihat response rate, filter, export).
- Mengelola **Manajemen Survei** (membuat, mengedit, menghapus, menduplikasi survei; menyusun pertanyaan melalui Form Builder; mengelola responden; mengirim email undangan, reminder, dan terima kasih).
- Mengelola **Manajemen Satker** (menambah, mengedit, menghapus, mengimpor, dan mengekspor data Jabatan, Satuan Kerja, dan Unit Kerja).
- Mengelola **Manajemen Lulusan** (menambah manual, mengimpor, mengekspor, mengedit, menghapus data lulusan; mengunduh template Excel).
- Mengelola **Manajemen Pengguna Lulusan** (menambah, mengimpor, mengekspor, mengedit, menghapus, melihat detail data pengguna lulusan).
- Mengelola **Template Email** (mengkustomisasi isi template email undangan, reminder, dan terima kasih).
- Mengelola **Manajemen Profil Admin** (mengubah informasi profil dan password admin).

**Batasan:** Tidak ada batasan fungsional. Administrator bertanggung jawab penuh atas kelancaran dan integritas data dalam sistem.

### Supervisor

**Hak Akses:** Hanya mode lihat (View Only) pada modul admin.

Supervisor memiliki wewenang untuk:
- Mengakses **Dashboard** (melihat statistik, grafik, dan analitik).
- Mengakses **Monitoring** (melihat response rate dan melakukan export).
- Melihat **daftar survei, daftar lulusan, daftar pengguna lulusan, dan detail survei** tanpa kemampuan mengelola data.

**Batasan:** Supervisor **tidak dapat** menambah, mengedit, menghapus, mengimpor data, maupun mengirim email. Seluruh tombol aksi pengelolaan data disembunyikan oleh sistem untuk akun Supervisor.

### User Lulusan

**Hak Akses:** Akses terbatas sebagai responden (partisipan pengisian survei).

User Lulusan dapat:
- Melihat **daftar survei** yang ditugaskan kepadanya beserta status (Aktif/Tidak Aktif) dan aksi (ISI SURVEI / EDIT JAWABAN / DONE / SURVEI KADALUWARSA).
- **Mengisi survei** yang berstatus Aktif dan belum pernah disubmit (tombol "ISI SURVEI").
- **Mengedit jawaban** survei yang sudah disubmit namun masih berstatus Aktif (tombol "EDIT JAWABAN").
- **Mengedit profil** pribadi: Jabatan, Satuan Kerja, Unit Kerja, dan No HP melalui panel "Informasi User" di halaman beranda.

**Batasan:** Tidak dapat mengakses panel admin, tidak dapat mengisi survei yang berstatus Tidak Aktif/kadaluwarsa, tidak dapat mengubah Nama, Email, NIP, Program Studi, dan Tahun Lulus.

### User Pengguna Lulusan

**Hak Akses:** Sama dengan User Lulusan, yaitu sebagai responden evaluator eksternal.

User Pengguna Lulusan dapat:
- Melihat **daftar survei** bertipe "Pengguna Lulusan" yang ditugaskan kepadanya.
- **Mengisi survei** evaluasi kinerja lulusan.
- **Mengedit profil** pribadi: Jabatan, Satuan Kerja, Unit Kerja, dan No HP.

**Batasan:** Sama dengan User Lulusan. Hanya dapat melihat dan mengisi survei yang secara spesifik ditugaskan oleh Administrator.

---

# BAB 2 PERSYARATAN SISTEM

## 2.1 Perangkat Keras Minimum

| Komponen | Spesifikasi Minimum | Disarankan |
|---|---|---|
| Processor | Intel Core i3 / AMD setara | Intel Core i5 ke atas |
| RAM | 4 GB | 8 GB |
| Penyimpanan | 500 MB ruang kosong (untuk cache browser) | 1 GB |
| Layar | Resolusi 1366 × 768 piksel | 1920 × 1080 piksel |

## 2.2 Perangkat Lunak Minimum

- **Sistem Operasi:** Windows 10/11, macOS 10.14+, atau distribusi Linux modern dengan desktop GUI.
- **Aplikasi Pendukung:** Microsoft Excel, LibreOffice Calc, atau WPS Office untuk membuka file hasil ekspor berformat `.xlsx`.

## 2.3 Browser yang Didukung

Sistem ini merupakan aplikasi berbasis web (web-based application). Untuk performa optimal, gunakan versi terbaru dari salah satu browser berikut:

| Browser | Dukungan |
|---|---|
| Google Chrome | ✅ Sangat Disarankan |
| Microsoft Edge (Chromium) | ✅ Didukung |
| Mozilla Firefox | ✅ Didukung |
| Apple Safari | ✅ Didukung |

## 2.4 Koneksi Internet

- Kecepatan koneksi internet stabil minimal **2 Mbps**.
- Koneksi yang stabil diperlukan agar fitur autosave dan pengiriman jawaban berjalan tanpa gangguan.

## 2.5 Persyaratan Akun

- Setiap pengguna wajib memiliki **Email** dan **Password** yang telah terdaftar dalam sistem.
- Akun Lulusan dan Pengguna Lulusan **dibuat dan dikelola oleh Administrator**. Tidak tersedia fitur registrasi mandiri (self-registration).
- Akun Admin dan Supervisor dibuat secara internal oleh tim pengelola sistem.

---

# BAB 3 ALUR BISNIS SISTEM

## 3.1 Alur Pengelolaan Data Master

Sebelum survei dapat dibuat dan didistribusikan, Administrator harus terlebih dahulu mempersiapkan data master yang menjadi fondasi sistem:

1. **Manajemen Satker:** Administrator membuka menu **Manajemen Satker** di sidebar. Halaman ini memiliki tiga tab: **Jabatan**, **Satuan Kerja**, dan **Unit Kerja**. Pada masing-masing tab, admin dapat menambah data satu per satu melalui tombol **Tambah**, atau mengunggah data secara massal menggunakan tombol **Import** dengan file Excel (kolom wajib: `nama`). Data yang telah diinput akan tersedia sebagai opsi dropdown di seluruh modul sistem.

2. **Manajemen Lulusan:** Administrator membuka menu **Manajemen Lulusan**. Admin dapat menambah data lulusan secara manual (satu per satu) melalui tombol **Tambah Manual**, atau mengunggah data secara massal melalui tombol **Import Data** menggunakan template Excel yang dapat diunduh dari tombol **Template Excel**. Setiap lulusan memiliki atribut: Nama, NIP, Email, Program Studi, Jabatan, Satuan Kerja, Unit Kerja, No HP, Tanggal Lahir, Tahun Lulus, dan NIP Pengguna Lulusan. Sistem menghitung **Status Data** (Data Lengkap / Data Tidak Lengkap) secara otomatis berdasarkan kelengkapan field Prodi, Jabatan, Satuan Kerja, dan Unit Kerja.

3. **Manajemen Pengguna Lulusan:** Administrator membuka menu **Manajemen Pengguna Lulusan**. Admin menambah data pengguna lulusan (atasan/HRD) dengan atribut: Nama, NIP, Email, Jabatan, Satuan Kerja, Unit Kerja, dan No HP. Proses penambahan dapat dilakukan manual atau impor massal.

**[SCREENSHOT: Tampilan sidebar admin menunjukkan seluruh menu navigasi]**

## 3.2 Alur Pembuatan Survei

1. Administrator membuka menu **Manajemen Survei** di sidebar dan melihat halaman **Daftar Survei**.
2. Klik tombol **Tambah Survei** di kanan atas.
3. Di halaman **Edit Survey** (Form Builder), admin mengisi **Informasi Survey**:
   - **Nama Survey** (wajib)
   - **Tipe Survey** (wajib): pilih "Lulusan" atau "Pengguna Lulusan"
   - **Tanggal Mulai** (wajib)
   - **Tanggal Selesai** (wajib)
   - **Deskripsi Survey** (opsional)
4. Di bagian **Form Builder**, admin menyusun pertanyaan:
   - Klik **Tambah Block** untuk membuat blok pertanyaan baru. Setiap blok memiliki Nama Block, Deskripsi Block, dan pengaturan Navigasi Block (Lanjut ke block berikutnya / Akhiri survey).
   - Di dalam setiap blok, klik **Tambah Pertanyaan** untuk menambah pertanyaan baru. Setiap pertanyaan memiliki: Teks Pertanyaan, Deskripsi, Tipe Pertanyaan, Visualisasi (Bar Chart / Pie Chart / Tidak ada), opsi Wajib Diisi, dan opsi Tabel Analitik.
   - Untuk pertanyaan tipe Radio Button, Checkbox, Dropdown, dan Multiple Choice Grid: admin menambahkan pilihan jawaban.
5. Klik **Update Survey** untuk menyimpan seluruh konfigurasi survei.

**[SCREENSHOT: Halaman Form Builder dengan blok dan pertanyaan yang sudah ditambahkan]**

## 3.3 Alur Penugasan Responden

Setelah survei dibuat, admin harus menugaskan responden:

1. Dari halaman **Daftar Survei**, klik ikon **Details** (ikon info) pada baris survei yang dituju.
2. Di halaman **Details Survey**, pada bagian **Daftar User**, admin dapat menambahkan responden melalui dua cara:
   - **Tambah Responden by Tahun Lulus:** Pilih tahun lulus dari dropdown, maka seluruh lulusan dengan tahun lulus tersebut akan otomatis ditugaskan ke survei.
   - **Tambah User Individual:** Ketik nama/email di kolom pencarian Select2, pilih pengguna, dan pengguna tersebut akan langsung ditugaskan.
3. Responden yang sudah ditugaskan akan muncul di tabel Daftar User. Admin dapat menghapus responden dari survei dengan mengklik ikon hapus.

## 3.4 Alur Pengiriman Email

Dari halaman **Details Survey**, admin dapat mengirim email kepada responden:

1. **Kirim Undangan:** Klik tombol **Kirim Undangan** (biru) untuk mengirim email undangan ke seluruh responden yang belum mengisi.
2. **Reminder Pengerjaan:** Klik tombol **Reminder Pengerjaan** (oranye) untuk mengirim email pengingat ke responden yang belum menyelesaikan survei.
3. **Ucapan Terima Kasih:** Klik tombol **Ucapan Terima Kasih** (hijau) untuk mengirim email terima kasih ke responden yang sudah menyelesaikan survei.
4. **Kelola Template Email:** Klik tombol **Kelola Template Email** (abu-abu) untuk mengkustomisasi isi template email melalui menu Template Email.

**[SCREENSHOT: Halaman Details Survey menunjukkan tombol-tombol pengiriman email dan daftar responden]**

## 3.5 Alur Pengisian Survei oleh Responden

1. Responden (Lulusan/Pengguna Lulusan) mengakses sistem melalui URL yang diberikan.
2. Responden login menggunakan Email dan Password.
3. Pada halaman beranda, responden melihat panel **Informasi Survei** (kiri) dan **Informasi User** (kanan).
4. Di tabel Informasi Survei, responden melihat daftar survei beserta status dan aksi:
   - **ISI SURVEI** (hijau): Survei aktif yang belum pernah diisi.
   - **EDIT JAWABAN** (indigo): Survei yang sudah disubmit namun masih aktif, sehingga jawaban dapat diubah.
   - **DONE** (abu-abu): Survei yang sudah disubmit dan sudah tidak aktif.
   - **SURVEI KADALUWARSA** (merah): Survei yang belum diisi namun sudah melewati tanggal selesai.
5. Responden mengklik **ISI SURVEI** dan diarahkan ke halaman pengisian pertanyaan.
6. Sistem menampilkan **satu pertanyaan per halaman** dengan informasi progress (Pertanyaan X dari Y, progress bar).
7. Responden menjawab pertanyaan dan klik **Lanjutkan** untuk berpindah ke pertanyaan berikutnya.
8. Pada pertanyaan terakhir, setelah responden mengklik Lanjutkan, sistem menyimpan jawaban dan menampilkan halaman **Selesai (Done)**.

**[SCREENSHOT: Halaman beranda responden menampilkan tabel Informasi Survei dengan berbagai status aksi]**

**[SCREENSHOT: Halaman pengisian survei menampilkan satu pertanyaan dengan progress bar]**

## 3.6 Alur Monitoring

1. Administrator atau Supervisor membuka menu **Monitoring** di sidebar.
2. Halaman Monitoring menampilkan panel filter dan tabel daftar survei.
3. Filter yang tersedia: **Cari Survei** (teks), **Survei** (dropdown pilih survei spesifik), **Tahun Lulus**, **Program Studi**, dan **Jenis Visualisasi** (Bar Chart / Pie Chart).
4. Klik **Terapkan Filter** untuk menampilkan hasil.
5. Setiap baris survei menampilkan: Nama Survei, Status, Tanggal Aktif, Tipe Survei, **Response Rate (Keseluruhan)**, dan **Response Rate (Sesuai Filter)**.
6. Jika satu survei dipilih, panel **Visualisasi Response Rate** di atas tabel akan menampilkan grafik response rate.
7. Klik **Export Hasil Survei** pada baris survei untuk mengunduh data jawaban responden dalam format Excel.
8. Klik **Export Hasil Visualisasi** untuk mengunduh grafik visualisasi response rate sebagai gambar PNG.

**[SCREENSHOT: Halaman Monitoring menunjukkan filter, grafik visualisasi response rate, dan tabel survei]**

## 3.7 Alur Dashboard

1. Administrator atau Supervisor membuka menu **Dashboard** di sidebar.
2. Halaman Dashboard menampilkan tiga kartu statistik di bagian atas: **Total Survei**, **Total Pengguna Lulusan**, dan **Total Lulusan**.
3. Di bawahnya, tabel **Persentase Pengerjaan Survei** menampilkan setiap survei aktif beserta progress bar persentase pengerjaan dan jumlah responden yang telah mengisi.
4. Setiap survei memiliki tiga tautan aksi:
   - **Grafik:** Membuka halaman Dashboard Grafik yang menampilkan grafik (Pie/Bar Chart) dan tabel rangkuman per pertanyaan.
   - **Analitik:** Membuka halaman Dashboard Analitik yang menampilkan tabel tabulasi silang per pertanyaan.
   - **Export Excel:** Mengunduh seluruh data jawaban responden survei ke file Excel.

**[SCREENSHOT: Halaman Dashboard admin menampilkan kartu statistik dan tabel Persentase Pengerjaan Survei]**

## 3.8 Alur Export dan Import

### Import Data
1. Import data digunakan di awal proses untuk memasukkan data master secara massal.
2. Admin mengunduh **Template Excel** dari sistem (tersedia di halaman Manajemen Lulusan, Manajemen Pengguna Lulusan, dan Manajemen Satker).
3. Admin mengisi template sesuai format kolom yang telah ditentukan.
4. Admin mengunggah file melalui tombol **Import Data** dan sistem memproses data.
5. Jika terdapat kesalahan pada baris tertentu, sistem menampilkan pesan error yang menyebutkan baris dan penyebab kesalahan.

### Export Data
1. Export data digunakan untuk mengunduh laporan atau data mentah.
2. Dari **Monitoring**, klik **Export Hasil Survei** untuk mengekspor jawaban responden per survei.
3. Dari **Dashboard**, klik **Export Excel** untuk mengekspor data jawaban.
4. Dari **Manajemen Lulusan**, klik **Export Data** untuk mengekspor seluruh data profil lulusan.
5. Dari **Manajemen Pengguna Lulusan**, klik **Export Data** untuk mengekspor seluruh data profil pengguna lulusan.
6. Dari **Manajemen Satker**, klik **Export** pada tab yang aktif untuk mengekspor data master.

## 3.9 Alur Autosave Survei

Sistem pengisian survei menggunakan mekanisme satu pertanyaan per halaman. Setiap kali responden mengklik tombol **Lanjutkan**, jawaban pertanyaan tersebut langsung dikirim dan disimpan ke server. Dengan demikian:

1. Jawaban tersimpan secara otomatis setelah setiap pertanyaan diselesaikan.
2. Jika responden menutup browser di tengah pengisian, progress tidak hilang.
3. Saat responden login kembali dan mengklik survei yang sama, sistem mengarahkan ke pertanyaan terakhir yang belum dijawab.
4. Status survei responden berubah dari 0 (Belum Mengisi) ke proses pengisian begitu responden mulai menjawab pertanyaan pertama.

---

# BAB 4 PANDUAN ADMINISTRATOR

Bagian ini menjelaskan secara rinci setiap menu dan fitur yang tersedia bagi Administrator. Seluruh menu diakses melalui **sidebar navigasi** di sisi kiri layar.

**Menu sidebar Administrator:**
1. Dashboard
2. Monitoring
3. Manajemen Survei
4. Manajemen Satker
5. Manajemen Lulusan
6. Manajemen Pengguna Lulusan
7. Template Email
8. Manajemen Profil Admin

**[SCREENSHOT: Sidebar navigasi admin dengan semua menu terlihat]**

---

## 4.1 Dashboard

### Tujuan Fitur
Menampilkan ringkasan statistik dan progres pengisian survei aktif, serta menyediakan akses cepat ke visualisasi grafik dan analitik.

### Prasyarat
Minimal satu survei berstatus Aktif yang telah ditugaskan kepada responden.

### Langkah-langkah

1. Klik menu **Dashboard** pada sidebar.
2. **Membaca Kartu Statistik:** Perhatikan tiga kartu di bagian atas halaman:
   - **Survei:** Menampilkan jumlah total survei yang ada dalam sistem.
   - **Pengguna Lulusan:** Menampilkan jumlah total pengguna lulusan yang terdaftar.
   - **Lulusan:** Menampilkan jumlah total lulusan yang terdaftar.
3. **Membaca Tabel Persentase Pengerjaan Survei:** Di bawah kartu statistik, tabel menampilkan:
   - **Nama:** Nama survei aktif.
   - **Persentase Progress:** Persentase pengisian (jumlah responden yang sudah mengisi / total responden), dilengkapi progress bar visual dan keterangan "X responden telah mengisi dari Y responden".
   - **Visualisasi:** Tiga tombol aksi:
     - **Grafik** (biru): Membuka halaman Dashboard Grafik untuk survei tersebut.
     - **Analitik** (indigo): Membuka halaman Dashboard Analitik untuk survei tersebut.
     - **Export Excel** (hijau): Mengunduh data jawaban responden ke file Excel.

**[SCREENSHOT: Halaman Dashboard lengkap dengan kartu statistik dan tabel persentase pengerjaan]**

### Validasi Sistem
- Jika tidak ada survei aktif, tabel Persentase Pengerjaan Survei akan kosong.
- Persentase dihitung hanya berdasarkan responden yang telah di-assign ke survei.

### Kemungkinan Error
- Dashboard lambat dimuat jika jumlah survei aktif sangat banyak.

### Solusi Error
- Tunggu beberapa detik hingga halaman selesai dimuat. Jika tetap lambat, lakukan refresh halaman (F5).

---

## 4.2 Dashboard Grafik

### Tujuan Fitur
Menampilkan visualisasi grafik (Pie Chart / Bar Chart) dan tabel rangkuman untuk setiap pertanyaan dalam survei, lengkap dengan filter berdasarkan Tahun Lulus dan Program Studi.

### Prasyarat
Survei sudah memiliki pertanyaan dengan pengaturan visualisasi (Bar Chart atau Pie Chart), dan sudah ada responden yang telah mengisi.

### Langkah-langkah

1. Dari halaman Dashboard, klik tombol **Grafik** pada baris survei yang dituju.
2. Di halaman Dashboard Grafik, perhatikan:
   - **Filter Tahun Lulus dan Program Studi** di bagian atas halaman. Pilih filter dan klik **Terapkan** atau **Filter** untuk memperbarui grafik.
   - **Grafik per Pertanyaan:** Setiap pertanyaan yang memiliki pengaturan visualisasi akan ditampilkan dalam bentuk grafik (Pie Chart atau Bar Chart) sesuai konfigurasi admin.
   - **Tabel Rangkuman:** Di bawah setiap grafik, tabel menampilkan: Pilihan Jawaban, Jumlah Frekuensi (N), dan Persentase (%).
3. Untuk **mengekspor data**, klik tombol **Export Excel** yang tersedia di halaman.

**[SCREENSHOT: Halaman Dashboard Grafik menampilkan filter, grafik pie chart, dan tabel rangkuman]**

**[SCREENSHOT: Contoh Bar Chart pada Dashboard Grafik untuk pertanyaan tipe Checkbox]**

### Catatan Penting
- Grafik hanya ditampilkan untuk pertanyaan yang memiliki pengaturan visualisasi (bukan "Tidak ada visualisasi").
- Tabel rangkuman menampilkan data semua responden yang telah mengisi (bukan draft).

---

## 4.3 Dashboard Analitik

### Tujuan Fitur
Menampilkan tabel tabulasi silang (cross-tabulation) yang mengkorelasikan jawaban responden dengan dimensi Program Studi dan/atau Tahun Lulus.

### Prasyarat
Pertanyaan dalam survei harus dicentang opsi **Tabel Analitik** pada Form Builder.

### Langkah-langkah

1. Dari halaman Dashboard, klik tombol **Analitik** pada baris survei yang dituju.
2. Di halaman Dashboard Analitik, perhatikan:
   - **Filter** di bagian atas untuk menyaring data berdasarkan Tahun Lulus dan/atau Program Studi.
   - **Tabel tabulasi silang** per pertanyaan yang dikonfigurasi sebagai Tabel Analitik. Tabel menampilkan distribusi jawaban per Program Studi atau per Tahun Lulus.
3. Klik **Export** untuk mengunduh tabel analitik ke file Excel.

**[SCREENSHOT: Halaman Dashboard Analitik menampilkan tabel tabulasi silang]**

---

## 4.4 Monitoring

### Tujuan Fitur
Memantau tingkat partisipasi (response rate) seluruh survei secara real-time, dengan kemampuan filter multi-dimensi dan visualisasi grafik.

### Prasyarat
Minimal satu survei sudah ada dengan responden yang telah ditugaskan.

### Langkah-langkah

1. Klik menu **Monitoring** pada sidebar.
2. **Panel Filter:** Gunakan filter berikut:
   - **Cari Survei:** Ketik nama atau tipe survei untuk pencarian cepat.
   - **Survei:** Pilih satu survei spesifik dari dropdown. **Filter Tahun Lulus dan Program Studi hanya aktif jika satu survei telah dipilih.**
   - **Tahun Lulus:** Filter berdasarkan tahun lulus responden.
   - **Program Studi:** Filter berdasarkan program studi responden.
   - **Jenis Visualisasi:** Pilih Bar Chart atau Pie Chart untuk grafik response rate.
3. Klik **Terapkan Filter** untuk menampilkan hasil.
4. **Visualisasi Response Rate:** Jika satu survei dipilih, panel grafik di atas tabel akan menampilkan grafik response rate berdasarkan dimensi filter. Jika tidak ada survei yang dipilih, pesan "Pilih 1 survei untuk menampilkan visualisasi response rate" akan tampil.
5. **Tabel Survei:** Menampilkan kolom:
   - **Nama Survei**
   - **Status** (Aktif / Tidak Aktif)
   - **Tanggal Aktif** (Tanggal Mulai – Tanggal Selesai)
   - **Tipe Survei** (Lulusan / Pengguna Lulusan)
   - **Response Rate (Keseluruhan):** Persentase dan rasio (X / Y) dari seluruh responden survei.
   - **Response Rate (Sesuai Filter):** Persentase dan rasio yang dihitung hanya dari responden yang sesuai filter aktif.
   - **Aksi:** Tautan **Export Hasil Survei** (ikon Excel) untuk mengunduh data jawaban responden.
6. Klik **Export Hasil Visualisasi** (tombol hijau di atas tabel) untuk mengunduh grafik response rate sebagai file gambar PNG.
7. Klik **Reset** untuk menghapus semua filter dan kembali ke tampilan default.

**[SCREENSHOT: Halaman Monitoring lengkap dengan panel filter, grafik visualisasi response rate, dan tabel survei]**

### Validasi Sistem
- Jika filter Tahun Lulus atau Program Studi diaktifkan tanpa memilih survei terlebih dahulu, sistem menampilkan pesan validasi: "Pilih survei terlebih dahulu jika ingin menampilkan visualisasi berdasarkan filter."
- Filter dropdown Tahun Lulus dan Program Studi secara dinamis menyesuaikan opsi yang tersedia berdasarkan survei yang dipilih (cascading filter).

### Kemungkinan Error
- Data response rate menampilkan 0% padahal responden sudah mengisi: Pastikan responden telah menyelesaikan survei hingga halaman Done (status = selesai).

---

## 4.5 Manajemen Survei

### Tujuan Fitur
Pusat pengelolaan seluruh instrumen kuesioner dalam sistem, mulai dari pembuatan, penyusunan pertanyaan, penugasan responden, hingga pengiriman email.

### Langkah-langkah

**Melihat Daftar Survei:**
1. Klik menu **Manajemen Survei** pada sidebar.
2. Halaman **Daftar Survei** menampilkan tabel dengan kolom: Nama Survei, Status (badge Aktif / Tidak Aktif), Tanggal Aktif, Tipe Survei (Lulusan / Pengguna Lulusan), dan Aksi.
3. Gunakan kolom **Cari** di kanan atas untuk mencari survei berdasarkan nama.

**[SCREENSHOT: Halaman Daftar Survei menampilkan tabel survei dengan kolom dan badge status]**

**Membuat Survei Baru:**
1. Klik tombol **Tambah Survei**.
2. Isi form Informasi Survey: Nama Survey, Tipe Survey, Tanggal Mulai, Tanggal Selesai, Deskripsi (opsional).
3. Di bagian Form Builder, susun pertanyaan (detail di Bab 5).
4. Klik **Update Survey** untuk menyimpan.

**Mengedit Survei:**
1. Pada tabel Daftar Survei, klik ikon **Edit** (pensil biru) pada baris survei yang dituju.
2. Halaman **Edit Survey** terbuka dengan Form Builder. Admin dapat mengubah informasi survey, menambah/mengedit/menghapus blok dan pertanyaan.
3. Klik **Update Survey** untuk menyimpan perubahan.

**Menghapus Survei:**
1. Klik ikon **Delete** (tempat sampah merah) pada baris survei.
2. Sistem menampilkan dialog konfirmasi: "Apakah Anda yakin ingin menghapus survey ini? Semua data terkait termasuk pertanyaan, jawaban, dan responden akan ikut terhapus secara permanen."
3. Klik OK untuk mengonfirmasi penghapusan atau Cancel untuk membatalkan.

**⚠️ PERINGATAN KRITIS:** Menghapus survei akan menghapus **seluruh pertanyaan, jawaban responden, dan data penugasan** secara permanen dan tidak dapat dikembalikan. Pastikan Anda telah mengekspor data sebelum menghapus survei.

**Menduplikasi Survei:**
1. Klik ikon **Duplicate** (ikon copy abu-abu) pada baris survei.
2. Sistem menampilkan dialog konfirmasi: "Apakah Anda yakin ingin menduplikasi survey ini? Survey baru akan dibuat dengan semua pertanyaan dan pengaturan yang sama."
3. Klik OK untuk mengonfirmasi. Survei baru akan dibuat dengan seluruh struktur pertanyaan yang identik. **Jawaban responden dan penugasan user TIDAK ikut terduplikasi.**

**Melihat Detail Survei:**
1. Klik ikon **Details** (ikon info circle abu-abu) pada baris survei.
2. Halaman **Details Survey** menampilkan:
   - **Informasi Survei:** Nama Survei, Status, Tanggal Aktif, Tipe Survei.
   - **Tombol Email:** Kirim Undangan, Reminder Pengerjaan, Ucapan Terima Kasih, Kelola Template Email.
   - **Daftar Pertanyaan:** Tabel yang menampilkan seluruh pertanyaan per blok, termasuk tipe dan pilihan jawaban.
   - **Daftar User:** Tabel responden yang sudah ditugaskan ke survei, dengan aksi untuk menambah dan menghapus responden.

**[SCREENSHOT: Halaman Details Survey menampilkan informasi survei, tombol email, daftar pertanyaan per blok, dan daftar user]**

### Status Survei

Survei memiliki dua status yang ditentukan berdasarkan tanggal:
- **Aktif:** Tanggal saat ini berada di antara Tanggal Mulai dan Tanggal Selesai survei. Responden dapat mengisi survei.
- **Tidak Aktif:** Tanggal saat ini di luar rentang tanggal survei. Responden tidak dapat mengisi survei baru, tetapi yang sudah submit tetap tercatat.

---

## 4.6 Manajemen Lulusan

### Tujuan Fitur
Mengelola database profil seluruh lulusan Politeknik Statistika STIS yang menjadi responden tracer study.

### Langkah-langkah

**Melihat Daftar Lulusan:**
1. Klik menu **Manajemen Lulusan** pada sidebar.
2. Halaman menampilkan tabel **Daftar User Lulusan** dengan kolom: Nama/NIP, Email, Program Studi, Jabatan, Satuan Kerja, Unit Kerja, No HP, Tanggal Lahir, Tahun Lulus, NIP Pengguna Lulusan, Status Data, dan Aksi.
3. Kolom **Nama/NIP** bersifat *sticky* (tetap terlihat saat tabel di-scroll horizontal).
4. **Pencarian:** Gunakan kolom pencarian "Search nama atau NIP..." di kanan atas untuk mencari lulusan.
5. **Filter Status Data:** Gunakan dropdown filter: Semua Status / Data Lengkap / Data Tidak Lengkap.

**[SCREENSHOT: Halaman Manajemen Lulusan menampilkan tabel daftar lulusan dengan filter dan tombol aksi]**

**Tambah Manual:**
1. Klik tombol **Tambah Manual** (biru).
2. Isi form: Nama, NIP, Email, Password, Program Studi (pilih dari: DIV Komputasi Statistik / DIV Statistika / DIII Statistika), Jabatan, Satuan Kerja, Unit Kerja, No HP, Tanggal Lahir, Tahun Lulus, NIP Pengguna Lulusan.
3. Klik **Simpan**.

**[SCREENSHOT: Form Tambah Lulusan Manual dengan seluruh field yang perlu diisi]**

**Import Data (Impor Massal):**
1. Klik tombol **Import Data** (biru).
2. Modal popup akan muncul. Klik **Choose Excel File** dan pilih file `.xls` atau `.xlsx`.
3. Klik **Upload** untuk mengunggah data.
4. Sistem memproses file dan menampilkan pesan sukses atau error.

**Sebelum mengimpor**, pastikan untuk mengunduh template terlebih dahulu:
1. Klik tombol **Template Excel** (hijau) untuk mengunduh template Excel kosong yang sudah memiliki format kolom yang benar.
2. **DILARANG mengubah nama kolom header pada template.**

**Export Data:**
1. Klik tombol **Export Data** (hijau) untuk mengunduh seluruh data lulusan ke file Excel.

**Edit:**
1. Klik ikon **Edit** (pensil) pada kolom Aksi di baris lulusan yang dituju.
2. Ubah data yang diperlukan pada form edit.
3. Klik **Simpan**.

**Hapus:**
1. Klik ikon **Hapus** (tempat sampah) pada kolom Aksi.
2. Lulusan beserta akun login-nya akan dihapus dari sistem.

**⚠️ PERINGATAN:** Menghapus data lulusan akan menghapus akun login dan seluruh jawaban kuesioner yang telah diisi oleh lulusan tersebut.

### Status Data
Sistem menghitung Status Data secara otomatis:
- **Data Lengkap** (badge hijau): Field Prodi, Jabatan, Satuan Kerja, dan Unit Kerja semua terisi.
- **Data Tidak Lengkap** (badge merah): Salah satu atau lebih field di atas masih kosong.

### Penanganan Data Mismatch saat Import
- Jika format file tidak sesuai (bukan `.xls` atau `.xlsx`), sistem akan menolak upload.
- Jika terdapat baris dengan data tidak valid (misal: format email salah, kolom wajib kosong), sistem akan menampilkan pesan error yang merinci baris dan penyebab kesalahan.
- **Solusi:** Perbaiki data pada file Excel dan unggah ulang hanya baris yang gagal, atau unggah ulang keseluruhan file yang sudah diperbaiki.

---

## 4.7 Manajemen Pengguna Lulusan

### Tujuan Fitur
Mengelola database profil pengguna lulusan (atasan/HRD/instansi) yang akan mengevaluasi kinerja alumni.

### Langkah-langkah

**Melihat Daftar Pengguna Lulusan:**
1. Klik menu **Manajemen Pengguna Lulusan** pada sidebar.
2. Halaman menampilkan tabel dengan kolom: Nama/NIP, Email, Jabatan, Satuan Kerja, Unit Kerja, No HP, Status Data, dan Aksi.
3. Gunakan kolom pencarian dan filter Status Data untuk memfilter data.

**[SCREENSHOT: Halaman Manajemen Pengguna Lulusan menampilkan tabel daftar pengguna lulusan]**

**Tambah Data:**
1. Klik tombol **Tambah Manual** (atau tombol serupa).
2. Isi form: Nama, NIP, Email, Password, Jabatan, Satuan Kerja, Unit Kerja, No HP.
3. Klik **Simpan**.

**Import Data:**
1. Klik **Import Data**.
2. Unduh **Template Excel** terlebih dahulu jika belum memiliki template.
3. Isi template, lalu upload melalui modal import.

**Export Data:**
1. Klik **Export Data** untuk mengunduh seluruh data pengguna lulusan ke file Excel.

**Detail:**
1. Klik ikon **Detail** pada kolom Aksi untuk melihat informasi lengkap pengguna lulusan.

**Edit dan Hapus:**
- Klik ikon **Edit** untuk mengubah data.
- Klik ikon **Hapus** untuk menghapus data pengguna lulusan beserta akun login-nya.

---

## 4.8 Manajemen Satker (Master Data)

### Tujuan Fitur
Mengelola data master referensi organisasi yang terdiri dari tiga jenis: **Jabatan**, **Satuan Kerja**, dan **Unit Kerja**. Data master ini digunakan sebagai opsi dropdown di seluruh modul sistem untuk memastikan konsistensi penulisan.

### Langkah-langkah

1. Klik menu **Manajemen Satker** pada sidebar.
2. Halaman **Master Data Satker** menampilkan tiga tab: **Jabatan**, **Satuan Kerja**, dan **Unit Kerja**.

**[SCREENSHOT: Halaman Manajemen Satker menampilkan tab Jabatan, Satuan Kerja, dan Unit Kerja]**

**Untuk setiap tab, tersedia fitur berikut:**

**Tambah Data:**
1. Klik tombol **Tambah [Jabatan/Satuan Kerja/Unit Kerja]**.
2. Modal popup muncul. Isi field **Nama** (wajib).
3. Klik **Simpan**.

**Import Data:**
1. Klik tombol **Import**.
2. Modal popup muncul. Pilih file Excel (format `.xls`, `.xlsx`, atau `.csv`) dengan satu kolom header: `nama`.
3. Klik **Upload**.

**Export Data:**
1. Klik tombol **Export** untuk mengunduh data tab yang aktif ke file Excel.

**Edit Data:**
1. Klik ikon **Edit** pada baris data.
2. Modal edit muncul dengan nama saat ini. Ubah nama sesuai kebutuhan.
3. Klik **Simpan**.

**Hapus Data:**
1. Klik ikon **Hapus** pada baris data.
2. Data akan dihapus dari master. **Catatan:** Jika data master ini sudah digunakan oleh profil lulusan/pengguna lulusan, data di profil tersebut tetap ada sebagai teks, namun tidak lagi terhubung ke master.

---

## 4.9 Template Email

### Tujuan Fitur
Mengkustomisasi isi template email yang digunakan untuk mengirim undangan, reminder, dan ucapan terima kasih kepada responden survei.

### Langkah-langkah

1. Klik menu **Template Email** pada sidebar.
2. Halaman menampilkan template email yang dapat diedit.
3. Ubah isi template sesuai kebutuhan. Gunakan placeholder variabel yang tersedia (seperti nama responden, nama survei, tautan survei) agar email dipersonalisasi.
4. Klik **Simpan** atau **Update** untuk menyimpan perubahan.
5. Gunakan tombol **Preview** untuk melihat pratinjau tampilan email.

**[SCREENSHOT: Halaman Template Email menampilkan editor template dan tombol preview]**

---

## 4.10 Manajemen Profil Admin

### Tujuan Fitur
Mengubah informasi profil dan password akun admin yang sedang login.

### Langkah-langkah

1. Klik menu **Manajemen Profil Admin** pada sidebar.
2. Ubah informasi profil yang diperlukan.
3. Klik **Simpan** atau **Update** untuk menyimpan perubahan.

**[SCREENSHOT: Halaman Manajemen Profil Admin]**

---

# BAB 5 PANDUAN PEMBUATAN PERTANYAAN SURVEI (FORM BUILDER)

Form Builder adalah antarmuka pembuat survei yang terintegrasi di halaman **Edit Survey**. Form Builder menggunakan konsep **Blok (Block)** sebagai wadah pengelompokan pertanyaan.

## 5.1 Konsep Blok (Block)

Setiap survei terdiri dari satu atau lebih blok. Setiap blok memiliki:
- **Nama Block** (wajib): Judul pengelompokan, misal "Data Pribadi", "Status Pekerjaan", "Penilaian Kompetensi".
- **Deskripsi Block** (opsional): Penjelasan singkat tentang isi blok.
- **Navigasi Block:** Menentukan apa yang terjadi setelah responden menyelesaikan seluruh pertanyaan dalam blok tersebut:
  - **Lanjut ke block berikutnya** (default): Responden diarahkan ke blok urutan selanjutnya.
  - **Akhiri survey:** Survei langsung berakhir setelah blok ini.
- **Mode Kompetensi (Opsional):** Opsi khusus untuk menjadikan blok sebagai blok penilaian kompetensi/indikator (lihat Bab 6).

**[SCREENSHOT: Form Builder menampilkan blok dengan Nama Block, Deskripsi Block, dan dropdown Navigasi Block]**

### Menambah Block
1. Klik tombol **Tambah Block** di bagian atas Form Builder, atau klik tombol **Tambah Block Baru** di bagian bawah setiap blok.
2. Blok baru akan muncul. Isi Nama Block dan konfigurasi lainnya.

### Menduplikasi Block
1. Klik ikon **Copy** (duplikat) pada header blok.
2. Blok beserta seluruh pertanyaan di dalamnya akan diduplikasi.

### Menghapus Block
1. Klik ikon **Trash** (hapus) pada header blok.
2. Blok beserta seluruh pertanyaan di dalamnya akan dihapus.

## 5.2 Menambah Pertanyaan

1. Di dalam sebuah blok, klik tombol **Tambah Pertanyaan** (hijau).
2. Form pertanyaan baru muncul dengan field:
   - **Pertanyaan** (wajib): Teks pertanyaan yang akan dilihat responden.
   - **Deskripsi** (opsional): Penjelasan tambahan di bawah teks pertanyaan.
   - **Tipe Pertanyaan** (wajib): Pilih salah satu tipe (lihat detail di bawah).
   - **Visualisasi:** Pilih jenis grafik yang akan ditampilkan di Dashboard Grafik: Bar Chart, Pie Chart, atau Tidak ada visualisasi.
   - **Wajib diisi:** Centang jika pertanyaan ini wajib dijawab oleh responden (ditandai tanda bintang merah *).
   - **Tabel Analitik:** Centang jika jawaban pertanyaan ini akan ditampilkan di Dashboard Analitik sebagai tabel tabulasi silang.

**[SCREENSHOT: Form pertanyaan dalam Form Builder menampilkan field pertanyaan, deskripsi, tipe, visualisasi, dan checkbox]**

### Menduplikasi dan Menghapus Pertanyaan
- Klik ikon **Copy** pada baris kontrol pertanyaan untuk menduplikasi pertanyaan.
- Klik ikon **Trash** untuk menghapus pertanyaan.
- Klik ikon **Plus** untuk menambah pertanyaan baru di bawah pertanyaan saat ini.

## 5.3 Tipe-tipe Pertanyaan

### Text Input (Short Text)
- **Kegunaan:** Pertanyaan yang memerlukan jawaban singkat satu baris, seperti "Nama Instansi Tempat Anda Bekerja".
- **Tampilan di Responden:** Kolom input teks satu baris.
- **Validasi:** Tidak ada validasi khusus selain wajib diisi (jika dicentang).
- **Visualisasi:** Umumnya tidak divisualisasikan karena bersifat teks bebas.

### Text Area (Long Text)
- **Kegunaan:** Pertanyaan yang memerlukan jawaban panjang multi-baris, seperti "Tuliskan saran Anda untuk perbaikan kurikulum".
- **Tampilan di Responden:** Area teks besar multi-baris.
- **Visualisasi:** Umumnya tidak divisualisasikan.

### Radio Button (Pilihan Ganda — Satu Jawaban)
- **Kegunaan:** Responden wajib memilih **satu** opsi dari daftar pilihan. Contoh: "Apakah pekerjaan Anda sesuai bidang studi? Ya / Tidak".
- **Konfigurasi di Form Builder:** Setelah memilih tipe Radio Button, bagian **Pilihan Jawaban** muncul. Klik **Tambah Pilihan** untuk menambah opsi. Setiap opsi dapat dikonfigurasi dengan navigasi kustom (jika dipilih, lanjut ke blok tertentu atau akhiri survei).
- **Tampilan di Responden:** Daftar tombol radio, hanya bisa dipilih satu.
- **Visualisasi:** Mendukung Pie Chart dan Bar Chart.
- **Navigasi Custom:** Pada tipe Radio Button, admin dapat mengaktifkan "Custom navigation untuk pilihan ini" pada setiap opsi jawaban. Jika dicentang, admin bisa menentukan: jika responden memilih opsi tersebut, survei akan lanjut ke blok tertentu atau langsung selesai.

**[SCREENSHOT: Konfigurasi pertanyaan Radio Button di Form Builder dengan pilihan jawaban dan opsi custom navigation]**

### Checkbox (Pilihan Ganda — Banyak Jawaban)
- **Kegunaan:** Responden dapat memilih **lebih dari satu** opsi. Contoh: "Sumber dana kuliah Anda? Beasiswa / Orang Tua / Mandiri".
- **Konfigurasi:** Sama dengan Radio Button — tambahkan pilihan jawaban melalui tombol **Tambah Pilihan**.
- **Tampilan di Responden:** Daftar checkbox, bisa dipilih lebih dari satu.
- **Visualisasi:** Mendukung Bar Chart. Total persentase bisa melampaui 100% karena satu responden dapat memilih lebih dari satu opsi.

### Dropdown (Select)
- **Kegunaan:** Sama dengan Radio Button (satu jawaban), tetapi pilihan dikemas dalam dropdown untuk menghemat ruang layar. Cocok jika opsi jawaban sangat banyak.
- **Konfigurasi:** Tambahkan pilihan jawaban. Mendukung custom navigation per pilihan (sama seperti Radio Button).
- **Tampilan di Responden:** Kotak dropdown yang bisa dibuka untuk melihat pilihan.
- **Visualisasi:** Mendukung Pie Chart dan Bar Chart.

### Multiple Choice Grid
- **Kegunaan:** Menampilkan matriks di mana setiap baris adalah satu pertanyaan turunan dan setiap kolom adalah skala penilaian yang sama. Cocok untuk skala Likert.
- **Konfigurasi di Form Builder:**
  - **Pilihan Jawaban:** Merupakan label baris (pertanyaan turunan).
  - **Kolom Grid:** Klik **Tambah Kolom** untuk menambah kolom (skala penilaian), misal: "Sangat Tidak Setuju", "Tidak Setuju", "Netral", "Setuju", "Sangat Setuju".
- **Tampilan di Responden:** Tabel grid dengan radio button di setiap sel.
- **Visualisasi:** Mendukung Bar Chart dan Pie Chart.

**[SCREENSHOT: Konfigurasi Multiple Choice Grid di Form Builder menampilkan pilihan jawaban (baris) dan kolom grid]**

### Date (Tanggal)
- **Kegunaan:** Pertanyaan yang memerlukan jawaban berupa tanggal. Contoh: "Tanggal mulai bekerja di instansi saat ini".
- **Tampilan di Responden:** Kalender date picker.
- **Visualisasi:** Umumnya tidak divisualisasikan.

### File Upload
- **Kegunaan:** Pertanyaan yang memerlukan unggahan dokumen.
- **Tampilan di Responden:** Tombol pilih file.
- **Visualisasi:** Tidak divisualisasikan.

### Gaji (Angka)
- **Kegunaan:** Khusus untuk memproses input nominal gaji dalam format angka. Contoh: "Berapa penghasilan rata-rata Anda per bulan?"
- **Tampilan di Responden:** Kolom input angka dengan format rupiah.
- **Validasi:** Hanya menerima karakter numerik.
- **Pengolahan di Dashboard:** Sistem melakukan pengelompokan (binning) otomatis berdasarkan rentang nominal untuk menghasilkan grafik distribusi gaji.
- **Visualisasi:** Mendukung Bar Chart dan Pie Chart.

**[SCREENSHOT: Contoh pengisian pertanyaan tipe Gaji oleh responden]**

## 5.4 Pengaturan Visualisasi per Pertanyaan

Setiap pertanyaan dapat dikonfigurasi visualisasinya:
- **Tidak ada visualisasi:** Pertanyaan tidak ditampilkan sebagai grafik di Dashboard Grafik (tetapi jawabannya tetap tersimpan dan bisa diekspor).
- **Bar Chart:** Jawaban ditampilkan sebagai grafik batang horizontal/vertikal.
- **Pie Chart:** Jawaban ditampilkan sebagai diagram lingkaran (pie).

## 5.5 Pengaturan Tabel Analitik

Jika checkbox **Tabel Analitik** dicentang pada pertanyaan, jawaban pertanyaan tersebut akan ditampilkan di halaman Dashboard Analitik dalam bentuk tabel tabulasi silang yang mempersilangkan jawaban dengan Program Studi dan/atau Tahun Lulus.

## 5.6 Urutan Pilihan Jawaban

Setiap pilihan jawaban pada pertanyaan Radio Button, Checkbox, Dropdown, dan Multiple Choice Grid dapat diurutkan menggunakan tombol panah atas (↑) dan panah bawah (↓) di sisi kiri setiap pilihan.

---

# BAB 6 BLOK PENILAIAN KOMPETENSI / INDIKATOR

## Tujuan Fitur

Fitur Mode Kompetensi memungkinkan admin mengonfigurasi sebuah blok survei agar seluruh pertanyaan di dalamnya diperlakukan sebagai indikator penilaian kompetensi. Semua indikator dalam blok tersebut akan diolah menjadi satu kesatuan tabel analitik dan visualisasi terintegrasi di dashboard.

## Cara Mengaktifkan

1. Di halaman Edit Survey (Form Builder), pada blok yang diinginkan, centang checkbox **"Jadikan sebagai Blok Penilaian Kompetensi/Indikator (Satu Tabel Analitik)"**.
2. Bagian konfigurasi tambahan akan muncul, termasuk field **Pertanyaan Utama**.

**[SCREENSHOT: Checkbox Mode Kompetensi pada blok di Form Builder dengan panel konfigurasi yang terbuka]**

## Aturan Sistem

Saat mode kompetensi diaktifkan, sistem menerapkan aturan berikut:
1. Seluruh pertanyaan dalam blok **wajib menggunakan tipe pertanyaan yang sama** (Radio Button, Checkbox, Dropdown, atau Multiple Choice Grid).
2. Seluruh **pilihan jawaban juga wajib sama/seragam** di semua pertanyaan dalam blok.
3. Pertanyaan pada blok ini **wajib memiliki label** (teks pertanyaan).
4. Tipe yang diperbolehkan: **Radio Button, Checkbox, Dropdown, Multiple Choice Grid**.

## Batasan Sistem

- Jika aturan di atas dilanggar (misalnya ada pertanyaan dengan tipe berbeda atau pilihan jawaban berbeda dalam satu blok kompetensi), sistem mungkin tidak mengolah data secara konsisten.
- Mode kompetensi dirancang untuk skala penilaian seragam, sehingga tidak cocok untuk pertanyaan campuran berbagai tipe.

## Cara Menambahkan Indikator

Setiap pertanyaan yang ditambahkan ke dalam blok kompetensi berfungsi sebagai satu indikator. Contoh:

1. Buat blok bernama "Penilaian Kompetensi Lulusan".
2. Aktifkan mode kompetensi.
3. Isi **Pertanyaan Utama**: "Bagaimana tingkat kompetensi [indikator] Anda?" (gunakan placeholder `[indikator]` agar teks pertanyaan otomatis diganti dengan nama indikator saat ditampilkan ke responden).
4. Tambahkan pertanyaan pertama: "Etika Kerja" (ini adalah indikator pertama).
5. Pilih tipe: Radio Button. Tambahkan pilihan jawaban: "Sangat Kurang (1)", "Kurang (2)", "Cukup (3)", "Baik (4)", "Sangat Baik (5)".
6. Tambahkan pertanyaan kedua: "Komunikasi" (indikator kedua). Dengan pilihan jawaban yang sama.
7. Ulangi untuk indikator lainnya: "Kepemimpinan", "Kerjasama Tim", "Manajemen Proyek", dsb.

## Cara Menampilkan ke Responden

Pada layar responden, jika blok memiliki pertanyaan utama dengan placeholder `[indikator]`, sistem akan mengganti `[indikator]` dengan teks pertanyaan (nama indikator). Contoh:
- Pertanyaan Utama: "Bagaimana tingkat kompetensi [indikator] Anda?"
- Indikator: "Komunikasi"
- Tampil ke responden: "Bagaimana tingkat kompetensi **Komunikasi** Anda?" (dengan "Komunikasi" disorot biru)

Jika tidak ada placeholder `[indikator]`, sistem akan menampilkan: "Bagaimana tingkat kompetensi Anda? **(Komunikasi)**"

**[SCREENSHOT: Tampilan pertanyaan mode kompetensi di layar responden dengan indikator disorot biru]**

## Cara Penyimpanan Data

Setiap jawaban indikator disimpan sebagai record terpisah di database:
- `survey_user_id`: ID relasi responden-survei
- `template_pertanyaan_id`: ID pertanyaan (indikator)
- `jawaban`: Nilai yang dipilih responden (misal: "Baik (4)")

## Cara Pengolahan Data

Pada Dashboard Grafik dan Analitik, sistem:
1. Mengumpulkan seluruh jawaban untuk semua indikator dalam blok kompetensi.
2. Mengolahnya menjadi satu tabel rangkuman gabungan yang menampilkan distribusi penilaian per indikator.
3. Menampilkan visualisasi terintegrasi yang memperlihatkan perbandingan antar indikator.

## Cara Menampilkan Hasil

- **Tabel Rangkuman:** Menampilkan per indikator, distribusi frekuensi dan persentase per pilihan jawaban.
- **Tabel Analitik:** Menampilkan tabulasi silang indikator kompetensi dengan dimensi Program Studi / Tahun Lulus.
- **Visualisasi:** Grafik (Bar/Pie) yang memvisualisasikan distribusi penilaian kompetensi.

### Contoh Hasil:

**Pertanyaan Utama:** "Bagaimana tingkat kompetensi [indikator] Anda?"

**Pilihan Jawaban:** Sangat Kurang (1), Kurang (2), Cukup (3), Baik (4), Sangat Baik (5)

**Indikator:**
1. Etika Kerja
2. Komunikasi
3. Kepemimpinan
4. Kerjasama Tim

**Contoh Tabel Rangkuman:**

| Indikator | Sangat Kurang | Kurang | Cukup | Baik | Sangat Baik | N |
|---|---|---|---|---|---|---|
| Etika Kerja | 2% | 5% | 20% | 45% | 28% | 200 |
| Komunikasi | 3% | 8% | 25% | 40% | 24% | 200 |
| Kepemimpinan | 5% | 12% | 30% | 35% | 18% | 200 |
| Kerjasama Tim | 1% | 4% | 15% | 50% | 30% | 200 |

---

# BAB 7 PANDUAN USER LULUSAN

Bagian ini ditujukan bagi lulusan (alumni) Politeknik Statistika STIS yang berpartisipasi sebagai responden tracer study.

## 7.1 Login

1. Buka URL sistem Tracer Study di browser Anda.
2. Klik tombol **Login** atau **Sign In** pada halaman utama.
3. Masukkan **Email** dan **Password** yang telah diberikan oleh Administrator.
4. Klik tombol **Log in**.
5. Jika kredensial benar, Anda akan diarahkan ke halaman beranda responden.

**[SCREENSHOT: Halaman login sistem Tracer Study]**

### Kemungkinan Error Login
- **"These credentials do not match our records"**: Email atau password salah. Periksa kembali pengetikan, perhatikan huruf besar/kecil.
- **Solusi:** Gunakan fitur **Lupa Password** di halaman login. Tautan reset akan dikirim ke email Anda. Jika email sudah tidak aktif, hubungi Administrator.

## 7.2 Halaman Beranda Responden

Setelah login, Anda akan melihat halaman beranda dengan dua panel utama:

### Panel Kiri: Informasi Survei
Menampilkan tabel atau daftar kartu survei yang ditugaskan kepada Anda. Setiap survei menampilkan:
- **Nama Survei**: Judul survei yang harus diisi.
- **Status**: Badge "Aktif" (hijau) atau "Tidak Aktif" (abu-abu).
- **Tanggal**: Rentang waktu survei aktif (tanggal mulai s/d tanggal selesai).
- **Aksi**: Tombol tindakan sesuai kondisi survei:
  - **ISI SURVEI** (hijau): Survei aktif dan Anda belum pernah mengisi. Klik untuk mulai mengisi.
  - **EDIT JAWABAN** (indigo): Survei aktif dan Anda sudah pernah submit. Klik untuk mengubah jawaban.
  - **DONE** (abu-abu): Survei sudah tidak aktif dan Anda sudah submit. Jawaban terkunci.
  - **SURVEI KADALUWARSA** (merah): Survei sudah melewati tanggal selesai dan Anda belum mengisi. Tidak dapat diisi lagi.

### Panel Kanan: Informasi User
Menampilkan data profil Anda: Nama, Email, NIP, Jabatan, Satuan Kerja, Unit Kerja, dan No HP. Anda dapat mengedit beberapa field (lihat bagian 7.7).

**[SCREENSHOT: Halaman beranda responden Lulusan menampilkan panel Informasi Survei dan Informasi User]**

## 7.3 Mengisi Survei

1. Pada tabel Informasi Survei, klik tombol **ISI SURVEI** pada survei yang ingin Anda isi.
2. Anda akan diarahkan ke halaman pengisian survei. Halaman menampilkan:
   - **Judul Survei** dan deskripsi di bagian atas.
   - **Progress:** Informasi "Pertanyaan X / Y" dan progress bar persentase.
   - **Blok Pertanyaan:** Label blok (jika ada) ditampilkan sebagai badge biru di atas pertanyaan.
   - **Pertanyaan:** Teks pertanyaan dengan tanda bintang merah (*) jika wajib diisi.
   - **Deskripsi Pertanyaan:** Penjelasan tambahan di bawah pertanyaan (jika ada).
   - **Input Jawaban:** Sesuai tipe pertanyaan (radio button, checkbox, input teks, dropdown, grid, date picker, atau input gaji).
   - **Tombol Lanjutkan:** Klik untuk menyimpan jawaban dan berpindah ke pertanyaan berikutnya.

**[SCREENSHOT: Halaman pengisian survei menampilkan pertanyaan Radio Button dengan progress bar]**

3. **Jawab pertanyaan** dan klik **Lanjutkan**.
4. Ulangi untuk setiap pertanyaan hingga seluruh pertanyaan selesai.

### Tips Pengisian (ditampilkan di layar):
"Jawab dengan jujur dan lengkap. Anda dapat mengubah jawaban sebelum melanjutkan ke pertanyaan berikutnya."

## 7.4 Autosave

Pada sistem ini, jawaban Anda **otomatis tersimpan setiap kali Anda mengklik tombol Lanjutkan**. Mekanisme penyimpanan bekerja per pertanyaan, sehingga:
- Anda tidak perlu menekan tombol "Save" terpisah.
- Jika koneksi internet terputus setelah Anda menjawab beberapa pertanyaan, jawaban yang sudah diklik "Lanjutkan" tetap aman tersimpan di server.

## 7.5 Melanjutkan Survei

Jika Anda menutup browser di tengah pengisian (misal setelah menjawab pertanyaan ke-5 dari 15):
1. Login kembali ke sistem.
2. Pada halaman beranda, survei yang sedang dikerjakan tetap menampilkan tombol **ISI SURVEI**.
3. Klik **ISI SURVEI**. Sistem akan mengarahkan Anda ke pertanyaan terakhir yang belum dijawab, bukan mengulangi dari awal.
4. Seluruh jawaban sebelumnya tetap utuh.

## 7.6 Submit Survei (Selesai)

1. Pada pertanyaan terakhir, setelah Anda mengklik **Lanjutkan**, sistem akan menyimpan jawaban terakhir dan menampilkan halaman **Selesai (Done)**.
2. Halaman ini mengonfirmasi bahwa Anda telah berhasil menyelesaikan survei.
3. Status survei Anda di tabel beranda berubah dari "ISI SURVEI" menjadi "EDIT JAWABAN" (jika survei masih aktif) atau "DONE" (jika survei sudah tidak aktif).

**[SCREENSHOT: Halaman Done setelah responden menyelesaikan survei]**

## 7.7 Edit Jawaban

Jika survei masih berstatus **Aktif** setelah Anda submit:
1. Di halaman beranda, tombol aksi akan berubah menjadi **EDIT JAWABAN** (indigo).
2. Klik **EDIT JAWABAN**.
3. Anda akan diarahkan ke halaman pengisian survei. Jawaban sebelumnya sudah terisi otomatis.
4. Ubah jawaban yang perlu diperbaiki, lalu klik **Lanjutkan** untuk menyimpan.

Jika survei sudah berstatus **Tidak Aktif**, aksi berubah menjadi **DONE** dan jawaban tidak dapat diubah lagi.

## 7.8 Edit Profil

1. Di panel **Informasi User** (kanan halaman beranda), klik tombol **Edit**.
2. Field yang dapat diedit akan berubah dari teks statis menjadi dropdown (untuk Jabatan, Satuan Kerja, Unit Kerja) atau input teks (untuk No HP).
3. Pilih nilai yang sesuai dari dropdown. Dropdown ini menggunakan fitur pencarian (Select2) — Anda bisa mengetik untuk mencari nilai yang tersedia.
4. Klik tombol **Simpan** untuk menyimpan perubahan.
5. Klik **Batal** jika ingin membatalkan perubahan.

**[SCREENSHOT: Panel Informasi User dalam mode edit menampilkan dropdown Select2 untuk Jabatan, Satuan Kerja, dan Unit Kerja]**

### Field yang TIDAK dapat diubah oleh responden:
- Nama, Email, NIP (hanya bisa diubah oleh Administrator).

## 7.9 Logout

1. Klik ikon profil atau menu pengguna di kanan atas halaman.
2. Klik **Logout**.
3. Anda akan diarahkan kembali ke halaman login.

**Catatan Penting:** Selalu logout jika Anda mengakses sistem dari komputer bersama atau milik orang lain.

---

# BAB 8 PANDUAN USER PENGGUNA LULUSAN

User Pengguna Lulusan adalah pihak eksternal (atasan langsung, HRD, atau pimpinan instansi) yang memberikan evaluasi terhadap kinerja lulusan Politeknik Statistika STIS di tempat kerja.

## 8.1 Login

1. Akses URL sistem Tracer Study.
2. Masukkan **Email** dan **Password** yang telah diberikan oleh Administrator Politeknik Statistika STIS.
3. Klik **Log in**.

## 8.2 Halaman Beranda

Tampilan halaman beranda sama dengan User Lulusan:
- **Panel Kiri (Informasi Survei):** Menampilkan daftar survei bertipe "Pengguna Lulusan" yang ditugaskan kepada Anda. Aksi yang tersedia sama: ISI SURVEI, EDIT JAWABAN, DONE, atau SURVEI KADALUWARSA.
- **Panel Kanan (Informasi User):** Menampilkan profil Anda: Nama, Email, NIP, Jabatan, Satuan Kerja, Unit Kerja, No HP.

## 8.3 Mengisi Survei (Evaluasi)

1. Klik **ISI SURVEI** pada survei yang ditugaskan.
2. Pertanyaan biasanya berfokus pada penilaian kompetensi dan kinerja lulusan (mode kompetensi). Contoh pertanyaan: "Bagaimana tingkat kompetensi **Etika Kerja** lulusan kami yang bekerja di instansi Anda?"
3. Jawab setiap pertanyaan berdasarkan pengamatan Anda di tempat kerja.
4. Klik **Lanjutkan** untuk berpindah ke pertanyaan berikutnya.

## 8.4 Autosave dan Melanjutkan

Mekanisme autosave dan melanjutkan survei sama persis dengan User Lulusan (lihat Bab 7.4 dan 7.5).

## 8.5 Submit Survei

Setelah pertanyaan terakhir diklik **Lanjutkan**, survei selesai dan halaman Done ditampilkan.

## 8.6 Edit Jawaban

Jika survei masih aktif, tombol **EDIT JAWABAN** tersedia di halaman beranda.

## 8.7 Edit Profil

Sama dengan User Lulusan — Anda dapat mengedit Jabatan, Satuan Kerja, Unit Kerja, dan No HP melalui panel Informasi User.

## 8.8 Logout

Sama dengan User Lulusan — klik menu profil di kanan atas > Logout.

---

# BAB 9 PANDUAN SUPERVISOR

Supervisor adalah peran pimpinan (seperti Direktur, Wakil Direktur, Kepala Unit) yang memiliki akses melihat data dan laporan tanpa kemampuan mengelola atau mengubah data.

## 9.1 Batasan Akses

Supervisor login melalui halaman yang sama dengan Administrator (panel admin), namun:
- **Seluruh tombol pengelolaan disembunyikan:** Tombol Tambah, Edit, Hapus, Import, Duplicate, Kirim Email, dan sejenisnya **tidak ditampilkan** pada antarmuka Supervisor.
- Supervisor **hanya dapat melihat** data dan **mengekspor** laporan.

## 9.2 Dashboard

Supervisor mengakses menu **Dashboard** dengan tampilan identik seperti Administrator:
- Melihat kartu statistik (Total Survei, Total Pengguna Lulusan, Total Lulusan).
- Melihat tabel Persentase Pengerjaan Survei.
- Mengklik tautan **Grafik**, **Analitik**, dan **Export Excel** pada setiap survei.

## 9.3 Monitoring

Supervisor mengakses menu **Monitoring** untuk:
- Melihat tabel response rate seluruh survei.
- Menggunakan filter Survei, Tahun Lulus, Program Studi, dan Jenis Visualisasi.
- Melihat grafik visualisasi response rate.
- **Mengekspor hasil survei** (Export Hasil Survei per baris survei).
- **Mengekspor visualisasi** (Export Hasil Visualisasi).

## 9.4 Melihat Daftar Survei

Supervisor dapat melihat halaman **Manajemen Survei** (Daftar Survei) dan mengklik **Details** untuk melihat informasi survei, daftar pertanyaan, dan daftar user. Namun, tombol Edit, Delete, Duplicate, Kirim Email, dan tombol manajemen responden tidak ditampilkan.

## 9.5 Melihat Daftar Lulusan dan Pengguna Lulusan

Supervisor dapat melihat tabel data Lulusan dan Pengguna Lulusan melalui menu sidebar yang sama, namun tombol Tambah Manual, Import Data, Export Data, Template Excel, Edit, dan Hapus tidak ditampilkan.

## 9.6 Export Data

Supervisor dapat mengekspor data melalui:
1. **Dashboard** > Export Excel.
2. **Monitoring** > Export Hasil Survei dan Export Hasil Visualisasi.

---

# BAB 10 DASHBOARD DAN ANALISIS DATA

## 10.1 Filter Tahun Lulus dan Program Studi

Pada halaman Dashboard Grafik dan Dashboard Analitik, filter global tersedia di bagian atas:
- **Tahun Lulus:** Pilih satu tahun lulus spesifik. Jika tidak dipilih, data semua tahun lulus diagregasi.
- **Program Studi:** Pilih satu program studi spesifik (DIV Komputasi Statistik, DIV Statistika, atau DIII Statistika). Jika tidak dipilih, data semua prodi diagregasi.

Jika kedua filter diubah, seluruh grafik dan tabel di halaman akan diperbarui sesuai subset data yang baru.

## 10.2 Grafik (Dashboard Grafik)

Setiap pertanyaan dengan pengaturan visualisasi akan ditampilkan sebagai:
- **Pie Chart:** Diagram lingkaran yang menampilkan proporsi setiap pilihan jawaban. Cocok untuk pertanyaan Radio Button/Dropdown yang total = 100%.
- **Bar Chart:** Diagram batang yang menampilkan frekuensi atau persentase setiap pilihan jawaban. Cocok untuk Checkbox (total bisa > 100%) dan pertanyaan numerik.

## 10.3 Tabel Rangkuman

Di bawah setiap grafik pada Dashboard Grafik, tabel rangkuman menampilkan:

| Pilihan Jawaban | Frekuensi (N) | Persentase (%) |
|---|---|---|
| Opsi A | 150 | 50% |
| Opsi B | 90 | 30% |
| Opsi C | 60 | 20% |
| **Total** | **300** | **100%** |

## 10.4 Tabel Analitik (Dashboard Analitik)

Tabel tabulasi silang menampilkan distribusi jawaban yang dipersilangkan dengan dimensi lain. Contoh:

| Program Studi | Bekerja | Wirausaha | Belum Bekerja | Total |
|---|---|---|---|---|
| DIV Komputasi Statistik | 85% | 10% | 5% | 200 |
| DIV Statistika | 80% | 12% | 8% | 150 |
| DIII Statistika | 75% | 15% | 10% | 100 |

## 10.5 Perhitungan Persentase

Untuk setiap pertanyaan pilihan, persentase dihitung dengan rumus:

**Persentase = (Jumlah responden yang memilih opsi tertentu ÷ Total responden yang menjawab pertanyaan tersebut) × 100%**

- Responden yang tidak menjawab pertanyaan (kosong) **tidak dihitung** dalam total pembagi.
- Untuk pertanyaan tipe **Checkbox**, total persentase seluruh opsi **dapat melebihi 100%** karena satu responden bisa memilih lebih dari satu opsi.

## 10.6 Perhitungan Response Rate

Pada halaman Monitoring:

**Response Rate = (Jumlah responden yang telah submit ÷ Total responden yang ditugaskan) × 100%**

Response rate dihitung dalam dua versi:
- **Keseluruhan:** Menggunakan seluruh responden survei tanpa filter.
- **Sesuai Filter:** Menggunakan hanya responden yang cocok dengan filter Tahun Lulus dan/atau Program Studi yang aktif.

## 10.7 Visualisasi Gaji

Pertanyaan bertipe "Gaji" diolah khusus:
1. Sistem mengumpulkan seluruh nilai nominal gaji dari responden.
2. Nilai dikelompokkan ke dalam rentang (binning) otomatis.
3. Distribusi per rentang ditampilkan sebagai grafik Bar Chart atau Pie Chart.
4. Rata-rata gaji dihitung: **Rata-rata = Total seluruh nominal gaji ÷ Jumlah responden yang mengisi**.

## 10.8 Visualisasi Kompetensi

Untuk blok dengan mode kompetensi, semua indikator dalam blok dikelompokkan dan ditampilkan sebagai satu unit visualisasi terintegrasi (lihat penjelasan lengkap di Bab 6).

## 10.9 Export Excel

Export Excel tersedia di beberapa titik:
1. **Dashboard > Export Excel per survei:** Mengekspor data jawaban mentah seluruh responden yang telah submit.
2. **Monitoring > Export Hasil Survei per survei:** Sama dengan di atas.
3. **Dashboard Analitik > Export:** Mengekspor tabel analitik.
4. **Monitoring > Export Hasil Visualisasi:** Mengekspor grafik response rate sebagai gambar PNG.

File Excel yang dihasilkan sudah dalam format yang siap digunakan, dengan header kolom yang deskriptif.

---

# BAB 11 PENANGANAN ERROR DAN TROUBLESHOOTING

| Masalah | Penyebab | Solusi |
|---|---|---|
| **Gagal Login — "These credentials do not match our records"** | Email atau password yang dimasukkan salah, atau akun belum terdaftar di sistem. | Periksa kembali pengetikan email dan password (perhatikan huruf besar/kecil). Gunakan fitur **Lupa Password**. Jika email tidak aktif, hubungi Administrator untuk reset akun. |
| **Gagal Import Excel — Pesan error muncul setelah upload** | Format file tidak sesuai (bukan .xls/.xlsx), kolom header berbeda dari template, ada baris dengan data tidak valid (format email salah, kolom wajib kosong). | Unduh ulang **Template Excel** dari sistem. Salin data Anda ke template baru menggunakan "Paste as Values". Pastikan tidak ada sel gabungan (merge cell). Pastikan seluruh kolom wajib terisi. |
| **Data Mismatch saat Import Lulusan** | Nilai Program Studi tidak sesuai dengan daftar valid (DIV Komputasi Statistik, DIV Statistika, DIII Statistika), atau format data tidak konsisten. | Periksa pesan error yang ditampilkan sistem (menyebutkan baris yang bermasalah). Perbaiki data pada file Excel dan impor ulang. |
| **Grafik tidak tampil di Dashboard Grafik** | Pertanyaan dikonfigurasi dengan visualisasi "Tidak ada visualisasi", atau tidak ada responden yang telah menjawab pertanyaan tersebut. | Periksa konfigurasi visualisasi pertanyaan di Form Builder. Pastikan ada responden yang telah submit survei. Pastikan filter tidak terlalu ketat. |
| **Dashboard lambat atau freeze saat dimuat** | Jumlah responden atau pertanyaan sangat banyak, server memerlukan waktu untuk menghitung kalkulasi. | Tunggu beberapa detik. Lakukan refresh halaman (F5). Gunakan filter Tahun Lulus atau Program Studi untuk memperkecil cakupan data. |
| **Survei tidak muncul di halaman beranda responden** | Admin belum menugaskan responden ke survei, atau survei belum berstatus Aktif (tanggal belum masuk rentang Tanggal Mulai - Tanggal Selesai). | Admin harus: (1) Memastikan responden telah ditambahkan di halaman Details Survey, dan (2) Memastikan tanggal saat ini berada dalam rentang Tanggal Mulai dan Tanggal Selesai survei. |
| **Status survei responden menampilkan "SURVEI KADALUWARSA"** | Survei sudah melewati Tanggal Selesai dan responden belum mengisi. | Admin perlu memperpanjang Tanggal Selesai survei melalui halaman Edit Survey jika ingin responden masih bisa mengisi. |
| **Export gagal atau file Excel kosong** | Data yang akan diekspor terlalu besar, atau tidak ada data yang sesuai filter. | Perkecil cakupan data menggunakan filter. Ekspor per tahun lulus atau per program studi secara terpisah. |
| **Data tidak tersimpan saat responden klik Lanjutkan** | Koneksi internet terputus saat pengiriman jawaban, atau session login sudah kedaluwarsa (timeout). | Responden harus login kembali. Jawaban yang telah disimpan pada pertanyaan sebelumnya tetap aman. Jawab ulang pertanyaan yang gagal tersimpan. |
| **Email undangan/reminder/terima kasih gagal terkirim** | Konfigurasi SMTP email server belum diatur dengan benar, atau alamat email responden tidak valid. | Hubungi tim IT untuk memeriksa konfigurasi SMTP server. Periksa alamat email responden di data lulusan/pengguna lulusan. |
| **Monitoring menampilkan validasi "Pilih survei terlebih dahulu..."** | Filter Tahun Lulus atau Program Studi diaktifkan tanpa memilih survei spesifik terlebih dahulu. | Pilih satu survei dari dropdown "Survei" sebelum mengaktifkan filter Tahun Lulus atau Program Studi. |
| **Responden tidak bisa mengubah Nama, Email, atau NIP di profil** | Field tersebut hanya bisa diubah oleh Administrator, bukan oleh responden sendiri. | Hubungi Administrator untuk mengubah data Nama, Email, atau NIP. |
| **Tombol Tambah/Edit/Hapus tidak muncul padahal login sebagai admin** | Akun Anda mungkin memiliki role Supervisor, bukan Administrator. | Periksa role akun Anda. Hubungi Super Admin untuk mengubah role jika diperlukan. |

---

# BAB 12 FAQ (FREQUENTLY ASKED QUESTIONS)

**1. Bagaimana jika lulusan lupa password?**
Gunakan fitur **Lupa Password** (Forgot Password) di halaman login. Tautan reset password akan dikirim ke email terdaftar. Jika email sudah tidak aktif, hubungi Administrator.

**2. Apakah kuesioner dapat diisi menggunakan smartphone?**
Ya. Tampilan sistem sudah responsif (mobile-friendly) dan dapat diakses melalui browser smartphone. Namun untuk kenyamanan optimal, disarankan menggunakan layar yang lebih besar.

**3. Bisakah saya melanjutkan survei besok jika hari ini sibuk?**
Bisa. Jawaban Anda tersimpan otomatis setiap kali Anda klik "Lanjutkan". Saat login kembali, Anda akan diarahkan ke pertanyaan terakhir yang belum dijawab.

**4. Mengapa total persentase pada grafik Bar Chart untuk pertanyaan Checkbox lebih dari 100%?**
Karena tipe Checkbox memungkinkan satu responden memilih lebih dari satu jawaban, sehingga total frekuensi seluruh opsi bisa melebihi jumlah responden.

**5. Apakah admin bisa melihat jawaban spesifik setiap responden?**
Ya. Melalui fitur Export Excel, admin dapat melihat data jawaban per responden secara detail.

**6. Berapa lama biasanya survei dibuka?**
Tergantung kebijakan instansi. Periode survei ditentukan oleh Admin melalui pengaturan Tanggal Mulai dan Tanggal Selesai.

**7. Saya sudah submit, tetapi ingin mengubah jawaban. Apakah bisa?**
Jika survei masih berstatus Aktif, tombol **EDIT JAWABAN** tersedia di halaman beranda Anda. Jika survei sudah Tidak Aktif, jawaban terkunci dan tidak bisa diubah.

**8. Apakah Pengguna Lulusan (Atasan) dapat mendaftar sendiri?**
Tidak. Akun Pengguna Lulusan harus dibuat oleh Administrator. Tidak tersedia fitur registrasi mandiri.

**9. Bagaimana sistem menentukan status Aktif/Tidak Aktif survei?**
Berdasarkan perbandingan tanggal saat ini dengan Tanggal Mulai dan Tanggal Selesai survei. Jika tanggal saat ini berada dalam rentang tersebut, survei berstatus Aktif.

**10. Bisakah admin menambah pertanyaan setelah survei sudah diisi oleh responden?**
Secara teknis bisa melalui halaman Edit Survey, namun sangat **tidak disarankan** karena responden yang sudah mengisi sebelumnya tidak akan menjawab pertanyaan baru tersebut, sehingga data menjadi tidak konsisten.

**11. Apa perbedaan Tabel Rangkuman dan Tabel Analitik?**
Tabel Rangkuman menampilkan distribusi jawaban satu pertanyaan (satu dimensi). Tabel Analitik menampilkan tabulasi silang jawaban dengan dimensi lain seperti Program Studi atau Tahun Lulus (dua dimensi).

**12. Apa itu "Status Data" pada Manajemen Lulusan?**
Status Data menunjukkan kelengkapan profil lulusan. "Data Lengkap" berarti field Prodi, Jabatan, Satuan Kerja, dan Unit Kerja semua terisi. "Data Tidak Lengkap" berarti ada yang kosong.

**13. Bisakah responden mengubah Nama atau Email sendiri?**
Tidak. Hanya field Jabatan, Satuan Kerja, Unit Kerja, dan No HP yang dapat diubah oleh responden melalui panel Informasi User.

**14. Apa yang terjadi jika saya menghapus survei yang sudah ada datanya?**
Seluruh pertanyaan, jawaban responden, dan data penugasan akan **terhapus permanen**. Pastikan Anda telah mengekspor data sebelum menghapus.

**15. Bagaimana cara mengirim email kepada responden yang belum mengisi?**
Buka halaman Details Survey, lalu klik tombol **Reminder Pengerjaan** (oranye). Email pengingat akan dikirim ke responden yang belum menyelesaikan survei.

**16. Apa itu Mode Kompetensi pada blok survei?**
Mode Kompetensi mengonfigurasi seluruh pertanyaan dalam satu blok sebagai indikator penilaian kompetensi dengan skala dan pilihan jawaban yang seragam. Data diolah menjadi satu kesatuan tabel dan visualisasi terintegrasi.

**17. Mengapa dropdown Tahun Lulus dan Program Studi di Monitoring berubah saat saya ganti survei?**
Karena sistem menerapkan cascading filter — opsi yang tersedia menyesuaikan secara dinamis berdasarkan data responden yang ada di survei yang dipilih.

**18. Apakah data di-backup secara otomatis?**
**[PERLU DIISI SESUAI IMPLEMENTASI SISTEM]** — Backup database merupakan tanggung jawab tim infrastruktur server/hosting instansi.

**19. Apa perbedaan "Export Hasil Survei" di Monitoring dengan "Export Excel" di Dashboard?**
Keduanya mengekspor data jawaban responden per survei. Fungsionalitasnya serupa, hanya berbeda lokasi aksesnya.

**20. Bagaimana jika filter pada Dashboard Grafik menghasilkan grafik kosong?**
Artinya tidak ada responden yang cocok dengan kombinasi filter Tahun Lulus dan Program Studi yang Anda pilih. Longgarkan filter atau pilih "Semua" untuk melihat data keseluruhan.

**21. Apakah ada batas jumlah pertanyaan dalam satu survei?**
Tidak ada batas teknis dari sistem. Namun, untuk menghindari kelelahan responden (survey fatigue), disarankan membatasi jumlah pertanyaan secara wajar (maksimal 40-50 pertanyaan).

**22. Apa itu "Navigasi Block" pada Form Builder?**
Pengaturan yang menentukan ke mana responden diarahkan setelah menyelesaikan pertanyaan dalam satu blok: ke blok berikutnya atau langsung mengakhiri survei.

**23. Bisakah satu pilihan jawaban Radio Button mengarahkan responden ke blok yang berbeda?**
Ya. Admin dapat mengaktifkan "Custom navigation" pada setiap pilihan jawaban Radio Button atau Dropdown. Jika dicentang, admin bisa menentukan target navigasi per pilihan (lanjut ke blok tertentu atau akhiri survei).

**24. Di mana saya bisa mengubah template email?**
Klik menu **Template Email** di sidebar admin, atau klik tombol **Kelola Template Email** di halaman Details Survey.

**25. Apa yang terjadi jika survei diduplikasi?**
Survei baru dibuat dengan seluruh struktur blok dan pertanyaan yang identik. Jawaban responden dan penugasan user **tidak ikut terduplikasi**.

**26. Bagaimana cara menambah responden ke survei secara massal?**
Di halaman Details Survey, gunakan dropdown **Tambah Responden by Tahun Lulus**. Pilih tahun lulus, dan seluruh lulusan pada tahun tersebut akan otomatis ditugaskan.

**27. Apakah Supervisor bisa mengekspor data?**
Ya. Supervisor dapat mengekspor data melalui Dashboard (Export Excel) dan Monitoring (Export Hasil Survei).

**28. Program Studi apa saja yang tersedia dalam sistem?**
Tiga program studi: DIV Komputasi Statistik, DIV Statistika, dan DIII Statistika.

**29. Bagaimana cara melihat daftar responden yang sudah/belum mengisi survei?**
Buka menu **Monitoring**, pilih survei dari filter, dan lihat kolom Response Rate. Untuk detail per responden, klik **Export Hasil Survei** untuk mengunduh daftar lengkap.

**30. Apa yang harus dilakukan jika muncul pesan "Data survei tidak ditemukan untuk filter yang dipilih" di Monitoring?**
Artinya tidak ada survei yang cocok dengan kata pencarian atau filter Anda. Klik tombol **Reset** untuk menghapus semua filter dan menampilkan seluruh data.

---

# BAB 13 LAMPIRAN

## Lampiran A: Struktur Data Sistem

Gambaran entitas utama dalam database:

| Tabel | Fungsi |
|---|---|
| `users` | Menyimpan kredensial login (email, password ter-hash) dan role pengguna. |
| `lulusan` | Master data profil lulusan: nama, nip, email, prodi, jabatan, satuan_kerja, unit_kerja, no_hp, tanggal_lahir, tahun_lulus, nip_pengguna_lulusan. |
| `pengguna_lulusan` | Master data profil pengguna lulusan (atasan): nama, nip, email, jabatan, satuan_kerja, unit_kerja, no_hp. |
| `master_jabatan` | Master data jabatan (field: nama). |
| `master_satuan_kerja` | Master data satuan kerja (field: nama). |
| `master_unit_kerja` | Master data unit kerja (field: nama). |
| `survey` | Data survei: nama, tanggal_mulai, tanggal_selesai, type_survei, deskripsi. |
| `survey_blocks` | Blok/section dalam survei: survey_id, kode, nama, deskripsi, urutan, is_terminal, navigation_type, is_kompetensi_mode, kompetensi_config. |
| `template_pertanyaan` | Pertanyaan dalam survei: id_survey, block_id, pertanyaan, deskripsi_pertanyaan, tipe, urutan, visualisasi, is_required, grid_columns, is_analytic_table. |
| `template_jawaban` | Pilihan jawaban: id_template_pertanyaan, pilihan_jawaban, urutan, navigation_target. |
| `survey_user` | Relasi penugasan responden ke survei: survey_id, user_id, status, current_question_id, tanggal_mengisi. |
| `survey_user_jawaban` | Jawaban responden: survey_user_id, template_pertanyaan_id, jawaban. |
| `template_email` | Template email yang dapat dikustomisasi oleh admin. |

## Lampiran B: Contoh Template Impor Lulusan

Format kolom header wajib pada file Excel template impor lulusan:

| nama | nip | email | prodi | jabatan | satuan_kerja | unit_kerja | no_hp | tanggal_lahir | tahun_lulus | nip_pengguna_lulusan |
|---|---|---|---|---|---|---|---|---|---|---|
| Ahmad Budiman | 199801012020 | ahmad@email.com | DIV Komputasi Statistik | Statistisi | BPS Provinsi Jawa Barat | Bidang IPDS | 081234567890 | 1998-01-01 | 2020 | 197001011995 |
| Siti Nurhaliza | 199902022021 | siti@email.com | DIV Statistika | Fungsional Umum | BPS Kabupaten Bogor | Subbag TU | 082345678901 | 1999-02-02 | 2021 | |

**Catatan Penting:**
- Jangan mengubah nama kolom header.
- Jangan menggunakan merge cell pada file Excel.
- Kolom `prodi` harus diisi persis sesuai salah satu dari: `DIV Komputasi Statistik`, `DIV Statistika`, `DIII Statistika`.

## Lampiran C: Contoh Template Impor Pengguna Lulusan

| nama | nip | email | jabatan | satuan_kerja | unit_kerja | no_hp |
|---|---|---|---|---|---|---|
| Dr. Budi Santoso | 197001011995 | budi.santoso@bps.go.id | Kepala Bidang IPDS | BPS Provinsi Jawa Barat | Bidang IPDS | 081122334455 |

## Lampiran D: Contoh Template Impor Master Satker

Format untuk import Jabatan / Satuan Kerja / Unit Kerja:

| nama |
|---|
| Statistisi |
| Pranata Komputer |
| Fungsional Umum |
| Kepala Bidang |

## Lampiran E: Contoh Konfigurasi Survei

**Survei: "Tracer Study 2026 — Lulusan"**

- **Tipe Survei:** Lulusan
- **Tanggal Mulai:** 2026-01-01
- **Tanggal Selesai:** 2026-06-30

**Blok 1: Data Pekerjaan**

| No | Pertanyaan | Tipe | Visualisasi | Wajib | Analitik |
|---|---|---|---|---|---|
| 1 | Apa status pekerjaan Anda saat ini? | Radio Button | Pie Chart | Ya | Ya |
| 2 | Nama instansi tempat Anda bekerja | Text Input | Tidak ada | Ya | Tidak |
| 3 | Berapa penghasilan rata-rata Anda per bulan? | Gaji | Bar Chart | Ya | Ya |

**Blok 2: Penilaian Kompetensi (Mode Kompetensi)**

- **Pertanyaan Utama:** "Seberapa baik kemampuan [indikator] yang Anda peroleh selama kuliah?"
- **Skala:** Sangat Kurang / Kurang / Cukup / Baik / Sangat Baik

| No | Indikator | Tipe | Visualisasi | Wajib |
|---|---|---|---|---|
| 1 | Etika dan Integritas | Radio Button | Bar Chart | Ya |
| 2 | Komunikasi | Radio Button | Bar Chart | Ya |
| 3 | Penguasaan Statistik | Radio Button | Bar Chart | Ya |
| 4 | Kemampuan IT | Radio Button | Bar Chart | Ya |

## Lampiran F: Contoh Tampilan Dashboard

**[SCREENSHOT: Halaman Dashboard admin lengkap dengan kartu statistik dan tabel persentase pengerjaan]**

**[SCREENSHOT: Halaman Dashboard Grafik menampilkan Pie Chart untuk pertanyaan status pekerjaan]**

**[SCREENSHOT: Halaman Dashboard Grafik menampilkan Bar Chart untuk pertanyaan kompetensi]**

**[SCREENSHOT: Halaman Dashboard Analitik menampilkan tabel tabulasi silang]**

## Lampiran G: Contoh Tabel Rangkuman

| Kategori Jawaban (Status Pekerjaan) | Frekuensi (N) | Persentase (%) |
|---|---|---|
| Bekerja sebagai PNS/ASN | 450 | 75.00% |
| Bekerja di BUMN/Swasta | 90 | 15.00% |
| Studi Lanjut | 30 | 5.00% |
| Belum Bekerja | 30 | 5.00% |
| **Total Responden** | **600** | **100.00%** |

## Lampiran H: Contoh Tabel Analitik

| Program Studi | PNS/ASN | BUMN/Swasta | Studi Lanjut | Belum Bekerja | Total (N) |
|---|---|---|---|---|---|
| DIV Komputasi Statistik | 72% | 18% | 6% | 4% | 250 |
| DIV Statistika | 78% | 12% | 5% | 5% | 200 |
| DIII Statistika | 76% | 14% | 4% | 6% | 150 |

## Lampiran I: Contoh Blok Penilaian Kompetensi (Tampilan Responden)

**Pertanyaan yang tampil di layar responden:**

"Seberapa baik kemampuan **Penguasaan Statistik** yang Anda peroleh selama kuliah?"

Pilihan jawaban (Radio Button):
- ○ Sangat Kurang
- ○ Kurang
- ○ Cukup
- ○ Baik
- ● Sangat Baik *(dipilih oleh responden)*

**[SCREENSHOT: Tampilan pertanyaan mode kompetensi di layar responden dengan indikator disorot biru dan radio button]**

---

**— AKHIR DOKUMEN —**

*Dokumen ini ditulis dalam format Markdown (.md) yang terstruktur ketat dengan heading hierarkis (H1-H4). Untuk mengonversi ke format DOCX:*
1. *Buka Microsoft Word.*
2. *Gunakan fitur Open > All Files, pilih file .md ini.*
3. *Atau gunakan tool konversi seperti Pandoc: `pandoc Buku_Pedoman_Sistem_Tracer_Study.md -o Buku_Pedoman_Sistem_Tracer_Study.docx`*
4. *Setelah dikonversi, gunakan fitur References > Table of Contents untuk generate Daftar Isi otomatis.*
5. *Ganti seluruh placeholder bertanda [SCREENSHOT: ...] dengan tangkapan layar aktual dari sistem.*
