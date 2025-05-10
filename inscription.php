<?php
class Hotels{
    public $id;
    public $nom;    
    public $localisation;    
    public $typechambre;    
    public $nbrchambre;    
    public $status;
    public $nbretoiles;
    

    function __constructeur($id,$nom,$localisation,$typechambre,$nbrchambre,$status,$nbretoiles){
        $this->id=$id;
        $this->nom=$nom;
        $this->localisation=$localisation;
        $this->typechambre=$typechambre;
        $this->nbrchambre=$nbrchambre;
        $this->status=$status;
        $this->nbretoiles=$nbretoiles;

    }
    
    function getId(){
        return $this->id;
    }

    function setId($id){
        $this->id=$id;
    }
    
    function getNom(){
        return $this->nom;
    }

    function setNom($nom){
        $this->nom=$nom;
    }

       
    function getLocalisation(){
        return $this->localisation;
    }

    function setLocalisation($localisation){
        $this->localistion=$localisation;
    }

       
    function getTypechambre(){
        return $this->typechambre;
    }

      function setTypechambre($typechambre){
        $this->typechambre=$typechambre;
    }
       
    function getNbrchambre(){
        return $this->nbrchambre;
    }

      function setnbrchambre($nbrchambre){
        $this->nbrchambre=$nbrchambre;
    }

       
    function getStatus(){
        return $this->status;
    }

      function setstatus($status){
        $this->status=$status;
    }
       function getNbretoiles(){
        return $this->nbretoiles;
    }

    function setNbretoiles($nbretoiles){
        $this->nbretoiles=$nbretoiles;
    }
    
}
?>