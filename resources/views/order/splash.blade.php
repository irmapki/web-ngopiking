<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ngopi King</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            background: radial-gradient(circle at 50% 30%, #A8734D 0%, #8B5E3C 45%, #6B4526 100%);
            font-family: system-ui, -apple-system, sans-serif;
        }

        /* Bintik-bintik biji kopi samar di background */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle, rgba(255,255,255,0.06) 1.5px, transparent 1.5px);
            background-size: 28px 28px;
            animation: drift 12s linear infinite;
        }

        @keyframes drift {
            from { background-position: 0 0; }
            to { background-position: 120px 120px; }
        }

        .content {
            position: relative;
            text-align: center;
            color: white;
            animation: fadeInUp 0.9s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .cup-wrap {
            position: relative;
            width: 90px;
            height: 90px;
            margin: 0 auto 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cup-glow {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.25) 0%, transparent 70%);
            animation: pulse 2.4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(0.9); opacity: 0.6; }
            50% { transform: scale(1.15); opacity: 1; }
        }

        .cup {
            font-size: 42px;
            position: relative;
            animation: bob 2.4s ease-in-out infinite;
        }

        @keyframes bob {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        .steam {
            position: absolute;
            top: -14px;
            font-size: 14px;
            opacity: 0;
            filter: blur(0.3px);
        }
        .steam1 { left: 30px; animation: steamRise 2.4s ease-in infinite; }
        .steam2 { left: 44px; animation: steamRise 2.4s ease-in infinite 0.5s; }
        .steam3 { left: 58px; animation: steamRise 2.4s ease-in infinite 1s; }

        @keyframes steamRise {
            0%   { opacity: 0; transform: translateY(0) scale(0.8); }
            30%  { opacity: 0.7; }
            100% { opacity: 0; transform: translateY(-30px) scale(1.3); }
        }

        h1 {
            font-size: 2.25rem;
            font-weight: 800;
            letter-spacing: 0.02em;
            text-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }

        p.tagline {
            color: #FDE9D9;
            margin-top: 6px;
            font-size: 0.9rem;
            letter-spacing: 0.05em;
        }

        .progress-track {
            width: 160px;
            height: 4px;
            background: rgba(255,255,255,0.25);
            border-radius: 999px;
            margin: 26px auto 0;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            width: 0%;
            background: white;
            border-radius: 999px;
            animation: fill 2s ease-in-out forwards;
        }

        @keyframes fill {
            from { width: 0%; }
            to { width: 100%; }
        }

        .loading-text {
            margin-top: 10px;
            font-size: 0.7rem;
            color: rgba(255,255,255,0.7);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="cup-wrap">
            <div class="cup-glow"></div>
            <span class="steam steam1">〰️</span>
            <span class="steam steam2">〰️</span>
            <span class="steam steam3">〰️</span>
            <span class="cup">☕</span>
        </div>

        <h1>Ngopi King!</h1>
        <p class="tagline">ORDER NOW</p>

        <div class="progress-track">
            <div class="progress-fill"></div>
        </div>
        <p class="loading-text">Menyiapkan menu...</p>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = "{{ route('order.menu') }}?table={{ $table }}";
        }, 2000);
    </script>
</body>
</html>