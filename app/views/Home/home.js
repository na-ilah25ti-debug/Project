const BASE_IMG = "http://localhost/Project/app/views/Home/images/";

const allResep = Object.entries(resepData).map(([id, r]) => ({ id, ...r }));
let currentId = null;        
let budgetSort = "price-asc"; 

function showPage(name) {
  document
    .querySelectorAll(".page")
    .forEach((p) => p.classList.remove("active")); 
  document.getElementById("page-" + name).classList.add("active"); 
  document
    .querySelectorAll(".nav-links a")
    .forEach((a) => a.classList.remove("active"));
  const navEl = document.getElementById("nav-" + name);
  if (navEl) navEl.classList.add("active"); 
  window.scrollTo(0, 0);
  if (name === "favorit") loadFavorit();
  if (name === "home") renderGrid(allResep);
  if (name === "budget") renderBudgetGrid();
}

function renderGrid(list) {
  const grid = document.getElementById("mainGrid");
  if (list.length === 0) {
    grid.innerHTML =
      '<p style="color:#C4A0AD;font-size:14px;grid-column:1/-1;">Tidak ada resep ditemukan.</p>';
    return;
  }
  grid.innerHTML = list
    .map(
      (r) => `
       <div class="recipe-card" onclick="showDetail('${r.id}','home')">
          <img class="recipe-thumb" src="${r.img}" alt="${r.title}" onerror="this.style.background='#FFE8F0'" />
          <div class="recipe-body">
            <p class="recipe-name">${r.title}</p>
            <div class="recipe-meta"><span>${r.time}</span><span class="badge">${r.badge}</span></div>
          </div>
        </div>`,
    )
    .join("");
}

function filterKategori(btn, kat) {
  document
    .querySelectorAll(".cat-btn")
    .forEach((b) => b.classList.remove("active"));
  btn.classList.add("active");
  const filtered =
    kat === "semua" ? allResep : allResep.filter((r) => r.kategori === kat);
  document.getElementById("gridTitle").textContent =
    kat === "semua" ? "Semua Resep" : btn.textContent.trim();
  renderGrid(filtered);
}

function doSearch() {
  const q = document.getElementById("searchInput").value.trim().toLowerCase();
  if (!q) {
    renderGrid(allResep);
    document.getElementById("gridTitle").textContent = "Semua Resep";
    return;
  }
  const hasil = allResep.filter(
    (r) =>
      r.title.toLowerCase().includes(q) || r.badge.toLowerCase().includes(q),
  );
  document.getElementById("gridTitle").textContent = `Hasil pencarian: "${q}"`;
  renderGrid(hasil);
}
document.getElementById("searchInput").addEventListener("keydown", (e) => {
  if (e.key === "Enter") doSearch(); 
});

