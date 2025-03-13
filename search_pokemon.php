<!-- 
    Ce fichier représente la page de résultats de recherche de pokémons du site.
-->
<?php
require_once("head.php");
require_once("database-connection.php");

// Récupérer le terme de recherche
$searchTerm = isset($_GET['q']) ? '%' . $databaseConnection->real_escape_string($_GET['q']) . '%' : '%';

// Requête pour rechercher les pokémons par nom
$query = "SELECT p.idPokemon, p.nomPokemon, t1.nomType as typePrincipal, p.urlPhoto 
          FROM pokemon p 
          JOIN type_pokemon t1 ON p.idType1 = t1.idType
          WHERE p.nomPokemon LIKE ?
          ORDER BY p.nomPokemon ASC";

$stmt = $databaseConnection->prepare($query);

if ($stmt) {
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $resultat = $stmt->get_result();
} else {
    echo "Erreur dans la préparation de la requête: " . $databaseConnection->error;
    exit();
}
?>

<div class="container mt-4">
    <h1 class="mb-4">Résultats de la recherche</h1>

    <table class="table table-striped table-hover mt-4">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Type principal</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($pokemon = $resultat->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo ($pokemon['idPokemon']); ?></td>
                    <td><?php echo ($pokemon['nomPokemon']); ?></td>
                    <td><?php echo ($pokemon['typePrincipal']); ?></td>
                    <td>
                        <?php if (!empty($pokemon['urlPhoto'])) { ?>
                            <img src="<?php echo ($pokemon['urlPhoto']); ?>" alt="<?php echo ($pokemon['nomPokemon']); ?>" style="width: 50px; height: auto;">
                        <?php } else { ?>
                            <span>Pas d'image</span>
                        <?php } ?>
                    </td>
                    <td>
                <form method="GET" action="details.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($pokemon['idPokemon']); ?>">
                    <button type="submit" class="btn btn-primary btn-sm">Voir détails</button>
                </form>
            </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php
require_once("footer.php");
?>