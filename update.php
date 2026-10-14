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
        die('Utilisateur introuvable. <a href="read.php"> Retour à la liste</a>');
    }
    if (isset($_POST['modifier'])) {
        $nom    = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $age    = (int) ($_POST['age'] ?? 0);
        $email  = trim($_POST['email'] ?? '');
        $numero = trim($_POST['numero'] ?? '');
        $u['nomuser']     = $nom;
        $u['prenomuser']  = $prenom;
        $u['ageuser']     = $age;
        $u['emailuser']   = $email;
        $u['numerouser']  = $numero;
        if ($nom === '' || $prenom === '')               $erreurs[] = "Le nom et le prénom sont obligatoires.";
        if ($age < 0 || $age > 120)                      $erreurs[] = "L'âge n'est pas valide.";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))  $erreurs[] = "L'email n'est pas valide.";
        if (!preg_match('/^[0-9+ ]{8,15}$/', $numero))   $erreurs[] = "Le numéro n'est pas valide.";

        if (!$erreurs) {
            $sql = "UPDATE user
                    SET nomuser = :nom, prenomuser = :prenom, ageuser = :age,
                        emailuser = :email, numerouser = :numero
                    WHERE iduser = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nom'    => $nom,
                ':prenom' => $prenom,
                ':age'    => $age,
                ':email'  => $email,
                ':numero' => $numero,
                ':id'     => $id
            ]);

            header('Location: read.php');
            exit;
        }
    }
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        $erreurs[] = "Cet email est déjà utilisé par un autre utilisateur.";
    } else {
        die("Erreur : " . htmlspecialchars($e->getMessage()));
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="POST" action="update.php">
        <h2>Modifier l'utilisateur</h2>

        <?php foreach ($erreurs as $e): ?>
            <p style="color: red;"><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>

        <input type="hidden" name="id" value="<?= (int) $u['iduser'] ?>">

        <p>
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($u['nomuser']) ?>" required>
        </p>
        <p>
            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($u['prenomuser']) ?>" required>
        </p>
        <p>
            <label for="age">Âge</label>
            <input type="number" name="age" id="age" value="<?= (int) $u['ageuser'] ?>" required>
        </p>
        <p>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($u['emailuser']) ?>" required>
        </p>
        <p>
            <label for="numero">Numéro</label>
            <input type="tel" name="numero" id="numero" value="<?= htmlspecialchars($u['numerouser']) ?>" required>
        </p>
        <p>
            <button type="submit" name="modifier">Modifier</button>
        </p>
        <a href="read.php"> Annuler</a>
    </form>
</body>
</html>