function showDetail(id, fromPage) {
  currentId = id;
  const r = resepData[id];
  document.getElementById("d-img").src = r.img;
  document.getElementById("d-img").alt = r.title;
  document.getElementById("d-title").textContent = r.title;
  document.getElementById("d-badge").textContent = r.badge;
  document.getElementById("d-time").textContent = r.time;
  document.getElementById("d-portions").textContent = r.portions;
  document.getElementById("d-diff").textContent = r.diff;
  document.getElementById("d-rating").textContent = r.rating;

  const priceCard = document.getElementById("d-price-card");
  if (r.hargaLabel) {
    document.getElementById("d-price").textContent = r.hargaLabel;
    priceCard.style.display = "";
  } else {
    priceCard.style.display = "none"; 
  }

  document.getElementById("d-bahan").innerHTML = r.bahan
    .map((b) => `<li>${b}</li>`)
    .join("");
  document.getElementById("d-steps").innerHTML = r.langkah
    .map(
      (l, i) => `
        <li class="step-item">
          <div class="step-num">${i + 1}</div>
          <div class="step-text">${l}</div>
        </li>`,
    )
    .join("");

  const backBtn = document.getElementById("detailBackBtn");
  if (fromPage === "budget") {
    backBtn.textContent = "← Kembali ke Resep Hemat";
    backBtn.onclick = () => showPage("budget");
  } else {
    backBtn.textContent = "← Kembali ke Beranda";
    backBtn.onclick = () => showPage("home");
  }

  const editContainer = document.getElementById("editBtnContainer");
  if (editContainer) {
    if (typeof isAdmin !== "undefined" && isAdmin) {
      editContainer.innerHTML = `
            <a href="/Project/app/index.php?aksi=edit&id=${id}" 
               style="display:inline-block; margin-bottom:16px; margin-right:10px; padding:8px 20px; 
                      background:linear-gradient(to right,#ffc0cb,#f5f5dc); 
                      border-radius:10px; color:#5d4037; font-weight:600; 
                      text-decoration:none; font-size:14px;">
                ✏️ Edit Resep
            </a>
            <button onclick="hapusResep('${id}')"
               style="display:inline-block; margin-bottom:16px; margin-right:10px; padding:8px 20px; 
                      background:linear-gradient(to right,#ffc0cb,#f5f5dc); 
                      border-radius:10px; color:#5d4037; font-weight:600; 
                       text-decoration:none; font-size:14px;">
                🗑️ Hapus Resep
            </button>`;
    } else {
      editContainer.innerHTML = "";
    }
  }
  const favs = JSON.parse(localStorage.getItem("yummyFavorit") || "[]");
  const btn = document.getElementById("favBtn");
  if (favs.find((f) => f.id === id)) {
    btn.textContent = "Tersimpan di Favorit";
    btn.classList.add("active");
  } else {
    btn.textContent = "Simpan ke Favorit";
    btn.classList.remove("active");
  }

  document.getElementById("komentarResepId").value = id; 
  document.getElementById("komentarRating").value = 5;   
  renderKomentarStarPicker(5);
  document.getElementById("komentarIsi").value = '';      
  renderKomentar(r.komentar || []);

  showPage("detail");
}
function starDisplay(rating) {
  let stars = "";
  for (let i = 1; i <= 5; i++) {
    stars += i <= rating ? "★" : "☆";
  }
  return stars;
}

function renderKomentar(list) {
  const wrap = document.getElementById("komentarList");
  if (!list || list.length === 0) {
    wrap.innerHTML = '<p style="color:#C4A0AD;font-size:14px;">Belum ada komentar. Jadilah yang pertama!</p>';
    return;
  }
  wrap.innerHTML = list
    .map((k) => {
      const bisaEdit = typeof currentUser !== "undefined" && (currentUser === k.nama || isAdmin);
      const rating = k.rating || 5;
      return `
        <div style="border-bottom:1px solid #ffe0ec; padding:10px 0;">
          <div style="display:flex; justify-content:space-between; align-items:center;">
            <strong style="color:#d9889a; font-size:14px;">${k.nama}</strong>
            <span style="font-size:11px; color:#C4A0AD;">${k.waktu}</span>
          </div>
          <div style="color:#f0a500; font-size:14px; margin-top:2px;">${starDisplay(rating)}</div>
          <p style="margin:6px 0; font-size:14px; color:#4a3b3b;">${k.isi}</p>
          ${bisaEdit ? `
            <div style="display:flex; gap:10px;">
              <a href="/Project/app/index.php?aksi=editKomentar&id=${k.id}" style="font-size:12px; color:#d9889a;">✏️ Edit</a>
              <a href="#" onclick="hapusKomentar('${k.id}'); return false;" style="font-size:12px; color:#d9889a;">🗑️ Hapus</a>
            </div>` : ''}
        </div>`;
    })
    .join("");
}

function renderKomentarStarPicker(selected = 5) {
  const wrap = document.getElementById("komentarStars");
  if (!wrap) return;
  wrap.innerHTML = '';
  for (let i = 1; i <= 5; i++) {
    const span = document.createElement('span');
    span.textContent = i;
    span.style.display = 'inline-flex';
    span.style.alignItems = 'center';
    span.style.justifyContent = 'center';
    span.style.width = '30px';
    span.style.height = '30px';
    span.style.marginRight = '6px';
    span.style.borderRadius = '50%';
    span.style.fontWeight = '600';
    span.style.fontSize = '14px';
    span.style.color = i === selected ? '#fff' : '#d9889a';
    span.style.background = i === selected ? '#d9889a' : '#fff0f4';
    span.style.border = '1px solid #ffb6c1';
    span.onclick = () => {
      document.getElementById('komentarRating').value = i;
      renderKomentarStarPicker(i);
    };
    wrap.appendChild(span);
  }
}
renderKomentarStarPicker(5);

