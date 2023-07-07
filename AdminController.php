<?php
header("Access-Control-Allow-Origin: Allow access to the specific origins");
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH');
header('Access-Control-Allow-Headers: token, Content-Type,X-Requested-With');

include_once 'Helper.php';
include_once 'Variables.php';
include_once 'ContractController.php';

use Slot\SlotController;
use Tools\Helper;
use Contract\ContractController;


class AdminController{

 function Main(){

    if(!isset($_POST['action'])){
      $json = file_get_contents('php://input');
      $data = json_decode(   $json , true);
      $_POST = $data;
    }

    switch ($_POST['action']) {
        case 'upload_data':
          ContractController::stageData();
          ContractController::uploadToOracle();
          break;
        case 'stage_data':    
          // ContractController::stageData();
          break;
        case 'rpt_contracts':
          ContractController::loadContract();
          break;
        default:

      } 
}

}

$Admin = new AdminController();
$Admin->Main();

?>