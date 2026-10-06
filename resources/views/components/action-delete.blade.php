@props([
    'action',
    'confirmMessage' => 'Yakin ingin menghapus data ini?',
    'title' => 'Hapus'
])

<form action="{{ $action }}" method="POST" class="inline" onsubmit="return confirm('{{ addslashes($confirmMessage) }}')">
    @csrf @method('DELETE')
    <button type="submit" title="{{ $title }}" aria-label="{{ $title }}"
            style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; background:#f8fafc; color:#ef4444; border-radius:6px; border:1px solid #e2e8f0; cursor:pointer; transition:all 0.15s;"
            onmouseover="this.style.background='#fef2f2'; this.style.borderColor='#fecaca';" 
            onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0';">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
    </button>
</form>
