<?php
session_start();
include 'db_connect.php';
define('UPLPATH', 'images/');


$admin = false;

// Check if the user is logged in and is an admin
if (isset($_SESSION['razina']) && $_SESSION['razina'] == 1) {
    $admin = true;
}

if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM vijesti WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}

if (isset($_POST['update'])) {
    $naslov = $_POST['naslov'];
    $sadrzaj = $_POST['sadrzaj'];
    $tekst = $_POST['tekst'];
    $kategorija = $_POST['kategorija'];
    $arhiva = isset($_POST['arhiva']) ? 1 : 0;
    $id = $_POST['id'];

    if (!empty($_FILES['slika']['name'])) {
        $slika = $_FILES['slika']['name'];
        $target_dir = "images/";
        $target_file = $target_dir . basename($slika);
        move_uploaded_file($_FILES['slika']['tmp_name'], $target_file);

        $query = "UPDATE vijesti SET naslov=?, sadrzaj=?, tekst=?, slika=?, kategorija=?, arhiva=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssssssi', $naslov, $sadrzaj, $tekst, $slika, $kategorija, $arhiva, $id);
    } else {
        $query = "UPDATE vijesti SET naslov=?, sadrzaj=?, tekst=?, kategorija=?, arhiva=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssssi', $naslov, $sadrzaj, $tekst, $kategorija, $arhiva, $id);
    }

    $stmt->execute();
    $stmt->close();
}?>
<!DOCTYPE html>
<html lang="hr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="F1 news page as a uni project">
    <meta name="keywords" content="Formula1, F1, Max Verstappen, Lewis Hamilton">
    <meta name="author" content="Toma Milićević">
    <link rel="shortcut icon" href="images/f1_favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <title>Administracija</title>
</head>

<body>
    
    <header>
        <img src="images/F1.svg.png" alt="">
        <h3>Welcome <?php echo isset($_SESSION['korisnicko_ime']) ? $_SESSION['korisnicko_ime'] : 'Guest'; ?></h3>
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
        <div class="form-search">
                <form action="" method="POST">
                    <label for="kategorija">Kategorija: </label>
                    <select class="kategorija" name="kategorija">
                        <option value="" disabled selected>Odabir kategorije</option>
                        <option value="f1">Formula 1</option>
                        <option value="f2">Formula 2</option>
                        <option value="f3">Formula 3</option>
                    </select>
                  <input type="submit" id="search" value="Search" />
             </form>
        </div>
        <div class="container">
                <?php $query = "SELECT * FROM vijesti";
                if (isset($_POST['kategorija'])) {
                    $kat = $_POST['kategorija'];
                    $query .= " WHERE kategorija = '$kat'";
                }
                $rezultat = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_array($rezultat)) : ?>
                    <div class="sub-container">
                        <form enctype="multipart/form-data" action="" method="POST">
                            <div class="form-item">
                                <label for="naslov">Naslov vijesti:</label>
                                <div class="form-field">
                                    <input type="text" name="naslov" class="form-field-textual" value="<?= $row['naslov']; ?>">
                                </div>
                            </div>
                            <div class="form-item">
                                <label for="sadrzaj">Kratki sadržaj vijesti (do 50 znakova):</label>
                                <div class="form-field">
                                    <textarea name="sadrzaj" cols="60" rows="5" class="form-field-textual"><?= $row['sadrzaj']; ?></textarea>
                                </div>
                            </div>
                            <div class="form-item">
                                <label for="tekst">Sadržaj vijesti:</label>
                                <div class="form-field">
                                    <textarea name="tekst" cols="60" rows="10" class="form-field-textual"><?= $row['tekst']; ?></textarea>
                                </div>
                            </div>
                            <div class="form-item">
                                <label for="slika">Slika:</label>
                                <div class="form-field">
                                    <input type="file" id="slika" name="slika" />
                                    <br>
                                    <img class="img_form" src="<?= UPLPATH . $row['slika']; ?>" width="200">
                                </div>
                            </div>
                            <div class="form-item">
                                <label for="kategorija">Kategorija vijesti:</label>
                                <div class="form-field">
                                    <select name="kategorija" class="form-field-textual kategorija">
                                        <option value="<?= $row['kategorija']; ?>"><?= ucfirst($row['kategorija']); ?></option>
                                        <option value="f1">Formula 1</option>
                                        <option value="f2">Formula 2</option>
                                        <option value="f3">Formula 3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-item" id="last_label">
                                <label>Spremiti u arhivu:
                                    <div class="form-field">
                                        <input type="checkbox" name="arhiva" <?= $row['arhiva'] ? 'checked' : ''; ?>> Arhiviraj?
                                    </div>
                                </label>
                            </div>
                            <div class="form-item">
                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                <button class="btn" type="reset" value="Poništi">Reset</button>
                                <button class="btn" type="submit" name="update" value="Prihvati">Izmjeni</button>
                                <button class="btn" type="submit" name="delete" value="Izbriši">Izbriši</button>
                            </div>
                        </form>
                    </div>
                    <script type="text/javascript">
                        document.getElementById('login').onclick = function(event) {
                            var slanje_forme = true;
                            var poljeUsername = document.getElementById("korisnicko_ime");
                            var username = document.getElementById("korisnicko_ime").value;
                            if (username.length == 0) {
                                slanje_forme = false;
                                poljeUsername.style.border = "1px dashed red";
                                document.getElementById("porukaKor").innerHTML = "<br>Unesite korisničko ime!<br><br>";
                            } else {
                                poljeUsername.style.border = "1px solid green";
                                document.getElementById("porukaKor").innerHTML = "";
                            }

                            var poljePass = document.getElementById("lozinka");
                            var pass = document.getElementById("lozinka").value;
                            if (pass.length == 0) {
                                slanje_forme = false;
                                poljePass.style.border = "1px dashed red";
                                document.getElementById("porukaPass").innerHTML = "<br>Unesite lozinku!<br><br>";
                            } else {
                                poljePass.style.border = "1px solid green";
                                document.getElementById("porukaPass").innerHTML = "";
                            }

                            if (!slanje_forme) {
                                event.preventDefault();
                            }
                        };
                    </script>
                <?php endwhile; ?>       
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Formula 1 News | Autor: Toma Milićević | Kontakt: tmilicev1@tvz.hr</p>
    </footer>
   
</body>
</html>