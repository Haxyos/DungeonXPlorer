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

    public function __construct($name, $className, $imsrc = null, $biographie)
    {
        $this->name = $name;
        $this->className = $className;
        if ($imsrc === null) {
            $this->imagesrc = '../images/Berserker.jpg';
        } else {
            $this->imagesrc = $imsrc;
        }
        $this->biography = $biographie;
        $this->secondaryWeapon = 1;
        $this->xp = 0;
        $this->currentLevel = 1;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getClass()
    {
        return $this->className;
    }
    public function getBiography()
    {
        return $this->biography;
    }
    public function getImSrc()
    {
        return $this->imagesrc;
    }
    public function setImage($imsrc)
    {
        $this->imsrc = $imsrc;
    }
    abstract public function handtoHandAttack();
    abstract public function castASpell($spell);
    abstract public function takeDamage($damage);
    abstract public function isAlive();
}
