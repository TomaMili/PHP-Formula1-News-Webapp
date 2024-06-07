<?php
session_start();
include 'db_connect.php';

// Provjera da li su podaci iz forme poslani
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $naslov = $_POST['naslov'];
    $sadrzaj = $_POST['sadrzaj'];
    $tekst = $_POST['tekst'];
    $kategorija = $_POST['kategorija'];
    $arhiva = isset($_POST['arhiva']) ? 1 : 0;

    // Upload slike
    $slika = $_FILES['slika']['name'];
    $target_dir = "images/";
    $target_file = $target_dir . basename($slika);

    if (move_uploaded_file($_FILES['slika']['tmp_name'], $target_file)) {
        // Priprema SQL upita
        $sql = "INSERT INTO vijesti (naslov, sadrzaj, tekst, slika, kategorija, arhiva) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $naslov, $sadrzaj, $tekst, $slika, $kategorija, $arhiva);

        // Izvršenje upita
        if ($stmt->execute()) {
            $message = "Vijest je uspješno unesena.";
        } else {
            $message = "Došlo je do pogreške: " . $stmt->error;
        }

        // Zatvaranje pripremljene izjave
        $stmt->close();
    } else {
        $message = "Došlo je do pogreške pri uploadu slike.";
    }
}

// Zatvaranje konekcije
$conn->close();
?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/skripta.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <title>Unos Vijesti - Formula 1 News</title>
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
        <h2>Rezultati Unosa</h2>
        <div class="dababy">
            <?php if (isset($message)) : ?>
                <p  class="paragraph"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <p>Naslov: <?php echo htmlspecialchars($naslov); ?></p>
            <p>Kratki Sadržaj: <?php echo htmlspecialchars($sadrzaj); ?></p>
            <p>Tekst Vijesti: <?php echo htmlspecialchars($tekst); ?></p>
            <p>Kategorija: <?php echo htmlspecialchars($kategorija); ?></p>
            <p>Arhiva: <?php echo $arhiva ? 'Da' : 'Ne'; ?></p>
            <p>Slika:</p>
            <img src="images/<?php echo htmlspecialchars($slika); ?>" alt="Slika Vijesti">
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Formula 1 News | Autor: Toma Milićević | Kontakt: tmilicev1@tvz.hr</p>
    </footer>
</body>

</html>