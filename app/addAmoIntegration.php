<?php
require_once("../vendor/autoload.php");

use App\Controllers\AmoController;

if ($_POST) {

    if (isset($_POST["amo_code"])) {
        try {
            $amo_code = $_POST["amo_code"];
            $amo = new AmoController();
            $options = $amo->addAmoIntegration($amo_code);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($options);
        } catch (Exception $e) {
            // log error
            header("HTTP/1.1 403 Api error");
            echo ("Произошла ошибка добавления амо");
        }
    }
}
