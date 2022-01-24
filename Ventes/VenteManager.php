<?php

class VenteManager
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function add(Vente $vente)
    {
        $req = $this->db->prepare('INSERT INTO ventes (adresse, ville, code_postal, prix, date, collaborateur) VALUES (:adresse, :ville, :code_postal, :prix, :date, :collaborateur)');
        $req->bindValue(':adresse', $vente->getLibele());
        $req->bindValue(':ville', $vente->getVille());
        $req->bindValue(':code_postal', $vente->getCode_postal());
        $req->bindValue(':date', $vente->getDate());
        $req->bindValue(':prix', $vente->getPrix());
        $req->bindValue(':collaborateur', $vente->getCollaborateur());
        $req->execute();
    }

    public function getList($annee, $id_collab)
    {
        $sql = 'SELECT CONCAT(collaborateurs.nom,' . "' '" . ', collaborateurs.prenom) as collab, ventes.* FROM ventes JOIN collaborateurs ON ventes.collaborateur = collaborateurs.id WHERE collaborateurs.id = ' . $id_collab . ' AND ventes.actif = 1 AND ventes.date LIKE "' . $annee . '-%" ORDER BY ventes.date DESC;';

        file_put_contents('../.log', $sql . PHP_EOL, FILE_APPEND);


        $req = $this->db->prepare($sql);
        $req->execute();

        $ventes = [];

        foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $vente) {
            $ventes[] = [
                'id' => $vente['id'],
                'adresse' => $vente['adresse'],
                'ville' => $vente['ville'],
                'code_postal' => $vente['code_postal'],
                'date' => $vente['date'],
                'prix' => $vente['prix'],
                'collaborateur' => $vente['collaborateur'],
                'collab' => $vente['collab']
            ];
        }


        return json_encode($ventes);
    }

    public function update($vente)
    {
        $req = $this->db->prepare('UPDATE ventes SET adresse = :adresse, ville = :ville, code_postal = :code_postal, date = :date, prix = :prix, collaborateur = :collaborateur WHERE id = :id');
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
        $this->db->exec('UPDATE ventes SET actif = 0 WHERE id = ' . $id_vente);

        file_put_contents(
            '../.log',
            date('d/m/Y H:i:s') . ' - ' .
                'UPDATE ventes SET actif = 0 WHERE id = ' . $id_vente . "\n",
            FILE_APPEND
        );
    }
}
