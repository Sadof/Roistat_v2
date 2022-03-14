<?php

require_once("../vendor/autoload.php");

use App\Controllers\AmoController;

if ($_POST) {
    $check = false;
    $regex = "/^([a-zA-Z0-9\.]+@+[a-zA-Z]+(\.)+[a-zA-Z]{2,3})$/";

    if (!isset($_POST["amo_options"])) {
        $check = true;
    }
    if (!isset($_POST["name"])) {
        $check = true;
    }
    if (!isset($_POST["email"]) || !preg_match($regex, $_POST["email"])) {
        $check = true;
    }
    if (!isset($_POST["phone"]) || strlen($_POST["phone"]) != 11) {
        $check = true;
    }
    if (!isset($_POST["price"])) {
        $check = true;
    }

    if (!$check) {
        try {
            $amo = new AmoController();
            $options = $amo->amoSendDeal($_POST["amo_options"], $_POST["name"], $_POST["email"], $_POST["phone"], $_POST["price"]);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($options);
        } catch (Exception $e) {
            // log error

            header("HTTP/1.1 403 Api error");
            echo ("Произошла ошибка отправки");
        }
    }
}
