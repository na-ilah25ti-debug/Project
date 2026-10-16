<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Komentar - Yummyyum</title>
    <link rel="stylesheet" href="http://localhost/Project/app/public/assets/css/home.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .form-container h1 {
            color: #d9889a;
            margin-bottom: 30px;
            font-size: 22px;
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

        .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #ffb6c1;
            border-radius: 10px;
            font-size: 15px;
            color: #4a3b3b;
            background: #fff8f9;
            box-sizing: border-box;
            height: 120px;
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
        <h1>✏️ Edit Komentar</h1>

        <form action="/Project/app/index.php?aksi=updateKomentar" method="POST">
            <input type="hidden" name="id" value="<?= $komentar['id'] ?? '' ?>">

            <div class="form-group">
                <label>Rating</label>
                <select name="rating" style="width:100%; padding:10px 14px; border:1px solid #ffb6c1; border-radius:10px; font-size:15px; color:#4a3b3b; background:#fff8f9;">
                    <?php $ratingLama = (int)($komentar['rating'] ?? 5); ?>
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <option value="<?= $i ?>" <?= $ratingLama === $i ? 'selected' : '' ?>>
                            <?= str_repeat('★', $i) . str_repeat('☆', 5 - $i) ?> (<?= $i ?>)
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Isi Komentar</label>
                <textarea name="isi" required><?= htmlspecialchars($komentar['isi'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn-submit">💾 Simpan Perubahan</button>
        </form>
    </div>
</body>

</html>
