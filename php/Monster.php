<?php

class Monster
{
    protected $name;
    protected $health;
    protected $mana;
    protected $experienceValue;
    protected $treasure;
    protected $strength;
    protected $primaryWeapon;

    public function __construct($name, $health, $mana, $experienceValue, $treasure = null)
    {
        $this->name = $name;
        $this->health = $health;
        $this->mana = $mana;
        $this->experienceValue = $experienceValue;
        $this->treasure = $treasure;
        $this->strength = 5; 
        $this->primaryWeapon = null;
    }

    public function attack()
    {
        return "{$this->name} vous attaque";
    }

    public function setTreasure($treasure)
    {
        $this->treasure = $treasure;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getHealth()
    {
        return $this->health;
    }

    public function getMana()
    {
        return $this->mana;
    }

    public function takeDamage($damage)
    {
        $this->health -= $damage;
    }

    public function isAlive()
    {
        return $this->health > 0;
    }

    public function getExperienceValue()
    {
        return $this->experienceValue;
    }

    public function getTreasure()
    {
        return $this->treasure;
    }
    
    public function choisirAction()
    {
        return 'physique'; 
    }
    
    public function getInitiative()
    {
        return 6;
    }
    
    public function getStrength()
    {
        return $this->strength;
    }
    
    public function setStrength($strength)
    {
        $this->strength = $strength;
    }
    
    public function getPrimaryWeapon()
    {
        return $this->primaryWeapon;
    }
    
    public function setPrimaryWeapon($weapon)
    {
        $this->primaryWeapon = $weapon;
    }
    
    public function getClasse()
    {
        return 'Monstre';
    }
    
    public function getArmorItem()
    {
        return null;
    }
}
