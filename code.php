<?php
if (!isset($_POST["enregistrer"])) {
    header('Location: index.html');
    exit;
}
$nom     = trim($_POST["nom"] ?? '');
$prenom  = trim($_POST["prenom"] ?? '');
$age     = (int) ($_POST["age"] ?? 0);
$email   = trim($_POST["email"] ?? '');
$numero  = trim($_POST["numero"] ?? '');
$erreurs = [];
if ($nom === '' || $prenom === '')                $erreurs[] = "Le nom et le prénom sont obligatoires.";
if ($age < 0 || $age > 120)                       $erreurs[] = "L'âge n'est pas valide.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL))   $erreurs[] = "L'email n'est pas valide.";
if (!preg_match('/^[0-9+ ]{8,15}$/', $numero))    $erreurs[] = "Le numéro n'est pas valide.";
if ($erreurs) {
    echo "<h2>Erreurs :</h2>";
    foreach ($erreurs as $e) {
        echo "<p>" . htmlspecialchars($e) . "</p>";
    }
    echo '<a href="index.html">← Retour au formulaire</a>';
    exit;
}
$host = 'localhost';
$dbname = 'application';
$username = 'root';
$userpassword = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $userpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "INSERT INTO user (nomuser, prenomuser, ageuser, emailuser, numerouser)
            VALUES (:nomuser, :prenomuser, :ageuser, :emailuser, :numerouser)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nomuser'    => $nom,
        ':prenomuser' => $prenom,
        ':ageuser'    => $age,
        ':emailuser'  => $email,
        ':numerouser' => $numero
    ]);
    header('Location: read.php');
    
    exit;
} catch (PDOException $e) {
    echo "Erreur : " . htmlspecialchars($e->getMessage());
}