<?php

// models/Monster.php

abstract class Monster
{
    protected $name;
    protected $pv;
    protected $mana;
    protected $experienceValue;
    protected $treasure;
    protected $force;

    public function __construct($name, $health, $mana, $experienceValue, $treasure, $strength)
    {
        $this->name = $name;
        $this->pv = $health;
        $this->mana = $mana;
        $this->experienceValue = $experienceValue;
        $this->treasure = $treasure;
        $this->force = $strength;
    }

    abstract public function attack();

    public function getName()
    {
        return $this->name;
    }

    public function getHealth()
    {
        return $this->pv;
    }

    public function getMana()
    {
        return $this->mana;
    }

    public function takeDamage($damage)
    {
        $this->pv -= $damage;
    }

    public function isAlive()
    {
        return $this->pv > 0;
    }

    public function getExperienceValue()
    {
        return $this->experienceValue;
    }

    public function getTreasure()
    {
        return $this->treasure;
    }
}
