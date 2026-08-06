<?php

use PlanetTeamSpeak\TeamSpeak3Framework\Exception\TeamSpeak3Exception;
use PlanetTeamSpeak\TeamSpeak3Framework\TeamSpeak3;

$tea = new Tea();

class Tea extends Controller
{

    function getStatus($node_id, $ts_port)
    {

        try {
            $SQL = self::db()->prepare("SELECT * FROM `teaspeak_hosts` WHERE `id` = :id");
            $SQL->execute(array(":id" => $node_id));
            $nodeInfos = $SQL->fetch(PDO::FETCH_ASSOC);

            $uri = "serverquery://" . $nodeInfos['login_name'] . ":" . $nodeInfos['login_passwort'] . "@" . $nodeInfos['login_ip'] . ":" . $nodeInfos['login_port'] . "/?server_port=" . $ts_port;
            $ts3_VirtualServer = TeamSpeak3::factory($uri);

            if($ts3_VirtualServer->isOnline()){return "ONLINE";}else{return "OFFLINE";}

            $ts3_VirtualServer->serverDeselect();

        } catch (TeamSpeak3Exception $e) {
            return 'OFFLINE';
        }

    }

    function startServer($node_id, $ts_port, $sid)
    {

        if ($this->getStatus($node_id, $ts_port) == 'OFFLINE') {

            $SQL = self::db()->prepare("SELECT * FROM `teaspeak_hosts` WHERE `id` = :id");
            $SQL->execute(array(":id" => $node_id));
            $nodeInfos = $SQL->fetch(PDO::FETCH_ASSOC);

            $ts3_ServerInstance = TeamSpeak3::factory("serverquery://" . $nodeInfos['login_name'] . ":" . $nodeInfos['login_passwort'] . "@" . $nodeInfos['login_ip'] . ":" . $nodeInfos['login_port'] . "/");

            $ts3_ServerInstance->serverStart($sid);

        }

    }

    function stopServer($node_id, $ts_port, $sid)
    {

        if ($this->getStatus($node_id, $ts_port) == 'ONLINE') {

            sleep(2);

            $SQL = self::db()->prepare("SELECT * FROM `teaspeak_hosts` WHERE `id` = :id");
            $SQL->execute(array(":id" => $node_id));
            $nodeInfos = $SQL->fetch(PDO::FETCH_ASSOC);

            $ts3_ServerInstance = TeamSpeak3::factory("serverquery://" . $nodeInfos['login_name'] . ":" . $nodeInfos['login_passwort'] . "@" . $nodeInfos['login_ip'] . ":" . $nodeInfos['login_port'] . "/");
            $ts3_ServerInstance->serverStop($sid);

        }

    }

    function createServer($node_id, $max_slots, $server_port)
    {

        $SQL = self::db()->prepare("SELECT * FROM `teaspeak_hosts` WHERE `id` = :id");
        $SQL->execute(array(":id" => $node_id));
        $nodeInfos = $SQL->fetch(PDO::FETCH_ASSOC);

        $ts3_ServerInstance = TeamSpeak3::factory("serverquery://" . $nodeInfos['login_name'] . ":" . $nodeInfos['login_passwort'] . "@" . $nodeInfos['login_ip'] . ":" . $nodeInfos['login_port'] . "/");

        $new_sid = $ts3_ServerInstance->serverCreate(array(
            "virtualserver_name" => "Teaspeak hosted by " . $this->siteName(),
            "virtualserver_maxclients" => $max_slots,
            "virtualserver_port" => $server_port,
        ));

        return $new_sid;

    }

    function deleteServer($node_id, $sid)
    {

        $SQL = self::db()->prepare("SELECT * FROM `teaspeak_hosts` WHERE `id` = :id");
        $SQL->execute(array(":id" => $node_id));
        $nodeInfos = $SQL->fetch(PDO::FETCH_ASSOC);

        $ts3_ServerInstance = TeamSpeak3::factory("serverquery://" . $nodeInfos['login_name'] . ":" . $nodeInfos['login_passwort'] . "@" . $nodeInfos['login_ip'] . ":" . $nodeInfos['login_port'] . "/");

        $ts3_ServerInstance->serverDelete($sid);

    }

    public function changeSlots($node_id, $ts_port, $slots)
    {

        $SQL = self::db()->prepare("SELECT * FROM `teaspeak_hosts` WHERE `id` = :id");
        $SQL->execute(array(":id" => $node_id));
        $nodeInfos = $SQL->fetch(PDO::FETCH_ASSOC);

        $uri = "serverquery://" . $nodeInfos['login_name'] . ":" . $nodeInfos['login_passwort'] . "@" . $nodeInfos['login_ip'] . ":" . $nodeInfos['login_port'] . "/?server_port=" . $ts_port;
        $ts3_VirtualServer = TeamSpeak3::factory($uri);

        $ts3_VirtualServer->modify([
            'virtualserver_maxclients' => $slots,
        ]);

    }

}
