<?php

namespace Tests\Feature\Phase3;

use App\Models\GajiBerkala;
use App\Models\KenaikanPangkat;
use App\Models\Pegawai;
use App\Models\PengajuanCuti;
use App\Models\User;
use App\Models\Role;
use App\Models\Surat;
use App\Models\JenisSurat;
use App\Models\KlasifikasiSurat;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class Phase3WorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed Roles
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'staff']);
        Role::firstOrCreate(['name' => 'verifikator']);
        Role::firstOrCreate(['name' => 'pimpinan']);
        
        // Register Observers for tests
        \App\Models\Pegawai::observe(\App\Observers\PegawaiObserver::class);
        \App\Models\PengajuanCuti::observe(\App\Observers\ActivityLogObserver::class);
        \App\Models\KenaikanPangkat::observe(\App\Observers\ActivityLogObserver::class);
        \App\Models\GajiBerkala::observe(\App\Observers\ActivityLogObserver::class);
    }

    public function test_cuti_workflow_and_number_generation()
    {
        $user = User::factory()->create(['role_id' => Role::where('name', 'staff')->first()->id]);
        $pegawai = Pegawai::create([
            'nip' => '12345',
            'nama' => 'PNS Test',
            'pangkat' => 'Penata',
            'golongan' => 'III/c',
            'jabatan' => 'Staf',
            'status_aktif' => true,
        ]);

        $this->actingAs($user);

        // 1. Create Draft
        $response = $this->post(route('kepegawaian.cuti.store'), [
            'pegawai_id' => $pegawai->id,
            'jenis_cuti' => 'Tahunan',
            'alasan' => 'Libur',
            'tanggal_mulai' => '2026-10-01',
            'tanggal_selesai' => '2026-10-05',
        ]);

        $cuti = PengajuanCuti::first();
        $this->assertEquals('draft', $cuti->status);
        $this->assertEquals(5, $cuti->lama_cuti);
        $this->assertNull($cuti->surat); // No surat yet
        
        // 2. Transisi Status dan Activity Log
        $this->put(route('kepegawaian.cuti.status', $cuti), ['status' => 'diajukan']);
        $cuti->refresh();
        $this->assertEquals('diajukan', $cuti->status);
        
        $log = \App\Models\ActivityLog::where('subject_type', PengajuanCuti::class)->where('action', 'updated')->latest()->first();
        $this->assertNotNull($log);
        $this->assertEquals('diajukan', $log->new_values['status']);
        
        // 3. Diterbitkan (Generate Surat & Nomor)
        $jenisSurat = JenisSurat::firstOrCreate(['kode' => 'CUTI', 'nama' => 'Surat Cuti']);
        $klasifikasi = KlasifikasiSurat::firstOrCreate(['kode' => '850', 'nama' => 'Kepegawaian', 'status' => 'aktif']);
        
        $this->put(route('kepegawaian.cuti.status', $cuti), ['status' => 'diterbitkan']);
        $cuti->refresh();
        $this->assertEquals('diterbitkan', $cuti->status);
        
        // Verify Surat generated
        $surat = $cuti->surat;
        $this->assertNotNull($surat);
        $this->assertEquals($jenisSurat->id, $surat->jenis_surat_id);
        $this->assertNotNull($surat->nomor_surat);
        $this->assertStringContainsString('850', $surat->nomor_surat);
    }

    public function test_kenaikan_pangkat_auto_update_pegawai()
    {
        $user = User::factory()->create(['role_id' => Role::where('name', 'staff')->first()->id]);
        $pegawai = Pegawai::create([
            'nip' => '12345',
            'nama' => 'PNS Pangkat',
            'pangkat' => 'Penata Muda',
            'golongan' => 'III/a',
            'jabatan' => 'Staf',
            'status_aktif' => true,
        ]);

        $this->actingAs($user);

        // 1. Create Usulan
        $this->post(route('kepegawaian.pangkat.store'), [
            'pegawai_id' => $pegawai->id,
            'pangkat_lama' => 'Penata Muda',
            'golongan_lama' => 'III/a',
            'pangkat_baru' => 'Penata Muda Tingkat I',
            'golongan_baru' => 'III/b',
            'tmt' => '2026-10-01',
        ]);

        $pangkat = KenaikanPangkat::first();
        $this->assertEquals('draft', $pangkat->status);
        
        // 2. Selesai -> trigger Pegawai update & history
        $response = $this->put(route('kepegawaian.pangkat.status', $pangkat), ['status' => 'selesai']);
        $response->assertSessionHasNoErrors();
        
        $pegawai->refresh();
        $this->assertEquals('Penata Muda Tingkat I', $pegawai->pangkat);
        $this->assertEquals('III/b', $pegawai->golongan);
        
        // Check History from Observer Phase 1
        $riwayat = \App\Models\RiwayatJabatanPangkat::where('pegawai_id', $pegawai->id)->orderByDesc('id')->first();
        $this->assertNotNull($riwayat);
        $this->assertEquals('Penata Muda Tingkat I', $riwayat->pangkat);
        $this->assertEquals('III/b', $riwayat->golongan);
        $this->assertEquals('2026-10-01', $riwayat->tmt->format('Y-m-d'));
    }

    public function test_gaji_berkala_tmt_calculation()
    {
        $user = User::factory()->create(['role_id' => Role::where('name', 'staff')->first()->id]);
        $pegawai = Pegawai::create([
            'nip' => '12345',
            'nama' => 'PNS KGB',
            'pangkat' => 'Penata',
            'golongan' => 'III/c',
            'jabatan' => 'Staf',
            'status_aktif' => true,
        ]);

        $this->actingAs($user);

        $this->post(route('kepegawaian.kgb.store'), [
            'pegawai_id' => $pegawai->id,
            'gaji_pokok_lama' => 3000000,
            'gaji_pokok_baru' => 3200000,
            'tmt_sebelumnya' => '2024-10-01',
        ]);

        $kgb = GajiBerkala::first();
        $this->assertEquals('2024-10-01', $kgb->tmt_sebelumnya->format('Y-m-d'));
        // +24 bulan = 2026-10-01
        $this->assertEquals('2026-10-01', $kgb->tmt_berikutnya->format('Y-m-d'));
    }
}
