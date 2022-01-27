<?php

require "../Collaborateurs/CollaborateurManager.php";

class VenteManager
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function add(Vente $vente)
    {
        $collaborateurManager = new CollaborateurManager($this->db);

        $sql = sprintf(
            'INSERT INTO `ventes` (`adresse`, `ville`, `code_postal`, `commission_ht`, `commission_ttc`, `date`, `id_collaborateur`, `frais_agence`) VALUES ("%s", "%s", "%s", %f, %f, "%s", %d, %f)',
            $vente->getLibele(),
            $vente->getVille(),
            $vente->getCode_postal(),
            $vente->getFrais_agence() * $collaborateurManager->get_palier($vente) / 100,
            $vente->getFrais_agence() * 0.8 * $collaborateurManager->get_palier($vente) / 100,
            $vente->getDate(),
            $vente->getCollaborateur(),
            $vente->getFrais_agence()
        );
        file_put_contents('../.log', $sql, FILE_APPEND);

        $req = $this->db->prepare($sql);


        $req->execute();
    }

    public function getList($annee, $id_collab)
    {

        $sql = sprintf(
            "SELECT CONCAT(collaborateurs.nom,' ' ,collaborateurs.prenom) AS collab, ventes.* FROM ventes JOIN collaborateurs ON ventes.id_collaborateur = collaborateurs.id_collaborateur WHERE collaborateurs.id_collaborateur = %d AND ventes.actif = 1 AND ventes.date LIKE '%s-%%' ORDER BY ventes.date DESC;",
            $id_collab,
            $annee
        );

        $req = $this->db->prepare($sql);
        $req->execute();

        $ventes = [];
        $result = $req->fetchAll(PDO::FETCH_ASSOC);


        foreach ($result as $vente) {
            $ventes[] = [
                'collab' => $vente['collab'],
                'id' => $vente['id_vente'],
                'adresse' => $vente['adresse'],
                'ville' => $vente['ville'],
                'code_postal' => $vente['code_postal'],
                'date' => $vente['date'],
                'collaborateur' => $vente['id_collaborateur'],
                'commission_ht' => $vente['commission_ht'],
                'commission_ttc' => $vente['commission_ttc'],
            ];
        }


        return json_encode($ventes);
    }

    public function update($vente)
    {
        $req = $this->db->prepare('UPDATE ventes SET adresse = :adresse, ville = :ville, code_postal = :code_postal, date = :date, prix = :prix, collaborateur = :collaborateur WHERE id_vente = :id');
        $req->bindValue(':id', $vente->getId());
        $req->bindValue(':adresse', $vente->getLibele());
        $req->bindValue(':ville', $vente->getVille());
        $req->bindValue(':code_postal', $vente->getCode_postal());
        $req->bindValue(':date', $vente->getDate());
        $req->bindValue(':prix', $vente->getPrix());
        $req->bindValue(':collaborateur', $vente->getCollaborateur());

        $req->execute();
    }


    public function delete($id_vente)
    {
        $this->db->exec('UPDATE ventes SET actif = 0 WHERE id_vente = ' . $id_vente);
    }
}
