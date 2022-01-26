<?php

class VenteManager
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function add(Vente $vente)
    {
        $sql = sprintf(
            "INSERT INTO ventes (adresse, ville, code_postal, commission_ht, commission_ttc, date, collaborateur) VALUES ('%s', '%s', '%s', %f, %f, '%s', %d)\n\n",
            $vente->getLibele(),
            $vente->getVille(),
            $vente->getCode_postal(),
            $vente->getCommission_ht(),
            $vente->getCommission_ttc(),
            $vente->getDate(),
            $vente->getCollaborateur()
        );
        $req = $this->db->prepare($sql);
        $req->bindValue(':collaborateur', $vente->getCollaborateur());

        file_put_contents(
            "../.log",
            print_r($vente, true),
            FILE_APPEND
        );

        $req->execute();
    }

    public function getList($annee, $id_collab)
    {
        $sql = 'SELECT CONCAT(collaborateurs.nom,' . "' '" . ', collaborateurs.prenom) AS collab, ventes.* FROM ventes JOIN collaborateurs ON ventes.collaborateur = collaborateurs.id_collaborateur WHERE collaborateurs.id_collaborateur = ' . $id_collab . ' AND ventes.actif = 1 AND ventes.date LIKE "' . $annee . '-%" ORDER BY ventes.date DESC;';

        $req = $this->db->prepare($sql);
        $req->execute();

        $ventes = [];

        foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $vente) {
            $ventes[] = [
                'id' => $vente['id_vente'],
                'adresse' => $vente['adresse'],
                'ville' => $vente['ville'],
                'code_postal' => $vente['code_postal'],
                'date' => $vente['date'],
                'commission_ht' => $vente['commission_ht'],
                'commission_ttc' => $vente['commission_ttc'],
                'collaborateur' => $vente['collaborateur'],
                'collab' => $vente['collab']
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
