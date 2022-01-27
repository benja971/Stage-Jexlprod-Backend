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

    private function getTotalCommission($id_collab, $annee)
    {
        file_put_contents('../.log', "getTotalCommission \n", FILE_APPEND);
        $sql = sprintf(
            "SELECT SUM(commission_ht) AS total_commission FROM ventes WHERE id_collaborateur = %d AND date LIKE '%s-%%'",
            $id_collab,
            $annee
        );

        file_put_contents('../.log', $sql, FILE_APPEND);

        $req = $this->db->prepare($sql);
        $req->execute();

        $result = $req->fetch(PDO::FETCH_ASSOC);

        file_put_contents(
            '../.log',
            $result['total_commission'] . "\n",
            FILE_APPEND
        );

        return $result['total_commission'];
    }

    public function get_palier($vente)
    {

        file_put_contents('../.log', "\n\nget_palier \n", FILE_APPEND);

        $sql = sprintf(
            'SELECT paliers.id_palier, paliers.valeur FROM paliers_commission paliers JOIN collaborateurs ON paliers.id_role = collaborateurs.statut WHERE collaborateurs.id_collaborateur = %d AND paliers.limite >= %d LIMIT 1;',
            $vente->getCollaborateur(),
            $this->getTotalCommission($vente->getCollaborateur(), substr($vente->getDate(), 0, 4))
        );

        file_put_contents('../.log', "\n\n" . $sql . " \n\n", FILE_APPEND);

        $req = $this->db->prepare($sql);
        $req->execute();

        $palier = [];

        foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $palier) {
            $palier[] = [
                'id' => $palier['id_palier'],
                'valeur' => $palier['valeur'],
            ];
        }

        file_put_contents(
            '../.log',
            $palier[0]['valeur'],
            FILE_APPEND
        );

        return $palier[0]['valeur'];
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

        $sql = sprintf(
            "SELECT X.*, IFNULL(Y.cumul_ht, 0) AS commission_ht, IFNULL(Y.cumul_ttc, 0) AS commission_ttc
            FROM collaborateurs X 
            LEFT JOIN (SELECT
                    SUM(commission_ht) AS cumul_ht,
                    SUM(commission_ttc) AS cumul_ttc,
                    id_collaborateur
                        FROM
                            ventes
                        WHERE
                            date LIKE '%s-%%' AND
                            actif = 1
                        GROUP BY id_collaborateur) Y 
            ON X.id_collaborateur = Y.id_collaborateur
            GROUP BY
                X.id_collaborateur
            ORDER BY
                X.nom",
            $annee
        );

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
