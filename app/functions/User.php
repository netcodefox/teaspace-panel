<?php

$user = new User();

class User extends Controller
{

    public function exists($data)
    {
        $SQL = self::db()->prepare("SELECT * FROM `users` WHERE `email` = :email OR `username` = :username");
        $SQL->execute(array(":email" => $data, ":username" => $data));
        if($SQL->rowCount() == 1){
            return true;
        } else {
            return false;
        }
    }

    public function getState($data)
    {
        $SQL = self::db()->prepare("SELECT * FROM `users` WHERE `email` = :email OR `username` = :username");
        $SQL->execute(array(":email" => $data, ":username" => $data));
        $data = $SQL->fetch(PDO::FETCH_ASSOC);

        return $data['state'];
    }

    public function verifyLogin($data, $password)
    {
        $SQL = self::db()->prepare('SELECT * FROM `users` WHERE `email` = :email OR `username` = :username');
        $SQL->execute(array(":email" => $data, ":username" => $data));
        $data = $SQL->fetch(PDO::FETCH_ASSOC);

        if(password_verify($password, $data['password'])) {
            return true;
        } else {
            return false;
        }
    }

    public function generateSessionToken($data)
    {
        global $helper;
        $session_token = $helper->generateRandomString(30);

        $SQL = self::db()->prepare("UPDATE `users` SET `session_token` = :session_token WHERE `email` = :email OR `username` = :username");
        $SQL->execute(array(":session_token" => $session_token, ":email" => $data, ":username" => $data));

        return $session_token;
    }

    public function create($username, $firstname, $lastname, $email, $password, $state = 'pending', $role = 'customer')
    {
        $cost = 10;
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);

