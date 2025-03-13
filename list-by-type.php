<?php
require_once("head.php");
require_once("database-connection.php");

$queryTypes = "SELECT idType, nomType FROM type_pokemon ORDER BY nomType";
$resultTypes = mysqli_query($databaseConnection, $queryTypes);

if (!$resultTypes) {
    die("Erreur SQL : " . mysqli_error($databaseConnection));
}

echo "<h1>Liste des Pokémon classés par type</h1>";

while ($type = mysqli_fetch_assoc($resultTypes)) {
    $typeId = $type['idType'];
    $typeName = htmlspecialchars($type['nomType']);

    $queryPokemons = "SELECT idPokemon, nomPokemon, urlPhoto FROM pokemon 
                      WHERE idType1 = $typeId OR idType2 = $typeId
                      ORDER BY nomPokemon";
    $resultPokemons = mysqli_query($databaseConnection, $queryPokemons);

    if (!$resultPokemons) {
        die("Erreur SQL : " . mysqli_error($databaseConnection));
    }

    if (mysqli_num_rows($resultPokemons) > 0) {
        echo "<h2>Type : $typeName</h2>";
        echo "<table>";
        echo "<tr>
                <th>Numéro</th>
                <th>Image</th>
                <th>Nom</th>
              </tr>";

              while ($pokemon = mysqli_fetch_assoc($resultPokemons)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($pokemon['idPokemon']) . "</td>";
                echo "<td><img src='" . htmlspecialchars($pokemon['urlPhoto']) . "' alt='" . htmlspecialchars($pokemon['nomPokemon']) . "' width='80'></td>";
                echo "<td>" . htmlspecialchars($pokemon['nomPokemon']) . "</td>";
                echo "<td>
                        <form method='GET' action='details.php' style='display:inline;'>
                            <input type='hidden' name='id' value='" . htmlspecialchars($pokemon['idPokemon']) . "'>
                            <button type='submit' class='btn btn-primary btn-sm'>Voir détails</button>
                        </form>
                      </td>";
                echo "</tr>";
            
            
        }

        echo "</table>";
    } else {
        echo "<p>Aucun Pokémon trouvé pour le type $typeName.</p>";
    }
}