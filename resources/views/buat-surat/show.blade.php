<x-app-layout>
    <x-slot name="header">Preview Surat</x-slot>

    <div style="max-width:760px; display:flex; flex-direction:column; gap:20px;">

        {{-- Breadcrumb --}}
        <div style="display:flex; align-items:center; gap:8px; font-size:13px; color:#94a3b8;">
            <a href="{{ route('dashboard') }}" style="color:#64748b; text-decoration:none; font-weight:500;" onmouseover="this.style.color='#1d4ed8'" onmouseout="this.style.color='#64748b'">Dashboard</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span style="color:#1e293b; font-weight:600;">Preview Surat</span>
        </div>

        {{-- Status Alert --}}
        @if (session('status'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:14px; color:#065f46; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('status') }}
            </div>
        @endif

        {{-- Info Card --}}
        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05); background:linear-gradient(135deg,#f8fafc,#f0f9ff); display:flex; align-items:center; justify-content:space-between;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:15px; font-weight:700; color:#1e293b; margin:0;">Informasi Surat</h3>
                        <p style="font-size:12px; color:#94a3b8; margin:0;">Detail dokumen yang dihasilkan</p>
                    </div>
                </div>
                {{-- Status Badge --}}
                @if ($surat->status === 'draft')
                    <span style="display:inline-flex; align-items:center; gap:6px; padding:6px 14px; background:#fef3c7; color:#92400e; font-size:12px; font-weight:700; border-radius:20px; border:1px solid #fde68a;">
                        <span style="width:7px; height:7px; background:#f59e0b; border-radius:50%;"></span>
                        Draft
                    </span>
                @else
                    <span style="display:inline-flex; align-items:center; gap:6px; padding:6px 14px; background:#ecfdf5; color:#065f46; font-size:12px; font-weight:700; border-radius:20px; border:1px solid #a7f3d0;">
                        <span style="width:7px; height:7px; background:#059669; border-radius:50%;"></span>
                        Final
                    </span>
                @endif
            </div>

            <div style="padding:24px; display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                @foreach ([['Nomor Surat',$surat->nomor_surat],['Perihal',$surat->perihal],['Tanggal Surat',$surat->tanggal_surat?->format('d M Y')],['Status',ucfirst($surat->status)]] as $field)
                    <div style="display:flex; flex-direction:column; gap:4px;">
                        <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:#94a3b8;">{{ $field[0] }}</span>
                        <span style="font-size:14px; font-weight:600; color:#1e293b;">{{ $field[1] ?? '—' }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- PDF Preview --}}
        @if ($surat->file_pdf)
            <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
                <div style="padding:14px 20px; border-bottom:1px solid rgba(0,0,0,0.05); display:flex; align-items:center; gap:8px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <span style="font-size:13px; font-weight:700; color:#1e293b;">Preview PDF</span>
                </div>
                <iframe src="{{ asset('storage/' . $surat->file_pdf) }}"
                        style="width:100%; height:520px; border:none; display:block;"></iframe>
            </div>
        @else
            <div style="display:flex; align-items:center; gap:12px; padding:16px 20px; background:#fffbeb; border:1px solid #fde68a; border-radius:14px;">
                <div style="width:36px; height:36px; background:#fef3c7; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <div>
                    <p style="font-size:13px; font-weight:700; color:#92400e; margin:0 0 2px 0;">PDF Belum Tersedia</p>
                    <p style="font-size:12px; color:#b45309; margin:0;">LibreOffice belum terpasang di server. File Word tetap bisa diunduh.</p>
                </div>
            </div>
        @endif

        {{-- Action Buttons --}}
        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:20px 24px; display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            {{-- Download Word --}}
            <a href="{{ asset('storage/' . $surat->file_word) }}"
               style="display:inline-flex; align-items:center; gap:8px; padding:11px 20px; background:#f0f9ff; color:#0369a1; font-size:13px; font-weight:600; border-radius:12px; border:1px solid #bae6fd; text-decoration:none; transition:all 0.15s;"
               onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9ff'">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download Word
            </a>

            @if ($surat->file_pdf)
                <a href="{{ asset('storage/' . $surat->file_pdf) }}"
                   style="display:inline-flex; align-items:center; gap:8px; padding:11px 20px; background:#fff1f2; color:#be123c; font-size:13px; font-weight:600; border-radius:12px; border:1px solid #fecdd3; text-decoration:none; transition:all 0.15s;"
                   onmouseover="this.style.background='#ffe4e6'" onmouseout="this.style.background='#fff1f2'">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Download PDF
                </a>
            @endif

            @if ($surat->status === 'draft')
                <form action="{{ route('buat-surat.finalize', $surat) }}" method="POST" style="display:inline;">
                    @csrf @method('PATCH')
                    <button type="submit"
                            style="display:inline-flex; align-items:center; gap:8px; padding:11px 20px; background:linear-gradient(135deg,#059669,#10b981); color:white; font-size:13px; font-weight:600; border-radius:12px; border:none; cursor:pointer; box-shadow:0 2px 8px rgba(5,150,105,0.3); transition:all 0.2s;"
                            onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Finalisasi & Simpan ke Arsip
                    </button>
                </form>
            @else
                <div style="display:inline-flex; align-items:center; gap:8px; padding:11px 20px; background:#ecfdf5; color:#065f46; font-size:13px; font-weight:600; border-radius:12px; border:1px solid #a7f3d0;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Sudah final – tersimpan di arsip
                </div>
            @endif
        </div>
    </div>
</x-app-layout>