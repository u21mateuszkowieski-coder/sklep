<?php
session_start();

if (!isset($_SESSION['logged']) ||
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['username']) ||
    !empty($_SESSION['is_admin'])) {
    
    header("Location: /public/index.php");
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/api/db.php';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel użytkownika</title>
    <link rel="stylesheet" href="../client-logged-assets/client-styles/client-dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(8px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background: linear-gradient(135deg, #001d3d, #003566);
            padding: 40px 35px;
            border-radius: 24px;
            width: 90%;
            max-width: 520px;
            border: 1px solid rgba(255, 215, 10, 0.3);
            box-shadow: 0 15px 40px rgba(255, 215, 10, 0.25);
            transform: scale(0.85);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            color: #fff;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .modal-header h3 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #ffd60a;
            margin-bottom: 8px;
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            background: none;
            border: none;
            font-size: 2rem;
            color: #ffd60a;
            cursor: pointer;
            transition: 0.3s;
        }

        .close-modal:hover {
            transform: rotate(90deg);
            color: #fff;
        }

        .modal-actions {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .modal-btn {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 20px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 215, 10, 0.3);
            border-radius: 16px;
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .modal-btn:hover {
            background: rgba(255, 215, 10, 0.15);
            border-color: #ffd60a;
            transform: translateX(8px);
        }

        .modal-btn span {
            font-size: 1.8rem;
        }
    </style>
</head>
<body data-logged="true" data-user-id="<?= $_SESSION['user_id'] ?>">

    <?php require('../client-components/client-header.php'); ?>

    <section class="user-section">
        <h2>Witaj ponownie</h2>

        <div class="user-grid">
            <!-- KAFLIK ZAMÓWIENIA -->
            <div class="user-card" data-modal="orders">
                <h3>📦 Twoje zamówienia</h3>
                <p>Sprawdź status i historię zakupów</p>
            </div>

            <!-- KAFLIK PROFIL -->
            <div class="user-card" data-modal="profile">
                <h3>👤 Profil</h3>
                <p>Zobacz i edytuj dane konta</p>
            </div>

            <!-- KAFLIK USTAWIENIA -->
            <div class="user-card" data-modal="settings">
                <h3>⚙️ Ustawienia konta</h3>
                <p>Zarządzaj bezpieczeństwem i preferencjami</p>
            </div>
        </div>
    </section>

    <!-- MODAL OVERLAY -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-content">
            <button class="close-modal">&times;</button>
            <div class="modal-header">
                <h3 id="modalTitle">Tytuł</h3>
            </div>
            <div class="modal-actions" id="modalActions">
            </div>
        </div>
    </div>

    <script>
        const cards = document.querySelectorAll('.user-card');
        const overlay = document.getElementById('modalOverlay');
        const title = document.getElementById('modalTitle');
        const actions = document.getElementById('modalActions');
        const closeBtn = document.querySelector('.close-modal');

        const modals = {
            orders: {
                title: "📦 Twoje zamówienia",
                items: [
                   
                ]
            },
            profile: {
                title: "👤 Twój profil",
                items: [

                  
                ]
            },
            settings: {
                title: "⚙️ Ustawienia konta",
                items: [
                  
                ]
            }
        };

        cards.forEach(card => {
            card.addEventListener('click', () => {
                const modalKey = card.getAttribute('data-modal');
                const data = modals[modalKey];

                title.textContent = data.title;

                actions.innerHTML = '';
                data.items.forEach(item => {
                    const btn = document.createElement('a');
                    btn.href = item.link;
                    btn.className = 'modal-btn';
                    btn.innerHTML = `<span>${item.icon}</span> ${item.text}`;
                    actions.appendChild(btn);
                });

                overlay.classList.add('active');
            });
        });

        closeBtn.addEventListener('click', () => {
            overlay.classList.remove('active');
        });

        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.classList.remove('active');
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && overlay.classList.contains('active')) {
                overlay.classList.remove('active');
            }
        });
    </script>
<script src="/src/js/cart.js"></script></body>
</html>
