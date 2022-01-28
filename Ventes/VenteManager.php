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
            'INSERT INTO `ventes` (`adresse`, `ville`, `code_postal`, `commission_ht`, `commission_ttc`, `date`, `id_collaborateur`, `frais_agence`) VALUES ("%s", "%s", "%s", %f, %f, "%s", %d, %f);',
            $vente->getLibele(),
            $vente->getVille(),
            $vente->getCode_postal(),
            $vente->getFrais_agence() * $collaborateurManager->get_palier($vente) / 100,
            $vente->getFrais_agence() * 0.8 * $collaborateurManager->get_palier($vente) / 100,
            $vente->getDate(),
            $vente->getCollaborateur(),
            $vente->getFrais_agence()
        );

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

        $result = $req->fetchAll(PDO::FETCH_ASSOC);

        return json_encode($result);
    }

    public function update($vente)
    {
        $collaborateurManager = new CollaborateurManager($this->db);

        $sql = sprintf(
            "UPDATE ventes SET adresse = '%s', ville = '%s', code_postal = '%s', date = '%s', frais_agence = %f, id_collaborateur = %d, commission_ht = %f, commission_ttc = %f WHERE id_vente = %d",
            $vente->getLibele(),
            $vente->getVille(),
            $vente->getCode_postal(),
            $vente->getDate(),
            $vente->getFrais_agence(),
            $vente->getCollaborateur(),
            $vente->getFrais_agence() * $collaborateurManager->get_palier($vente) / 100,
            $vente->getFrais_agence() * 0.8 * $collaborateurManager->get_palier($vente) / 100,
            $vente->getId(),
        );

        $req = $this->db->prepare($sql);

        $req->execute();
    }


    public function delete($id_vente)
    {
        $this->db->exec('UPDATE ventes SET actif = 0 WHERE id_vente = ' . $id_vente);
    }
}
