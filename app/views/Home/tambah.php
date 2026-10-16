<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Resep - Yummyyum</title>
    <link rel="stylesheet" href="http://localhost/Project/app/public/assets/css/home.css">
    <style>
        .form-container {
            max-width: 700px;
            margin: 40px auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .form-container h1 {
            color: #d9889a;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #8c6b6b;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #ffb6c1;
            border-radius: 10px;
            font-size: 15px;
            color: #4a3b3b;
            background: #fff8f9;
            box-sizing: border-box;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #ff9fb3;
            box-shadow: 0 0 8px rgba(255, 159, 179, 0.3);
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: linear-gradient(to right, #ffc0cb, #f5f5dc);
            border: none;
            border-radius: 10px;
            color: #5d4037;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 159, 179, 0.4);
        }

        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
            color: #d9889a;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-back:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="nav-brand">🌸 Yummyyum</div>
        <div class="nav-right">
            <button class="btn-logout" onclick="window.location.href='/Project/app/index.php?aksi=logout'">Logout</button>
        </div>
    </nav>

    <div class="form-container">
        <a href="/Project/app/index.php?aksi=index" class="btn-back">← Kembali ke Daftar Resep</a>
        <h1> Tambah Resep Baru</h1>

        <form action="/Project/app/index.php?aksi=simpan" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Resep</label>
                <input type="text" name="nama">
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="nusantara">Nusantara</option>
                    <option value="mie">Mie & Pasta</option>
                    <option value="vegetarian">Vegetarian</option>
                    <option value="dessert">Kue & Dessert</option>
                    <option value="minuman">Minuman</option>
                    <option value="Cemilan">Cemilan</option>
                </select>
            </div>

            <div class="form-group">
                <label>Estimasi Harga (Rp)</label>
                <input type="number" name="harga">
            </div>

            <div class="form-group">
                <label>Waktu Memasak (menit)</label>
                <input type="number" name="waktu">
            </div>

            <div class="form-group">
                <label>Rating (1-5)</label>
                <input type="number" name="rating" min="1" max="5" step="0.1">
            </div>

            <div class="form-group">
                <label>Bahan-bahan</label>
                <textarea name="bahan" ></textarea>
            </div>

            <div class="form-group">
                <label>Langkah Memasak</label>
                <textarea name="langkah" ></textarea>
            </div>

            <div class="form-group">
                <label>Gambar Resep</label>
                <input type="file" name="gambar" accept="image/*">
            </div>

            <button type="submit" class="btn-submit">💾 Simpan Resep</button>
        </form>
    </div>
</body>

</html>