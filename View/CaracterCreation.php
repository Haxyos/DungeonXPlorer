<!DOCTYPE html>
<html lang="en">

<?php
if (isset($_POST["characterName"]) && isset($_POST["class"]) && isset($_POST["descChar"])) {
    $nom = $_POST["characterName"];
    $class = $_POST["class"];
    $descriptif = $_POST["descChar"];

    echo "<h2>Personnage créé :</h2>";
    echo "<p>Nom : " . htmlspecialchars($nom) . "</p>";
    echo "<p>Classe : " . htmlspecialchars($class) . "</p>";
    echo "<p>Histoire : " . htmlspecialchars($descriptif) . "</p>";

    if ($class == "Warrior") {
        echo "warrior";
    } else if ($class == "Wizard") {
        echo "wizard";
    } else if ($class == "Stealer") {
        echo "stealer";
    }
}
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form method="POST">
        <label>Nom du personnage : </label><input type="text" name="characterName">
        <label>Classe : </label>
        <select name="class">
            <option value="Warrior">Guerrier</option>
            <option value="Wizard">Sorcier</option>
            <option value="Stealer">Voleur</option>
        </select>
        <label>Histoire : </label>
        <input type="text" name="descChar" />
        <button type="submit">Construire mon personnage</button>
    </form>
</body>

</html>