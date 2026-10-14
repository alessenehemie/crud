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
$erreurs = [];
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $userpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("SELECT * FROM user WHERE iduser = :id");
    $stmt->execute([':id' => $id]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$u) {
        die("Utilisateur introuvable. <a href='read.php'>Retour à la liste</a>");
    }
} catch (PDOException $e) {
    die("Erreur : " . htmlspecialchars($e->getMessage()));
}?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Détails de l'utilisateur</title>
</head>
<body>
    <div style="background: rgb(240, 246, 247); padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); width: 100%; max-width: 400px;">
        <h2>Détails de l'utilisateur</h2>     
        <p><strong>ID :</strong> <?= $u['iduser'] ?></p>
        <p><strong>Nom :</strong> <?= htmlspecialchars($u['nomuser']) ?></p>
        <p><strong>Prénom :</strong> <?= htmlspecialchars($u['prenomuser']) ?></p>
        <p><strong>Âge :</strong> <?= (int) $u['ageuser'] ?> ans</p>
        <p><strong>Email :</strong> <?= htmlspecialchars($u['emailuser']) ?></p>
        <p><strong>Numéro :</strong> <?= htmlspecialchars($u['numerouser']) ?></p>

        <p style="margin-top: 30px;">
            <a href="read.php"> @Retour</a>
        </p>
    </div>
</body>
</html>