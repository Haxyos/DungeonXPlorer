<?php
require_once __DIR__ . '/../Modele/HeroModel.php';

class HeroController {
    private $model;
    private $userId;

    public function __construct($db, $userId) {
        $this->model = new HeroModel($db);
        $this->userId = $userId;
    }

    public function handleRequest() {
        $error = null;

        // --- Configuration (Logique Métier) ---
        $defaultLoadouts = [
            1 => ['img' => '/images/guerrier.png', 'weapon_id' => 10, 'armor_id' => 40, 'shield_id' => 30], // Guerrier
            3 => ['img' => '/images/voleur.png', 'weapon_id' => 13, 'armor_id' => 44, 'shield_id' => null]   // Voleur
        ];

        // GESTION SUPPRESSION (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_hero_id'])) {
            try {
                $this->model->deleteHero(intval($_POST['delete_hero_id']), $this->userId);
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } catch (Exception $e) {
                $error = "Erreur suppression : " . $e->getMessage();
            }
        }

        // GESTION CRÉATION (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_hero'])) {
            $classId = intval($_POST["class"]);
            
            // Bloquer la création de mage (class_id 2)
            if ($classId == 2) {
                $error = "La classe Mage n'est plus disponible.";
            }

            if (!$error) {
                // Récupération des stats de base depuis le modèle pour calculs
                $allClasses = $this->model->getAllClasses(); // Optimisation possible: getOneClass
                $classInfo = null;
                foreach($allClasses as $c) { if($c['id'] == $classId) $classInfo = $c; }

                if ($classInfo) {
                    // Préparation des données pour le modèle
                    $data = [
                        'name' => htmlspecialchars($_POST["characterName"]),
                        'class_id' => $classId,
                        'bio' => htmlspecialchars($_POST["descChar"]),
                        'spells' => []
                    ];
                    
                    $stats = [
                        'pv' => $classInfo['base_pv'],
                        'mana' => $classInfo['base_mana'],
                        'str' => $classInfo['strength'],
                        'init' => $classInfo['initiative'] + intval($_POST["initiative"])
                    ];

                    $loadout = $defaultLoadouts[$classId] ?? ['img' => '', 'weapon_id' => null, 'armor_id' => null, 'shield_id' => null];

                    try {
                        $this->model->createHero($data, $stats, $loadout, $this->userId);
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    } catch (Exception $e) {
                        $error = "Erreur création : " . $e->getMessage();
                    }
                } else {
                    $error = "Classe inconnue.";
                }
            }
        }

        // PRÉPARATION DONNÉES POUR LA VUE
        $heroes = $this->model->getHeroesByUser($this->userId);
        $classes = $this->model->getAllClasses();
        $items = $this->model->getAllItems();
        $spellsList = $this->model->getAllSpells();
        
        // On indexe les classes par ID pour un accès facile dans la vue
        $classesById = [];
        foreach($classes as $c) $classesById[$c['id']] = $c;

        // Chargement de la vue
        // On rend les variables disponibles pour la vue
        require_once __DIR__ . '/../View/hero/hero_selection.php';
    }
}
?>