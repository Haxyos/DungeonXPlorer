<!DOCTYPE html>
<html lang="en">

<?php
require '../Classe/Warrior.php';
require '../Classe/Stealer.php';
require '../Classe/Wizard.php';

if (isset($_POST["characterName"]) && isset($_POST["class"]) && isset($_POST["descChar"]) && isset($_POST["initiative"]) && isset($_POST["arme"]) && isset($_POST["armure"])) {
    $nom = $_POST["characterName"];
    $class = $_POST["class"];
    $descriptif = $_POST["descChar"];
    $initiative = $_POST["initiative"];
    $arme = $_POST["arme"];
    $armure = $_POST["armure"];

    echo "<h2>Personnage créé :</h2>";
    echo "<p>Nom : " . htmlspecialchars($nom) . "</p>";
    echo "<p>Classe : " . htmlspecialchars($class) . "</p>";
    echo "<p>Histoire : " . htmlspecialchars($descriptif) . "</p>";

    switch ($class) {
        case "warrior":
            $warrior = new Warrior($nom, $class, $img, $descriptif, $initiative, $armure, $arme, );

            break;
        case "wizard":
            $wizard = new Warrior($nom, $class, $img, $descriptif, $initiative, $armure, $arme, );
            break;
        case "stealer":
            $stealer = new Warrior($nom, $class, $img, $descriptif, $initiative, $armure, $arme, );
            break;
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
        <select id="select" name="class">
            <option value="Warrior">Guerrier</option>
            <option value="Wizard">Sorcier</option>
            <option value="Stealer">Voleur</option>
        </select>
        <label>Histoire : </label>
        <input type="text" name="descChar" />*
        <label>Lancer pour votre initiative :</label>
        <input type="text" name="initiative" disabled />
        <button>Roll !</button>
        <label>Lancer pour votre arme</label>
        <input type="text" name="arme" disabled />
        <button>Roll !</button>
        <label>Lancer pour votre armure</label>
        <input type="text" name="armure" disabled />
        <button>Roll !</button>
        <div id="shield">
            <label>Lancer pour votre bouclier</label>
            <input type="text" name="bouclier" disabled />
            <button>Roll !</button>
        </div>
        <div id="sort" class="hidden">
            <label>Selectionner vos sorts</label>
            <select name="" id=""></select>
            <option></option>
            <option></option>
            <option></option>
            <select name="" id=""></select>
            <option></option>
            <option></option>
            <option></option>
            <select name="" id=""></select>
            <option></option>
            <option></option>
            <option></option>
        </div>
        <button type="submit">Construire mon personnage</button>
    </form>
</body>

</html>