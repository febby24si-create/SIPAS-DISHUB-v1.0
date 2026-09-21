<?php

namespace App\Services\Kepegawaian;

use App\Models\Pegawai;
use App\Models\SystemSetting;
use Carbon\Carbon;

class EarlyWarningService
{
    public function getKgbWarning()
    {
        $intervalMonths = SystemSetting::getSetting('kgb_interval_months');
        $reminderDays = SystemSetting::getSetting('reminder_kgb_days');

        // Mengambil pegawai aktif beserta KGB terakhir yang berstatus selesai
        $pegawais = Pegawai::where('status_aktif', true)->with(['gajiBerkala' => function ($query) {
            $query->where('status', 'selesai')->orderBy('tmt_berikutnya', 'desc');
        }])->get();

        $results = [];

        foreach ($pegawais as $pegawai) {
            $latestKgb = $pegawai->gajiBerkala->first();
            
            if (!$latestKgb || !$latestKgb->tmt_berikutnya) {
                $results[] = [
                    'pegawai' => $pegawai,
                    'tanggal_jatuh_tempo' => null,
                    'status' => 'DATA TIDAK LENGKAP',
                    'sisa_hari' => null,
                ];
                continue;
            }

            if (!$intervalMonths || !$reminderDays) {
                $results[] = [
                    'pegawai' => $pegawai,
                    'tanggal_jatuh_tempo' => Carbon::parse($latestKgb->tmt_berikutnya),
                    'status' => 'PENGATURAN BELUM LENGKAP',
                    'sisa_hari' => null,
                ];
                continue;
            }

            $jatuhTempo = Carbon::parse($latestKgb->tmt_berikutnya);
            $sisaHari = Carbon::now()->startOfDay()->diffInDays($jatuhTempo, false);

            if ($sisaHari < 0) {
                $status = 'JATUH TEMPO';
            } elseif ($sisaHari <= (int) $reminderDays) {
                $status = 'AKAN JATUH TEMPO';
            } else {
                $status = 'AMAN';
            }

            $results[] = [
                'pegawai' => $pegawai,
                'tanggal_jatuh_tempo' => $jatuhTempo,
                'status' => $status,
                'sisa_hari' => $sisaHari,
            ];
        }

        return $results;
    }

    public function getKpWarning()
    {
        $intervalYears = SystemSetting::getSetting('kp_interval_years');
        $reminderDays = SystemSetting::getSetting('reminder_kp_days');

        $pegawais = Pegawai::where('status_aktif', true)->get();

        $results = [];

        foreach ($pegawais as $pegawai) {
            if (!$pegawai->tmt_pangkat) {
                $results[] = [
                    'pegawai' => $pegawai,
                    'tanggal_jatuh_tempo' => null,
                    'status' => 'DATA TIDAK LENGKAP',
                    'sisa_hari' => null,
                ];
                continue;
            }

            if (!$intervalYears || !$reminderDays) {
                 $results[] = [
                    'pegawai' => $pegawai,
                    'tanggal_jatuh_tempo' => null,
                    'status' => 'PENGATURAN BELUM LENGKAP',
                    'sisa_hari' => null,
                ];
                continue;
            }
            
            $jatuhTempo = Carbon::parse($pegawai->tmt_pangkat)->addYears((int) $intervalYears);
            $sisaHari = Carbon::now()->startOfDay()->diffInDays($jatuhTempo, false);

            if ($sisaHari < 0) {
                $status = 'JATUH TEMPO';
            } elseif ($sisaHari <= (int) $reminderDays) {
                $status = 'AKAN JATUH TEMPO';
            } else {
                $status = 'AMAN';
            }

             $results[] = [
                'pegawai' => $pegawai,
                'tanggal_jatuh_tempo' => $jatuhTempo,
                'status' => $status,
                'sisa_hari' => $sisaHari,
            ];
        }
        
        return $results;
    }
}
