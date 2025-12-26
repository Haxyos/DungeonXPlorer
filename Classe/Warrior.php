<?php
require_once __DIR__. '/Hero.php';

class Warrior extends Hero
{
    protected $health;
    protected $mana;
    protected $strength;
    protected $initiative;
    protected $armorItemId;
    protected $primaryWeapon;
    protected $shieldItem;

    public function __construct()
    {
        
    }

    public function constructeurAvecParam ($name, $class, $imagesrc, $bio, $initiative, $armorId, $primaryWeapon, $shield = 0)
    {
        // Warrior n'a pas de sorts
        $this->health = 10; // Plus de PV
        $this->mana = 0; // Pas de mana
        $this->strength = 5; // Plus de force
        $this->initiative = $initiative;
        $this->armorItemId = $armorId;
        $this->primaryWeapon = $primaryWeapon;
        $this->shieldItem = $shield;
        parent::__construct($name, $class, $imagesrc, $bio);
    }

    public function constructeurPréfait($id_personnage)
    {
        global $db;
        if(!isset($db)) {
            die("Erreur: La connexion à la base de données n'est pas établie.");
        }

        $stmt = $db->prepare('SELECT * FROM Hero WHERE id = :id');
        $stmt->execute(['id' => $id_personnage]);
        $hero = $stmt->fetch(PDO::FETCH_ASSOC);

        // PAS de sorts pour le Warrior
        $this->health = $hero['pv'];
        $this->mana = $hero['mana'];
        $this->strength = $hero['strength'];
        $this->initiative = $hero['initiative'];
        $this->armorItemId = $hero['armor_item_id'];
        $this->primaryWeapon = $hero['primary_weapon_item_id'];
        $this->shieldItem = $hero['shield_item_id'] ?? 0;
        parent::__construct($hero['name'], $hero['class_id'], $hero['image'], $hero['biography']);
    }

    public function handtoHandAttack()
    {
        return $this->primaryWeapon->basicAttack();
    }
    
    public function castASpell($spell)
    {
        return -1; // Warrior ne lance pas de sorts
    }
    
    public function takeDamage($damage)
    {
        $this->health -= $damage;
    }
    
    public function isAlive()
    {
        return $this->health > 0;
    }
    
    public function getShield()
    {
        return $this->shieldItem;
    }
    
    public function getHealth()
    {
        return $this->health;
    }
    
    public function getArmorItem()
    {
        return $this->armorItemId;
    }
    
    public function getMana()
    {
        return $this->mana;
    }
    
    public function getStrength()
    {
        return $this->strength;
    }
    
    public function getInitiative()
    {
        return $this->initiative;
    }
    
    public function getPrimaryWeapon()
    {
        return $this->primaryWeapon;
    }
    
    public function getExp()
    {
        return $this->xp;
    }
    
    public function getLevel()
    {
        return $this->currentLevel;
    }

    public function getPvMax() {
        return 13 + ($this->currentLevel * 2); 
    }
}
