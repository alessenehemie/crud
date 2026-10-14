<?php
    $id = $_POST['id'] ?? $_GET['id'] ?? '';
if (!ctype_digit((string) $id)) {
    header('Location: read.php');
    exit;
}      
$host = 'localhost';
$dbname = 'application';
$username = 'root';
$userpassword = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $userpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("DELETE FROM user WHERE iduser = :id");
    $stmt->execute([':id' => $id]);
    header('Location: read.php');
    exit;
} catch (PDOException $e) {
    die("Erreur lors de la suppression : " . htmlspecialchars($e->getMessage()));
}