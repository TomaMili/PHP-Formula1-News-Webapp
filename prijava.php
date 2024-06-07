<?php
session_start(); // Start or resume session

include 'db_connect.php';

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $korisnicko_ime = $_POST['korisnicko_ime'];
    $lozinka = $_POST['lozinka'];

    // Check username and password
    $sql = "SELECT id, lozinka, razina FROM korisnici WHERE korisnicko_ime = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $korisnicko_ime);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password, $razina);
        $stmt->fetch();

        if (password_verify($lozinka, $hashed_password)) {
            $_SESSION['korisnicko_ime'] = $korisnicko_ime;
            $_SESSION['razina'] = $razina;
            header("Location: index.php"); // Redirect to home page
            exit(); // Stop further execution
        } else {
            $_SESSION['wrong_username'] = "Pogrešno korisničko ime ili lozinka."; // Set the error message
            header("Location: prijava.php"); // Redirect back to the login page
            exit(); // Stop further execution
        }
    } else {
        $_SESSION['wrong_username'] = "Pogrešno korisničko ime ili lozinka."; // Set the error message
        header("Location: prijava.php"); // Redirect back to the login page
        exit(); // Stop further execution
    }

    // Close prepared statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap"
        rel="stylesheet">
    <title>Prijava - Formula 1 News</title>
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
        <h2>Prijava</h2>
        <form action="prijava.php" method="POST">
            <label class="label_flex" for="korisnicko_ime">Korisničko ime:  <?php
            if (isset($_SESSION['wrong_username'])) {
                echo "<p class='error'>" . $_SESSION['wrong_username'] . "</p>";
                unset($_SESSION['wrong_username']); // Remove the session variable after displaying the error
            }
            ?></label>
            <input type="text" id="korisnicko_ime" name="korisnicko_ime" required>
            
            <br>
            <label for="lozinka">Lozinka:</label>
            <input type="password" id="lozinka" name="lozinka" required>
            <br>
            <input type="submit" value="Prijava" class="btn">
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Formula 1 News | Autor: Toma Milićević | Kontakt: tmilicev1@tvz.hr</p>
    </footer>
</body>

</html>