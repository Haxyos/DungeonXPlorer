<?php

// models/Monster.php

abstract class Monster
{
    protected $name;
    protected $class_id;
    protected $class_name;
    protected $imagesrc;
    protected $biography;
    protected $health;
    protected $mana;
    protected $strength;
    protected $initiative;
    protected $armorItemId;
    protected $primary_weapon;
    protected $xp;

    public function __construct($name, $health, $mana, $experienceValue, $treasure)
    {
        $this->name = $name;
        $this->health = $health;
        $this->mana = $mana;
        $this->experienceValue = $experienceValue;
        $this->treasure = $treasure;
    }

    abstract public function attack();

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
}
