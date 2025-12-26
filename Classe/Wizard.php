<?php
require_once __DIR__. '/Hero.php';
require_once __DIR__. '/../php/Database.php';

class Wizard extends Hero
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

    public function constructeurAvecParam ($name, $class, $imagesrc, $bio, $initiative, $armorId, $primaryWeapon)
    {
        // Pas de liste de sorts, juste Boule de feu automatique
        $this->health = 6;
        $this->mana = 10; // Plus de mana pour le Wizard
        $this->strength = 2; // Moins de force physique
        $this->initiative = $initiative;
        $this->armorItemId = $armorId;
        $this->primaryWeapon = $primaryWeapon;
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

        // PAS de récupération de sorts depuis la BDD
        $this->health = $hero['pv'];
        $this->mana = $hero['mana'];
        $this->strength = $hero['strength'];
        $this->initiative = $hero['initiative'];
        $this->armorItemId = $hero['armor_item_id'];
        $this->primaryWeapon = $hero['primary_weapon_item_id'];
        parent::__construct($hero['name'], $hero['class_id'], $hero['image'], $hero['biography']);
    }

    public function handtoHandAttack()
    {
        return "{$this->name} frappe avec son bâton.";
    }

    public function castASpell($spell = null)
    {
        return "🔥 Boule de feu";
    }
    
    public function choisirSort()
    {
        // Toujours Boule de feu pour le Wizard
        return (object)[
            'nom' => 'Boule de feu',
            'cout_mana' => 5,
            'degats_base' => 8
        ];
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
        return null; // Wizard n'a pas de bouclier
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
        return 6 + ($this->currentLevel * 2); 
    }
}
