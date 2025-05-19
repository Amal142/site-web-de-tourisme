<?php
class inscription{
    private $id;
    private $nom;    
    private $prenom;    
    private $email;
    private $mot_de_passe;    
    private $message;    
    

    function __constructeur($id,$nom,$prenom,$email,$mot_de_passe,$message){
        $this->id=$id;
        $this->nom=$nom;
        $this->prenom=$prenom;
        $this->email=$email;
        $this->mot_de_passe=$mot_de_passe;
        $this->message=$message;
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

     function getEmail(){
        return $this->email;
    }

      function setEmail($email){
        $this->email=$email;
    }
    function getpassword(){
        return $this->mot_de_passe;
    }

      function setpassword($mot_de_passe){
        $this->mot_de_passe=$mot_de_passe;
    }

       
    function getTel(){
        return $this->message;
    }

      function setTel($message){
        $this->message=$message;
    }
}
?>