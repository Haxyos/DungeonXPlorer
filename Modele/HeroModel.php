<?php

class HeroModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Récupérer les données de configuration (Classes, Items, Sorts)
    public function getAllClasses() {
        return $this->db->query("SELECT * FROM Class ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllItems() {
        $stmt = $this->db->query("SELECT id, name, description FROM Items");
        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[$row['id']] = $row;
        }
        return $items;
    }

    public function getAllSpells() {
        return $this->db->query("SELECT * FROM Spell ORDER BY cout_mana ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les héros d'un utilisateur
    public function getHeroesByUser($userId) {
        $stmt = $this->db->prepare("SELECT * FROM Hero WHERE user_id = :user_id ORDER BY id DESC");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer le chapitre actuel d'un héros
    public function getHeroProgress($heroId) {
        $req = $this->db->prepare("SELECT chapter_id FROM Hero_Progress WHERE hero_id = ?");
        $req->execute([$heroId]);
        $res = $req->fetch();
        return $res ? $res['chapter_id'] : 1;
    }

    // Supprimer un héros et ses dépendances
    public function deleteHero($heroId, $userId) {
        try {
            $this->db->beginTransaction();
            // Suppression dépendances
            $this->db->prepare("DELETE FROM Hero_Progress WHERE hero_id = ?")->execute([$heroId]);
            $this->db->prepare("DELETE FROM Pouvoir WHERE id_heros = ?")->execute([$heroId]);
            $this->db->prepare("DELETE FROM Inventory WHERE hero_id = ?")->execute([$heroId]);
            // Suppression Héros (vérification user_id pour sécurité)
            $stmt = $this->db->prepare("DELETE FROM Hero WHERE id = ? AND user_id = ?");
            $stmt->execute([$heroId, $userId]);
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // Créer un héros complet
    public function createHero($data, $stats, $loadout, $userId) {
        try {
            $this->db->beginTransaction();

            // 1. Insertion Héros
            $sql = "INSERT INTO Hero (
                        name, class_id, image, biography, 
                        pv, mana, strength, initiative, 
                        armor_item_id, primary_weapon_item_id, shield_item_id, 
                        xp, current_level, user_id
                    ) VALUES (
                        :name, :cid, :img, :bio, 
                        :pv, :mana, :str, :init, 
                        :armor, :wep, :shield, 
                        0, 1, :uid
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':cid' => $data['class_id'],
                ':img' => $loadout['img'],
                ':bio' => $data['bio'],
                ':pv' => $stats['pv'],
                ':mana' => $stats['mana'],
                ':str' => $stats['str'],
                ':init' => $stats['init'],
                ':armor' => $loadout['armor_id'],
                ':wep' => $loadout['weapon_id'],
                ':shield' => $loadout['shield_id'],
                ':uid' => $userId
            ]);
            
            $heroId = $this->db->lastInsertId();

            // 2. Initialisation Progression
            $this->db->prepare("INSERT INTO Hero_Progress (user_id, hero_id, chapter_id, status, completion_date) VALUES (?, ?, 1, 'In Progress', NOW())")
                     ->execute([$userId, $heroId]);

            // 3. Ajout des 3 potions de soin dans l'inventaire
            $this->db->prepare("INSERT INTO Inventory (hero_id, item_id, quantity) VALUES (?, 50, 3)")
                     ->execute([$heroId]);

            // 4. Ajout des sorts (Si fournis)
            if (!empty($data['spells'])) {
                $sqlSpell = "INSERT INTO Pouvoir (id_heros, id_spell) VALUES (?, ?)";
                $stmtS = $this->db->prepare($sqlSpell);
                foreach($data['spells'] as $spellId) {
                    $stmtS->execute([$heroId, $spellId]);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
?>