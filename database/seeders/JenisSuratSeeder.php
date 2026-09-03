<?php

namespace Database\Seeders;

use \App\Models\JenisSurat;
use Illuminate\Database\Seeder;

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Surat Keputusan', 'kode' => 'SK'],
            ['nama' => 'Surat Edaran', 'kode' => 'SE'],
            ['nama' => 'Surat Tugas', 'kode' => 'ST'],
            ['nama' => 'Surat Undangan', 'kode' => 'SU'],
            ['nama' => 'Nota Dinas', 'kode' => 'ND'],
            ['nama' => 'Surat Peringatan', 'kode' => 'SPR'],
            ['nama' => 'Surat Keterangan', 'kode' => 'SKET'],
            ['nama' => 'Surat Perintah', 'kode' => 'SP'],
            ['nama' => 'Surat Perjanjian Kerja Sama', 'kode' => 'SPK'],
            ['nama' => 'Surat Pemberitahuan', 'kode' => 'SPB'],
        ];
            foreach ($data as $item) {
                JenisSurat::firstOrCreate(['kode' => $item['kode']], $item);
            }
        }
    }
