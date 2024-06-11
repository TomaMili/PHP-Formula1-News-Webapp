<?php
session_start(); 
include 'db_connect.php';
define('UPLPATH', 'images/');

    $kategorija = mysqli_real_escape_string($conn, $_GET['kategorija']);
    $query = "SELECT * FROM vijesti WHERE kategorija = '$kategorija'";
    $rezultat = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/kategorija.css">
    <meta name="description" content="F1 news page as a uni project">
    <meta name="keywords" content="Formula1, F1, Max Verstappen, Lewis Hamilton">
    <meta name="author" content="Toma Milićević">
    <link rel="shortcut icon" href="images/f1_favicon.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <title>Kategorija - Formula 1 News</title>
</head>

<body>
    <header>
        <img src="images/F1.svg.png" alt="">
        <h3>Welcome <?php echo isset($_SESSION['korisnicko_ime']) ? $_SESSION['korisnicko_ime'] : 'Guest'; ?></h3>
        <div> <?php
            echo date('D, M jS, Y');?>
        </div>
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
                <section>
                    <h1>FORMULA <span><?php echo strtoupper(substr($kategorija, 1)); ?></span></h1>
                      <?php
                    while ($red = mysqli_fetch_assoc($rezultat)) {
                        echo '<a href="news.php?id=' . $red['id'] . '">';
                        echo '<article class="col">';
                        echo '<img src="' . UPLPATH . htmlspecialchars($red['slika']) . '" alt="' . htmlspecialchars($red['naslov']) . '">';
                        echo '<h2>' . htmlspecialchars($red['naslov']) . '</h2>';
                        echo '</article>';
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