<?php
$host = 'localhost';
$dbname = 'application';
$username = 'root';
$userpassword = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $userpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM user ORDER BY iduser ASC";
     $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?> 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste des utilisateurs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('message.php'); ?>
    <h2>ENREGISTREMENTS</h2>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Âge</th>
                    <th>Email</th>
                    <th>Numéro</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= $u['iduser'] ?></td>
                            <td><?= htmlspecialchars($u['nomuser']) ?></td>
                            <td><?= htmlspecialchars($u['prenomuser']) ?></td>
                            <td><?= (int) $u['ageuser'] ?></td>
                            <td><?= htmlspecialchars($u['emailuser']) ?></td>
                            <td><?= htmlspecialchars($u['numerouser']) ?></td>
                             <td>
                                <a class="btn btn-voir" href="detail.php?id=<?= $u['iduser'] ?>">voir</a>
                                <a class="btn btn-modifier" href="update.php?id=<?= $u['iduser'] ?>">Modifier</a>
                                <a class="btn btn-supprimer" href="delete.php?id=<?= $u['iduser'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="vide">Aucun utilisateur enregistré pour le moment</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <a class="btn btn-ajouter" href="index.html">Ajouter un nouvel utilisateur</a>
</body>
</html>