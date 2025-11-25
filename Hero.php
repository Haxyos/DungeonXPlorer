<?php

// models/Monster.php

abstract class Hero
{
    protected $name;
    protected $classId;
    protected $className;
    protected $imagesrc;
    protected $biography;
    protected $secondaryWeapon;
    /* put in different subclasses
    protected $health;
    protected $mana;
    protected $strength;
    protected $initiative;
    protected $armorItemId;
    protected $primaryWeapon;
    protected $shieldItem;
    protected $spellList;
    */
    protected $xp;
    protected $currentLevel;

    public function __construct($name, $className, $imsrc, $biographie)
    {
        $this->name = $name;
        $this->className = $className;
        $this->imagesrc = $imsrc;
        $this->biography = $biographie;
        $this->secondaryWeapon = 0;
        $this->xp = 0;
        $this->currentLevel = 1;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getClass(){
        return $this->className;
    }
    public function getBiography(){
        return $this->biography;
    }
    public function getImSrc(){
        return $this->imagesrc;
    }
    abstract public function handtoHandAttack();
    abstract public function castASpell();
    abstract public function takeDamage($damage);
    abstract public function isAlive();
    abstract public function getShield();
    
}
