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

    public function getList($annee)
    {
        $ventes = [];
        $req = $this->db->query('SELECT * FROM ventes WHERE date LIKE "' . $annee . '-%"');
        while ($vente = $req->fetch(PDO::FETCH_ASSOC)) {
            $ventes[] = [
                'id' => $vente['id'],
                'adresse' => $vente['adresse'],
                'ville' => $vente['ville'],
                'code_postal' => $vente['code_postal'],
                'date' => $vente['date'],
                'prix' => $vente['prix'],
                'collaborateur' => $vente['collaborateur'],
            ];
        }

        return json_encode($ventes);
    }
}
