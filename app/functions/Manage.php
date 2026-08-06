<?php

$manage = new Manage();

class Manage extends Controller
{

    public function songName($data)
    {
        try {
            global $request;
            $response = $request->bot($data, '/song')->Title;
        } catch (Exception $e) {
            $response = 'Aktuell läuft kein Song';
        }

        return ($response);
    }

}