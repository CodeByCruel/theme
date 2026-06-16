<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#df3050">
    <title>Login — BSDK Panel</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #0c0a09;
            color: #e7e5e4;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        #particles-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/DGEN/background.webp') no-repeat center center;
            background-size: cover;
            z-index: 0;
        }

        .bg-overlay::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(12, 10, 9, 0.85);
            z-index: 1;
        }

        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: rgba(28, 25, 23, 0.6);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(223, 48, 80, 0.15);
            border-radius: 20px;
            padding: 40px 36px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.5), 0 0 80px rgba(223, 48, 80, 0.05);
        }

        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-logo h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, #df3050, #ff6b6b, #df3050);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 3s ease infinite;
        }

        .login-logo p {
            font-size: 13px;
            color: #78716c;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #a8a29e;
            margin-bottom: 8px;
        }

        .form-group .input-wrapper {
            position: relative;
        }

        .form-group .input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #57534e;
            font-size: 15px;
            transition: color 0.3s;
        }

        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px 14px 12px 42px;
            background: rgba(41, 37, 36, 0.6);
            border: 1px solid rgba(87, 83, 78, 0.3);
            border-radius: 10px;
            color: #e7e5e4;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-group input[type="email"]::placeholder,
        .form-group input[type="password"]::placeholder {
            color: #57534e;
        }

        .form-group input[type="email"]:focus,
        .form-group input[type="password"]:focus {
            border-color: rgba(223, 48, 80, 0.5);
            box-shadow: 0 0 0 3px rgba(223, 48, 80, 0.1);
        }

        .form-group input[type="email"]:focus + i,
        .form-group input[type="password"]:focus ~ i {
            color: #df3050;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 13px;
            color: #a8a29e;
        }

        .remember-me input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border: 1.5px solid #57534e;
            border-radius: 4px;
            background: transparent;
            cursor: pointer;
            position: relative;
            transition: border-color 0.3s, background 0.3s;
        }

        .remember-me input[type="checkbox"]:checked {
            background: #df3050;
            border-color: #df3050;
        }

        .remember-me input[type="checkbox"]:checked::after {
            content: '\f00c';
            font-family: 'FontAwesome';
            font-size: 10px;
            color: #fff;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .forgot-link {
            font-size: 13px;
            color: #df3050;
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #ff6b6b;
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #df3050, #c4243d);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.3s, opacity 0.3s;
            letter-spacing: 0.3px;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(223, 48, 80, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .error-message {
            background: rgba(223, 48, 80, 0.1);
            border: 1px solid rgba(223, 48, 80, 0.3);
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #fca5a5;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .error-message i {
            color: #df3050;
            font-size: 14px;
        }

        .login-footer {
            text-align: center;
            margin-top: 28px;
            font-size: 12px;
            color: #57534e;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
            }

            .login-logo h1 {
                font-size: 26px;
            }

            .form-options {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="bg-overlay"></div>
    <canvas id="particles-canvas"></canvas>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-logo">
                <h1>BSDK</h1>
                <p>Game Server Management Panel</p>
            </div>

            @if($errors->any())
                <div class="error-message">
                    <i class="fa fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
                        <i class="fa fa-envelope"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <i class="fa fa-lock"></i>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Remember Me
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>

            <div class="login-footer">
                Made by Akshit
            </div>
        </div>
    </div>

    <script>
        (function() {
            const canvas = document.getElementById('particles-canvas');
            const ctx = canvas.getContext('2d');
            let particles = [];
            let animationId;

            function resize() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }

            function createParticle() {
                return {
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    size: Math.random() * 2 + 0.5,
                    speedX: (Math.random() - 0.5) * 0.6,
                    speedY: (Math.random() - 0.5) * 0.6,
                    opacity: Math.random() * 0.5 + 0.1
                };
            }

            function init() {
                resize();
                particles = [];
                const count = Math.min(Math.floor((canvas.width * canvas.height) / 12000), 120);
                for (let i = 0; i < count; i++) {
                    particles.push(createParticle());
                }
            }

            function draw() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                for (let i = 0; i < particles.length; i++) {
                    const p = particles[i];

                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(223, 48, 80, ${p.opacity})`;
                    ctx.fill();

                    for (let j = i + 1; j < particles.length; j++) {
                        const p2 = particles[j];
                        const dx = p.x - p2.x;
                        const dy = p.y - p2.y;
                        const dist = Math.sqrt(dx * dx + dy * dy);

                        if (dist < 120) {
                            ctx.beginPath();
                            ctx.moveTo(p.x, p.y);
                            ctx.lineTo(p2.x, p2.y);
                            ctx.strokeStyle = `rgba(223, 48, 80, ${0.1 * (1 - dist / 120)})`;
                            ctx.lineWidth = 0.5;
                            ctx.stroke();
                        }
                    }

                    p.x += p.speedX;
                    p.y += p.speedY;

                    if (p.x < 0 || p.x > canvas.width) p.speedX *= -1;
                    if (p.y < 0 || p.y > canvas.height) p.speedY *= -1;
                }

                animationId = requestAnimationFrame(draw);
            }

            window.addEventListener('resize', () => {
                cancelAnimationFrame(animationId);
                init();
                draw();
            });

            init();
            draw();
        })();
    </script>
</body>
</html>
