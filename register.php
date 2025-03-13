
<?php
require_once("database-connection.php");

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Vérifier si le login est déjà pris
    $checkQuery = "SELECT * FROM user WHERE login = ?";
    $stmt = $databaseConnection->prepare($checkQuery);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "Ce login est déjà pris.";
    } else {
        // Insérer le nouvel utilisateur
        $query = "INSERT INTO user (nom, prenom, login, passwordHash) VALUES (?, ?, ?, ?)";
        $stmt = $databaseConnection->prepare($query);
        $stmt->bind_param("ssss", $nom, $prenom, $login, $passwordHash);
        if ($stmt->execute()) {
            $message = "Compte créé avec succès. Vous pouvez maintenant vous connecter.";
        } else {
            $message = "Erreur lors de la création du compte.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h2>Créer un compte</h2>
    <p><?php echo htmlspecialchars($message); ?></p>
    <form method="POST" action="register.php">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required><br>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required><br>

        <label for="login">Login :</label>
        <input type="text" id="login" name="login" required><br>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>
