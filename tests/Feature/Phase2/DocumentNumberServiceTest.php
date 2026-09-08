<?php

namespace Tests\Feature\Phase2;

use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use App\Models\User;
use App\Models\JenisSurat;
use App\Services\DocumentNumberService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentNumberServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_sequence_sk_2026()
    {
        $klasifikasiSK = KlasifikasiSurat::create(['kode' => 'SK', 'nama' => 'Surat Keputusan']);
        $klasifikasiST = KlasifikasiSurat::create(['kode' => 'ST', 'nama' => 'Surat Tugas']);

        $date2026 = Carbon::create(2026, 9, 1);
        $date2027 = Carbon::create(2027, 1, 1);

        // Sequence SK 2026 #1
        $nomor1 = DocumentNumberService::generate($klasifikasiSK->id, $date2026);
        $this->assertStringContainsString('001', $nomor1);
        $this->assertStringContainsString('SK', $nomor1);
        $this->assertStringContainsString('2026', $nomor1);

        // Sequence SK 2026 #2
        $nomor2 = DocumentNumberService::generate($klasifikasiSK->id, $date2026);
        $this->assertStringContainsString('002', $nomor2);

        // Sequence ST 2026
        $nomor3 = DocumentNumberService::generate($klasifikasiST->id, $date2026);
        $this->assertStringContainsString('001', $nomor3);
        $this->assertStringContainsString('ST', $nomor3);

        // Sequence SK 2027
        $nomor4 = DocumentNumberService::generate($klasifikasiSK->id, $date2027);
        $this->assertStringContainsString('001', $nomor4);
        $this->assertStringContainsString('2027', $nomor4);
    }

    public function test_draft_does_not_consume_number()
    {
        $user = User::factory()->create();
        $jenisSurat = JenisSurat::create(['kode' => 'UM', 'nama' => 'Umum']);
        
        $response = $this->actingAs($user)->post(route('surat-keluar.store'), [
            'jenis_surat_id' => $jenisSurat->id,
            'perihal' => 'Draft Surat',
            'tanggal_surat' => '2026-09-01',
            'status' => 'draft',
        ]);
        
        $surat = Surat::first();
        $this->assertEquals('draft', $surat->status);
        $this->assertNull($surat->nomor_surat);

        // Finalize
        $surat->update(['status' => 'final']);
        $this->actingAs($user)->put(route('surat-keluar.update', $surat), [
            'jenis_surat_id' => $jenisSurat->id,
            'perihal' => 'Draft Surat Final',
            'tanggal_surat' => '2026-09-01',
            'status' => 'final',
        ]);
        
        $surat->refresh();
        $this->assertEquals('final', $surat->status);
        $this->assertNotNull($surat->nomor_surat);
    }

    public function test_preview_does_not_consume_sequence()
    {
        $klasifikasiSK = KlasifikasiSurat::create(['kode' => 'SK', 'nama' => 'Surat Keputusan']);
        $date = Carbon::create(2026, 9, 1);

        // Current sequence
        $nomor1 = DocumentNumberService::generate($klasifikasiSK->id, $date);
        $this->assertStringContainsString('001', $nomor1);

        // Preview next number
        $preview = DocumentNumberService::previewNextNumber($klasifikasiSK->id, $date);
        $this->assertStringContainsString('002', $preview);

        // Verify sequence is still 1
        $sequence = \App\Models\DocumentSequence::where('kategori', 'SK')->where('tahun', 2026)->first();
        $this->assertEquals(1, $sequence->last_number);

        // Generate official number again
        $nomor2 = DocumentNumberService::generate($klasifikasiSK->id, $date);
        $this->assertStringContainsString('002', $nomor2);

        // Verify sequence is now 2
        $sequence->refresh();
        $this->assertEquals(2, $sequence->last_number);
    }
}
