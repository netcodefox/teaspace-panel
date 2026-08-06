<?php

use PlanetTeamSpeak\TeamSpeak3Framework\Exception\TeamSpeak3Exception;
use PlanetTeamSpeak\TeamSpeak3Framework\Helper\Convert;
use PlanetTeamSpeak\TeamSpeak3Framework\TeamSpeak3;
use PlanetTeamSpeak\TeamSpeak3Framework\Viewer\Html;

try{
    $SQL = $db->prepare("SELECT * FROM `teaspeak_hosts` WHERE `id` = :id");
    $SQL -> execute(array(":id" => $serverInfos['node_id']));
    $nodeInfos = $SQL->fetch(PDO::FETCH_ASSOC);
    $uri = "serverquery://".$nodeInfos['login_name'].":".$nodeInfos['login_passwort']."@".$nodeInfos['login_ip'].":".$nodeInfos['login_port']."/?server_id=".$serverInfos['sid']."";
    $ts3_query = TeamSpeak3::factory($uri);

			if($ts3_query->isOnline()){
				$serverStatus = 'ONLINE';
			}else{
				$serverStatus = 'OFFLINE';
			}
    #$serverStatus = 'ONLINE';
}catch(TeamSpeak3Exception $e){
    $serverStatus = 'OFFLINE';
}

function getViewer($ts3_query, $picUrl, $serverStatus){
    if($serverStatus == 'ONLINE') {
        try{
            return $ts3_query->getViewer(new Html($picUrl . "viewer/", $picUrl . "flags/", "data:image"));
        }catch(TeamSpeak3Exception $e){
            return 'Es konnten nicht alle Server-Icons geladen werden.';
        }
    } else {
        return 'Teaspeak Server ist Offline';
    }
}

function listTokens($ts3_query, $serverStatus){
    if($serverStatus == 'ONLINE') {
        try {
            return $ts3_query->privilegeKeyList();
        } catch (TeamSpeak3Exception $e) {
            return [];
        }
    }
}

function createToken($ts3_query, $group_id, $description, $serverStatus){

    if($group_id <= '5'){

    } else {
        if ($serverStatus == 'ONLINE') {
            if (empty($description)) {
                $description = NULL;
            }

            return $ts3_query->privilegeKeyCreate('0', $group_id, '0', $description, NULL);
        }
    }
}

function deleteToken($ts3_query, $token, $serverStatus){
    if($serverStatus == 'ONLINE') {
        return $ts3_query->privilegeKeyDelete($token);
    }
}

function getVersion($ts3_query){
    return $ts3_query->version();
}

function getClientsOnline($ts3_query, $serverStatus){
    if($serverStatus == 'ONLINE'){
        return $ts3_query->clientCount();
    } elseif($serverStatus == 'OFFLINE') {
        return '0';
    } else {
        return 'FEHLER';
    }
}

function getOnlineTime($ts3_query){
    return Convert::seconds($ts3_query->virtualserver_uptime, false, "%d Tage %02d:%02d:%02d");
}

function getInfos($ts3_query){
    return $ts3_query->connectionInfo();
}

function getChannelCount($ts3_query){
    return count($ts3_query->channelList());
}

function getServerGroups($ts3_query, $serverStatus, $filter = ["type" => 1]){
    if($serverStatus == 'ONLINE') {
        return $ts3_query->serverGroupList($filter);
    }
}

function getGroupName($ts3_query, $group_id){
    return $ts3_query->serverGroupGetById($group_id);
}