function kirimKomentar(e) {
  e.preventDefault();
  const resepId = document.getElementById("komentarResepId").value;
  const isi = document.getElementById("komentarIsi").value.trim();
  const rating = document.getElementById("komentarRating").value || 5;
  if (!isi) return false;

  const form = document.createElement("form");
  form.method = "POST";
  form.action = "/Project/app/index.php?aksi=simpanKomentar";
  form.innerHTML = `<input type="hidden" name="resep_id" value="${resepId}">
                     <input type="hidden" name="rating" value="${rating}">
                     <input type="hidden" name="isi" value="${isi.replace(/"/g, '&quot;')}">`;
  document.body.appendChild(form);
  form.submit(); 
  return false;
}

function hapusKomentar(id) {
  showConfirm("Yakin ingin menghapus komentar ini?", function () {
    window.location.href = `/Project/app/index.php?aksi=hapusKomentar&id=${id}`;
  });
}

function hapusResep(id) {
  const r = resepData[id];
  const nama = r ? r.title : "resep ini";
  showConfirm(`Yakin ingin menghapus "${nama}"? Tindakan ini tidak bisa dibatalkan.`, function () {
    window.location.href = `/Project/app/index.php?aksi=hapus&id=${id}`;
  });
}

function toggleFav() {
  const r = resepData[currentId];
  const btn = document.getElementById("favBtn");
  let favs = JSON.parse(localStorage.getItem("yummyFavorit") || "[]");
  const idx = favs.findIndex((f) => f.id === currentId);
  if (idx === -1) {
    favs.push({
      id: currentId,
      title: r.title,
      img: r.img,
      time: r.time,
      badge: r.badge,
    });
    btn.textContent = "❤️ Tersimpan di Favorit";
    btn.classList.add("active");
  } else {
    favs.splice(idx, 1);
    btn.textContent = "🤍 Simpan ke Favorit";
    btn.classList.remove("active");
  }
  localStorage.setItem("yummyFavorit", JSON.stringify(favs));
}

function loadFavorit() {
  const favs = JSON.parse(localStorage.getItem("yummyFavorit") || "[]");
  const grid = document.getElementById("favGrid");
  const empty = document.getElementById("emptyState");
  const count = document.getElementById("favCount");
  grid.innerHTML = "";
  if (favs.length === 0) {
    empty.style.display = "block";
    count.textContent = "Kamu belum menyimpan resep apapun.";
    return;
  }
  empty.style.display = "none";
  count.textContent = favs.length + " resep tersimpan";
  grid.innerHTML = favs
    .map(
      (f) => `
        <div class="recipe-card" onclick="showDetail('${f.id}','home')">
          <button class="remove-btn" onclick="hapusFavorit('${f.id}', event)">✕</button>
          <img class="recipe-thumb" src="${f.img}" alt="${f.title}" onerror="this.style.background='#FFE8F0'" />
          <div class="recipe-body">
            <p class="recipe-name">${f.title}</p>
            <div class="recipe-meta"><span>${f.time}</span><span class="badge">${f.badge}</span></div>
          </div>
        </div>`,
    )
    .join("");
}

function hapusFavorit(id, e) {
  e.stopPropagation(); 
  let favs = JSON.parse(localStorage.getItem("yummyFavorit") || "[]");
  favs = favs.filter((f) => f.id !== id);
  localStorage.setItem("yummyFavorit", JSON.stringify(favs));
  loadFavorit();
}

const maxPrice = 100000;
function formatRupiah(n) {
  return "Rp " + n.toLocaleString("id-ID");
}

function onSliderChange(el) {
  const val = parseInt(el.value);
  const pct = ((val - 5000) / (maxPrice - 5000)) * 100;
  el.style.setProperty("--pct", pct + "%"); // buat efek warna terisi di slider
  document.getElementById("budgetDisplay").textContent =
    val >= maxPrice ? "Semua" : formatRupiah(val);
  document
    .querySelectorAll(".preset-chip")
    .forEach((c) => c.classList.remove("active"));
  renderBudgetGrid();
}

