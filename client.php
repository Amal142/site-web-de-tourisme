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

       
    function getPrenom(){
        return $this->prenom;
    }

    function setPrenom($prenom){
        $this->prenom=$prenom;
    }

       
    function getTypechambre(){
        return $this->typechambre;
    }

      function setTypechambre($typechambre){
        $this->typechambre=$typechambre;
    }
       
    function getEmail(){
        return $this->email;
    }

      function setEmail($email){
        $this->email=$email;
    }

       
    function getTel(){
        return $this->tel;
    }

      function setTel($tel){
        $this->tel=$tel;
    }
       function getAdresse(){
        return $this->adresse;
    }

    function setAdresse($adresse){
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