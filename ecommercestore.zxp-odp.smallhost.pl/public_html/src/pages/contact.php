<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakt - EcommerceStore</title>
    <link rel="stylesheet" href="/src/assets/styles/contact.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    
    <?php require('../components/header.php'); ?>

    <section class="contact">
        <div class="contact-container">
            <h2>Skontaktuj się z nami</h2>
            <p>Masz pytania dotyczące zamówienia lub produktów? Jesteśmy tu, aby pomóc!</p>

            <div class="contact-info">
                <div class="contact-item">
                    <h3>Dane firmy</h3>
                    <p><strong>EcommerceStore Sp. z o.o.</strong></p>
                    <p>ul. Kwiatowa 12, 00-123 Warszawa</p>
                    <p>NIP: 123-456-78-90</p>
                    <p>KRS: 0000123456</p>
                </div>

                <div class="contact-item">
                    <h3>Kontakt</h3>
                    <p>E-mail: <a href="mailto:kontakt@ecommercestore.pl">kontakt@ecommercestore.pl</a></p>
                    <p>Telefon: <a href="tel:+48600123456">+48 600 123 456</a></p>
                    <p>Biuro obsługi: pon.–pt. 9:00–17:00</p>
                </div>
            </div>
        </div>
    </section>

    <?php require('../components/footer.php'); ?>

    <script src="../js/script.js"></script>
</body>
</html>