function setPreset(btn, val) {
  document
    .querySelectorAll(".preset-chip")
    .forEach((c) => c.classList.remove("active"));
  btn.classList.add("active");
  const slider = document.getElementById("budgetSlider");
  slider.value = val;
  const pct = ((val - 5000) / (maxPrice - 5000)) * 100;
  slider.style.setProperty("--pct", pct + "%");
  document.getElementById("budgetDisplay").textContent =
    val >= maxPrice ? "Semua" : formatRupiah(val);
  renderBudgetGrid();
}

function setSort(btn, mode) {
  document
    .querySelectorAll(".sort-btn")
    .forEach((b) => b.classList.remove("active"));
  btn.classList.add("active");
  budgetSort = mode;
  renderBudgetGrid();
}

function getPriceClass(price) {
  if (price <= 15000) return "cheap";
  if (price <= 40000) return "mid";
  return "pricey";
}

function getHematScore(price) {
  return Math.max(0, Math.round(100 - (price / maxPrice) * 100));
}

function renderBudgetGrid() {
  const maxBudget = parseInt(document.getElementById("budgetSlider").value);
  let list = allResep.filter((r) => r.hargaMin && r.hargaMin <= maxBudget); 
  if (budgetSort === "price-asc") list.sort((a, b) => a.hargaMin - b.hargaMin);
  else if (budgetSort === "price-desc")
    list.sort((a, b) => b.hargaMin - a.hargaMin);
  else if (budgetSort === "rating")
    list.sort((a, b) => parseFloat(b.rating) - parseFloat(a.rating));
  else if (budgetSort === "time")
    list.sort((a, b) => (a.timeMinutes || 60) - (b.timeMinutes || 60));
  const grid = document.getElementById("budgetGrid");
  const countEl = document.getElementById("budgetResultCount");
  countEl.innerHTML = "Menampilkan <strong>" + list.length + "</strong> resep";
  if (list.length === 0) {
    grid.innerHTML =
      '<div class="no-results"><div class="icon">😢</div><p>Tidak ada resep dengan budget ini.<br>Coba naikkan sedikit budgetnya!</p></div>';
    return;
  }
  grid.innerHTML = list
    .map((r) => {
      const hemat = getHematScore(r.hargaMin);
      const cls = getPriceClass(r.hargaMin);
      const label =
        cls === "cheap"
          ? "Super Hemat"
          : cls === "mid"
            ? "Terjangkau"
            : "Agak Mahal";
      return `
          <div class="budget-card" onclick="showDetail('${r.id}','budget')">
            <img class="budget-card-img" src="${r.img}" alt="${r.title}" onerror="this.style.background='#FFE8F0'" />
            <div class="price-ribbon ${cls}">${label}</div>
            <div class="budget-card-body">
              <div class="budget-card-name">${r.title}</div>
              <div class="budget-card-sub">${r.time} · ${r.diff}</div>
              <div class="budget-card-footer">
                <div class="price-tag">${r.hargaLabel} <small>/ porsi</small></div>
                <div class="portion-tag">${r.portions}</div>
              </div>
              <div class="hemat-bar-wrap">
                <div class="hemat-bar-label"><span>Kehematan</span><span>${hemat}%</span></div>
                <div class="hemat-bar-track">
                  <div class="hemat-bar-fill" style="width:${hemat}%"></div>
                </div>
              </div>
            </div>t 
          </div>`;
    })
    .join("");
}

(function () {
  const slider = document.getElementById("budgetSlider");
  if (slider) {
    const pct = ((parseInt(slider.value) - 5000) / (maxPrice - 5000)) * 100;
    slider.style.setProperty("--pct", pct + "%");
  }
})();

renderGrid(allResep);

(function () {
  const hash = window.location.hash;
  if (hash.startsWith("#resep=")) {
    const id = hash.replace("#resep=", "");
    if (resepData[id]) {
      showDetail(id, "home");
    }
  }
})();