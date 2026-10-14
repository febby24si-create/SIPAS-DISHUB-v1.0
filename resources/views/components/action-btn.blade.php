@props([
    'type', 
    'url' => '#',
    'title' => ''
])

@php
    $icon = '';
    $colorClass = '';
    $hoverBg = '';
    $hoverBorder = '';
    
    if ($type === 'view') {
        $title = $title ?: 'Detail';
        $icon = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
        $color = '#3b82f6'; // blue-500
        $hoverBg = '#eff6ff'; // blue-50
        $hoverBorder = '#bfdbfe'; // blue-200
    } elseif ($type === 'edit') {
        $title = $title ?: 'Edit';
        $icon = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>';
        $color = '#10b981'; // emerald-500
        $hoverBg = '#ecfdf5'; // emerald-50
        $hoverBorder = '#a7f3d0'; // emerald-200
    } elseif ($type === 'download') {
        $title = $title ?: 'Download';
        $icon = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>';
        $color = '#8b5cf6'; // violet-500
        $hoverBg = '#f5f3ff'; // violet-50
        $hoverBorder = '#ddd6fe'; // violet-200
    } elseif ($type === 'delete') {
        $title = $title ?: 'Hapus';
        $icon = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>';
        $color = '#ef4444'; // red-500
        $hoverBg = '#fef2f2'; // red-50
        $hoverBorder = '#fecaca'; // red-200
    }
@endphp

<a href="{{ $url }}" title="{{ $title }}" aria-label="{{ $title }}" {{ $attributes }}
   style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; background:var(--card-bg,#f8fafc); color:{{ $color }}; border-radius:6px; border:1px solid var(--border,#e2e8f0); text-decoration:none; transition:all 0.15s; cursor:pointer;"
   onmouseover="this.style.background='{{ $hoverBg }}'; this.style.borderColor='{{ $hoverBorder }}';" 
   onmouseout="this.style.background='var(--card-bg,#f8fafc)'; this.style.borderColor='var(--border,#e2e8f0)';">
    {!! $icon !!}
</a>
