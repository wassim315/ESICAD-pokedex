<?php
require_once("database-connection.php");
session_start(); // Assure-toi que la session démarre correctement

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);

    // Prépare la requête pour récupérer l'utilisateur
    $query = "SELECT * FROM user WHERE login = ?";
    $stmt = $databaseConnection->prepare($query);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Vérifie si le mot de passe est correct
    if ($user && password_verify($password, $user['passwordHash'])) {
        // Stocke les infos dans la session
        $_SESSION['idUser'] = $user['idUser'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['loggedIn'] = true; // ✅ Identifie l'utilisateur comme connecté

        // Redirige vers l'accueil
        header('Location: index.php');
        exit();
    } else {
        $error = "Login ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Connexion</h2>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div>
                <label for="login">Login :</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>
        <a href="register.php">Créer un compte</a>
    </div>
</body>
</html>


