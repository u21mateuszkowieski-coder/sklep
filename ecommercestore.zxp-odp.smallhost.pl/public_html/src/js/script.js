// cart.js 
document.addEventListener("DOMContentLoaded", () => {
    document.body.addEventListener("click", function(e) {
        const btn = e.target.closest(".btn-add-cart");
        if (!btn) return;


        const item = {
            id: btn.dataset.id || null,
            name: btn.dataset.name || btn.closest('.product-card')?.querySelector('h3')?.textContent || "Produkt",
            price: parseFloat(btn.dataset.price || 0),
            image: btn.dataset.image || "default-product.jpg",
            quantity: 1
        };

        if (!item.id) {
            alert("Błąd: produkt nie ma ID");
            return;
        }

        if (document.body.dataset.logged === "true") {
            fetch("/src/api/cart_api.php?action=add", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(item)
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === "success") {
                    alert("Dodano do koszyka: " + item.name);
                    location.reload(); 
                } else {
                    alert("Błąd: " + (data.message || "nie udało się dodać"));
                }
            })
            .catch(err => {
                console.error(err);
                alert("Błąd połączenia z serwerem");
            });
        } else {
            // gość – localStorage
            let cart = JSON.parse(localStorage.getItem("cart") || "[]");
            const exists = cart.find(x => x.id === item.id);
            if (exists) exists.quantity++;
            else cart.push(item);
            localStorage.setItem("cart", JSON.stringify(cart));
            alert("Dodano: " + item.name+ (exists ? " (zwiększono ilość)" : ""));
        }
    });
});
