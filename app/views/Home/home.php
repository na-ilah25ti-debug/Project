<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="http://localhost/project/app/public/assets/css/home.css">

  <title>Yummyyum</title>
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

  <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Konfirmasi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="confirmModalBody">Yakin ingin melanjutkan?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-danger" id="confirmModalYes">Ya, Lanjutkan</button>
        </div>
      </div>
    </div>
  </div>
  <nav class="navbar">
    <div class="nav-brand" onclick="showPage('home')">🌸 Yummyyum</div>
    <ul class="nav-links">
      <li><a id="nav-home" class="active" onclick="showPage('home')">(ㅅ´ ˘ `)Beranda</a></li>
      <li><a id="nav-budget" onclick="showPage('budget')">(ᵕ • ᴗ •)Resep Hemat</a></li>
      <li><a id="nav-favorit" onclick="showPage('favorit')">(❀❛ ֊ ❛„)♡Favorit</a></li>
    </ul>

    <div class="nav-right">
      <span style="font-size:13px; color:#b5838d; margin-right:8px; text-transform:capitalize;">
        <?= isset($_SESSION['jabatan']) ? $_SESSION['jabatan'] : '' ?>
      </span>
      <div class="nav-avatar">👤</div>
      <button class="btn-logout" onclick="window.location.href='/Project/app/index.php?aksi=logout'">Logout</button>
    </div>
  </nav>

  <div id="page-home" class="page active">
    <main class="container">
      <section class="hero">
        <div class="hero-text">
          <h1>Masak Enak,<br><span>Mulai dari Sini</span></h1>
          <p>cook with Yummyyum, taste the love</p>
          <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Cari resep, bahan, atau masakan..." />
            <button onclick="doSearch()">Cari</button>
          </div>

          <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] === 'admin'): ?>
            <button onclick="window.location.href='/Project/app/index.php?aksi=tambah'"
              style="margin-top:16px; padding:10px 24px; background:linear-gradient(to right,#ffc0cb,#f5f5dc); border:none; border-radius:10px; color:#5d4037; font-weight:600; font-size:15px; cursor:pointer; display:block;">
              ➕ Tambah Resep
            </button>
          <?php endif; ?>
        </div>


        <div class="hero-plate"></div>
      </section>

      <div class="categories">
        <button class="cat-btn active" onclick="filterKategori(this, 'semua')">Semua</button>
        <button class="cat-btn" onclick="filterKategori(this, 'nusantara')">Nusantara</button>
        <button class="cat-btn" onclick="filterKategori(this, 'mie')">Mie & Pasta</button>
        <button class="cat-btn" onclick="filterKategori(this, 'vegetarian')">Vegetarian</button>
        <button class="cat-btn" onclick="filterKategori(this, 'dessert')">Kue & Dessert</button>
        <button class="cat-btn" onclick="filterKategori(this, 'minuman')">Minuman</button>
        <button class="cat-btn" onclick="filterKategori(this, 'cemilan')">Cemilan</button>
      </div>

      <h2 class="section-title" id="gridTitle">Resep Populer</h2>
      <div class="recipe-grid" id="mainGrid"></div>

      <div class="tips-box">
        <h3>Yummyyum</h3>
        <p>Pasti akan membuat lebih hemat.</p>
      </div>
    </main>
  </div>

  <div id="page-detail" class="page">
    <div class="container" style="max-width:860px;">
      <div class="back-btn" id="detailBackBtn" onclick="showPage('home')">← Kembali ke Beranda</div>
      <div id="editBtnContainer"></div>
      <div class="recipe-hero">
        <img id="d-img" src="" alt="" />
        <div class="recipe-hero-overlay">
          <h1 id="d-title"></h1>
          <div class="recipe-tags"><span class="tag" id="d-badge"></span></div>
        </div>
      </div>
      <div class="info-strip">
        <div class="info-card">
          <div class="icon">⏱</div>
          <div class="label">Waktu</div>
          <div class="value" id="d-time"></div>
        </div>
        <div class="info-card">
          <div class="icon"></div>
          <div class="label">Porsi</div>
          <div class="value" id="d-portions"></div>
        </div>
        <div class="info-card">
          <div class="icon"></div>
          <div class="label">Kesulitan</div>
          <div class="value" id="d-diff"></div>
        </div>
        <div class="info-card">
          <div class="icon"></div>
          <div class="label">Rating</div>
          <div class="value" id="d-rating"></div>
        </div>
        <div class="info-card" id="d-price-card" style="display:none;">
          <div class="icon"></div>
          <div class="label">Est. Harga</div>
          <div class="value" id="d-price"></div>
        </div>
      </div>
    </div>
    <div class="detail-layout">
      <div class="section-box">
        <h2>Bahan-bahan</h2>
        <ul class="bahan-list" id="d-bahan"></ul>
        <button class="fav-btn" id="favBtn" onclick="toggleFav()">Simpan ke Favorit</button>
      </div>
      <div class="section-box">
        <h2>Cara Memasak</h2>
        <ol class="step-list" id="d-steps"></ol>
      </div>
    </div>

    <div class="container" style="max-width:860px;">
      <div class="section-box" style="margin-top:20px;">
        <h2>Komentar</h2>

        <form id="komentarForm" onsubmit="return kirimKomentar(event)" style="margin-bottom:20px;">
          <input type="hidden" id="komentarResepId" name="resep_id" value="">
          <input type="hidden" id="komentarRating" name="rating" value="5">

          <div id="komentarStars" style="margin-bottom:10px; display:flex; align-items:center;"></div>

          <textarea id="komentarIsi" name="isi" placeholder="Tulis komentar tentang resep ini..."
            style="width:100%; padding:10px 14px; border:1px solid #ffb6c1; border-radius:10px; font-size:14px; height:70px; resize:vertical; box-sizing:border-box;" required></textarea>
          <button type="submit"
            style="margin-top:8px; padding:8px 20px; background:linear-gradient(to right,#ffc0cb,#f5f5dc); border:none; border-radius:10px; color:#5d4037; font-weight:600; cursor:pointer;">
            Kirim Komentar
          </button>
        </form>

        <div id="komentarList"></div>
      </div>
    </div>
  </div>

  <div id="page-favorit" class="page">
    <div class="container">
      <div class="page-header">
        <h1>Resep Favoritku</h1>
        <p id="favCount"></p>
      </div>
      <div class="recipe-grid" id="favGrid"></div>
      <div id="emptyState" class="empty-state" style="display:none;">
        <div class="icon"></div>
        <h2>Belum ada favorit</h2>
        <p>Simpan resep yang kamu suka dari halaman detail!</p>
        <button onclick="showPage('home')">Jelajahi Resep</button>
      </div>
    </div>
  </div>

  <div id="page-budget" class="page">
    <div class="container">
      <div class="budget-hero">
        <div class="budget-hero-text">
          <h1>Masak Enak,<br><span>Tetap Hemat</span></h1>
          <p>Cari resep berdasarkan estimasi harga bahan.<br>Cocok buat kamu yang mau masak tanpa kantong jebol!</p>
        </div>
        <div class="budget-hero-icon"></div>
      </div>

      <div class="budget-panel">
        <div class="budget-panel-title">Atur Budget Kamu</div>
        <div class="budget-controls">
          <div class="budget-slider-row">
            <span class="budget-slider-label">Maks. harga</span>
            <input type="range" class="budget-slider" id="budgetSlider" min="5000" max="100000" step="5000" value="50000" oninput="onSliderChange(this)" />
            <div class="budget-value-display" id="budgetDisplay">Rp 50.000</div>
          </div>
          <div class="budget-presets">
            <span class="sort-label">Preset:</span>
            <button class="preset-chip" onclick="setPreset(this, 15000)">≤ Rp 15rb</button>
            <button class="preset-chip active" onclick="setPreset(this, 50000)">≤ Rp 50rb</button>
            <button class="preset-chip" onclick="setPreset(this, 75000)">≤ Rp 75rb</button>
            <button class="preset-chip" onclick="setPreset(this, 100000)">Semua</button>
          </div>
          <div class="budget-divider"></div>
          <div class="budget-sort-row">
            <span class="sort-label">Urutkan:</span>
            <button class="sort-btn active" onclick="setSort(this,'price-asc')">Termurah ↑</button>
            <button class="sort-btn" onclick="setSort(this,'price-desc')">Termahal ↓</button>
            <button class="sort-btn" onclick="setSort(this,'rating')">Rating</button>
            <button class="sort-btn" onclick="setSort(this,'time')">Tercepat </button>
          </div>
        </div>
      </div>

      <div class="result-bar">
        <div class="result-count" id="budgetResultCount">Menampilkan <strong>0</strong> resep</div>
      </div>
      <div class="budget-grid" id="budgetGrid"></div>

      <h2 class="section-title">Tips Belanja Hemat</h2>
      <div class="budget-tips-strip">
        <div class="budget-tip-card">
          <div class="budget-tip-icon"></div>
          <div class="budget-tip-text">
            <h4>Belanja di Pasar Tradisional</h4>
            <p>Harga bahan segar bisa 20–40% lebih murah dibanding supermarket.</p>
          </div>
        </div>
        <div class="budget-tip-card">
          <div class="budget-tip-icon"></div>
          <div class="budget-tip-text">
            <h4>Telur & Tempe, Pahlawan Dapur</h4>
            <p>Protein murah meriah. Bisa diolah jadi puluhan masakan berbeda.</p>
          </div>
        </div>
        <div class="budget-tip-card">
          <div class="budget-tip-icon"></div>
          <div class="budget-tip-text">
            <h4>Bumbu Dasar Beli Sekaligus</h4>
            <p>Bawang, cabai, dan kunyit beli banyak sekaligus — hemat lebih banyak.</p>
          </div>
        </div>
        <div class="budget-tip-card">
          <div class="budget-tip-icon"></div>
          <div class="budget-tip-text">
            <h4>Nasi Sisa = Modal Goreng</h4>
            <p>Nasi sisa kemarin? Jadikan nasi goreng, onigiri, atau bubur instan.</p>
          </div>
        </div>
      </div>
    </div>
  </div>


  <footer class="footer">
    <p>IG:@meownive</p>
  </footer>


  <script>
    const isAdmin = <?= (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] === 'admin') ? 'true' : 'false' ?>;
    const currentUser = <?= json_encode($_SESSION['nama'] ?? '') ?>;
    const resepData = <?php
                      $resepObj = [];
                      if ($resep) {
                        while ($row = mysqli_fetch_assoc($resep)) {
                          $id = $row['id'] ?? $row['ID'] ?? null;
                          if (!$id) continue;
                          $harga = (int)$row['Harga'];
                          $resepObj[$id] = [
                            'id'         => (string)$id,
                            'title'      => $row['Nama'],
                            'badge'      => $row['Kategori'],
                            'kategori'   => strtolower($row['Kategori']),
                            'time'       => $row['Waktu'] . ' menit',
                            'timeMinutes' => (int)$row['Waktu'],
                            'rating'     => $row['Rating'],
                            'portions'   => '2 porsi',
                            'diff'       => 'Mudah',
                            'img' => !empty($row['Gambar'])
                              ? '/Project/app/views/Home/images/' . $row['Gambar']
                              : 'https://placehold.co/400x300?text=No+Image',
                            'bahan'      => explode("\n", $row['Bahan']),
                            'langkah'    => explode("\n", $row['Langkah']),
                            'hargaMin'   => $harga,
                            'hargaLabel' => 'Rp ' . number_format($harga, 0, ',', '.'),
                            'komentar'   => [],
                          ];
                        }
                      }
                      if (!empty($komentarRaw)) {
                        while ($k = mysqli_fetch_assoc($komentarRaw)) {
                          $rid = $k['resep_id'];
                          if (isset($resepObj[$rid])) {
                            $resepObj[$rid]['komentar'][] = [
                              'id'     => (string)$k['id'],
                              'nama'   => $k['nama'],
                              'isi'    => $k['isi'],
                              'waktu'  => $k['created_at'],
                              'rating' => (int)($k['rating'] ?? 5),
                            ];
                          }
                        }
                      }
                      echo json_encode($resepObj);
                      ?>;
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function showAppToast(message, type = 'danger') {
      const toastEl = document.getElementById('appToast');
      toastEl.classList.remove('text-bg-danger', 'text-bg-success');
      toastEl.classList.add(type === 'success' ? 'text-bg-success' : 'text-bg-danger');
      document.getElementById('appToastBody').textContent = message;
      new bootstrap.Toast(toastEl).show();
    }

    function showConfirm(message, onYes) {
      document.getElementById('confirmModalBody').textContent = message;
      const modalEl = document.getElementById('confirmModal');
      const modal = new bootstrap.Modal(modalEl);
      const yesBtn = document.getElementById('confirmModalYes');

      const handler = function () {
        modal.hide();
        yesBtn.removeEventListener('click', handler);
        onYes();
      };
      yesBtn.addEventListener('click', handler);
      modal.show();
    }

    <?php if (isset($_GET['denied'])): ?>
    document.addEventListener('DOMContentLoaded', function () {
      showAppToast('Akses Ditolak! Jabatan Anda (<?= htmlspecialchars($_GET['role'] ?? '') ?>) tidak diizinkan mengakses halaman ini.');
    });
    <?php endif; ?>
  </script>
  <script src="http://localhost/Project/app/views/Home/home.js"></script>
 
</body>

</html>