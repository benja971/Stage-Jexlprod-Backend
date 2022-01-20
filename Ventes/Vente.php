<?php

class Vente
{
    private $id;
    private $libele;
    private $ville;
    private $code_postal;
    private $date;
    private $prix;
    private $collaborateur;

    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
    }

    public function hydrate(array $donnees)
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
     * Set the value of libele
     */
    public function setLibele($libele)
    {
        $this->libele = $libele;
    }

    /**
     * Get the value of libele
     */
    public function getLibele()
    {
        return $this->libele;
    }

    /**
     * Get the value of ville
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set the value of ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    /**
     * Set the value of code_postal
     */
    public function setCode_postal($code_postal)
    {
        $this->code_postal = $code_postal;
    }

    /**
     * Get the value of code_postal
     */
    public function getCode_postal()
    {
        return $this->code_postal;
    }

    /**
     * Get the value of date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get the value of prix
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set the value of prix
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    /**
     * Get the value of collaborateur
     */
    public function getCollaborateur()
    {
        return $this->collaborateur;
    }

    /**
     * Set the value of collaborateur
     */
    public function setCollaborateur($collaborateur)
    {
        $this->collaborateur = $collaborateur;
    }

    public function __toString()
    {
        return $this->libele . " " . $this->ville . " " . $this->code_postal . " " . $this->date . " " . $this->prix . " " . $this->collaborateur;
    }
}