        $db = self::db();
        $SQL = $db->prepare("INSERT INTO `users`(`username`, `firstname`, `lastname`, `email`, `password`, `state`, `role`) VALUES (?,?,?,?,?,?,?)");
        $SQL->execute(array($username, $firstname, $lastname, $email, $hash, $state, $role));
        return $db->lastInsertId();
    }

    public function loggedIn($session_token = null)
    {
        if(is_null($session_token)){
            return false;
        } else {
            return $this->sessionExists($session_token);
        }
    }

    public function sessionExists($session_token)
    {
        $SQL = self::db()->prepare("SELECT * FROM `users` WHERE `session_token` = :session_token");
        $SQL->execute(array(":session_token" => $session_token));
        if($SQL->rowCount() == 1){
            return true;
        } else {
            return false;
        }
    }

    public function getDataBySession($session_token, $data)
    {
        $SQL = self::db()->prepare("SELECT * FROM `users` WHERE `session_token` = :session_token");
        $SQL->execute(array(":session_token" => $session_token));
        $response = $SQL->fetch(PDO::FETCH_ASSOC);

        return $response[$data];
    }

    public function getDataByName($username, $data)
    {
        $SQL = self::db()->prepare("SELECT * FROM `users` WHERE `username` = :username");
        $SQL->execute(array(":username" => $username));
        $response = $SQL->fetch(PDO::FETCH_ASSOC);

        return $response[$data];
    }

    public function getDataById($id, $data)
    {
        $SQL = self::db()->prepare("SELECT * FROM `users` WHERE `id` = :id");
        $SQL->execute(array(":id" => $id));
        $response = $SQL->fetch(PDO::FETCH_ASSOC);

        return $response[$data];
    }

    public function isInTeam($session_token)
    {
        $role = $this->getDataBySession($session_token,'role');

        if($role == 'admin'){
            return true;
        } elseif($role == 'support'){
            return true;
			
		}elseif($role == 'techniker'){
            return true;
		
		} elseif($role == 'content'){
            return true;
        } elseif($role == 'p_support'){
            return true;
        } else {
            return false;
        }
    }

    public function isAdmin($session_token)
    {
        $role = $this->getDataBySession($session_token,'role');

        if($role == 'admin'){
            return true;
		} elseif($role == 'techniker'){
            return true;
		} elseif($role == 'content'){
            return true;
        } else {
            return false;
        }
    }

    public function getIP()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe

                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
    }

    public function serviceCount($user_id)
    {
        $count = 0;

       
        $SQL = self::db()->prepare('SELECT * FROM `teaspeaks` WHERE `user_id` = :user_id AND `deleted_at` IS NULL AND `state` = "active"');
        $SQL->execute(array(":user_id" => $user_id));
        $count = $count + $SQL->rowCount();

       
		
		

        return $count;
    }
	
	
	 public function adminserviceCount($user_id)
    {
        $count = 0;

       

        $SQL = self::db()->prepare('SELECT * FROM `teaspeaks` WHERE  `state` = "active"');
        $SQL->execute();
        $count = $count + $SQL->rowCount();

       

        return $count;
    }


    public function montlyCosts($user_id)
    {
        $price = 0;

       

        $SQL = self::db()->prepare("SELECT * FROM `teaspeaks` WHERE `user_id` = :user_id AND `deleted_at` IS NULL AND `state` = 'active'");
        $SQL->execute(array(":user_id" => $user_id));
        if ($SQL->rowCount() != 0) {
            while ($row = $SQL->fetch(PDO::FETCH_ASSOC)) {
                $price = $price + round($row['price'] * $row['slots'],2);
            }
        }

       

        return $price;
    }

    public function getOpenTickets($user_id)
    {
        $SQL = self::db()->prepare('SELECT * FROM `tickets` WHERE `user_id` = :user_id AND `state` = :state');
        $SQL->execute(array(":user_id" => $user_id, ":state" => 'OPEN'));
        return $SQL->rowCount();
    }
	
	public function getOpenTicketsAdmin($user_id)
    {
        $SQL = self::db()->prepare('SELECT * FROM `tickets` WHERE `state` = :state');
        $SQL->execute(array(":state" => 'OPEN'));
        return $SQL->rowCount();
    }
	public function getcloseTicketsAdmin($user_id)
    {
        $SQL = self::db()->prepare('SELECT * FROM `tickets` WHERE `state` = :state');
        $SQL->execute(array(":state" => 'CLOSED'));
        return $SQL->rowCount();
    }
	
	public function getAllUser($user_id)
    {
        $SQL = self::db()->prepare('SELECT * FROM `users` WHERE `state` = :state');
        $SQL->execute(array(":state" => 'active'));

        return $SQL->rowCount();
    }
	
	
		 public function adminsCount($user_id)
    {
        $count = 0;

        $SQL = self::db()->prepare('SELECT * FROM `users` WHERE  `role` = "admin"');
        $SQL->execute();
        $count = $count + $SQL->rowCount();

        $SQL = self::db()->prepare('SELECT * FROM `users` WHERE  `role` = "content"');
        $SQL->execute();
        $count = $count + $SQL->rowCount();

        $SQL = self::db()->prepare('SELECT * FROM `users` WHERE  `role` = "techniker"');
        $SQL->execute();
        $count = $count + $SQL->rowCount();

        $SQL = self::db()->prepare('SELECT * FROM `users` WHERE  `role` = "p_support"');
        $SQL->execute();
        $count = $count + $SQL->rowCount(); 
		
		$SQL = self::db()->prepare('SELECT * FROM `users` WHERE  `role` = "support"');
        $SQL->execute();
        $count = $count + $SQL->rowCount();

        return $count;
    }
	
	
	
	

    public function addMoney($money, $user_id){
        $SQL = self::db()->prepare("SELECT * FROM `users` WHERE `id` = :id");
        $SQL->execute(array(':id' => $user_id));
        $userData = $SQL -> fetch(PDO::FETCH_ASSOC);

        $newUserMoney = $userData['amount'] + $money;
        $updateUserMoney = self::db()->prepare("UPDATE `users` SET `amount`=:newUserMoney WHERE `id` = :user_id");
        $updateUserMoney->execute(array(":newUserMoney" => $newUserMoney, ":user_id" => $user_id));
    }

    public function removeMoney($price, $user_id){
        $SQL = self::db()->prepare("SELECT * FROM `users` WHERE `id` = :id");
        $SQL->execute(array(':id' => $user_id));
        $userData = $SQL -> fetch(PDO::FETCH_ASSOC);

        $newUserMoney = $userData['amount'] - $price;
        $updateUserMoney = self::db()->prepare("UPDATE `users` SET `amount`=:newUserMoney WHERE `id` = :user_id");
        $updateUserMoney->execute(array(":newUserMoney" => $newUserMoney, ":user_id" => $user_id));
    }

    public function addTransaction($user_id, $amount, $desc){
        $SQL = self::db()->prepare("INSERT INTO `user_transactions`(`user_id`, `amount`, `desc`) VALUES (?,?,?)");
        $SQL->execute(array($user_id, $amount, $desc));
    }

    public function emailBlacklist($mail){
        $SQL = self::db()->prepare("SELECT * FROM `mail_blacklist` WHERE `mail` = :mail");
        $SQL->execute(array(":mail" => $mail));
        if($SQL->rowCount() != 0){
            return true;
        } else {
            return false;
        }
    }

    public function getDataByAffiliateId($id, $data)
    {
        $SQL = self::db()->prepare("SELECT * FROM `users` WHERE `affiliate_id` = :affiliate_id");
        $SQL->execute(array(":affiliate_id" => $id));
        $response = $SQL->fetch(PDO::FETCH_ASSOC);

        return $response[$data];
    }

}
