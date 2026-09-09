<?php

namespace Tests\Feature;

use App\Models\KenaikanPangkat;
use App\Models\Pegawai;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KenaikanPangkatTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $pegawai;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'id' => 1]);

        $this->admin = User::factory()->create(['role_id' => $adminRole->id]); // Asumsi admin

        $this->pegawai = Pegawai::create([
            'nama' => 'John Doe',
            'nip' => '199001012020121001',
            'pangkat' => 'Penata Muda',
            'golongan' => 'III/a',
            'jabatan' => 'Staff',
            'tmt_input' => '2020-01-01',
            'status_aktif' => true,
        ]);
    }

    public function test_admin_can_create_pengajuan_pangkat()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('dokumen.pdf', 100);

        $response = $this->actingAs($this->admin)->post(route('kepegawaian.pangkat.store'), [
            'pegawai_id' => $this->pegawai->id,
            'pangkat_lama' => $this->pegawai->pangkat,
            'golongan_lama' => $this->pegawai->golongan,
            'pangkat_baru' => 'Penata Muda Tingkat I',
            'golongan_baru' => 'III/b',
            'tmt' => '2024-04-01',
            'catatan' => 'Usulan reguler',
            'file_pendukung' => $file,
        ]);

        $this->assertDatabaseHas('kenaikan_pangkat', [
            'pegawai_id' => $this->pegawai->id,
            'pangkat_baru' => 'Penata Muda Tingkat I',
            'status' => 'draft',
        ]);

        $pangkat = KenaikanPangkat::first();
        
        // Assert attachment
        $this->assertCount(1, $pangkat->attachments);
        
        // Assert activity log
        $this->assertDatabaseHas('activity_logs', [
            'subject_type' => KenaikanPangkat::class,
            'subject_id' => $pangkat->id,
            'aktivitas' => 'Membuat pengajuan kenaikan pangkat: ' . $pangkat->nomor_usulan,
        ]);

        $response->assertRedirect(route('kepegawaian.pangkat.show', $pangkat));
    }

    public function test_workflow_transisi_status_hingga_selesai()
    {
        $pangkat = KenaikanPangkat::create([
            'nomor_usulan' => 'PKT-123456',
            'pegawai_id' => $this->pegawai->id,
            'pangkat_lama' => $this->pegawai->pangkat,
            'golongan_lama' => $this->pegawai->golongan,
            'pangkat_baru' => 'Penata Muda Tingkat I',
            'golongan_baru' => 'III/b',
            'tmt' => '2024-04-01',
            'status' => 'draft',
        ]);

        // draft -> diajukan
        $this->actingAs($this->admin)->put(route('kepegawaian.pangkat.status', $pangkat), [
            'status' => 'diajukan',
        ])->assertSessionHasNoErrors();
        $this->assertEquals('diajukan', $pangkat->fresh()->status);

        // diajukan -> verifikasi
        $this->actingAs($this->admin)->put(route('kepegawaian.pangkat.status', $pangkat), [
            'status' => 'verifikasi',
        ])->assertSessionHasNoErrors();
        $this->assertEquals('verifikasi', $pangkat->fresh()->status);

        // verifikasi -> diproses
        $this->actingAs($this->admin)->put(route('kepegawaian.pangkat.status', $pangkat), [
            'status' => 'diproses',
        ])->assertSessionHasNoErrors();
        $this->assertEquals('diproses', $pangkat->fresh()->status);

        // diproses -> selesai
        $this->actingAs($this->admin)->put(route('kepegawaian.pangkat.status', $pangkat), [
            'status' => 'selesai',
        ])->assertSessionHasNoErrors();
        $this->assertEquals('selesai', $pangkat->fresh()->status);

        // Assert Pegawai updated
        $this->pegawai->refresh();
        $this->assertEquals('Penata Muda Tingkat I', $this->pegawai->pangkat);
        $this->assertEquals('III/b', $this->pegawai->golongan);

        // Assert RiwayatJabatanPangkat created
        $this->assertDatabaseHas('riwayat_jabatan_pangkat', [
            'pegawai_id' => $this->pegawai->id,
            'pangkat' => 'Penata Muda Tingkat I',
            'golongan' => 'III/b',
        ]);
    }

    public function test_invalid_status_transition_is_rejected()
    {
        $pangkat = KenaikanPangkat::create([
            'nomor_usulan' => 'PKT-123456',
            'pegawai_id' => $this->pegawai->id,
            'pangkat_lama' => $this->pegawai->pangkat,
            'golongan_lama' => $this->pegawai->golongan,
            'pangkat_baru' => 'Penata Muda Tingkat I',
            'golongan_baru' => 'III/b',
            'tmt' => '2024-04-01',
            'status' => 'draft',
        ]);

        // draft -> selesai (invalid)
        $response = $this->actingAs($this->admin)->put(route('kepegawaian.pangkat.status', $pangkat), [
            'status' => 'selesai',
        ]);
        
        $response->assertSessionHas('error', 'Transisi status tidak valid.');
        $this->assertEquals('draft', $pangkat->fresh()->status);
    }
}
