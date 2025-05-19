<?php
class Client{
    private $id;
    private $nom;    
    private $prenom;    
    private $email;    
    private $tel;    
    private $adresse;
    private $nomhotel;
    private $typechambre;
    private $dateNaissance;


    function __constructeur($id,$nom,$prenom,$email,$tel,$adresse,$nomhotel,$typechambre,$dateNaissance){
        $this->id=$id;
        $this->nom=$nom;
        $this->prenom=$prenom;
        $this->typechambre=$typechambre;
        $this->tel=$tel;
        $this->adresse=$adresse;
        $this->nomhotel=$nomhotel;
        $this->email=$email;
        $this->dateNaissance=$dateNaissance;


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
        return $this->prenom;
    }

    function setLocalisation($prenom){
        $this->prenom=$prenom;
    }

       
    function getTypechambre(){
        return $this->typechambre;
    }

      function setTypechambre($typechambre){
        $this->typechambre=$typechambre;
    }
       
    function getNbrchambre(){
        return $this->email;
    }

      function setnbrchambre($email){
        $this->email=$email;
    }

       
    function getStatus(){
        return $this->tel;
    }

      function setstatus($tel){
        $this->tel=$tel;
    }
       function getNbretoiles(){
        return $this->adresse;
    }

    function setNbretoiles($adresse){
        $this->adresse=$adresse;
    }
           function getDateNaissance(){
        return $this->dateNaissance;
    }

    function setDateNaissance($dateNaissance){
        $this->dateNaissance=$dateNaissance;
    }
           function getNomHotel(){
        return $this->nomhotel;
    }

    function setNomHotel($nomhotel){
        $this->nomhotel=$nomhotel;
    }
}

?>