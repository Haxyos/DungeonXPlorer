<?php

// models/Orc.php

class Wizard extends Hero
{
    protected $health;
    protected $mana;
    protected $strength;
    protected $initiative;
    protected $armorItemId;
    protected $primaryWeapon;
    protected $shieldItem;
    protected $spellList;
    public function __construct($name, $class, $imagesrc, $bio, $initiative, $armorId, $primaryWeapon,$armorItem)
    {
        $this->spellList = ['spell1' => "", 'spell2' => ""];
        $this->health = 8;
        $this->mana = 0;
        $this->strength = 0;
        $this->initiative = $initiative;
        $this->armorItemId = $armorId;
        $this->armorItem = $armorItem;
        $this->primaryWeapon = $primaryWeapon;
        parent::__construct($name, $class, $imagesrc, $bio, );
    }

    public function handtoHandAttack()
    {
        return $this->primaryWeapon->basicAttack();
    }
    public function castASpell($spell)
    {
        return "you can't cast a spell ! ";
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
}
