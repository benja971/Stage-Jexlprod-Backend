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
        $sql = "select count(*) as success from utilisateurs where email = '" . $user->getEmail() . "' and mot_de_passe = '" . $user->getPassword() . "'\n";
        $req = $this->db->prepare($sql);

        $req->execute();
        $datas = $req->fetch(PDO::FETCH_ASSOC);
        $req->closeCursor();

        return json_encode($datas);
    }
}
