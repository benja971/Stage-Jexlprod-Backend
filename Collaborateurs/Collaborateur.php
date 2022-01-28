<?php

class Collaborateur
{

    private $id;
    private $civilite;
    private $nom;
    private $prenom;
    private $email;
    private $id_role;
    private $volume;

    public function __construct($donnees)
    {
        $this->hydrater($donnees);
    }

    public function hydrater($donnees)
    {
        foreach ($donnees as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the value of nom
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * Get the value of prenom
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set the value of prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * Get the value of volume
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * Set the value of volume
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;
    }

    /**
     * Get the value of civilite
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    /**
     * Set the value of civilite
     */
    public function setCivilite($civilite)
    {
        $this->civilite = $civilite;
    }

    /**
     * Get the value of id_role
     */
    public function getId_role()
    {
        return $this->id_role;
    }

    /**
     * Set the value of id_role
     */
    public function setId_role($id_role)
    {
        $this->id_role = $id_role;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function __toString()
    {
        return $this->civilite . " " . $this->nom . " " . $this->prenom . " " . $this->email . " " . $this->id_role . " " . $this->volume;
    }
}
