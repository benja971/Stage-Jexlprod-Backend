<?php

class SecurityManager
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getIfBannedIp($ip)
    {
        $sql = sprintf("SELECT COUNT(*) AS succes FROM ip_bannies WHERE ip_utilisateur = INET_ATON('%s');", $ip);
        $req = $this->db->prepare($sql);
        $req->execute();

        $is_banned_ip = $req->fetchAll();

        return $is_banned_ip;
    }

    public function AddEchec($ip)
    {
        $sql = sprintf(
            "INSERT INTO echecs_connexion(ip_utilisateur, date) VALUES (INET_ATON('%s'), NOW());",
            $ip,
        );

        $req = $this->db->prepare($sql);

        $req->execute();
        $req->closeCursor();
    }

    public function clearEchecCompteur($ip)
    {
        $sql = sprintf("DELETE FROM echecs_connexion WHERE ip_utilisateur = INET_ATON('%s');", $ip);

        $req = $this->db->prepare($sql);

        $req->execute();
        $req->closeCursor();
    }

    public function VerifNeedBanIp($ip)
    {

        $sql = sprintf(
            "INSERT INTO ip_bannies (ip_utilisateur) SELECT ip_utilisateur from echecs_connexion WHERE (select count(*) from echecs_connexion where ip_utilisateur = INET_ATON('%s') AND TIMEDIFF(now(), date) <= '00:05:00') >= 20 GROUP BY ip_utilisateur",
            $ip,
        );

        $req = $this->db->prepare($sql);

        $req->execute();
        $req->closeCursor();
    }
}
