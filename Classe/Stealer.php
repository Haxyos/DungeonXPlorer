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
    public function __construct($name, $class, $imagesrc, $bio, $initiative, $armorId, $primaryWeapon)
    {
        $this->spellList = ['spell1' => "", 'spell2' => ""];
        $this->health = 8;
        $this->mana = 0;
        $this->strength = 0;
        $this->initiative = $initiative;
        $this->armorItemId = $armorId;
        $this->primaryWeapon = $primaryWeapon;
        parent::__construct($name, $class, $imagesrc, $bio, );
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
