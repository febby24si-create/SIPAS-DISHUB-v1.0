@props([
    'icon'        => '',
    'title'       => '',
    'description' => null,
])
<div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
    {{-- Icon Container --}}
    <div style="width:40px; height:40px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:var(--tc-radius-md, 8px); display:flex; align-items:center; justify-content:center; color:#1d4ed8; flex-shrink:0;">
        {!! $icon !!}
    </div>
    {{-- Title + Description --}}
    <div>
        <div style="font-size:17px; font-weight:600; color:#1e293b; line-height:1.2;">{{ $title }}</div>
        @if($description)
            <div style="margin-top:2px; font-size:12.5px; color:#64748b; font-weight:400;">{{ $description }}</div>
        @endif
    </div>
    {{-- Slot untuk konten kanan (badge, counter, dll) --}}
    @isset($trailing)
        <div style="margin-left:auto; flex-shrink:0;">{{ $trailing }}</div>
    @endisset
</div>
