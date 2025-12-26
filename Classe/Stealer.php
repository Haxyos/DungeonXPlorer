<?php
require_once __DIR__. '/Hero.php';
// models/Orc.php

class Stealer extends Hero
{
    protected $health;
    protected $mana;
    protected $strength;
    protected $initiative;
    protected $armorItemId;
    protected $primaryWeapon;
    protected $spellList;
    public function __construct()
    {
        
    }

    public function constructeurAvecParam ($name, $class, $imagesrc, $bio, $initiative, $armorId, $primaryWeapon)
    {
        $this->spellList = ['spell1' => "", 'spell2' => ""];
        $this->health = 6;
        $this->mana = 4;
        $this->strength = 3;
        $this->initiative = $initiative;
        $this->armorItemId = $armorId;
        $this->primaryWeapon = $primaryWeapon;
        parent::__construct($name, $class, $imagesrc, $bio);
    }

    public function constructeurPréfait($id_personnage)
    {
        global $db;
        if(!isset($db)) { // Vérification que db existe
            die("Erreur: La connexion à la base de données n'est pas établie.");
        }

        $stmt = $db->prepare('SELECT * FROM Hero WHERE id = :id');
        $stmt->execute(['id' => $id_personnage]);
        $hero = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $db->prepare('SELECT * FROM Pouvoir WHERE id_heros = :id');
        $stmt->execute(['id' => $id_personnage]);
        $pouvoir = $stmt->fetch(PDO::FETCH_ASSOC);

        $listSpellTemp = array();
        foreach($pouvoir as $spell) {
            array_push($listSpellTemp, $spell['id_spell']);
        }

        $this->spellList = $listSpellTemp;
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
        return $this->primaryWeapon->basicAttack();
    }
    public function castASpell($spell){
        return -1;
    }
    
    public function takeDamage($damage)
    {
        $this->health -= $damage;
    }
    public function isAlive()
    {
        if ($this->health > 0){
            return true;
        }
        else {
            return false;
        }
    }
    public function getHealth()
    {
        return $this->health;
    }
    public function getArmorItem()
    {
        return $this->armorItemId;
    }
    public function getSpellList()
    {
        return $this->spellList;
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
    public function getPrimaryWeapon(){
        return $this->primaryWeapon;
    }
    public function getExp(){
        return $this->xp;
    }
    public function getLevel(){
        return $this->currentLevel;
    }
}
