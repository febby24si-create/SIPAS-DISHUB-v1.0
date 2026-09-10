<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\GajiBerkala;
use App\Models\JenisSurat;
use App\Models\KenaikanPangkat;
use App\Models\PengajuanCuti;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $pegawai;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->pegawai = \App\Models\Pegawai::factory()->create();
    }

    public function test_draft_tidak_dihitung_sebagai_total_surat()
    {
        $jenisSurat = JenisSurat::create(['kode' => 'UM', 'nama' => 'Umum']);

        Surat::create(['jenis_surat_id' => $jenisSurat->id, 'status' => 'draft', 'arah' => 'keluar', 'perihal' => 'Draft 1', 'created_by' => $this->admin->id]);
        Surat::create(['jenis_surat_id' => $jenisSurat->id, 'status' => 'final', 'arah' => 'masuk', 'perihal' => 'Final 1', 'created_by' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('totalSurat', 1);
        $response->assertViewHas('totalDraft', 1);
    }

    public function test_surat_masuk_hanya_menghitung_surat_final()
    {
        $jenisSurat = JenisSurat::create(['kode' => 'UM', 'nama' => 'Umum']);

        Surat::create(['jenis_surat_id' => $jenisSurat->id, 'status' => 'draft', 'arah' => 'masuk', 'perihal' => 'Draft Masuk', 'created_by' => $this->admin->id]);
        Surat::create(['jenis_surat_id' => $jenisSurat->id, 'status' => 'final', 'arah' => 'masuk', 'perihal' => 'Final Masuk', 'created_by' => $this->admin->id]);
        Surat::create(['jenis_surat_id' => $jenisSurat->id, 'status' => 'final', 'arah' => 'keluar', 'perihal' => 'Final Keluar', 'created_by' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertViewHas('totalMasuk', 1);
        $response->assertViewHas('totalKeluar', 1);
    }

    public function test_cuti_pending_dihitung_dengan_benar()
    {
        PengajuanCuti::create(['pegawai_id' => $this->pegawai->id, 'jenis_cuti' => 'Tahunan', 'tanggal_mulai' => now(), 'tanggal_selesai' => now(), 'lama_cuti' => 1, 'alasan' => 'Libur', 'status' => 'diajukan']);
        PengajuanCuti::create(['pegawai_id' => $this->pegawai->id, 'jenis_cuti' => 'Besar', 'tanggal_mulai' => now(), 'tanggal_selesai' => now(), 'lama_cuti' => 1, 'alasan' => 'Libur', 'status' => 'verifikasi']);
        PengajuanCuti::create(['pegawai_id' => $this->pegawai->id, 'jenis_cuti' => 'Sakit', 'tanggal_mulai' => now(), 'tanggal_selesai' => now(), 'lama_cuti' => 1, 'alasan' => 'Sakit', 'status' => 'selesai']);

        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertViewHas('cutiPending', 2);
    }

    public function test_kenaikan_pangkat_pending_dihitung_dengan_benar()
    {
        KenaikanPangkat::create(['pegawai_id' => $this->pegawai->id, 'pangkat_lama' => 'Penata', 'golongan_lama' => 'III/c', 'pangkat_baru' => 'Penata Tk. I', 'golongan_baru' => 'III/d', 'tmt' => now(), 'status' => 'diajukan']);
        KenaikanPangkat::create(['pegawai_id' => $this->pegawai->id, 'pangkat_lama' => 'Penata', 'golongan_lama' => 'III/c', 'pangkat_baru' => 'Penata Tk. I', 'golongan_baru' => 'III/d', 'tmt' => now(), 'status' => 'diproses']);
        KenaikanPangkat::create(['pegawai_id' => $this->pegawai->id, 'pangkat_lama' => 'Penata', 'golongan_lama' => 'III/c', 'pangkat_baru' => 'Penata Tk. I', 'golongan_baru' => 'III/d', 'tmt' => now(), 'status' => 'ditolak']);

        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertViewHas('kenaikanPangkatPending', 2);
    }

    public function test_gaji_berkala_pending_dihitung_dengan_benar()
    {
        GajiBerkala::create(['pegawai_id' => $this->pegawai->id, 'gaji_pokok_lama' => 1000, 'gaji_pokok_baru' => 2000, 'tmt_sebelumnya' => now(), 'tmt_berikutnya' => now()->addYears(2), 'status' => 'verifikasi']);
        GajiBerkala::create(['pegawai_id' => $this->pegawai->id, 'gaji_pokok_lama' => 1000, 'gaji_pokok_baru' => 2000, 'tmt_sebelumnya' => now(), 'tmt_berikutnya' => now()->addYears(2), 'status' => 'disetujui']);
        GajiBerkala::create(['pegawai_id' => $this->pegawai->id, 'gaji_pokok_lama' => 1000, 'gaji_pokok_baru' => 2000, 'tmt_sebelumnya' => now(), 'tmt_berikutnya' => now()->addYears(2), 'status' => 'selesai']);

        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertViewHas('gajiBerkalaPending', 2);
    }

    public function test_dashboard_dapat_menampilkan_activity_log()
    {
        ActivityLog::create([
            'user_id' => $this->admin->id,
            'aktivitas' => 'membuat dokumen baru',
        ]);

        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertSee('membuat dokumen baru');
        $response->assertSee($this->admin->name);
    }

    public function test_dashboard_search_mengarah_ke_pencarian()
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard'));
        $response->assertSee(route('pencarian.index'));
        $response->assertSee('name="q"', false);
    }

    public function test_data_grafik_tren_berasal_dari_database()
    {
        $jenisSurat = \App\Models\JenisSurat::create(['kode' => 'UM', 'nama' => 'Umum']);
        \App\Models\Surat::create([
            'jenis_surat_id' => $jenisSurat->id,
            'arah'           => 'masuk',
            'perihal'        => 'Surat Tren Test',
            'tanggal_surat'  => now(),
            'status'         => 'final',
            'created_by'     => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('dashboard'));
        $response->assertViewHas('trendLabels');
        $response->assertViewHas('trendMasuk');
        $response->assertViewHas('trendKeluar');

        // Harus ada 6 bulan di label tren
        $this->assertCount(6, $response->viewData('trendLabels'));
    }

    public function test_data_distribusi_jenis_surat_berasal_dari_database()
    {
        $jenisSurat = \App\Models\JenisSurat::create(['kode' => 'SK', 'nama' => 'Surat Keputusan']);
        \App\Models\Surat::create([
            'jenis_surat_id' => $jenisSurat->id,
            'arah'           => 'keluar',
            'perihal'        => 'SK Test Distribusi',
            'tanggal_surat'  => now(),
            'status'         => 'final',
            'created_by'     => $this->admin->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('dashboard'));
        $response->assertViewHas('distribusiLabels');
        $response->assertViewHas('distribusiData');

        // SK harus muncul di label distribusi
        $labels = $response->viewData('distribusiLabels');
        $this->assertContains('SK', $labels);
    }
}
