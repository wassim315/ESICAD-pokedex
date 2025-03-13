<!-- 
    Ce fichier représente la page de liste de tous les pokémons.
-->
<?php
require_once("head.php");
require_once("database-connection.php");

// Requête pour récupérer tous les pokémons avec leur type principal et leur image
$query = "SELECT p.idPokemon, p.nomPokemon, t1.nomType as typePrincipal, p.urlPhoto 
          FROM pokemon p 
          JOIN type_pokemon t1 ON p.idType1 = t1.idType
          ORDER BY p.idPokemon ASC";

$resultat = $databaseConnection->query($query);

// Vérifier si des résultats ont été trouvés
if (!$resultat) {
    echo "Erreur: " . $databaseConnection->error;
} else {
?>
    <div class="container mt-4">
        <h1 class="mb-4">Liste des Pokémon</h1>
        <table class="table table-striped table-hover">
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
                        <td><?php echo htmlspecialchars($pokemon['idPokemon']); ?></td>
                        <td><?php echo htmlspecialchars($pokemon['nomPokemon']); ?></td>
                        <td><?php echo htmlspecialchars($pokemon['typePrincipal']); ?></td>
                        <td>
                            <?php if (!empty($pokemon['urlPhoto'])) { ?>
                                <img src="<?php echo htmlspecialchars($pokemon['urlPhoto']); ?>" alt="<?php echo htmlspecialchars($pokemon['nomPokemon']); ?>" style="width: 50px; height: auto;">
                            <?php } else { ?>
                                <span>Pas d'image</span>
                            <?php } ?>
                        </td>
                        <td>
    <a href="details.php?id=<?php echo urlencode($pokemon['idPokemon']); ?>" class="btn btn-primary btn-sm">Voir détails</a>
</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php
session_start(); // Démarre la session pour récupérer les infos de l'utilisateur connecté
?>

<?php if (isset($_SESSION['idUser'])): ?>
    <?php if (isset($pokemon['idPokemon'])): ?>
        <form method="POST" action="ajouter_equipe.php" style="display:inline;">
            <input type="hidden" name="idPokemon" value="<?php echo htmlspecialchars($pokemon['idPokemon']); ?>">
            <button type="submit" class="btn btn-success btn-sm">Ajouter à mon équipe</button>
        </form>
    <?php else: ?>
        <p>Erreur : Pokémon non trouvé.</p>
    <?php endif; ?>
<?php endif; ?>
<?php
}
require_once("footer.php");
?>
