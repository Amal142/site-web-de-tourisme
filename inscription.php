<?php
class inscription{
    private $id;
    private $nom;    
    private $prenom;    
    private $email;
    private $passwords;    
    private $tel;    
    

    function __constructeur($id,$nom,$prenom,$email,$mot_de_passe,$tel){
        $this->id=$id;
        $this->nom=$nom;
        $this->prenom=$prenom;
        $this->email=$email;
        $this->passwords=$passwords;
        $this->tel=$tel;
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
        return $this->passwords;
    }

      function setpassword($passwords){
        $this->passwords=$passwords;
    }

       
    function getTel(){
        return $this->tel;
    }

      function setTel($tel){
        $this->tel=$tel;
    }
}
?>