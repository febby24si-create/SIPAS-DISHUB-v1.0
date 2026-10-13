{{--
    Badge arah surat untuk Arsip Digital (daftar & detail memakai komponen yang sama
    sehingga warna, ikon, dan teks selalu konsisten antar halaman).

    Aturan warna:
        arah = "masuk"   → latar hijau lembut, ikon arrow-down-left
        arah = "keluar"  → latar biru lembut,  ikon arrow-up-right
        selain itu (kosong / tidak dikenal) → label netral, tanpa warna masuk/keluar

    Prop:
        arah  : nilai arah surat (masuk|keluar|lainnya|null)
        icon  : "arrow" (default, ikon arah) | "tag" (ikon jenis surat)
        label : teks pengganti; null → "Masuk" / "Keluar" / "Tidak Diketahui"

    Semua ikon SVG outline dan bersifat dekoratif (aria-hidden).
--}}
@props(['arah' => null, 'icon' => 'arrow', 'label' => null])

@php
    $nilai  = strtolower(trim((string) $arah));
    $masuk  = $nilai === 'masuk';
    $keluar = $nilai === 'keluar';

    // Latar lembut + teks kontras; tidak ada aturan override gelap untuk warna ini,
    // sehingga badge tetap terbaca pada mode terang maupun gelap.
    $background = $masuk ? '#ecfdf5' : ($keluar ? '#eff6ff' : '#f1f5f9');
    $color      = $masuk ? '#047857' : ($keluar ? '#1d4ed8' : '#4b5563');

    // Bentuk: badge arah berbentuk pil, badge jenis memakai sudut membulat seperti sebelumnya.
    $radius = $icon === 'tag' ? '8px' : '20px';

    if ($icon === 'tag') {
        $path = '<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.83z"/><line x1="7" y1="7" x2="7.01" y2="7"/>';
    } elseif ($masuk) {
        $path = '<path d="M17 7 7 17"/><path d="M17 17H7V7"/>';   // arrow-down-left
    } elseif ($keluar) {
        $path = '<path d="M7 7h10v10"/><path d="M7 17 17 7"/>';    // arrow-up-right
    } else {
        $path = '<path d="M5 12h14"/>';                            // netral, tanpa arah
    }

    $teks = $label ?? ($masuk ? 'Masuk' : ($keluar ? 'Keluar' : 'Tidak Diketahui'));
@endphp

<span style="display:inline-flex; align-items:center; gap:5px; padding:4px 10px; background:{{ $background }}; color:{{ $color }}; font-size:11px; font-weight:600; border-radius:{{ $radius }}; white-space:nowrap;"{{ $attributes }}>
    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $path !!}</svg>
    {{ $teks }}
</span>
