<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

class CollaborateurManager
{
    private $db;


    public function __construct($dbPDO)
    {
        $this->db = $dbPDO;
    }

    public function add(Collaborateur $collaborateur)
    {
        $req = $this->db->prepare('INSERT INTO collaborateurs (civilite, nom, prenom, email, statut) VALUES (:civilite, :nom, :prenom, :email, :statut)');
        $req->bindValue(':civilite', $collaborateur->getCivilite());
        $req->bindValue(':nom', $collaborateur->getNom());
        $req->bindValue(':prenom', $collaborateur->getPrenom());
        $req->bindValue(':email', $collaborateur->getEmail());
        $req->bindValue(':statut', $collaborateur->getStatut());

        $req->execute();
    }

    public function delete($id)
    {
        $this->db->exec('UPDATE collaborateurs SET actif = 0 WHERE id = ' . $id);

        echo json_encode($this->getList());
    }

    public function get($id)
    {
        $id = (int) $id;
        $req = $this->db->query('SELECT * FROM collaborateurs WHERE id = ' . $id);
        $donnees = $req->fetch(PDO::FETCH_ASSOC);
        return [
            'id' => $donnees['id'],
            'civilite' => $donnees['civilite'],
            'nom' => $donnees['nom'],
            'prenom' => $donnees['prenom'],
            'email' => $donnees['email'],
            'statut' => $donnees['statut'],
        ];
    }

    public function getList()
    {
        $collaborateurs = [];
        $req = $this->db->query('SELECT * FROM collaborateurs WHERE actif = 1 ORDER BY nom');
        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            $collaborateurs[] =
                [
                    'id' => $donnees['id'],
                    'civilite' => $donnees['civilite'],
                    'nom' => $donnees['nom'],
                    'prenom' => $donnees['prenom'],
                    'statut' => $donnees['statut'],
                    'email' => $donnees['email'],
                ];
        }
        return $collaborateurs;
    }

    public function update(Collaborateur $collaborateur)
    {
        $req = $this->db->prepare('UPDATE collaborateurs SET civilite = :civilite, nom = :nom, prenom = :prenom, email = :email, statut = :statut WHERE id = :id');
        $req->bindValue(':id', $collaborateur->getId());
        $req->bindValue(':civilite', $collaborateur->getCivilite());
        $req->bindValue(':nom', $collaborateur->getNom());
        $req->bindValue(':prenom', $collaborateur->getPrenom());
        $req->bindValue(':email', $collaborateur->getEmail());
        $req->bindValue(':statut', $collaborateur->getStatut());
        $req->execute();
    }
}
