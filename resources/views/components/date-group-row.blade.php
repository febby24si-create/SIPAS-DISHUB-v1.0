@props([
    'group',
    'colspan' => 8,
    'partial' => false,
    'noun' => 'surat',
])

{{--
    Baris pemisah kelompok tanggal input di dalam tabel (bukan tabel terpisah).
    Dipakai oleh tabel Surat Masuk, Surat Keluar, Draft Surat Keluar, dan Arsip Digital.

    Prop "noun" hanya menentukan satuan jumlah (mis. "surat" atau "arsip");
    nilai bawaannya mempertahankan tampilan lama sehingga Surat Masuk dan
    Surat Keluar tidak berubah.
--}}
<tr class="dtg-row" data-date-group="{{ $group['key'] }}" @if(($group['prefix'] ?? null) === \App\Helpers\DateGroupHelper::TODAY_LABEL) data-today="1" @endif>
    <td class="dtg-cell" colspan="{{ $colspan }}">
        <span class="dtg-inner">
            <span class="dtg-ic" aria-hidden="true">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </span>
            @if ($group['prefix'])
                <span class="dtg-prefix">{{ $group['prefix'] }}</span>
                <span class="dtg-sep" aria-hidden="true">&mdash;</span>
            @endif
            <span class="dtg-date">{{ $group['label'] }}</span>
            <span class="dtg-count" title="{{ $partial ? 'Jumlah '.$noun.' yang tampil pada halaman ini' : 'Jumlah '.$noun.' pada kelompok tanggal ini' }}">{{ $group['count'] }} {{ $noun }}</span>
        </span>
    </td>
</tr>
