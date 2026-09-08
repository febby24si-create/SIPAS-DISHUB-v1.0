<?php

namespace Tests\Feature\Phase2;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Surat;
use App\Models\JenisSurat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_activity_log()
    {
        $user = User::factory()->create();
        $jenisSurat = JenisSurat::create(['kode' => 'UM', 'nama' => 'Umum']);
        $surat = Surat::create([
            'jenis_surat_id' => $jenisSurat->id,
            'arah' => 'keluar',
            'perihal' => 'Test',
            'tanggal_surat' => '2026-09-01',
            'created_by' => $user->id,
        ]);

        $log = ActivityLog::create([
            'user_id' => $user->id,
            'surat_id' => $surat->id,
            'aktivitas' => 'Membuat surat lama',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'id' => $log->id,
            'surat_id' => $surat->id,
            'aktivitas' => 'Membuat surat lama',
        ]);
    }

    public function test_polymorphic_activity_log()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Register observer manually for testing
        \App\Models\Pegawai::observe(\App\Observers\ActivityLogObserver::class);

        $pegawai = \App\Models\Pegawai::create([
            'nip' => '123',
            'nama' => 'Budi',
            'status_aktif' => true,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'subject_type' => \App\Models\Pegawai::class,
            'subject_id' => $pegawai->id,
            'action' => 'created',
            'description' => 'Membuat data Pegawai',
        ]);

        $log = ActivityLog::where('subject_type', \App\Models\Pegawai::class)->first();
        $this->assertNotNull($log->new_values);
        $this->assertEquals('123', $log->new_values['nip']);
    }
}
