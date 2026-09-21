<?php

namespace Database\Seeders;

use App\Models\UnitKerja;
use Illuminate\Database\Seeder;

class UnitKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $struktur = [
            'Bidang Lalu Lintas Jalan' => [
                'Seksi Manajemen dan Rekayasa Lalu Lintas',
                'Seksi Pengawasan & Pengendalian Lalu Lintas & Jalan',
            ],
            'Bidang Angkutan Jalan' => [
                'Seksi Angkutan Orang Dalam Trayek dan Tidak Dalam Trayek',
                'Seksi Keselamatan dan Teknik Sarana',
            ],
            'Bidang Pelayaran' => [
                'Seksi Kepelabuhan',
                'Seksi Angkutan Pelayaran Rakyat dan ASDP',
            ],
            'Bidang Pengembangan Transportasi' => [
                'Seksi Pengembangan Sistem Transportasi',
                'Seksi Regulasi dan Pendataan Transportasi',
            ]
        ];

        $kodeUnit = 1;
        foreach ($struktur as $bidangNama => $seksiList) {
            $bidang = UnitKerja::where('nama', $bidangNama)->first();
            
            if (!$bidang) {
                // To avoid duplicate kode_unit if randomly matching an existing one
                $kode = 'B.' . str_pad($kodeUnit, 2, '0', STR_PAD_LEFT);
                while(UnitKerja::where('kode_unit', $kode)->exists()) {
                    $kodeUnit++;
                    $kode = 'B.' . str_pad($kodeUnit, 2, '0', STR_PAD_LEFT);
                }
                
                $bidang = UnitKerja::create([
                    'nama' => $bidangNama,
                    'kode_unit' => $kode,
                    'parent_id' => null
                ]);
            } else {
                $bidang->update(['parent_id' => null]);
            }

            $kodeSeksi = 1;
            foreach ($seksiList as $seksiNama) {
                $seksi = UnitKerja::where('nama', $seksiNama)->first();
                
                if (!$seksi) {
                    $kodeS = 'S.' . str_pad($kodeUnit, 2, '0', STR_PAD_LEFT) . '.' . str_pad($kodeSeksi, 2, '0', STR_PAD_LEFT);
                    while(UnitKerja::where('kode_unit', $kodeS)->exists()) {
                        $kodeSeksi++;
                        $kodeS = 'S.' . str_pad($kodeUnit, 2, '0', STR_PAD_LEFT) . '.' . str_pad($kodeSeksi, 2, '0', STR_PAD_LEFT);
                    }

                    UnitKerja::create([
                        'nama' => $seksiNama,
                        'kode_unit' => $kodeS,
                        'parent_id' => $bidang->id
                    ]);
                } else {
                    $seksi->update(['parent_id' => $bidang->id]);
                }
                $kodeSeksi++;
            }
            $kodeUnit++;
        }
    }
}
