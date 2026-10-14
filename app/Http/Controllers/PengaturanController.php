<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    /**
     * Tampilkan halaman pengaturan umum dengan tata letak kategori profesional.
     */
    public function index()
    {
        // ── 1. Identitas Instansi ──
        $instansiNama    = SystemSetting::getSetting('instansi_nama', 'Dinas Perhubungan Provinsi Riau');
        $instansiSub     = SystemSetting::getSetting('instansi_sub', 'Pemerintah Provinsi Riau');
        $appName         = SystemSetting::getSetting('app_name', 'SIPAS-DISHUB');
        $appDescription  = SystemSetting::getSetting('app_description', 'Sistem Informasi Persuratan dan Administrasi Dinas Perhubungan Provinsi Riau');
        $instansiAlamat  = SystemSetting::getSetting('instansi_alamat', 'Jl. Jenderal Sudirman No. 474, Pekanbaru, Riau, Kode Pos 28126');
        $instansiEmail   = SystemSetting::getSetting('instansi_email', 'dishub@riau.go.id');
        $instansiTelepon = SystemSetting::getSetting('instansi_telepon', '(0761) 32155');

        $logoPath     = SystemSetting::getSetting('instansi_logo');
        $isCustomLogo = $logoPath && Storage::disk('public')->exists($logoPath);
        $logoUrl      = $isCustomLogo ? asset('storage/' . $logoPath) : asset('logoDishub.png');

        // ── 2. Profil Pengguna ──
        $currentUser = Auth::user();
        if ($currentUser) {
            $currentUser->loadMissing(['role', 'pegawai.unitKerja']);
        }

        // ── 3. Preferensi Sistem ──
        $appTimezone = SystemSetting::getSetting('app_timezone', 'Asia/Jakarta');
        $dateFormat  = SystemSetting::getSetting('date_format', 'd/m/Y');
        $timeFormat  = SystemSetting::getSetting('time_format', '24');
        $appLocale   = SystemSetting::getSetting('app_locale', 'id');

        // ── 4. Tampilan Dashboard ──
        $defaultTheme  = SystemSetting::getSetting('default_theme', 'light');
        $defaultAccent = SystemSetting::getSetting('default_accent', 'blue');

        $bannerPath     = SystemSetting::getSetting('dashboard_banner_image');
        $isCustomBanner = $bannerPath && Storage::disk('public')->exists($bannerPath);
        $bannerUrl      = $isCustomBanner ? asset('storage/' . $bannerPath) : asset('images/default-banner.svg');

        // ── 5. Notifikasi & Pengingat ──
        $reminderKgbDays = SystemSetting::getSetting('reminder_kgb_days', 60);
        $reminderKpDays  = SystemSetting::getSetting('reminder_kp_days', 90);
        $reminderBupDays = SystemSetting::getSetting('reminder_bup_days', 180);

        // ── 6. Keamanan & Akses ──
        $sessionLifetime = config('session.lifetime', 120);
        $sessionDriver   = config('session.driver', 'database');

        return view('pengaturan.index', compact(
            'instansiNama',
            'instansiSub',
            'appName',
            'appDescription',
            'instansiAlamat',
            'instansiEmail',
            'instansiTelepon',
            'logoUrl',
            'isCustomLogo',
            'currentUser',
            'appTimezone',
            'dateFormat',
            'timeFormat',
            'appLocale',
            'defaultTheme',
            'defaultAccent',
            'bannerUrl',
            'isCustomBanner',
            'reminderKgbDays',
            'reminderKpDays',
            'reminderBupDays',
            'sessionLifetime',
            'sessionDriver'
        ));
    }

    /**
     * Perbarui identitas instansi dan aplikasi (khusus admin).
     */
    public function updateIdentitas(Request $request)
    {
        $validated = $request->validate([
            'instansi_nama'    => 'required|string|max:255',
            'instansi_sub'     => 'nullable|string|max:255',
            'app_name'         => 'required|string|max:100',
            'app_description'  => 'nullable|string|max:500',
            'instansi_alamat'  => 'nullable|string|max:500',
            'instansi_email'   => 'nullable|email|max:255',
            'instansi_telepon' => 'nullable|string|max:50',
            'instansi_logo'    => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ], [
            'instansi_nama.required' => 'Nama instansi wajib diisi.',
            'app_name.required'      => 'Nama aplikasi wajib diisi.',
            'instansi_email.email'   => 'Format email resmi tidak valid.',
            'instansi_logo.image'    => 'File logo harus berupa gambar yang valid.',
            'instansi_logo.mimes'    => 'Format logo yang diperbolehkan: JPG, JPEG, PNG, atau WebP.',
            'instansi_logo.max'      => 'Ukuran file logo maksimal 2 MB.',
        ]);

        if ($request->hasFile('instansi_logo')) {
            $oldLogo = SystemSetting::getSetting('instansi_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('instansi_logo')->store('logos', 'public');
            SystemSetting::setSetting('instansi_logo', $path, 'Logo resmi instansi');
        }

        SystemSetting::setSetting('instansi_nama', $validated['instansi_nama'], 'Nama instansi resmi');
        SystemSetting::setSetting('instansi_sub', $validated['instansi_sub'] ?? 'Pemerintah Provinsi Riau', 'Pemerintah daerah / hierarki instansi');
        SystemSetting::setSetting('app_name', $validated['app_name'], 'Nama singkatan aplikasi');
        SystemSetting::setSetting('app_description', $validated['app_description'] ?? '', 'Deskripsi aplikasi');
        SystemSetting::setSetting('instansi_alamat', $validated['instansi_alamat'] ?? '', 'Alamat kantor instansi');
        SystemSetting::setSetting('instansi_email', $validated['instansi_email'] ?? '', 'Email resmi instansi');
        SystemSetting::setSetting('instansi_telepon', $validated['instansi_telepon'] ?? '', 'Nomor telepon kantor');

        if (class_exists(ActivityLog::class)) {
            ActivityLog::create([
                'user_id'   => Auth::id(),
                'aktivitas' => 'Memperbarui identitas instansi dan aplikasi',
                'action'    => 'update',
            ]);
        }

        return redirect()->to(route('pengaturan.index') . '#identitas')->with('status', 'Identitas instansi dan aplikasi berhasil disimpan.');
    }

    /**
     * Kembalikan logo instansi ke logo bawaan (khusus admin).
     */
    public function resetLogo(Request $request)
    {
        $oldLogo = SystemSetting::getSetting('instansi_logo');
        if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
            Storage::disk('public')->delete($oldLogo);
        }

        SystemSetting::where('key', 'instansi_logo')->delete();

        if (class_exists(ActivityLog::class)) {
            ActivityLog::create([
                'user_id'   => Auth::id(),
                'aktivitas' => 'Mengembalikan logo instansi ke logo bawaan sistem',
                'action'    => 'reset',
            ]);
        }

        return redirect()->to(route('pengaturan.index') . '#identitas')->with('status', 'Logo instansi telah dikembalikan ke logo bawaan sistem.');
    }

    /**
     * Perbarui preferensi sistem (khusus admin).
     */
    public function updatePreferensi(Request $request)
    {
        $validated = $request->validate([
            'app_timezone' => 'required|string|in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura',
            'date_format'  => 'required|string|in:d/m/Y,Y-m-d,d F Y',
            'time_format'  => 'required|string|in:24,12',
            'app_locale'   => 'required|string|in:id',
        ], [
            'app_timezone.required' => 'Pilih zona waktu sistem.',
            'date_format.required'  => 'Pilih format tanggal tampilan.',
            'time_format.required'  => 'Pilih format waktu tampilan.',
        ]);

        SystemSetting::setSetting('app_timezone', $validated['app_timezone'], 'Zona waktu aplikasi');
        SystemSetting::setSetting('date_format', $validated['date_format'], 'Format tanggal tampilan');
        SystemSetting::setSetting('time_format', $validated['time_format'], 'Format waktu tampilan');
        SystemSetting::setSetting('app_locale', $validated['app_locale'], 'Bahasa sistem');

        if (class_exists(ActivityLog::class)) {
            ActivityLog::create([
                'user_id'   => Auth::id(),
                'aktivitas' => 'Memperbarui preferensi sistem (zona waktu, format tanggal/waktu)',
                'action'    => 'update',
            ]);
        }

        return redirect()->to(route('pengaturan.index') . '#preferensi')->with('status', 'Preferensi sistem berhasil disimpan.');
    }

    /**
     * Perbarui preferensi tampilan default aplikasi (khusus admin).
     */
    public function updateTampilan(Request $request)
    {
        $validated = $request->validate([
            'default_theme'  => 'required|string|in:light,dark,system',
            'default_accent' => 'required|string|in:blue,indigo,purple,red,orange,green,teal,cyan',
        ], [
            'default_theme.required'  => 'Pilih mode tampilan default.',
            'default_accent.required' => 'Pilih warna aksen default.',
        ]);

        SystemSetting::setSetting('default_theme', $validated['default_theme'], 'Tema default sistem (light/dark/system)');
        SystemSetting::setSetting('default_accent', $validated['default_accent'], 'Warna aksen default sistem');

        if (class_exists(ActivityLog::class)) {
            ActivityLog::create([
                'user_id'   => Auth::id(),
                'aktivitas' => 'Memperbarui tema dan aksen default sistem',
                'action'    => 'update',
            ]);
        }

        return redirect()->to(route('pengaturan.index') . '#tampilan')->with('status', 'Pengaturan tema default sistem berhasil disimpan.');
    }

    /**
     * Perbarui parameter notifikasi dan pengingat (khusus admin).
     */
    public function updateNotifikasi(Request $request)
    {
        $validated = $request->validate([
            'reminder_kgb_days' => 'required|integer|min:1|max:365',
            'reminder_kp_days'  => 'required|integer|min:1|max:365',
            'reminder_bup_days' => 'required|integer|min:1|max:730',
        ], [
            'reminder_kgb_days.required' => 'Batas waktu pengingat KGB wajib diisi.',
            'reminder_kp_days.required'  => 'Batas waktu pengingat KP wajib diisi.',
            'reminder_bup_days.required' => 'Batas waktu pengingat BUP wajib diisi.',
        ]);

        SystemSetting::setSetting('reminder_kgb_days', $validated['reminder_kgb_days'], 'Hari pengingat kenaikan gaji berkala');
        SystemSetting::setSetting('reminder_kp_days', $validated['reminder_kp_days'], 'Hari pengingat kenaikan pangkat');
        SystemSetting::setSetting('reminder_bup_days', $validated['reminder_bup_days'], 'Hari pengingat batas usia pensiun');

        if (class_exists(ActivityLog::class)) {
            ActivityLog::create([
                'user_id'   => Auth::id(),
                'aktivitas' => 'Memperbarui parameter notifikasi dan pengingat kepegawaian',
                'action'    => 'update',
            ]);
        }

        return redirect()->to(route('pengaturan.index') . '#notifikasi')->with('status', 'Parameter notifikasi dan pengingat berhasil disimpan.');
    }

    /**
     * Unggah dan simpan gambar banner dashboard baru (khusus admin).
     */
    public function updateBanner(Request $request)
    {
        $request->validate([
            'banner_image' => 'required|image|mimes:jpeg,jpg,png,webp|max:3072',
        ], [
            'banner_image.required' => 'Pilih file gambar banner terlebih dahulu.',
            'banner_image.image'    => 'File yang diunggah harus berupa gambar yang valid.',
            'banner_image.mimes'    => 'Format gambar yang diperbolehkan: JPG, JPEG, PNG, atau WebP.',
            'banner_image.max'      => 'Ukuran file gambar maksimal 3 MB.',
        ]);

        $oldPath = SystemSetting::getSetting('dashboard_banner_image');
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        $path = $request->file('banner_image')->store('banners', 'public');

        SystemSetting::setSetting('dashboard_banner_image', $path, 'Gambar latar banner selamat datang di Dashboard');

        if (class_exists(ActivityLog::class)) {
            ActivityLog::create([
                'user_id'   => Auth::id(),
                'aktivitas' => 'Memperbarui gambar latar banner dashboard',
                'action'    => 'update',
            ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Gambar banner berhasil disimpan dan diterapkan.',
                'banner_url' => asset('storage/' . $path),
            ]);
        }

        return redirect()->to(route('pengaturan.index') . '#tampilan')->with('status', 'Gambar latar banner dashboard berhasil disimpan dan diterapkan.');
    }

    /**
     * Kembalikan gambar banner dashboard ke gambar bawaan sistem (khusus admin).
     */
    public function resetBanner(Request $request)
    {
        $oldPath = SystemSetting::getSetting('dashboard_banner_image');
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        SystemSetting::where('key', 'dashboard_banner_image')->delete();

        if (class_exists(ActivityLog::class)) {
            ActivityLog::create([
                'user_id'   => Auth::id(),
                'aktivitas' => 'Mengembalikan gambar latar banner dashboard ke gambar bawaan',
                'action'    => 'reset',
            ]);
        }

        $defaultUrl = asset('images/default-banner.svg');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Gambar banner telah dikembalikan ke gambar bawaan sistem.',
                'banner_url' => $defaultUrl,
            ]);
        }

        return redirect()->to(route('pengaturan.index') . '#tampilan')->with('status', 'Gambar banner telah dikembalikan ke gambar bawaan sistem.');
    }
}
