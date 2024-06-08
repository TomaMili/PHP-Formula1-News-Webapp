<?php
session_start();
include 'db_connect.php';

$unique = true;
$registriranKorisnik = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reg'])) {
    if (
        !empty($_POST['korime']) &&
        !empty($_POST['ime']) &&
        !empty($_POST['prezime']) &&
        !empty($_POST['lozinka1']) &&
        !empty($_POST['lozinka2']) &&
        ($_POST['lozinka1'] == $_POST['lozinka2'])
    ) {
        $korime = $_POST['korime'];
        $ime = $_POST['ime'];
        $prezime = $_POST['prezime'];
        $lozinka = $_POST['lozinka1'];
        $hash_lozinka = password_hash($lozinka, PASSWORD_BCRYPT);
        $razina = 0;

        
        $sql = "SELECT id FROM korisnici WHERE korisnicko_ime = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $korime);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $unique = false;
        } else {
            session_start();
            $_SESSION['username'] = $korime;
            $_SESSION['level'] = $razina;

            
            $sql = "INSERT INTO korisnici (ime, prezime, korisnicko_ime, lozinka, razina) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssd", $ime, $prezime, $korime, $hash_lozinka, $razina);

            if ($stmt->execute()) {
                $registriranKorisnik = true;
            } else {
                echo "Došlo je do pogreške: " . $stmt->error;
            }
        }

        
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="F1 news page as a uni project">
    <meta name="keywords" content="Formula1, F1, Max Verstappen, Lewis Hamilton">
    <meta name="author" content="Toma Milićević">
    <link rel="shortcut icon" href="images/f1_favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/registracija.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <title>Registracija - Formula 1 News</title>
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
    <main class="cont-main">
        <div class="reg-container">
            <h1>Registracija</h1><br>
            <form enctype="multipart/form-data" method="POST" action="">
                <label for="korime">Korisničko ime: </label><br>
                <input type="text" id="korime" name="korime" required><br>
                <span id='PorukaUser' class='BojaPoruke'></span>
                <?php if (!$unique) {
                    echo "<br><span id='PorukaUser' class='BojaPoruke'>Korisničko ime se već koristi!</span>";
                } ?><br>
                <label for="ime">Ime: </label><br>
                <input type="text" id="ime" name="ime" required><br>
                <span id="porukaIme" class="BojaPoruke"></span><br>
                <label for="prezime">Prezime: </label><br>
                <input type="text" id="prezime" name="prezime" required><br>
                <span id="porukaPrezime" class="BojaPoruke"></span><br>
                <label for="lozinka1">Lozinka: </label><br>
                <input type="password" id="lozinka1" name="lozinka1" required><br>
                <span id="Porukaloz" class="BojaPoruke"></span><br>
                <label for="lozinka2">Ponovite lozinku: </label><br>
                <input type="password" id="lozinka2" name="lozinka2" required><br>
                <span id="Porukaloz2" class="BojaPoruke"></span><br>
                <button type="submit" class="reg_form" id="reg" name="reg">Registriraj</button>
            </form>
            
            <?php if ($registriranKorisnik) {
                echo '<p>Korisnik je uspješno registriran!</p>';
            } ?>

            <script type="text/javascript">
                document.getElementById('reg').onclick = function(event) {
                    var slanjeForme = true;

                    var poljeIme = document.getElementById("ime");
                    var ime = document.getElementById("ime").value;
                    if (ime.length == 0) {
                        slanjeForme = false;
                        poljeIme.style.border = "1px dashed red";
                        document.getElementById("porukaIme").innerHTML = "Unesite ime!<br>";
                    } else {
                        poljeIme.style.border = "1px solid green";
                        document.getElementById("porukaIme").innerHTML = "";
                    }

                    var poljePrezime = document.getElementById("prezime");
                    var prezime = document.getElementById("prezime").value;
                    if (prezime.length == 0) {
                        slanjeForme = false;
                        poljePrezime.style.border = "1px dashed red";
                        document.getElementById("porukaPrezime").innerHTML = "Unesite Prezime!<br>";
                    } else {
                        poljePrezime.style.border = "1px solid green";
                        document.getElementById("porukaPrezime").innerHTML = "";
                    }

                    var poljeUsername = document.getElementById("korime");
                    var username = document.getElementById("korime").value;
                    if (username.length == 0) {
                        slanjeForme = false;
                        poljeUsername.style.border = "1px dashed red";
                        document.getElementById("PorukaUser").innerHTML = "Unesite korisničko ime!<br>";
                    } else {
                        poljeUsername.style.border = "1px solid green";
                        document.getElementById("PorukaUser").innerHTML = "";
                    }

                    var poljePass = document.getElementById("lozinka1");
                    var pass = document.getElementById("lozinka1").value;
                    var poljePassRep = document.getElementById("lozinka2");
                    var passRep = document.getElementById("lozinka2").value;
                    if (pass.length == 0 || passRep.length == 0 || pass != passRep) {
                        slanjeForme = false;
                        poljePass.style.border = "1px dashed red";
                        poljePassRep.style.border = "1px dashed red";
                        document.getElementById("Porukaloz").innerHTML = "Lozinke nisu iste!<br>";
                        document.getElementById("Porukaloz2").innerHTML = "Lozinke nisu iste!<br>";
                    } else {
                        poljePass.style.border = "1px solid green";
                        poljePassRep.style.border = "1px solid green";
                        document.getElementById("Porukaloz").innerHTML = "";
                        document.getElementById("Porukaloz2").innerHTML = "";
                    }

                    if (!slanjeForme) {
                        event.preventDefault();
                    }
                };
            </script>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Formula 1 News | Autor: Toma Milićević | Kontakt: tmilicev1@tvz.hr</p>
    </footer>
</body>

</html>