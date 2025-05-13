<?php
$host = "localhost:3308";
$dbname = "blogspot";
$username = "root";
$password = "";

try
{
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch(PDOException $e)
{
    die("Σφάλμα: " . $e->getMessage());
}