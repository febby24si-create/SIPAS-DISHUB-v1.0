<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kunjungan Berhasil - Dinas Perhubungan Provinsi Riau</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .success-container {
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            display: flex;
            flex-direction: column;
            text-align: center;
            border-top: 4px solid #10b981;
            padding: 40px 30px;
            box-sizing: border-box;
        }
        .icon-circle {
            width: 80px;
            height: 80px;
            background-color: #d1fae5;
            color: #059669;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 24px;
        }
        .icon-circle svg {
            width: 40px;
            height: 40px;
        }
        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 16px 0;
            letter-spacing: -0.5px;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            color: #475569;
            margin: 0 0 8px 0;
        }
        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 30px 0;
            width: 100%;
        }
        .identity {
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 24px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-back {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            background-color: #3b82f6;
            color: #ffffff;
            font-weight: 500;
            font-size: 15px;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.2s;
            width: 100%;
            box-sizing: border-box;
        }
        .btn-back:hover {
            background-color: #2563eb;
        }
        .btn-back:active {
            transform: scale(0.98);
        }
        
        @media (min-width: 481px) {
            .success-container {
                border-radius: 12px;
                padding: 50px 40px;
            }
            .btn-back {
                width: auto;
                min-width: 200px;
            }
        }
    </style>
</head>
<body>

    <div class="success-container">
        <div class="icon-circle">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
        </div>
        
        <h1>Terima Kasih</h1>
        
        <p>Data kunjungan Anda telah berhasil dicatat.</p>
        <p>Silakan melanjutkan keperluan Anda.</p>

        <div class="divider"></div>

        <div class="identity">
            Dinas Perhubungan Provinsi Riau
        </div>

        <a href="{{ route('buku-tamu.create') }}" class="btn-back">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali ke Buku Tamu
        </a>
    </div>

</body>
</html>
