<?php
$host = "localhost";
$dbname = "immanuel_library";
$user = "root";
$password = "";

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Memberi tahu informasi error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mengembalikan array as associative array
    PDO::ATTR_EMULATE_PREPARES => false, //Mengatur siapa yang eksekusi prepare
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    die("Koneksi ke database gagal: " . $e->getMessage());
}