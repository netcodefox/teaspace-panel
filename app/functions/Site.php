<?php

$site = new Site();
$db = $helper->db();

class Site extends Controller
{
    public function validateCaptcha($captacha) {
        $fields_string = '';
        $fields = array(
            'secret' => $this->grecaptchaSecret(),
            'response' => $captacha
        );
        foreach($fields as $key=>$value)
            $fields_string .= $key . '=' . $value . '&';
        $fields_string = rtrim($fields_string, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    public function formatDate($datetime)
    {
        $date = new DateTime($datetime, new DateTimeZone('Europe/Berlin'));
        return $date->format('d.m.Y H:i:s');
    }

    public function getRecaptcha()
    {
        $captach = '<script src="https://www.google.com/recaptcha/api.js?render='.$this->grecaptchaSiteKey().'"></script>
<script>
    var grecaptchaSiteKey = "'.$this->grecaptchaSiteKey().'";

    var _RECAPTCHA = _RECAPTCHA || {};

    _RECAPTCHA.init = function() {
        grecaptcha.ready(function() {
            grecaptcha.execute(grecaptchaSiteKey, {action: \'homepage\'}).then(function(token) {
                if (jQuery(form)[0]) {
                    if (jQuery(".grecaptchaToken")[0]) {
                        jQuery(form).find(".grecaptchaToken").remove();
                    }

                    jQuery(form).append(\'<input type="hidden" class="grecaptchaToken" name="grecaptchaToken" id="grecaptchaToken" value="\' + token + \'" />\');
                }
            });
        });
    };

    _RECAPTCHA.init();

</script>';

        return $captach;
    }

    public function currentUrl()
    {
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        return $actual_link;
    }

    public function getIntervalFactor($interval){

        $IF = $interval / 30;
        return $IF;

    }

    public function addTransaction($user_id, $gateway, $state, $amount, $desc, $tid){
        $SQL = self::db()->prepare("INSERT INTO `transactions`(`user_id`, `gateway`, `state`, `amount`, `desc`, `tid`) VALUES (:user_id,:gateway,:state,:amount,:desc,:tid)");
        $SQL->execute(array(':user_id' => $user_id, ':gateway' => $gateway, ':state' => $state, ':amount' => $amount, ':desc' => $desc, ':tid' => $tid));
    }

    public function isTS3PortAviable($node_id, $port)
    {
        $SQL = self::db()->prepare("SELECT * FROM `teamspeaks` WHERE `node_id` = :node_id AND `teamspeak_port` = :port AND `deleted_at` IS NULL");
        $SQL->execute(array(":node_id" => $node_id, ":port" => $port));
        if ($SQL->rowCount() == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isTeaPortAviable($node_id, $port)
    {
        $SQL = self::db()->prepare("SELECT * FROM `teaspeaks` WHERE `node_id` = :node_id AND `teaspeak_port` = :port AND `deleted_at` IS NULL");
        $SQL->execute(array(":node_id" => $node_id, ":port" => $port));
        if ($SQL->rowCount() == 0) {
            return true;
        } else {
            return false;
        }
    }


	 function formatDateWithoutTime($date)
    {
        $date = new DateTime($date, new DateTimeZone('Europe/Berlin'));
        return $date->format('d.m.Y');
    }


    public function getProductPrice($product_id)
    {
        $SQL = self::db()->prepare('SELECT * FROM `product_prices` WHERE `name` = :name');
        $SQL->execute(array(":name" => $product_id));
        $response = $SQL->fetch(PDO::FETCH_ASSOC);

        return $response['price'];
    }

    public function getDiffInDays($dateTime)
    {
        $datetime1 = new DateTime('now', new DateTimeZone('Europe/Berlin'));
        $datetime2 = new DateTime($dateTime, new DateTimeZone('Europe/Berlin'));
        $interval = $datetime1->diff($datetime2);
        return $interval->format('%a');
    }

    public function getProduct($id, $data)
    {
        $SQL = self::db()->prepare('SELECT * FROM `store_product_entries` WHERE `id` = :id');
        $SQL->execute(array(":id" => $id));
        $response = $SQL->fetch(PDO::FETCH_ASSOC);
        if($SQL->rowCount() == 1){
            return $response[$data];
        } else {
            return false;
        }
    }
	
	public function userHaveValidProfile($userid){

        $SQL = self::db()->prepare("SELECT * FROM `users` WHERE `id` = :id");
        $SQL->execute(array(':id' => $userid));
        $userInfos = $SQL -> fetch(PDO::FETCH_ASSOC);

        if($userInfos['firstname'] == NULL){
            return FALSE;
        } elseif($userInfos['lastname'] == NULL) {
            return FALSE;
        } elseif($userInfos['postcode'] == NULL) {
            return FALSE;
        } elseif($userInfos['street'] == NULL) {
            return FALSE;
        } elseif($userInfos['city'] == NULL) {
            return FALSE;
        } else {
            return TRUE;
        }

    }

}