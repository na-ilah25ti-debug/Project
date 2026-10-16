<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resep - Yummyyum</title>
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
        }

        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
            color: #d9889a;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
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
        <a href="/Project/app/index.php" class="btn-back">← Kembali</a>
        <h1>✏️ Edit Resep</h1>

        <form action="/Project/app/index.php?aksi=update" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $resep['id'] ?? $resep['ID'] ?? '' ?>">
            <input type="hidden" name="gambar_lama" value="<?= $resep['Gambar'] ?? '' ?>">

            <div class="form-group">
                <label>Nama Resep</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($resep['Nama']) ?>" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="nusantara" <?= $resep['Kategori'] === 'nusantara' ? 'selected' : '' ?>>Nusantara</option>
                    <option value="mie" <?= $resep['Kategori'] === 'mie' ? 'selected' : '' ?>>Mie & Pasta</option>
                    <option value="vegetarian" <?= $resep['Kategori'] === 'vegetarian' ? 'selected' : '' ?>>Vegetarian</option>
                    <option value="dessert" <?= $resep['Kategori'] === 'dessert' ? 'selected' : '' ?>>Kue & Dessert</option>
                    <option value="minuman" <?= $resep['Kategori'] === 'minuman' ? 'selected' : '' ?>>Minuman</option>
                    <option value="Cemilan" <?= $resep['Kategori'] === 'Cemilan' ? 'selected' : '' ?>>Cemilan</option>
                </select>
            </div>

            <div class="form-group">
                <label>Estimasi Harga (Rp)</label>
                <input type="number" name="harga" value="<?= $resep['Harga'] ?>" required>
            </div>

            <div class="form-group">
                <label>Waktu Memasak (menit)</label>
                <input type="number" name="waktu" value="<?= $resep['Waktu'] ?>" required>
            </div>

            <div class="form-group">
                <label>Rating (1-5)</label>
                <input type="number" name="rating" min="1" max="5" step="0.1" value="<?= $resep['Rating'] ?>" required>
            </div>

            <div class="form-group">
                <label>Bahan-bahan</label>
                <textarea name="bahan" required><?= htmlspecialchars($resep['Bahan']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Langkah Memasak</label>
                <textarea name="langkah" required><?= htmlspecialchars($resep['Langkah']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Ganti Gambar (kosongkan jika tidak ingin ganti)</label>
                <?php if (!empty($resep['Gambar'])): ?>
                    <img src="/Project/app/views/Home/images/<?= $resep['Gambar'] ?>"
                        style="width:100%; border-radius:10px; margin-bottom:10px;">
                <?php endif; ?>
                <input type="file" name="gambar" accept="image/*">
            </div>

            <button type="submit" class="btn-submit">💾 Simpan Perubahan</button>
        </form>
    </div>
</body>

</html>