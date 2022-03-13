<?php
namespace App;
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: POST,GET,OPTIONS');
// header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');


use App\Controllers\AmoController;

print_r($_POST);
if ($_POST){
    if (isset($_POST["amo_code"])){
        $amo_code = $_POST["amo_code"];
    }
    $amo = new AmoController();
    $amo->addAmoIntegration($amo_code);
}