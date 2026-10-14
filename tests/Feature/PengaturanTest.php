<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengaturanTest extends TestCase
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

    public function test_halaman_pengaturan_bisa_diakses_oleh_admin()
    {
        $response = $this->actingAs($this->admin)->get(route('pengaturan.index'));

        $response->assertStatus(200);
        $response->assertSee('Pengaturan Umum');
        $response->assertSee('Identitas Instansi');
        $response->assertSee('Profil Pengguna');
        $response->assertSee('Preferensi Sistem');
        $response->assertSee('Tampilan Dashboard');
        $response->assertSee('Notifikasi');
        $response->assertSee('Keamanan &amp; Akses', false);
    }

    public function test_halaman_pengaturan_tidak_bisa_diakses_oleh_non_admin()
    {
        $response = $this->actingAs($this->staff)->get(route('pengaturan.index'));
        $response->assertStatus(403);
    }

    public function test_guest_diarahkan_ke_login_saat_akses_pengaturan()
    {
        $response = $this->get(route('pengaturan.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_dapat_memperbarui_identitas_instansi()
    {
        $response = $this->actingAs($this->admin)->put(route('pengaturan.identitas.update'), [
            'instansi_nama'    => 'Dinas Perhubungan Riau Baru',
            'instansi_sub'     => 'Pemprov Riau',
            'app_name'         => 'SIPAS-RIAU',
            'app_description'  => 'Sistem Persuratan Terpadu',
            'instansi_alamat'  => 'Jl. Sudirman No. 100, Pekanbaru',
            'instansi_telepon' => '0761-12345',
            'instansi_email'   => 'sekretariat@dishub.riau.go.id',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Identitas instansi dan aplikasi berhasil disimpan.');

        $this->assertEquals('Dinas Perhubungan Riau Baru', SystemSetting::getSetting('instansi_nama'));
        $this->assertEquals('SIPAS-RIAU', SystemSetting::getSetting('app_name'));
        $this->assertEquals('sekretariat@dishub.riau.go.id', SystemSetting::getSetting('instansi_email'));
    }

    public function test_admin_dapat_mengunggah_dan_mereset_logo_instansi()
    {
        $file = UploadedFile::fake()->image('logo-dishub-baru.png', 100, 100);

        $response = $this->actingAs($this->admin)->put(route('pengaturan.identitas.update'), [
            'instansi_nama' => 'Dinas Perhubungan',
            'app_name'      => 'SIPAS',
            'instansi_logo' => $file,
        ]);

        $response->assertRedirect();
        $logoPath = SystemSetting::getSetting('instansi_logo');
        $this->assertNotNull($logoPath);
        Storage::disk('public')->assertExists($logoPath);

        // Reset logo ke default
        $resetResponse = $this->actingAs($this->admin)->delete(route('pengaturan.logo.reset'));
        $resetResponse->assertRedirect();
        $this->assertNull(SystemSetting::getSetting('instansi_logo'));
        Storage::disk('public')->assertMissing($logoPath);
    }

    public function test_validasi_logo_menolak_file_bukan_gambar()
    {
        $invalidFile = UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin)->put(route('pengaturan.identitas.update'), [
            'instansi_nama' => 'Dinas Perhubungan',
            'app_name'      => 'SIPAS',
            'instansi_logo' => $invalidFile,
        ]);

        $response->assertSessionHasErrors(['instansi_logo']);
    }

    public function test_admin_dapat_memperbarui_preferensi_sistem()
    {
        $response = $this->actingAs($this->admin)->put(route('pengaturan.preferensi.update'), [
            'app_timezone' => 'Asia/Makassar',
            'date_format'  => 'd F Y',
            'time_format'  => '12',
            'app_locale'   => 'id',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Preferensi sistem berhasil disimpan.');

        $this->assertEquals('Asia/Makassar', SystemSetting::getSetting('app_timezone'));
        $this->assertEquals('d F Y', SystemSetting::getSetting('date_format'));
        $this->assertEquals('12', SystemSetting::getSetting('time_format'));
    }

    public function test_admin_dapat_memperbarui_tampilan_default()
    {
        $response = $this->actingAs($this->admin)->put(route('pengaturan.tampilan.update'), [
            'default_theme'  => 'dark',
            'default_accent' => 'teal',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Pengaturan tema default sistem berhasil disimpan.');

        $this->assertEquals('dark', SystemSetting::getSetting('default_theme'));
        $this->assertEquals('teal', SystemSetting::getSetting('default_accent'));
    }

    public function test_admin_dapat_memperbarui_notifikasi()
    {
        $response = $this->actingAs($this->admin)->put(route('pengaturan.notifikasi.update'), [
            'reminder_kgb_days' => 45,
            'reminder_kp_days'  => 60,
            'reminder_bup_days' => 120,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Parameter notifikasi dan pengingat berhasil disimpan.');

        $this->assertEquals(45, SystemSetting::getSetting('reminder_kgb_days'));
        $this->assertEquals(60, SystemSetting::getSetting('reminder_kp_days'));
        $this->assertEquals(120, SystemSetting::getSetting('reminder_bup_days'));
    }

    public function test_admin_dapat_mengunggah_banner_dashboard()
    {
        $file = UploadedFile::fake()->image('banner.jpg', 1200, 400);

        $response = $this->actingAs($this->admin)->post(route('pengaturan.banner.update'), [
            'banner_image' => $file,
        ]);

        $response->assertRedirect();
        $bannerPath = SystemSetting::getSetting('dashboard_banner_image');
        $this->assertNotNull($bannerPath);
        Storage::disk('public')->assertExists($bannerPath);
    }

    public function test_non_admin_tidak_bisa_mengubah_pengaturan_secara_langsung()
    {
        $response = $this->actingAs($this->staff)->put(route('pengaturan.identitas.update'), [
            'instansi_nama' => 'Hacked Name',
            'app_name'      => 'HACKED',
        ]);

        $response->assertStatus(403);
        $this->assertNotEquals('Hacked Name', SystemSetting::getSetting('instansi_nama'));
    }
}
