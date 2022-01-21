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

    public function delete($id, $annee)
    {
        $this->db->exec('UPDATE collaborateurs SET actif = 0 WHERE id = ' . $id);

        echo json_encode($this->getList($annee));
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

    public function getList($annee)
    {

        $collaborateurs = [];
        $req = $this->db->query('SELECT collaborateurs.*, SUM(ventes.prix) as volume FROM collaborateurs join ventes on collaborateurs.id = ventes.collaborateur WHERE collaborateurs.actif = 1 and ventes.date like "' . $annee . '-%" GROUP BY collaborateurs.id ORDER BY collaborateurs.nom');

        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            $collaborateurs[] =
                [
                    'id' => $donnees['id'],
                    'civilite' => $donnees['civilite'],
                    'nom' => $donnees['nom'],
                    'prenom' => $donnees['prenom'],
                    'statut' => $donnees['statut'],
                    'email' => $donnees['email'],
                    'volume' => $donnees['volume'],
                ];
        }
        return json_encode($collaborateurs);
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
