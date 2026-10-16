<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Yummyyum</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-zSwDPD6INVrV/1HTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="public/assets/css/login.css">
    <style>
        /* Toast notifikasi custom (pengganti Bootstrap Toast) */
        #appToast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1080;
            min-width: 260px;
            max-width: 350px;
            background-color: #d9534f;
            color: #fff;
            padding: 14px 16px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            opacity: 0;
            transform: translateY(-10px);
            pointer-events: none;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        #appToast.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        #appToast .toast-close {
            background: none;
            border: none;
            color: #fff;
            font-size: 18px;
            line-height: 1;
            cursor: pointer;
            opacity: 0.8;
        }
        #appToast .toast-close:hover {
            opacity: 1;
        }
    </style>
</head>

<body>
    <div id="appToast">
        <span id="appToastBody"></span>
        <button type="button" class="toast-close" onclick="hideAppToast()" aria-label="Close">&times;</button>
    </div>

    <div class="container">
        <div class="glass-card">

            <h1>Login Form</h1>

            <form id="loginForm" action="?aksi=prosesLogin" method="POST">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required />
                    <i class="fas fa-envelope"></i>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required />
                    <i class="fas fa-lock"></i>
                </div>

                <div class="remember-forgot">
                    <div class="remember">
                        <input type="checkbox" id="remember" />
                        <label for="remember">Remember me</label>
                    </div>
                    <div class="forgot">
                        <a href="#">Forgot password?</a>
                    </div>
                </div>

                <button type="submit" class="btn">Login</button>

                <div class="register-link">
                    Don't have an account? <a href="?aksi=register">Sign up</a>
                </div>
            </form>
        </div>
    </div>

    <div class="floating-elements"></div>
    <div class="floating-elements"></div>
    <div class="floating-elements"></div>
    <div class="floating-elements"></div>
    <div class="login-box"></div>

    <script>
        let toastTimer = null;

        // tampilkan toast custom (tanpa Bootstrap), otomatis hilang setelah 3 detik
        function showAppToast(message) {
            const toast = document.getElementById('appToast');
            document.getElementById('appToastBody').textContent = message;
            toast.classList.add('show');

            clearTimeout(toastTimer);
            toastTimer = setTimeout(hideAppToast, 3000);
        }

        // sembunyikan toast (dipanggil otomatis atau saat tombol ✕ diklik)
        function hideAppToast() {
            document.getElementById('appToast').classList.remove('show');
        }

        document.getElementById('loginForm').addEventListener('submit', function (e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            if (email === "" || password === "") {
                e.preventDefault(); 
                showAppToast('Email dan Password wajib diisi!');
            }
            
        });
    </script>

</body>
</html>