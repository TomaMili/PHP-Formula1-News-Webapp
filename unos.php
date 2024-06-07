<?php
session_start(); // Start or resume session
include 'db_connect.php';
define('UPLPATH', 'images/');

$admin = false;

// Check if the user is logged in and is an admin
if (isset($_SESSION['razina']) && $_SESSION['razina'] == 1) {
    $admin = true;
}

?>
<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/unos.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap"
        rel="stylesheet">

    <title>Unos Vijesti - Formula 1 News</title>
</head>

<body>
    <header>
        <img src="images/F1.svg.png" alt="">
        <h3>Welcome
            <?php echo isset($_SESSION['korisnicko_ime']) ? $_SESSION['korisnicko_ime'] : 'Guest'; ?>
            </h2>
            <nav>
                <ul>
                    <li><a href="index.php">HOME</a></li>
                    <li><a href="unos.php">UNOS VIJESTI</a></li>
                    <?php if (isset($_SESSION['razina']) && $_SESSION['razina'] == 1) : ?>
                    <li><a href="admin.php">ADMIN</a></li>
                    <?php endif; ?>
                    <li><a href="registracija.php">REGISTRACIJA</a></li>
                    <li><a href="prijava.php">PRIJAVA</a></li>
                </ul>
            </nav>
    </header>
    <main>
        <?php if (!$admin) : ?>
            <p class="err">Access to this page is restricted. Only administrators have permission to access this page.</p>
        <?php else : ?>
        <h2>Unos nove vijesti</h2>
        <form id="newsForm" action="skripta.php" method="POST" enctype="multipart/form-data">
            <label for="naslov">Naslov Vijesti:</label>
            <input type="text" id="naslov" name="naslov" required>
            <span id="naslovError" class="error"></span>
            <br>
            <label for="sadrzaj">Kratki Sadržaj:</label>
            <textarea id="sadrzaj" name="sadrzaj" required></textarea>
            <span id="sadrzajError" class="error"></span>
            <br>
            <label for="tekst">Tekst Vijesti:</label>
            <textarea id="tekst" name="tekst" required></textarea>
            <span id="tekstError" class="error"></span>
            <br>
            <label for="slika">URL Slike:</label>
            <input type="file" id="slika" name="slika" accept="image/*" required>
            <span id="slikaError" class="error"></span>
            <br>
            <label for="kategorija">Kategorija:</label>
            <select id="kategorija" name="kategorija" required>
                <option value="">Odaberi Kategoriju</option>
                <option value="f1">Formula 1</option>
                <option value="f2">Formula 2</option>
                <option value="f3">Formula 3</option>
            </select>
            <span id="kategorijaError" class="error"></span>
            <br>
            <label for="arhiva">Spremiti u arhivu:&nbsp;
            <input type="checkbox" id="arhiva" name="arhiva"></label>
            <br>
            <input type="submit" value="Unesi">
        </form>
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; 2024 Formula 1 News | Autor: Toma Milićević | Kontakt: tmilicev1@tvz.hr</p>
    </footer>
    <script>
        document.getElementById('newsForm').onsubmit = function (event) {
            var valid = true;

            // Naslov
            var naslov = document.getElementById('naslov').value;
            if (naslov.length < 5 || naslov.length > 100) {
                valid = false;
                document.getElementById('naslovError').textContent = "Naslov mora imati između 5 i 100 znakova.";
                document.getElementById('naslov').style.border = "1px dashed red";
            } else {
                document.getElementById('naslovError').textContent = "";
                document.getElementById('naslov').style.border = "1px solid green";
            }

            // Kratki sadržaj
            var sadrzaj = document.getElementById('sadrzaj').value;
            if (sadrzaj.length < 10 || sadrzaj.length > 200) {
                valid = false;
                document.getElementById('sadrzajError').textContent = "Kratki sadržaj mora imati između 10 i 200 znakova.";
                document.getElementById('sadrzaj').style.border = "1px dashed red";
            } else {
                document.getElementById('sadrzajError').textContent = "";
                document.getElementById('sadrzaj').style.border = "1px solid green";
            }

            // Tekst vijesti
            var tekst = document.getElementById('tekst').value;
            if (tekst.length == 0) {
                valid = false;
                document.getElementById('tekstError').textContent = "Tekst vijesti ne smije biti prazan.";
                document.getElementById('tekst').style.border = "1px dashed red";
            } else {
                document.getElementById('tekstError').textContent = "";
                document.getElementById('tekst').style.border = "1px solid green";
            }

            // Slika
            var slika = document.getElementById('slika').value;
            if (slika.length == 0) {
                valid = false;
                document.getElementById('slikaError').textContent = "URL slike mora biti unesen.";
                document.getElementById('slika').style.border = "1px dashed red";
            } else {
                document.getElementById('slikaError').textContent = "";
                document.getElementById('slika').style.border = "1px solid green";
            }

            // Kategorija
            var kategorija = document.getElementById('kategorija').value;
            if (kategorija == "") {
                valid = false;
                document.getElementById('kategorijaError').textContent = "Kategorija mora biti odabrana.";
                document.getElementById('kategorija').style.border = "1px dashed red";
            } else {
                document.getElementById('kategorijaError').textContent = "";
                document.getElementById('kategorija').style.border = "1px solid green";
            }

            if (!valid) {
                event.preventDefault();
            }
        };
    </script>
</body>

</html>