<?php

abstract class Hero
{
    protected $name;
    protected $classId;
    protected $className;
    protected $imagesrc;
    protected $biography;
    protected $secondaryWeapon;
    protected $nombre_potions_pv = 3; 
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

    public function getClasse() 
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

    public function choisirAction() {
        if (isset($_POST['combat_action'])) {
            $action = htmlspecialchars($_POST['combat_action']);
            
            if($action == 'attaque_physique') {
                return 'physique';
            }
            if($action == 'attaque_magique') {
                return 'magie';
            }
            if($action == 'potion') {
                return 'potion';
            }
        }
        
        return null;
    }

    abstract public function handtoHandAttack();
    abstract public function castASpell($spell);
    abstract public function takeDamage($damage);
    abstract public function isAlive();

    public function setHealth($health) {
        $this->health = $health;
    }

    public function setMana($mana) {
        $this->mana = $mana;
    }

    public function getManaMax() {
        return 5 + ($this->currentLevel * 2);
    }

    public function getNombrePotionsPv() {
        return $this->nombre_potions_pv;
    }

    public function setNombrePotionsPv($nombre) {
        $this->nombre_potions_pv = $nombre;
    }
    
    public function choisirSort() {
        return (object)[
            'nom' => 'Boule de feu',
            'cout_mana' => 5,
            'degats_base' => 8
        ];
    }
}
