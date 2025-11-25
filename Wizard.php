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
    public function __construct($name, $class, $imagesrc, $bio, $initiative, $armorId, $primaryWeapon)
    {
        $this->spellList = ['spell1' => "", 'spell2' => ""];
        $this->health = 6;
        $this->mana = 4;
        $this->strength = 0;
        $this->initiative = $initiative;
        $this->armorItemId = $armorId;
        $this->armorItem = 0;
        $this->primaryWeapon = $primaryWeapon;
        parent::__construct($name, $class, $imagesrc, $bio, );
    }

    public function handtoHandAttack()
    {
        return "{$this->name} vous charge avec sa massue.";
    }

    public function castASpell($spell)
    {
        return $spell->use();
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
