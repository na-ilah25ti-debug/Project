<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Yummyyum</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
          integrity="sha512-zSwDPD6INVrV/1HTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://localhost/Project/app/public/assets/css/register.css">
</head>
<body>
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:1080;">
        <div id="appToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="appToastBody"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <div class="floating-elements"></div>
    <div class="floating-elements"></div>
    <div class="floating-elements"></div>
    <div class="floating-elements"></div>

    <div class="container">
        <div class="glass-card">
            <h1>Daftar Akun Baru</h1>

            <form id="registerForm" action="?aksi=prosesRegister" method="POST">
                <div class="input-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" placeholder="Nama Lengkap" required />
                    <i class="fas fa-user"></i>
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Alamat Email" required />
                    <i class="fas fa-envelope"></i>
                </div>

                <div class="input-group">
                    <label for="jabatan">Jabatan / Role</label>
                    <select name="jabatan" id="jabatan" required
                        style="width:100%; padding:12px 15px; background:rgba(255,255,255,0.6); border:1px solid #ffb6c1; border-radius:10px; color:#4a3b3b; font-size:16px;">
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Buat password" required />
                    <i class="fas fa-lock"></i>
                </div>

                <button type="submit" class="btn">Daftar</button>
            </form>

            <div class="register-link">
                Sudah punya akun? <a href="?aksi=login">Login di sini</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showAppToast(message) {
            document.getElementById('appToastBody').textContent = message;
            new bootstrap.Toast(document.getElementById('appToast')).show();
        }

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            if (password.length < 4) {
                e.preventDefault();
                showAppToast('Password minimal 4 karakter!');
            }
        });
    </script>
</body>
</html>