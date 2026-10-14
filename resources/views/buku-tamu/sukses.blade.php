<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kunjungan Berhasil &ndash; {{ $sysInstansiNama ?? 'Dinas Perhubungan Provinsi Riau' }}</title>
    <link rel="icon" type="image/png" href="{{ $sysLogoUrl ?? asset('logoDishub.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #f8fafc 50%, #eff6ff 100%);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Background dekoratif */
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            pointer-events: none;
            z-index: 0;
        }
        body::before {
            width: 400px;
            height: 400px;
            background: #34d399;
            top: -150px;
            left: -150px;
            animation: float 8s ease-in-out infinite;
        }
        body::after {
            width: 400px;
            height: 400px;
            background: #60a5fa;
            bottom: -150px;
            right: -150px;
            animation: float 10s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.1); }
        }

        .success-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            box-shadow:
                0 20px 40px -10px rgba(15, 23, 42, 0.15),
                0 8px 16px -8px rgba(15, 23, 42, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 48px 32px 40px;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(24px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Icon */
        .icon-wrapper {
            position: relative;
            margin-bottom: 28px;
        }

        .icon-circle {
            position: relative;
            width: 88px;
            height: 88px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow:
                0 10px 25px -5px rgba(16, 185, 129, 0.5),
                0 0 0 8px rgba(16, 185, 129, 0.1);
            animation: popIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both;
        }

        @keyframes popIn {
            0% { transform: scale(0); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .icon-circle svg {
            width: 44px;
            height: 44px;
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: drawCheck 0.6s ease-out 0.6s forwards;
        }

        @keyframes drawCheck {
            to { stroke-dashoffset: 0; }
        }

        /* Ripple pulse */
        .icon-circle::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: #10b981;
            animation: pulse 2s ease-out infinite;
            z-index: -1;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.6; }
            100% { transform: scale(1.6); opacity: 0; }
        }

        h1 {
            font-size: 30px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 14px 0;
            letter-spacing: -0.8px;
            background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            font-size: 16px;
            line-height: 1.65;
            color: #64748b;
            margin: 0;
            max-width: 340px;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0 50%, transparent);
            margin: 32px 0 24px;
            width: 100%;
        }

        .identity {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 28px;
            padding: 8px 16px;
            background: #f1f5f9;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border: 1px solid #e2e8f0;
        }

        .identity::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }

        .btn-back {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            font-weight: 600;
            font-size: 15px;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            box-shadow:
                0 10px 20px -8px rgba(59, 130, 246, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .btn-back::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
            transition: left 0.6s ease;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow:
                0 16px 28px -8px rgba(59, 130, 246, 0.6),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .btn-back:hover::before {
            left: 100%;
        }

        .btn-back:active {
            transform: translateY(0) scale(0.98);
        }

        .btn-back svg {
            transition: transform 0.25s ease;
        }

        .btn-back:hover svg {
            transform: translateX(-3px);
        }

        /* Confetti */
        .confetti {
            position: absolute;
            width: 8px;
            height: 8px;
            top: -10px;
            opacity: 0;
            animation: confettiFall 3s ease-in 0.4s forwards;
            pointer-events: none;
            z-index: 2;
        }

        @keyframes confettiFall {
            0% {
                opacity: 1;
                transform: translateY(0) rotate(0deg);
            }
            100% {
                opacity: 0;
                transform: translateY(500px) rotate(720deg);
            }
        }

        @media (min-width: 481px) {
            .success-container {
                padding: 56px 48px 48px;
            }
            .btn-back {
                width: auto;
                min-width: 240px;
            }
        }
    </style>
</head>
<body>

    <div class="success-container">
        <!-- Confetti -->
        <span class="confetti" style="left: 15%; background: #10b981; animation-delay: 0.5s;"></span>
        <span class="confetti" style="left: 30%; background: #3b82f6; animation-delay: 0.7s; width: 6px; height: 6px;"></span>
        <span class="confetti" style="left: 50%; background: #f59e0b; animation-delay: 0.4s;"></span>
        <span class="confetti" style="left: 70%; background: #ec4899; animation-delay: 0.8s; width: 6px; height: 6px;"></span>
        <span class="confetti" style="left: 85%; background: #8b5cf6; animation-delay: 0.6s;"></span>

        <div class="icon-wrapper">
            <div class="icon-circle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
        </div>

        <h1>Terima Kasih!</h1>

        <p class="subtitle">
            Data kunjungan Anda telah berhasil dicatat. Silakan melanjutkan keperluan Anda.
        </p>

        <div class="divider"></div>

        <div class="identity">
            {{ $sysInstansiNama ?? 'Dinas Perhubungan Provinsi Riau' }}
        </div>

        <a href="{{ route('buku-tamu.create') }}" class="btn-back">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Kembali ke Buku Tamu
        </a>
    </div>

</body>
</html>