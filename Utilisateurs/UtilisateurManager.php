<?php

class UtilisateurManager
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getUtilisateur($user)
    {
        $sql = sprintf(
            "SELECT COUNT(*) as success FROM utilisateurs WHERE email = '%s' AND mot_de_passe = '%s';",
            $user->getEmail(),
            $user->getPassword()
        );

        $req = $this->db->prepare($sql);

        $req->execute();
        $datas = $req->fetch(PDO::FETCH_ASSOC);
        $req->closeCursor();

        return json_encode($datas);
    }
}
