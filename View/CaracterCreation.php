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
            $warrior = new Warrior($nom, 0, $img, $descriptif, $initiative, $armure, $arme, );
            $db->prepare('INSERT INTO Hero (id, name, class_id, image, biographie, pv, mana, strength, initiative, armor_item_id, primary_weapon_item_id, shield_item_id, xp, current_level, user_id) VALUES (:valeur1, :valeur2, :valeur3, :valeur4, :valeur5, :valeur6, :valeur7, :valeur8, :valeur9, :valeur10, :valeur11, :valeur12 ,:valeur13, :valeur14)');
            $stmt->execute(array('valeur1' => $warrior->getName(), 'valeur2' => $warrior->getClass(), 'valeur3' => $warrior->getImSrc(), 'valeur4' => $warrior->getBiography(), 'valeur5' => $warrior->getHealth(), 'valeur6' => $warrior->getMana(), 'valeur7' => $warrior->getStrength(), 'valeur8' => $warrior->getInitiative(), 'valeur9' => $warrior->getArmorItem(), 'valeur10' => $warrior->getPrimaryWeapon(), 'valeur11' => $warrior->getShield(), 'valeur12' => $warrior->getExp(), 'valeur13' => $warrior->getLevel(), 'valeur14' => $_SESSION['user_id']));
            break;
        case "wizard":
            $wizard = new Warrior($nom, $class, $img, $descriptif, $initiative, $armure, $arme, );
            break;
        case "stealer":
            $stealer = new Warrior($nom, $class, $img, $descriptif, $initiative, $armure, $arme, );
            break;
    }
}
/*include "../php/components/header.php";*/
?>
<header>
    <script src="../CaracterCreation.php"></script>
</header>

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