<?php
session_start();
?>
<?php
include "../php/components/header.php";
include "../php/Database.php";
?>
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
        <button type="submit">Personnage par default</button>
        <button type="submit">Construire mon personnage</button>
    </form>
</body>


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
            $warrior = new Warrior($nom, 0, $img, $descriptif, $initiative, $armure, $arme, 0);
            $stmt = $db->prepare('INSERT INTO Hero (id, name, class_id, image, biographie, pv, mana, strength, initiative, armor_item_id, primary_weapon_item_id, shield_item_id, xp, current_level, user_id) VALUES (:valeur1, :valeur2, :valeur3, :valeur4, :valeur5, :valeur6, :valeur7, :valeur8, :valeur9, :valeur10, :valeur11, :valeur12 ,:valeur13, :valeur14)');
            $stmt->execute(array('valeur1' => $warrior->getName(), 'valeur2' => $warrior->getClass(), 'valeur3' => $warrior->getImSrc(), 'valeur4' => $warrior->getBiography(), 'valeur5' => $warrior->getHealth(), 'valeur6' => $warrior->getMana(), 'valeur7' => $warrior->getStrength(), 'valeur8' => $warrior->getInitiative(), 'valeur9' => $warrior->getArmorItem(), 'valeur10' => $warrior->getPrimaryWeapon(), 'valeur11' => $warrior->getShield(), 'valeur12' => $warrior->getExp(), 'valeur13' => $warrior->getLevel(), 'valeur14' => $_SESSION['user_id']));
            break;
        case "wizard":
            $wizard = new Wizard($nom, 1, $img, $descriptif, $initiative, $armure, $arme);
            $stmt = $db->prepare('INSERT INTO Hero (id, name, class_id, image, biographie, pv, mana, strength, initiative, armor_item_id, primary_weapon_item_id, shield_item_id, xp, current_level, user_id) VALUES (:valeur1, :valeur2, :valeur3, :valeur4, :valeur5, :valeur6, :valeur7, :valeur8, :valeur9, :valeur10, :valeur11, :valeur12 ,:valeur13, :valeur14)');
            $stmt->execute(array('valeur1' => $wizard->getName(), 'valeur2' => $wizard->getClass(), 'valeur3' => $wizard->getImSrc(), 'valeur4' => $wizard->getBiography(), 'valeur5' => $wizard->getHealth(), 'valeur6' => $wizard->getMana(), 'valeur7' => $wizard->getStrength(), 'valeur8' => $wizard->getInitiative(), 'valeur9' => $wizard->getArmorItem(), 'valeur10' => $wizard->getPrimaryWeapon(), 'valeur11' => $wizard->getShield(), 'valeur12' => $wizard->getExp(), 'valeur13' => $wizard->getLevel(), 'valeur14' => $_SESSION['user_id']));
            break;
        case "stealer":
            $stealer = new Stealer($nom, $class, $img, $descriptif, $initiative, $armure, $arme);
            $stmt = $db->prepare('INSERT INTO Hero (id, name, class_id, image, biographie, pv, mana, strength, initiative, armor_item_id, primary_weapon_item_id, shield_item_id, xp, current_level, user_id) VALUES (:valeur1, :valeur2, :valeur3, :valeur4, :valeur5, :valeur6, :valeur7, :valeur8, :valeur9, :valeur10, 0, :valeur12 ,:valeur13, :valeur14)');
            $stmt->execute(array('valeur1' => $stealer->getName(), 'valeur2' => $stealer->getClass(), 'valeur3' => $stealer->getImSrc(), 'valeur4' => $stealer->getBiography(), 'valeur5' => $stealer->getHealth(), 'valeur6' => $stealer->getMana(), 'valeur7' => $stealer->getStrength(), 'valeur8' => $stealer->getInitiative(), 'valeur9' => $stealer->getArmorItem(), 'valeur10' => $stealer->getPrimaryWeapon(), 'valeur12' => $stealer->getExp(), 'valeur13' => $stealer->getLevel(), 'valeur14' => $_SESSION['user_id']));
            break;
    }

} else if(isset($_POST["characterName"])){
    $warrior = new Warrior('Default', 0, null, 'je suis le personnage par default', 0, 0, 0, 0);
    $stmt = $db->prepare('INSERT INTO Hero (id, name, class_id, image, biography, pv, mana, strength, initiative, armor_item_id, primary_weapon_item_id, secondary_weapon_item_id, shield_item_id, xp, current_level, user_id) VALUES (:valeur1, :valeur2, :valeur3, :valeur4, :valeur5, :valeur6, :valeur7, :valeur8, :valeur9, :valeur10, :valeur11, :valeur12, :valeur13 ,:valeur14, :valeur15)');
    $stmt->execute(array('valeur1' => $warrior->getName(), 'valeur2' => $warrior->getClass(), 'valeur3' => $warrior->getImSrc(), 'valeur4' => $warrior->getBiography(), 'valeur5' => $warrior->getHealth(), 'valeur6' => $warrior->getMana(), 'valeur7' => $warrior->getStrength(), 'valeur8' => $warrior->getInitiative(), 'valeur9' => $warrior->getArmorItem(), 'valeur10' => $warrior->getPrimaryWeapon(), 'valeur11' => $warrior->getSecondaryWeapon(), 'valeur12' => $warrior->getShield(), 'valeur13' => $warrior->getExp(), 'valeur14' => $warrior->getLevel(), 'valeur15' => $_SESSION['user_id']));

}

?>

</html>