<?php
namespace App\Request;

use App\Controllers\AmoController;

if ($_POST){

    if (isset($_POST["amo_code"])){
        $amo_code = $_POST["amo_code"];
        $amo = new AmoController();
        $options = $amo->addAmoIntegration($amo_code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($options);
    }
   
}