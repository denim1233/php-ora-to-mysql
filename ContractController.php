<?php
namespace Contract;
use Tools\Helper;
use Variables;
use PDO;
class ContractController{

    function loadContract(){
        $contractFrom = isset($_POST['contract_from']) ? $_POST['contract_from']:'1990-01-01';
        $contractTo = isset($_POST['contract_to']) ? $_POST['contract_to']:'3000-12-30';
        $installedFrom = isset($_POST['installed_from']) ? $_POST['installed_from']:'1990-01-01';
        $installedTo = isset($_POST['installed_to']) ? $_POST['installed_to']:'3000-12-30';
        $storeId = isset($_POST['store_id']) ? $_POST['store_id']: 'NULL';
        $slotType = isset($_POST['slot_type']) ? "'".$_POST['slot_type']."'": 'NULL';
        $processFlag = isset($_POST['process_flag']) ? "'".$_POST['process_flag']."'": 'NULL';
        // $brandId = isset($_POST['brand_id']) ? $_POST['brand_id']: 'NULL';
        $supplierId = isset($_POST['supplier_id']) ? $_POST['supplier_id']: 'NULL';
        $statusId = isset($_POST['approval_status']) ? "'".$_POST['approval_status']."'": 'NULL';
    
        $retunData = array();
        $sql = "
            SELECT * from xxxxxxxxxxxxx
            WHERE 
                TRUNC(CONTRACT_START_DATE) BETWEEN TO_DATE('$contractFrom', 'YYYY-MM-DD') AND TO_DATE('$contractTo', 'YYYY-MM-DD')
            AND STORE_ID = NVL($storeId,STORE_ID)
            AND SLOT_TYPE = NVL($slotType,SLOT_TYPE)
            AND PROCESS_FLAG = NVL($processFlag,PROCESS_FLAG)
            AND
            TRUNC(NVL(DATE_INSTALLED,CONTRACT_START_DATE)) BETWEEN TO_DATE('$installedFrom', 'YYYY-MM-DD') AND TO_DATE('$installedTo', 'YYYY-MM-DD')
            AND SUPPLIER_ID = NVL($supplierId,SUPPLIER_ID)
            AND ATTRIBUTE1 = NVL($statusId,ATTRIBUTE1)
        ";

        $prodconn = oci_connect('appsro', 'appsro', '192.168.8.190/AUD');
        $result = oci_parse($prodconn, $sql);
        oci_execute($result);

        while($data = oci_fetch_array($result,OCI_BOTH )){ 
            array_push($retunData, $data);
        }
        ob_end_clean();
        echo json_encode($retunData);
        
    }

    function stageData(){
        $var = new Variables();
        $sqlData = Helper::manageSQLData($var->sqlExtractStagingData);
    }

    
    public function uploadToOracle()
    {
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_CASE => PDO::CASE_LOWER,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_NUM,
        ];

        $mysqlConn = new PDO(Helper::LOAD_INI('SQLCON', 'dsn'), Helper::LOAD_INI('SQLCON', 'username'), Helper::LOAD_INI('SQLCON', 'password'));
        $oracleConn = new PDO(Helper::LOAD_INI('ORACON', 'connstr'), Helper::LOAD_INI('ORACON', 'username'), Helper::LOAD_INI('ORACON', 'password'),$options);
        $var = new Variables();
        $sqlData = Helper::loadSQLData($var->sqlGetStagingData);

        // lacking data
        // store_id
        // process_flag

        // what is attribute2?
        // the data shows that there is a data in attribute2
        // when the status of the contracts is cancelled or expired
    
        try {
            $mysqlConn->beginTransaction();
            $oracleConn->beginTransaction();
            for($x=0;$x<count($sqlData);$x++){

                $parentNo = ($sqlData[$x]['parentcontractno'] === " " ? 'null' : $sqlData[$x]['parentcontractno']);
                
                // (select max(contract_id) + 1 contract_id from apps.xxch_contracts) 
                // save the id of your system in attribute15

                $oraQuery = "INSERT INTO xxxxx
   
                VALUES( ".intval($sqlData[$x]['contract_id']).",
                        '".$sqlData[$x]['contract_number']."',
                        ".$sqlData[$x]['supplier_id'].",
                        '".$sqlData[$x]['store_name']."',
                        '".$sqlData[$x]['slot_type']."',
                        '".$sqlData[$x]['slot_size']."',
                        '".strtoupper(date_format(date_create($sqlData[$x]['date_installed']),"d-M-y"))."',
                        '".strtoupper(date_format(date_create($sqlData[$x]['contract_start_date']),"d-M-y"))."',
                        '".strtoupper(date_format(date_create($sqlData[$x]['contract_end_date']),"d-M-y"))."',
                        '".$sqlData[$x]['remarks']."',
                        ".$sqlData[$x]['monthly_rental'].",
                        ".$sqlData[$x]['contract_total_months'].",
                        ".$sqlData[$x]['contract_price'].",
                        '".$sqlData[$x]['brand']."',
                        '".$sqlData[$x]['material']."',
                        '".$sqlData[$x]['mode_of_payment']."',
                        '".$sqlData[$x]['temrs_of_contract']."',
                        '".$parentNo."',
                        '".strtoupper(date_format(date_create($sqlData[$x]['creation_date']),"d-M-y"))."',
                        0,
                        '".$sqlData[$x]['attribute1']."',
                        ".$sqlData[$x]['store_ou'].",
                        '".$sqlData[$x]['supplier_name']."',
                        '".$sqlData[$x]['contract_type']."',
                        ".$sqlData[$x]['store_id'].",
                        'N'
                        )";
                        // var_dump($sqlData[$x]['parentcontractno']);
                        // echo "<br>";
                        // echo "<br>";
                        // echo $oraQuery;
                        // return;
                $oraStmt = $oracleConn->prepare($oraQuery);
                if ($oraStmt->execute()) {
                    $updateMyStaging = "UPDATE xxxxxx SET is_interfaced = 1 WHERE contract_id = '" . $sqlData[$x]['contract_id'] . "'";
                    $mysqlConn->query($updateMyStaging);
                }
            }

            $oracleConn->commit();
            $mysqlConn->commit();
        } catch (PDOException $exception) {
            $oracleConn->rollBack();
            $mysqlConn->rollBack();
            echo $exception->getMessage();
        }
    }

}


?>