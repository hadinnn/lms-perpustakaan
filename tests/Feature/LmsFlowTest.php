<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LmsFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $pustakawan;
    private Anggota $anggota;
    private Buku $buku;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic data for test
        $this->pustakawan = User::create([
            'name' => 'Sri Wahyuni',
            'email' => 'pustakawan@perpustakaan.go.id',
            'nip' => '198501012010012001',
            'password' => bcrypt('password123'),
            'role' => 'pustakawan',
            'is_active' => true,
        ]);

        $kategori = KategoriBuku::create([
            'nama' => 'Sains',
            'deskripsi' => 'Buku sains',
        ]);

        $this->buku = Buku::create([
            'isbn' => '978-979-756-001-1',
            'judul' => 'Sumatera Selatan dalam Sejarah',
            'pengarang' => 'Dr. Ahmad Zulkifli',
            'penerbit' => 'Penerbit Sriwijaya',
            'tahun_terbit' => 2020,
            'kategori_id' => $kategori->id,
            'stok_total' => 5,
            'stok_tersedia' => 5,
            'lokasi_rak' => 'A-01',
            'status' => 'tersedia',
        ]);

        $this->anggota = Anggota::create([
            'nik' => '1671040502950001',
            'nama' => 'Rizky Saputra',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1995-02-05',
            'alamat' => 'Jl. Sudirman No. 12, Palembang',
            'telepon' => '081234567890',
            'email' => 'rizky@email.com',
            'status' => 'aktif',
            'tanggal_bergabung' => '2022-01-15',
        ]);
    }

    public function test_guest_can_see_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('LMS Perpustakaan');
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $response = $this->post('/login', [
            'login' => 'pustakawan@perpustakaan.go.id',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->pustakawan);
    }

    public function test_user_cannot_login_with_incorrect_password()
    {
        $response = $this->post('/login', [
            'login' => 'pustakawan@perpustakaan.go.id',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $response = $this->actingAs($this->pustakawan)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Sri Wahyuni');
        $response->assertSee('Jatuh Tempo Terdekat');
    }

    public function test_authenticated_user_can_access_anggota_index()
    {
        $response = $this->actingAs($this->pustakawan)->get('/anggota');

        $response->assertStatus(200);
        $response->assertSee('Rizky Saputra');
    }

    public function test_authenticated_user_can_access_buku_index()
    {
        $response = $this->actingAs($this->pustakawan)->get('/buku');

        $response->assertStatus(200);
        $response->assertSee('Sumatera Selatan dalam Sejarah');
    }

    public function test_authenticated_user_can_access_transaksi_index()
    {
        $response = $this->actingAs($this->pustakawan)->get('/transaksi');

        $response->assertStatus(200);
        $response->assertSee('Terminal Transaksi');
    }

    public function test_ajax_search_nik()
    {
        $response = $this->actingAs($this->pustakawan)
            ->getJson('/transaksi/cari/nik?nik=1671040502950001');

        $response->assertStatus(200);
        $response->assertJsonPath('found', true);
        $response->assertJsonPath('anggota.nama', 'Rizky Saputra');
    }

    public function test_ajax_search_isbn()
    {
        $response = $this->actingAs($this->pustakawan)
            ->getJson('/buku/cari/isbn?isbn=978-979-756-001-1');

        $response->assertStatus(200);
        $response->assertJsonPath('found', true);
        $response->assertJsonPath('buku.judul', 'Sumatera Selatan dalam Sejarah');
    }

    public function test_borrow_and_return_flow()
    {
        $this->actingAs($this->pustakawan);

        // Perform borrow
        $borrowResponse = $this->post('/transaksi/pinjam', [
            'nik' => '1671040502950001',
            'isbn' => '978-979-756-001-1',
        ]);

        $borrowResponse->assertRedirect('/transaksi');
        $this->assertDatabaseHas('transaksi', [
            'anggota_id' => $this->anggota->id,
            'buku_id' => $this->buku->id,
            'status' => 'dipinjam',
        ]);

        // Stok should decrease
        $this->assertEquals(4, $this->buku->fresh()->stok_tersedia);

        // Perform return
        $transaksi = \App\Models\Transaksi::where('anggota_id', $this->anggota->id)->first();
        $returnResponse = $this->post('/transaksi/kembali', [
            'kode_transaksi' => $transaksi->kode_transaksi,
        ]);

        $returnResponse->assertRedirect('/transaksi');
        $this->assertDatabaseHas('transaksi', [
            'kode_transaksi' => $transaksi->kode_transaksi,
            'status' => 'dikembalikan',
        ]);

        // Stok should recover
        $this->assertEquals(5, $this->buku->fresh()->stok_tersedia);
    }

    // ─── Anggota CRUD Tests (previously failing due to {anggotum} route bug) ───

    public function test_can_view_anggota_detail()
    {
        $response = $this->actingAs($this->pustakawan)
            ->get("/anggota/{$this->anggota->id}");

        $response->assertStatus(200);
        $response->assertSee('Rizky Saputra');
        $response->assertSee('Profil Anggota');
    }

    public function test_can_view_anggota_edit_form()
    {
        $response = $this->actingAs($this->pustakawan)
            ->get("/anggota/{$this->anggota->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Ubah Data Anggota');
        $response->assertSee('Rizky Saputra');
    }

    public function test_can_update_anggota()
    {
        $response = $this->actingAs($this->pustakawan)
            ->put("/anggota/{$this->anggota->id}", [
                'nik'               => '1671040502950001',
                'nama'              => 'Rizky Saputra Wijaya',
                'jenis_kelamin'     => 'L',
                'tanggal_lahir'     => '1995-02-05',
                'alamat'            => 'Jl. Sudirman No. 12, Palembang',
                'telepon'           => '081234567890',
                'email'             => 'rizky@email.com',
                'tanggal_bergabung' => '2022-01-15',
                'status'            => 'aktif',
            ]);

        $response->assertRedirect('/anggota');
        $this->assertDatabaseHas('anggota', [
            'id'   => $this->anggota->id,
            'nama' => 'Rizky Saputra Wijaya',
        ]);
    }

    public function test_can_create_new_anggota()
    {
        $response = $this->actingAs($this->pustakawan)
            ->post('/anggota', [
                'nik'               => '1671050101000002',
                'nama'              => 'Dewi Lestari',
                'jenis_kelamin'     => 'P',
                'tanggal_lahir'     => '2000-01-01',
                'alamat'            => 'Jl. Merdeka No. 5, Palembang',
                'telepon'           => '082111222333',
                'email'             => 'dewi@email.com',
                'tanggal_bergabung' => '2024-01-01',
                'status'            => 'aktif',
            ]);

        $response->assertRedirect('/anggota');
        $this->assertDatabaseHas('anggota', [
            'nik'  => '1671050101000002',
            'nama' => 'Dewi Lestari',
        ]);
    }

    public function test_cannot_delete_anggota_with_active_loans()
    {
        $this->actingAs($this->pustakawan)->post('/transaksi/pinjam', [
            'nik'  => '1671040502950001',
            'isbn' => '978-979-756-001-1',
        ]);

        $response = $this->actingAs($this->pustakawan)
            ->delete("/anggota/{$this->anggota->id}");

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('anggota', ['id' => $this->anggota->id]);
    }

    public function test_can_delete_anggota_without_active_loans()
    {
        $anggotaBaru = Anggota::create([
            'nik'               => '1671050101990009',
            'nama'              => 'Ahmad Fauzi',
            'jenis_kelamin'     => 'L',
            'status'            => 'aktif',
            'tanggal_bergabung' => '2023-06-01',
        ]);

        $response = $this->actingAs($this->pustakawan)
            ->delete("/anggota/{$anggotaBaru->id}");

        $response->assertRedirect('/anggota');
        $this->assertDatabaseMissing('anggota', ['id' => $anggotaBaru->id]);
    }
}
