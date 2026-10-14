<?php

namespace Tests\Feature;

use App\Models\BukuTamu;
use App\Models\Pegawai;
use App\Models\Role;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BukuTamuTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $this->admin = User::factory()->create([
            'role_id'   => $adminRole->id,
            'is_active' => true,
        ]);

        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $this->staff = User::factory()->create([
            'role_id'   => $staffRole->id,
            'is_active' => true,
        ]);

        Storage::fake('public');
    }

    public function test_form_publik_buku_tamu_dapat_diakses_dan_memiliki_3_section()
    {
        $unit = UnitKerja::create(['nama' => 'Seksi Lalu Lintas Jalan', 'kode_unit' => 'LLJ']);
        $pegawai = Pegawai::create([
            'nama' => 'Budi Santoso, S.T.',
            'nip' => '198501012010011001',
            'status_aktif' => true,
        ]);

        $response = $this->get(route('buku-tamu.create'));

        $response->assertStatus(200);
        $response->assertSee('Buku Tamu Digital');
        $response->assertSee('Identitas Pengunjung');
        $response->assertSee('Informasi Kunjungan');
        $response->assertSee('Dokumen Pendukung');
        $response->assertSee('Kirim Data Kunjungan');
        $response->assertSee($pegawai->nama);
        $response->assertSee($unit->nama);
    }

    public function test_publik_dapat_mengirim_data_kunjungan_tanpa_spt()
    {
        $payload = [
            'nama'      => 'Ahmad Fauzi',
            'no_hp'     => '081234567890',
            'instansi'  => 'PT Trans Nusa Mandiri',
            'keperluan' => 'Koordinasi perizinan trayek angkutan barang antarkota',
        ];

        $response = $this->post(route('buku-tamu.store'), $payload);

        $response->assertRedirect(route('buku-tamu.sukses'));

        $this->assertDatabaseHas('buku_tamu', [
            'nama'      => 'Ahmad Fauzi',
            'no_hp'     => '081234567890',
            'instansi'  => 'PT Trans Nusa Mandiri',
            'keperluan' => 'Koordinasi perizinan trayek angkutan barang antarkota',
        ]);

        $tamu = BukuTamu::where('nama', 'Ahmad Fauzi')->first();
        $this->assertNotNull($tamu);
        $this->assertEquals(now()->toDateString(), $tamu->tanggal->toDateString());
    }

    public function test_publik_dapat_mengirim_data_kunjungan_dengan_lampiran_spt()
    {
        $file = UploadedFile::fake()->create('surat_tugas.pdf', 300, 'application/pdf');

        $payload = [
            'nama'      => 'Siti Rahmawati',
            'no_hp'     => '082198765432',
            'instansi'  => 'Dinas Perhubungan Kabupaten Kampar',
            'keperluan' => 'Kunjungan kerja studi banding operasional terminal tipe B',
            'spt_file'  => $file,
        ];

        $response = $this->post(route('buku-tamu.store'), $payload);

        $response->assertRedirect(route('buku-tamu.sukses'));

        $tamu = BukuTamu::where('nama', 'Siti Rahmawati')->first();
        $this->assertNotNull($tamu);
        $this->assertNotNull($tamu->spt_file);
        Storage::disk('public')->assertExists($tamu->spt_file);
    }

    public function test_validasi_form_publik_wajib_mengisi_nama_kontak_dan_keperluan()
    {
        $response = $this->from(route('buku-tamu.create'))->post(route('buku-tamu.store'), []);

        $response->assertRedirect(route('buku-tamu.create'));
        $response->assertSessionHasErrors(['nama', 'no_hp', 'keperluan']);
    }

    public function test_validasi_spt_menolak_ekstensi_ilegal()
    {
        $file = UploadedFile::fake()->create('script.exe', 100);

        $response = $this->from(route('buku-tamu.create'))->post(route('buku-tamu.store'), [
            'nama'      => 'Pengunjung Ilegal',
            'no_hp'     => '0812345678',
            'keperluan' => 'Test invalid file',
            'spt_file'  => $file,
        ]);

        $response->assertRedirect(route('buku-tamu.create'));
        $response->assertSessionHasErrors(['spt_file']);
    }

    public function test_halaman_sukses_bisa_diakses()
    {
        $response = $this->get(route('buku-tamu.sukses'));

        $response->assertStatus(200);
        $response->assertSee('Terima Kasih!');
        $response->assertSee('Kembali ke Buku Tamu');
    }

    public function test_halaman_admin_buku_tamu_hanya_bisa_diakses_oleh_admin()
    {
        // Tamu (guest) diarahkan ke login
        $guestResp = $this->get(route('buku-tamu.index'));
        $guestResp->assertRedirect(route('login'));

        // Staff dibatasi (403 Forbidden)
        $staffResp = $this->actingAs($this->staff)->get(route('buku-tamu.index'));
        $staffResp->assertStatus(403);

        // Admin diizinkan (200 OK)
        $adminResp = $this->actingAs($this->admin)->get(route('buku-tamu.index'));
        $adminResp->assertStatus(200);
    }

    public function test_halaman_admin_menampilkan_tabel_dengan_pengelompokan_tanggal_dan_ringkasan()
    {
        BukuTamu::create([
            'nama'      => 'Tamu Hari Ini 1',
            'no_hp'     => '0811111111',
            'instansi'  => 'Balai Pengelola Transportasi Darat',
            'keperluan' => 'Rapat koordinasi rekayasa lalu lintas jalan tol',
            'tanggal'   => now()->toDateString(),
            'jam'       => '09:15:00',
        ]);

        BukuTamu::create([
            'nama'      => 'Tamu Hari Ini 2',
            'no_hp'     => '0822222222',
            'instansi'  => 'PT Jasa Raharja Cabang Riau',
            'keperluan' => 'Silaturahmi dan koordinasi keselamatan jalan',
            'tanggal'   => now()->toDateString(),
            'jam'       => '10:30:00',
        ]);

        $response = $this->actingAs($this->admin)->get(route('buku-tamu.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Riwayat Kunjungan');
        $response->assertSee('Tamu Hari Ini 1');
        $response->assertSee('Tamu Hari Ini 2');
        $response->assertSee('Total Kunjungan');
        $response->assertSee('QR Buku Tamu');
        $response->assertSee('Tren Kunjungan');
        $response->assertSee('kunjungan');
    }

    public function test_filter_tanggal_pada_halaman_admin()
    {
        BukuTamu::create([
            'nama'      => 'Tamu Kemarin',
            'no_hp'     => '0811111111',
            'keperluan' => 'Rapat kemarin',
            'tanggal'   => now()->subDays(5)->toDateString(),
            'jam'       => '08:00:00',
        ]);

        BukuTamu::create([
            'nama'      => 'Tamu Terkini',
            'no_hp'     => '0822222222',
            'keperluan' => 'Rapat hari ini',
            'tanggal'   => now()->toDateString(),
            'jam'       => '11:00:00',
        ]);

        // Filter hanya hari ini
        $today = now()->toDateString();
        $response = $this->actingAs($this->admin)->get(route('buku-tamu.index', [
            'start_date' => $today,
            'end_date'   => $today,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Tamu Terkini');
        $response->assertDontSee('Tamu Kemarin');
    }

    public function test_filter_tanggal_mulai_lebih_besar_dari_selesai_ditolak()
    {
        $response = $this->actingAs($this->admin)->get(route('buku-tamu.index', [
            'start_date' => '2026-10-20',
            'end_date'   => '2026-10-10',
        ]));

        $response->assertRedirect(route('buku-tamu.index'));
        $response->assertSessionHas('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.');
    }

    public function test_admin_dapat_menghapus_data_kunjungan_dan_file_spt()
    {
        $file = UploadedFile::fake()->create('spt_tamu.pdf', 200, 'application/pdf');
        $path = $file->storeAs('buku-tamu/2026/10', 'test_spt.pdf', 'public');

        $tamu = BukuTamu::create([
            'nama'      => 'Tamu Dihapus',
            'no_hp'     => '0899999999',
            'keperluan' => 'Keperluan testing hapus',
            'tanggal'   => now()->toDateString(),
            'jam'       => '14:00:00',
            'spt_file'  => $path,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($this->admin)->delete(route('buku-tamu.destroy', $tamu));

        $response->assertRedirect(route('buku-tamu.index'));
        $response->assertSessionHas('status', 'Data kunjungan berhasil dihapus.');

        $this->assertDatabaseMissing('buku_tamu', [
            'id' => $tamu->id,
        ]);

        Storage::disk('public')->assertMissing($path);
    }

    public function test_non_admin_tidak_dapat_menghapus_data_kunjungan()
    {
        $tamu = BukuTamu::create([
            'nama'      => 'Tamu Aman',
            'no_hp'     => '0899999999',
            'keperluan' => 'Keperluan testing keamanan',
            'tanggal'   => now()->toDateString(),
            'jam'       => '14:00:00',
        ]);

        $response = $this->actingAs($this->staff)->delete(route('buku-tamu.destroy', $tamu));

        $response->assertStatus(403);
        $this->assertDatabaseHas('buku_tamu', [
            'id' => $tamu->id,
        ]);
    }

    public function test_admin_index_memiliki_dataset_modal_detail_dan_trigger_aksi_yang_valid()
    {
        $tamu = BukuTamu::create([
            'nama'      => 'Hendro Prasetyo',
            'no_hp'     => '081233445566',
            'instansi'  => 'PT Trans Logistik Riau',
            'keperluan' => 'Konsultasi izin operasional armada',
            'tanggal'   => now()->toDateString(),
            'jam'       => '10:00:00',
        ]);

        $response = $this->actingAs($this->admin)->get(route('buku-tamu.index'));

        $response->assertStatus(200);

        // Verifikasi dataset JSON untuk Alpine modal
        $response->assertSee('id="buku-tamu-dataset"', false);
        $response->assertSee('Hendro Prasetyo');
        $response->assertSee('PT Trans Logistik Riau');

        // Verifikasi trigger modal Detail dan Hapus
        $response->assertSee('openDetail(' . $tamu->id . ')', false);
        $response->assertSee('confirmDelete(' . $tamu->id . ')', false);

        // Verifikasi CSS modal overlay dan dialog
        $response->assertSee('.sipas-modal-overlay', false);
        $response->assertSee('.sipas-modal-dialog', false);

        // Verifikasi form delete konfirmasi memiliki method DELETE dan action
        $response->assertSee('id="deleteBukuTamuForm"', false);
        $response->assertSee('name="_method" value="DELETE"', false);

        // Verifikasi adanya 2 tombol terpisah: Lihat File dan Unduh File
        $response->assertSee('Lihat File', false);
        $response->assertSee('Unduh File', false);
        $response->assertSee(':href="activeItem.spt_url"', false);
        $response->assertSee(':href="activeItem.spt_download"', false);
    }

    public function test_admin_dapat_mengunduh_berkas_spt()
    {
        $file = UploadedFile::fake()->create('surat_tugas.pdf', 150, 'application/pdf');
        $path = $file->storeAs('buku-tamu/2026/10', 'spt_test.pdf', 'public');

        $tamu = BukuTamu::create([
            'nama'      => 'Ahmad Rivai',
            'no_hp'     => '081299887766',
            'keperluan' => 'Kunjungan koordinasi teknis',
            'tanggal'   => now()->toDateString(),
            'jam'       => '09:00:00',
            'spt_file'  => $path,
        ]);

        $response = $this->actingAs($this->admin)->get(route('buku-tamu.download', $tamu));

        $response->assertStatus(200);
        $this->assertTrue(
            str_contains($response->headers->get('content-disposition') ?? '', 'attachment')
        );
        $this->assertTrue(
            str_contains($response->headers->get('content-disposition') ?? '', 'SPT-ahmad-rivai-')
        );
    }

    public function test_non_admin_dan_guest_tidak_dapat_mengunduh_berkas_spt()
    {
        $file = UploadedFile::fake()->create('surat_tugas.pdf', 150, 'application/pdf');
        $path = $file->storeAs('buku-tamu/2026/10', 'spt_secure.pdf', 'public');

        $tamu = BukuTamu::create([
            'nama'      => 'Tamu Rahasia',
            'no_hp'     => '081299887711',
            'keperluan' => 'Kunjungan rahasia',
            'tanggal'   => now()->toDateString(),
            'jam'       => '09:00:00',
            'spt_file'  => $path,
        ]);

        // Guest redirect login
        $guestResp = $this->get(route('buku-tamu.download', $tamu));
        $guestResp->assertRedirect(route('login'));

        // Staff 403 Forbidden
        $staffResp = $this->actingAs($this->staff)->get(route('buku-tamu.download', $tamu));
        $staffResp->assertStatus(403);
    }

    public function test_unduh_spt_berkas_hilang_mengembalikan_error()
    {
        $tamu = BukuTamu::create([
            'nama'      => 'Tamu File Hilang',
            'no_hp'     => '081299887722',
            'keperluan' => 'Kunjungan tanpa file di disk',
            'tanggal'   => now()->toDateString(),
            'jam'       => '09:00:00',
            'spt_file'  => 'buku-tamu/2026/10/nonexistent.pdf',
        ]);

        $response = $this->actingAs($this->admin)->get(route('buku-tamu.download', $tamu));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Berkas SPT tidak ditemukan di penyimpanan server.');
    }
}
