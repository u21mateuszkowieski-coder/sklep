async function loadProducts(sectionId, count) {
  const grid = document.getElementById(sectionId);
  if (grid) {
    const res = await fetch("/src/components/productCard.html");
    const html = await res.text();

    for (let i = 1; i <= count; i++) {
      grid.innerHTML += html
        .replace("Nazwa produktu", `Produkt ${i}`)
        .replace("Opis produktu — wyjątkowa jakość i styl, stworzony z myślą o Tobie.", 
          "Nowoczesny, elegancki produkt w wyjątkowej ofercie.")
        .replace("../assets/images/tlo.jpg", `../src/assets/images/tlo.jpg`);
    }
  }
}

// Załaduj sekcje z produktami
loadProducts("promotions", 4); // dla strony głównej
loadProducts("new-products", 5); // dla strony głównej
loadProducts("promoted-products", 4); // dla strony głównej

loadProducts("promoted-product-page", 46); // dla strony promocji
loadProducts("new-products-page", 27); // dla strony nowości

loadProducts("products", 27); // dla strony wszystkich produktów
