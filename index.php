<?php
session_start(); // Start or resume session
include 'db_connect.php';
define('UPLPATH', 'images/');

?>
<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <title>Formula 1 News</title>
</head>

<body>
    <header>
        <img src="images/F1.svg.png" alt="">
        <h3>Welcome <?php echo isset($_SESSION['korisnicko_ime']) ? $_SESSION['korisnicko_ime'] : 'Guest'; ?></h2>
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
        <section class="news">
            <div class="container">
                <section id="top">
                    <h1>FORMULA 1</h1>
                    
                    <?php
                    $query = "SELECT * FROM vijesti WHERE arhiva=1 AND kategorija='f1' LIMIT 9";
                    $rezultat = mysqli_query($conn, $query);
                    $i = 0;
                    while ($red = mysqli_fetch_array($rezultat)) {
                        echo '<a href="news.php?id=' . $red['id'] . '">';
                        echo '<article class="col">';
                        echo '<img src="' . UPLPATH . $red['slika'] . '">';
                        echo '<h2>';
                        echo $red['naslov'];
                        echo '</h2></article>';
                        echo '</a>';
                    }
                    ?>
                    <div class="clear"></div>
                </section>
            </div>
            <div class="container">
                <section id="mid">
                    <h1>FORMULA 2</h1>
                    <?php
                    $query = "SELECT * FROM vijesti WHERE arhiva=1 AND kategorija='f2' LIMIT 9";
                    $rezultat = mysqli_query($conn, $query);
                    $i = 0;
                    while ($red = mysqli_fetch_array($rezultat)) {
                        echo '<a href="news.php?id=' . $red['id'] . '">';
                        echo '<article class="col">';
                        echo '<img src="' . UPLPATH . $red['slika'] . '">';
                        echo '<h2>';
                        echo $red['naslov'];
                        echo '</h2></article>';
                        echo '</a>';
                    }
                    ?>
                    <div class="clear"></div>
                </section>
            </div>
            <div class="container">
                <section id="bot">
                    <h1>FORMULA 3</h1>
                    <?php
                    $query = "SELECT * FROM vijesti WHERE arhiva=1 AND kategorija='f3' LIMIT 9";
                    $rezultat = mysqli_query($conn, $query);
                    $i = 0;
                    while ($red = mysqli_fetch_array($rezultat)) {
                        echo '<a href="news.php?id=' . $red['id'] . '">';
                        echo '<article class="col">';
                        echo '<img src="' . UPLPATH . $red['slika'] . '">';
                        echo '<h2>';
                        echo $red['naslov'];
                        echo '</h2></article>';
                        echo '</a>';
                    }
                    ?>
                    <div class="clear"></div>
                </section>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Formula 1 News | Autor: Toma Milićević | Kontakt: tmilicev1@tvz.hr</p>
    </footer>
</body>

</html>

<?php
mysqli_close($conn);
?>