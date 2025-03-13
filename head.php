<!-- 
    Ce fichier contient le début du document HTML et de sa balise <head>
    Il permet de charger le fichier de styles CSS, le JS le cas échéant... 
-->
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokedex Du Professeur Chen</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/svg" href="assets/pokeball.svg" />
</head>

<body>
    <header>
        <a href="index.php">
            <h1>Pokedex Du Professeur Chen</h1>
            <h1>       <?php
session_start();
?>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php"></a>
        <div>
            <?php if (!empty($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true): ?>
                <span class="text-white">
                    Connecté en tant que : 
                    <?php echo htmlspecialchars($_SESSION['prenom'] ?? '') . ' ' . htmlspecialchars($_SESSION['nom'] ?? ''); ?>
                </span>
                <a href="logout.php" class="btn btn-danger btn-sm ms-3">Se déconnecter</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary btn-sm">Se connecter</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
            </h1>
        </a>
        <form id="search-bar" action="search_pokemon.php">
            <span class="input-group">
                <input id="q" name="q" type="search" placeholder="Rechercher un pokémon"><button type="submit">🔎</button>
            </span>
        </form>
    </header>

    <div id="main-wrapper">


        <?php
        require_once("menu.php");
        ?>
        <main id="main">