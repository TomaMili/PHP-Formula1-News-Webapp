<?php
header('Content-Type: text/html; charset=utf-8');

$servername = "localhost";
$username = "root";
$password = "";
$nazivbaze = "formula_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $nazivbaze);

// Check connection
if (!$conn) {
    die('Pogreška pri povezivanju na MySQL server: ' . mysqli_connect_error());
}
