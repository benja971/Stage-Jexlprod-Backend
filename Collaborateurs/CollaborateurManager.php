<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Charset: UTF-8");

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
        $this->db->exec('UPDATE collaborateurs SET actif = 0 WHERE id_collaborateur = ' . $id);
    }

    public function getList($annee)
    {
        $collaborateurs = [];

        $sql = 'SELECT X.*, IFNULL(Y.cumul_ht, 0) AS commission_ht, IFNULL(Y.cumul_ttc, 0) AS commission_ttc
                FROM
                    collaborateurs X LEFT JOIN
                (SELECT
                    SUM(commission_ht) AS cumul_ht,
                    SUM(commission_ttc) AS cumul_ttc,
                    collaborateur
                FROM
                    ventes
                WHERE
                    actif = 1) Y ON X.id_collaborateur = Y.collaborateur
                GROUP BY
                    X.id_collaborateur
                ORDER BY
                    X.nom';

        $req = $this->db->prepare($sql);

        $req->execute();

        while ($donnees = $req->fetch(PDO::FETCH_ASSOC)) {
            $collaborateurs[] =
                [
                    'id_collaborateur' => $donnees['id_collaborateur'],
                    'civilite' => $donnees['civilite'],
                    'nom' => $donnees['nom'],
                    'prenom' => $donnees['prenom'],
                    'statut' => $donnees['statut'],
                    'email' => $donnees['email'],
                    'commission_ttc' => $donnees['commission_ttc'],
                    'commission_ht' => $donnees['commission_ht'],
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
