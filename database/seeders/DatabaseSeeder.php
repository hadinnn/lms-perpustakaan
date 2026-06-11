<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin & Petugas ──────────────────────────────────────────
        $admin = User::create([
            'name'      => 'Administrator LMS',
            'email'     => 'admin@perpustakaan.go.id',
            'nip'       => '196701011990011001',
            'password'  => Hash::make('password123'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        $petugas = User::create([
            'name'      => 'Sri Wahyuni',
            'email'     => 'pustakawan@perpustakaan.go.id',
            'nip'       => '198501012010012001',
            'password'  => Hash::make('password123'),
            'role'      => 'pustakawan',
            'is_active' => true,
        ]);

        // ── Kategori Buku ────────────────────────────────────────────
        $kategoriData = [
            ['nama' => 'Fiksi',           'deskripsi' => 'Novel dan cerita fiksi'],
            ['nama' => 'Non-Fiksi',       'deskripsi' => 'Buku berbasis fakta dan realita'],
            ['nama' => 'Sejarah',         'deskripsi' => 'Buku sejarah nasional dan daerah'],
            ['nama' => 'Sains & Teknologi', 'deskripsi' => 'Ilmu pengetahuan alam dan teknologi'],
            ['nama' => 'Sosial Budaya',   'deskripsi' => 'Ilmu sosial dan budaya lokal'],
            ['nama' => 'Referensi',       'deskripsi' => 'Kamus, ensiklopedia, dan referensi'],
            ['nama' => 'Anak-Anak',       'deskripsi' => 'Buku untuk anak-anak'],
            ['nama' => 'Hukum',           'deskripsi' => 'Buku hukum dan perundangan'],
        ];

        $kategori = [];
        foreach ($kategoriData as $k) {
            $kategori[] = KategoriBuku::create($k);
        }

        // ── Koleksi Buku ─────────────────────────────────────────────
        $bukuData = [
            ['isbn' => '978-979-756-001-1', 'judul' => 'Sumatera Selatan dalam Sejarah', 'pengarang' => 'Dr. Ahmad Zulkifli', 'penerbit' => 'Penerbit Sriwijaya', 'tahun_terbit' => 2020, 'kategori_id' => 3, 'stok_total' => 5, 'stok_tersedia' => 4, 'lokasi_rak' => 'A-01'],
            ['isbn' => '978-979-756-002-2', 'judul' => 'Atlas Flora Sumatera', 'pengarang' => 'Prof. Budi Santoso', 'penerbit' => 'LIPI Press', 'tahun_terbit' => 2019, 'kategori_id' => 4, 'stok_total' => 3, 'stok_tersedia' => 3, 'lokasi_rak' => 'B-03'],
            ['isbn' => '978-979-756-003-3', 'judul' => 'Teknik Budidaya Ikan Air Tawar', 'pengarang' => 'Ir. Hendra Gunawan', 'penerbit' => 'Agromedia', 'tahun_terbit' => 2021, 'kategori_id' => 4, 'stok_total' => 4, 'stok_tersedia' => 3, 'lokasi_rak' => 'B-07'],
            ['isbn' => '978-979-756-004-4', 'judul' => 'Songket: Warisan Budaya Palembang', 'pengarang' => 'Dr. Retno Wulandari', 'penerbit' => 'Balitbang Sumsel', 'tahun_terbit' => 2018, 'kategori_id' => 5, 'stok_total' => 2, 'stok_tersedia' => 2, 'lokasi_rak' => 'C-02'],
            ['isbn' => '978-979-756-005-5', 'judul' => 'Dasar-Dasar Pemrograman PHP', 'pengarang' => 'Andi Prasetyo', 'penerbit' => 'Informatika', 'tahun_terbit' => 2022, 'kategori_id' => 4, 'stok_total' => 6, 'stok_tersedia' => 5, 'lokasi_rak' => 'D-01'],
            ['isbn' => '978-979-756-006-6', 'judul' => 'Hukum Agraria Indonesia', 'pengarang' => 'Prof. Dr. Boedi Harsono', 'penerbit' => 'Djambatan', 'tahun_terbit' => 2017, 'kategori_id' => 8, 'stok_total' => 3, 'stok_tersedia' => 3, 'lokasi_rak' => 'E-04'],
            ['isbn' => '978-979-756-007-7', 'judul' => 'Kumpulan Cerita Rakyat Sumatera', 'pengarang' => 'Yuniati, S.Pd', 'penerbit' => 'Erlangga', 'tahun_terbit' => 2020, 'kategori_id' => 1, 'stok_total' => 8, 'stok_tersedia' => 7, 'lokasi_rak' => 'A-05'],
            ['isbn' => '978-979-756-008-8', 'judul' => 'Ensiklopedia Anak Indonesia', 'pengarang' => 'Tim Redaksi', 'penerbit' => 'Gramedia', 'tahun_terbit' => 2021, 'kategori_id' => 7, 'stok_total' => 10, 'stok_tersedia' => 9, 'lokasi_rak' => 'F-01'],
            ['isbn' => '978-979-756-009-9', 'judul' => 'Ekonomi Pembangunan Daerah', 'pengarang' => 'Dr. Mansyur Alfikri', 'penerbit' => 'UI Press', 'tahun_terbit' => 2019, 'kategori_id' => 2, 'stok_total' => 4, 'stok_tersedia' => 4, 'lokasi_rak' => 'G-02'],
            ['isbn' => '978-979-756-010-0', 'judul' => 'Kamus Besar Bahasa Indonesia', 'pengarang' => 'Badan Bahasa', 'penerbit' => 'Balai Pustaka', 'tahun_terbit' => 2023, 'kategori_id' => 6, 'stok_total' => 5, 'stok_tersedia' => 5, 'lokasi_rak' => 'H-01'],
        ];

        $buku = [];
        foreach ($bukuData as $b) {
            $buku[] = Buku::create($b);
        }

        // ── Data Anggota ─────────────────────────────────────────────
        $anggotaData = [
            ['nik' => '1671040502950001', 'nama' => 'Rizky Saputra', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '1995-02-05', 'alamat' => 'Jl. Sudirman No. 12, Palembang', 'telepon' => '081234567890', 'email' => 'rizky@email.com', 'status' => 'aktif', 'tanggal_bergabung' => '2022-01-15'],
            ['nik' => '1671025510010002', 'nama' => 'Maya Amalia', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '2001-10-15', 'alamat' => 'Jl. Merdeka No. 45, Palembang', 'telepon' => '085678901234', 'email' => 'maya@email.com', 'status' => 'aktif', 'tanggal_bergabung' => '2022-03-20'],
            ['nik' => '1671081103880005', 'nama' => 'Bambang Nurdin', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '1988-11-03', 'alamat' => 'Jl. Ahmad Yani No. 78, Palembang', 'telepon' => '087890123456', 'email' => 'bambang@email.com', 'status' => 'aktif', 'tanggal_bergabung' => '2021-06-10'],
            ['nik' => '1671056708930003', 'nama' => 'Siti Rahayu', 'jenis_kelamin' => 'P', 'tanggal_lahir' => '1993-07-27', 'alamat' => 'Jl. Diponegoro No. 33, Palembang', 'telepon' => '089012345678', 'email' => 'siti@email.com', 'status' => 'aktif', 'tanggal_bergabung' => '2023-01-05'],
            ['nik' => '1671091204870004', 'nama' => 'Dedi Kurniawan', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '1987-04-12', 'alamat' => 'Jl. Veteran No. 21, Palembang', 'telepon' => '082345678901', 'email' => 'dedi@email.com', 'status' => 'nonaktif', 'tanggal_bergabung' => '2020-09-15'],
        ];

        $anggotaList = [];
        foreach ($anggotaData as $a) {
            $anggotaList[] = Anggota::create($a);
        }

        // ── Data Transaksi ────────────────────────────────────────────
        // Transaksi aktif (sedang dipinjam)
        Transaksi::create([
            'kode_transaksi' => 'TRX20240601001',
            'anggota_id'     => $anggotaList[0]->id,
            'buku_id'        => $buku[0]->id,
            'petugas_id'     => $petugas->id,
            'tanggal_pinjam' => Carbon::today()->subDays(5),
            'tanggal_jatuh_tempo' => Carbon::today()->addDays(9),
            'status' => 'dipinjam',
        ]);

        // Transaksi terlambat
        Transaksi::create([
            'kode_transaksi' => 'TRX20240515001',
            'anggota_id'     => $anggotaList[2]->id,
            'buku_id'        => $buku[1]->id,
            'petugas_id'     => $petugas->id,
            'tanggal_pinjam' => Carbon::today()->subDays(20),
            'tanggal_jatuh_tempo' => Carbon::today()->subDays(6),
            'status' => 'terlambat',
            'denda'  => 6000,
        ]);

        // Transaksi selesai (dikembalikan)
        Transaksi::create([
            'kode_transaksi' => 'TRX20240510001',
            'anggota_id'     => $anggotaList[1]->id,
            'buku_id'        => $buku[2]->id,
            'petugas_id'     => $petugas->id,
            'tanggal_pinjam' => Carbon::today()->subDays(15),
            'tanggal_jatuh_tempo' => Carbon::today()->subDays(1),
            'tanggal_kembali' => Carbon::today()->subDays(2),
            'status' => 'dikembalikan',
            'denda'  => 0,
            'denda_dibayar' => true,
        ]);

        // Update stok buku yang sedang dipinjam
        $buku[0]->decrement('stok_tersedia');
        $buku[0]->updateStatusStok();

        $buku[1]->decrement('stok_tersedia');
        $buku[1]->updateStatusStok();
    }
}
