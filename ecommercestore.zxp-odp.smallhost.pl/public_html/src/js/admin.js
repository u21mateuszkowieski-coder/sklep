// === PRZEŁĄCZANIE SEKCJI ===
const menuLinks = document.querySelectorAll('.menu-link');
const sections = document.querySelectorAll('.card');

menuLinks.forEach(link => {
  link.addEventListener('click', e => {
    e.preventDefault();
    menuLinks.forEach(l => l.classList.remove('active'));
    link.classList.add('active');

    const target = link.dataset.section;
    sections.forEach(sec => {
      sec.classList.add('hidden');
      sec.classList.remove('visible');
      if (sec.id === target) {
        sec.classList.remove('hidden');
        sec.classList.add('visible');
      }
    });
  });
});

// === WYLOGOWANIE ADMINA ===
const logoutBtn = document.getElementById("logoutBtn");
if (logoutBtn) {
  logoutBtn.addEventListener("click", () => {
    // usuń dane sesji
    localStorage.removeItem("adminToken");
    sessionStorage.removeItem("adminSession");

    // przekieruj na stronę logowania
    window.location.href = "/admin/login.html";
  });
}

// === PODGLĄD ZDJĘCIA ===
const productImageInput = document.getElementById("productImage");
const imagePreview = document.getElementById("imagePreview");

if (productImageInput && imagePreview) {
  productImageInput.addEventListener("change", () => {
    const file = productImageInput.files[0];
    imagePreview.innerHTML = "";
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        const img = document.createElement("img");
        img.src = e.target.result;
        img.style.maxWidth = "150px";
        img.style.borderRadius = "8px";
        imagePreview.appendChild(img);
      };
      reader.readAsDataURL(file);
    } else {
      imagePreview.innerHTML = "<p>Brak wybranego zdjęcia</p>";
    }
  });
}

// === PROMOCJE ===
const promoCategory = document.getElementById("promoCategory");
const promoProduct = document.getElementById("promoProduct");

const produktyPoKategoriach = {
  elektronika: [
    { id: 1, name: "Słuchawki TWS" },
    { id: 2, name: "Laptop Dell" },
    { id: 3, name: "Smartwatch Huawei" }
  ],
  moda: [
    { id: 4, name: "Kurtka zimowa" },
    { id: 5, name: "Sneakersy Nike" }
  ],
  dom: [
    { id: 6, name: "Ekspres do kawy" },
    { id: 7, name: "Odkurzacz Dyson" }
  ]
};

if (promoCategory && promoProduct) {
  promoCategory.addEventListener("change", () => {
    const category = promoCategory.value;
    promoProduct.innerHTML = "";
    if (!category || !produktyPoKategoriach[category]) {
      promoProduct.disabled = true;
      promoProduct.innerHTML = `<option value="">— najpierw wybierz kategorię —</option>`;
      return;
    }
    const produkty = produktyPoKategoriach[category];
    promoProduct.disabled = false;
    promoProduct.innerHTML = `
      <option value="">— wybierz produkt —</option>
      ${produkty.map(p => `<option value="${p.id}">${p.name}</option>`).join("")}
    `;
  });
}
