<?php
require_once("head.php");
require_once("database-connection.php");

// Récupérer l'ID du Pokémon depuis l'URL
$idPokemon = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupérer les détails du Pokémon
$query = "SELECT p.*, t1.nomType AS typePrincipal, t2.nomType AS typeSecondaire 
          FROM pokemon p
          JOIN type_pokemon t1 ON p.idType1 = t1.idType
          LEFT JOIN type_pokemon t2 ON p.idType2 = t2.idType
          WHERE p.idPokemon = ?";
$stmt = $databaseConnection->prepare($query);
$stmt->bind_param("i", $idPokemon);
$stmt->execute();
$result = $stmt->get_result();
$pokemon = $result->fetch_assoc();

if (!$pokemon) {
    echo "<p>Pokémon non trouvé.</p>";
    require_once("footer.php");
    exit();
}

// Récupérer les évolutions et ancêtres
$queryEvolutions = "SELECT p.idPokemon, p.nomPokemon FROM pokemon p WHERE p.idPokemon = ?";
$stmt = $databaseConnection->prepare($queryEvolutions);
$stmt->bind_param("i", $pokemon['idEvolution']);
$stmt->execute();
$result = $stmt->get_result();
$evolution = $result->fetch_assoc();

$queryAncetre = "SELECT p.idPokemon, p.nomPokemon FROM pokemon p WHERE p.idPokemon = ?";
$stmt = $databaseConnection->prepare($queryAncetre);
$stmt->bind_param("i", $pokemon['idAncetre']);
$stmt->execute();
$result = $stmt->get_result();
$ancetre = $result->fetch_assoc();
?>

<div class="container mt-4">
    <h1><?php echo htmlspecialchars($pokemon['nomPokemon']); ?></h1>
    <img src="<?php echo htmlspecialchars($pokemon['urlPhoto']); ?>" alt="<?php echo htmlspecialchars($pokemon['nomPokemon']); ?>" style="width: 150px;">
    <p><strong>Type principal :</strong> <?php echo htmlspecialchars($pokemon['typePrincipal']); ?></p>
    <p><strong>Type secondaire :</strong> <?php echo htmlspecialchars($pokemon['typeSecondaire'] ?? 'Aucun'); ?></p>
    <h3>Statistiques</h3>
    <ul>
        <li><strong>HP :</strong> <?php echo htmlspecialchars($pokemon['PV']); ?></li>
        <li><strong>Attaque :</strong> <?php echo htmlspecialchars($pokemon['PtsAttaque']); ?></li>
        <li><strong>Défense :</strong> <?php echo htmlspecialchars($pokemon['PtsDefense']); ?></li>
        <li><strong>Vitesse :</strong> <?php echo htmlspecialchars($pokemon['PtsVitesse']); ?></li>
    </ul>
</div>

<?php require_once("footer.php"); ?>
