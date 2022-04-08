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
        $sql = sprintf(
            "SELECT SUM(commission_ht) AS total_commission_ht, SUM(commission_ttc) AS total_commission_ttc FROM ventes WHERE id_collaborateur = %d AND YEAR(date) = %d",
            $id_collab,
            $annee
        );

        $req = $this->db->prepare($sql);
        $req->execute();

        $result = $req->fetch(PDO::FETCH_ASSOC);

        return $result['total_commission'];
    }

    public function get_palier($vente)
    {

        $sql = sprintf(
            'SELECT paliers.id_palier, paliers.valeur FROM paliers_commission paliers JOIN collaborateurs ON paliers.id_role = collaborateurs.id_role WHERE collaborateurs.id_collaborateur = %d AND paliers.limite >= %d LIMIT 1;',
            $vente->getCollaborateur(),
            $this->getTotalCommission($vente->getCollaborateur(), substr($vente->getDate(), 0, 4))
        );

        file_put_contents(
            "../.log",
            $sql . PHP_EOL,
            FILE_APPEND
        );

        $req = $this->db->prepare($sql);
        $req->execute();

        $palier = $req->fetch(PDO::FETCH_ASSOC);

        return $palier['valeur'];
    }

    public function add(Collaborateur $collaborateur)
    {

        $sql = sprintf(
            "INSERT INTO collaborateurs (civilite, nom, prenom, email, id_role) VALUES ('%s', '%s', '%s', '%s', %d)",
            $collaborateur->getCivilite(),
            $collaborateur->getNom(),
            $collaborateur->getPrenom(),
            $collaborateur->getEmail(),
            $collaborateur->getId_role()
        );

        $req = $this->db->prepare($sql);


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

        $collaborateurs = $req->fetchAll(PDO::FETCH_ASSOC);

        return json_encode($collaborateurs);
    }

    public function update(Collaborateur $collaborateur)
    {
        $req = $this->db->prepare('UPDATE collaborateurs SET civilite = :civilite, nom = :nom, prenom = :prenom, email = :email, id_role = :id_role WHERE id = :id');
        $req->bindValue(':id', $collaborateur->getId());
        $req->bindValue(':civilite', $collaborateur->getCivilite());
        $req->bindValue(':nom', $collaborateur->getNom());
        $req->bindValue(':prenom', $collaborateur->getPrenom());
        $req->bindValue(':email', $collaborateur->getEmail());
        $req->bindValue(':id_role', $collaborateur->getId_role());
        $req->execute();
    }
}
