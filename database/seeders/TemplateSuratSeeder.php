<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;

class TemplateSuratSeeder extends Seeder
{
    public function run(): void
    {
        Storage::disk('public')->makeDirectory('templates');

        $templates = [
            [
                'jenis_kode'    => 'ST',
                'nama_template' => 'Surat Tugas Standar',
                'placeholders'  => ['nama_pegawai', 'nip', 'jabatan', 'keperluan', 'tanggal_mulai', 'tanggal_selesai', 'tempat_tujuan'],
            ],
            [
                'jenis_kode'    => 'SK',
                'nama_template' => 'Surat Keputusan Kepala Dinas',
                'placeholders'  => ['tentang', 'nama_penerima', 'jabatan_penerima', 'tmt'],
            ],
            [
                'jenis_kode'    => 'SE',
                'nama_template' => 'Surat Edaran Internal',
                'placeholders'  => ['perihal_detail', 'dasar_hukum', 'isi_edaran'],
            ],
            [
                'jenis_kode'    => 'SU',
                'nama_template' => 'Undangan Rapat',
                'placeholders'  => ['agenda', 'hari', 'tanggal_rapat', 'pukul', 'tempat'],
            ],
            [
                'jenis_kode'    => 'ND',
                'nama_template' => 'Nota Dinas Standar',
                'placeholders'  => ['kepada', 'dari_jabatan', 'isi_nota'],
            ],
            [
                'jenis_kode'    => 'SKET',
                'nama_template' => 'Surat Keterangan',
                'placeholders'  => ['nama_yang_diterangkan', 'nip', 'jabatan', 'keterangan'],
            ],
            [
                'jenis_kode'    => 'SPK',
                'nama_template' => 'Surat Perjanjian Kerja Sama',
                'placeholders'  => ['pihak_pertama', 'pihak_kedua', 'ruang_lingkup', 'jangka_waktu'],
            ],
            [
                'jenis_kode'    => 'SPB',
                'nama_template' => 'Surat Pemberitahuan',
                'placeholders'  => ['isi_pemberitahuan', 'batas_waktu'],
            ],
        ];

        // Hapus template lama agar tidak duplikat
        DB::table('template_surat')->truncate();
        // Hapus file lama
        foreach (Storage::disk('public')->files('templates') as $f) {
            Storage::disk('public')->delete($f);
        }

        foreach ($templates as $tmpl) {
            $jenis = JenisSurat::where('kode', $tmpl['jenis_kode'])->first();
            if (!$jenis) {
                $this->command->warn("Jenis [{$tmpl['jenis_kode']}] tidak ditemukan, skip.");
                continue;
            }

            $slug     = strtolower(str_replace([' ', '/'], '_', $tmpl['jenis_kode']));
            $fileName = "templates/{$slug}_" . time() . '_' . rand(100, 999) . '.docx';
            $filePath = Storage::disk('public')->path($fileName);

            try {
                $this->buatDokumen($filePath, $jenis->nama, $tmpl['placeholders']);

                DB::table('template_surat')->insert([
                    'jenis_surat_id'   => $jenis->id,
                    'nama_template'    => $tmpl['nama_template'],
                    'file_template'    => $fileName,
                    'placeholder_json' => json_encode($tmpl['placeholders']),
                    'status'           => 'aktif',
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                $this->command->info("  ✓ {$tmpl['nama_template']} [{$tmpl['jenis_kode']}]");
            } catch (\Throwable $e) {
                $this->command->error("  ✗ {$tmpl['nama_template']}: " . $e->getMessage());
            }
        }
    }

    private function buatDokumen(string $filePath, string $judulSurat, array $placeholders): void
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        // Ukuran mendekati F4 (215x330mm) – gunakan Folio yang didukung PhpWord
        $section = $phpWord->addSection([
            'paperSize'    => 'Folio',
            'marginTop'    => Converter::cmToTwip(2.5),
            'marginBottom' => Converter::cmToTwip(2.5),
            'marginLeft'   => Converter::cmToTwip(3.0),
            'marginRight'  => Converter::cmToTwip(2.5),
        ]);

        // ── Font defaults ──
        $fNormal   = ['name' => 'Times New Roman', 'size' => 12];
        $fBold     = ['name' => 'Times New Roman', 'size' => 12, 'bold' => true];
        $fBold14   = ['name' => 'Times New Roman', 'size' => 14, 'bold' => true];
        $fBold16   = ['name' => 'Times New Roman', 'size' => 16, 'bold' => true];
        $pCenter   = ['alignment' => Jc::CENTER, 'spaceAfter' => 0, 'spaceBefore' => 0];
        $pLeft     = ['alignment' => Jc::LEFT,   'spaceAfter' => 0, 'spaceBefore' => 0];
        $pRight    = ['alignment' => Jc::RIGHT,  'spaceAfter' => 0, 'spaceBefore' => 0];

        // ══════════════════════════════════════
        // KOP SURAT (menggunakan tabel 1 baris)
        // ══════════════════════════════════════
        $tableKop = $section->addTable([
            'borderSize' => 0,
            'borderColor' => 'FFFFFF',
            'cellMarginTop' => 0,
            'cellMarginBottom' => 0,
        ]);
        $tableKop->addRow();
        $cellKop = $tableKop->addCell(null, ['gridSpan' => 1]);

        $cellKop->addText('PEMERINTAH PROVINSI RIAU', $fBold, $pCenter);
        $cellKop->addText('DINAS PERHUBUNGAN', $fBold16, $pCenter);
        $cellKop->addText(
            'Jl. Sudirman No. 462 Pekanbaru – Riau 28282 | Telp. (0761) 21404',
            ['name' => 'Times New Roman', 'size' => 10],
            $pCenter
        );

        $section->addTextBreak(0);

        // Garis tebal bawah kop
        $tableLine = $section->addTable([
            'borderBottomSize'  => 36,
            'borderBottomColor' => '1F497D',
            'borderTopSize'     => 4,
            'borderTopColor'    => '1F497D',
            'borderLeftSize'    => 0,
            'borderRightSize'   => 0,
        ]);
        $tableLine->addRow(Converter::cmToTwip(0.1));
        $tableLine->addCell(null, ['borderSize' => 0]);

        $section->addTextBreak(1);

        // ══════════════════════════════════════
        // JUDUL SURAT
        // ══════════════════════════════════════
        $section->addText(strtoupper($judulSurat), $fBold14, $pCenter);
        $section->addText('Nomor: ${nomor}', $fNormal, $pCenter);

        $section->addTextBreak(1);

        // ══════════════════════════════════════
        // TANGGAL & PENERIMA
        // ══════════════════════════════════════
        $tableInfo = $section->addTable(['borderSize' => 0, 'borderColor' => 'FFFFFF']);
        $tableInfo->addRow();
        $tableInfo->addCell(Converter::cmToTwip(2.5), ['borderSize' => 0])->addText('Nomor', $fNormal, $pLeft);
        $tableInfo->addCell(Converter::cmToTwip(0.5), ['borderSize' => 0])->addText(':', $fNormal, $pLeft);
        $tableInfo->addCell(null, ['borderSize' => 0])->addText('${nomor}', $fNormal, $pLeft);

        $tableInfo->addRow();
        $tableInfo->addCell(Converter::cmToTwip(2.5), ['borderSize' => 0])->addText('Perihal', $fNormal, $pLeft);
        $tableInfo->addCell(Converter::cmToTwip(0.5), ['borderSize' => 0])->addText(':', $fNormal, $pLeft);
        $tableInfo->addCell(null, ['borderSize' => 0])->addText('${perihal}', $fNormal, $pLeft);

        $tableInfo->addRow();
        $tableInfo->addCell(Converter::cmToTwip(2.5), ['borderSize' => 0])->addText('Kepada Yth.', $fNormal, $pLeft);
        $tableInfo->addCell(Converter::cmToTwip(0.5), ['borderSize' => 0])->addText(':', $fNormal, $pLeft);
        $tableInfo->addCell(null, ['borderSize' => 0])->addText('${tujuan}', $fNormal, $pLeft);

        $tableInfo->addRow();
        $tableInfo->addCell(Converter::cmToTwip(2.5), ['borderSize' => 0])->addText('Di -', $fNormal, $pLeft);
        $tableInfo->addCell(Converter::cmToTwip(0.5), ['borderSize' => 0])->addText('', $fNormal, $pLeft);
        $tableInfo->addCell(null, ['borderSize' => 0])->addText('Tempat', $fNormal, $pLeft);

        $section->addTextBreak(1);

        // ══════════════════════════════════════
        // PEMBUKA
        // ══════════════════════════════════════
        $pJustify = ['alignment' => Jc::BOTH, 'spaceAfter' => 120, 'spaceBefore' => 0, 'lineHeight' => 240];

        $section->addText(
            'Dengan hormat,',
            $fNormal,
            $pJustify
        );

        // ══════════════════════════════════════
        // FIELD DINAMIS PLACEHOLDER
        // ══════════════════════════════════════
        foreach ($placeholders as $ph) {
            $label = ucwords(str_replace('_', ' ', $ph));
            $tableField = $section->addTable(['borderSize' => 0, 'borderColor' => 'FFFFFF']);
            $tableField->addRow();
            $tableField->addCell(Converter::cmToTwip(5), ['borderSize' => 0])->addText($label, $fNormal, $pLeft);
            $tableField->addCell(Converter::cmToTwip(0.5), ['borderSize' => 0])->addText(':', $fNormal, $pLeft);
            $tableField->addCell(null, ['borderSize' => 0])->addText('${' . $ph . '}', $fNormal, $pLeft);
        }

        $section->addTextBreak(1);

        // Penutup
        $section->addText(
            'Demikian surat ini dibuat untuk dapat dipergunakan sebagaimana mestinya.',
            $fNormal,
            $pJustify
        );

        $section->addTextBreak(1);

        // ══════════════════════════════════════
        // TANDA TANGAN
        // ══════════════════════════════════════
        $tableTtd = $section->addTable(['borderSize' => 0, 'borderColor' => 'FFFFFF']);
        $tableTtd->addRow();
        // Kolom kiri kosong (untuk cap / materai)
        $tableTtd->addCell(Converter::cmToTwip(8), ['borderSize' => 0])->addText('', $fNormal, $pLeft);
        // Kolom kanan tanda tangan
        $cellTtd = $tableTtd->addCell(null, ['borderSize' => 0]);
        $cellTtd->addText('Pekanbaru, ${tanggal}', $fNormal, $pLeft);
        $cellTtd->addText('Kepala Dinas Perhubungan', $fNormal, $pLeft);
        $cellTtd->addText('Provinsi Riau,', $fNormal, $pLeft);
        $cellTtd->addTextBreak(3);
        $cellTtd->addText('( ................................................... )', $fNormal, $pLeft);
        $cellTtd->addText('NIP. .............................................', $fNormal, $pLeft);

        $phpWord->save($filePath, 'Word2007');
    }
}
