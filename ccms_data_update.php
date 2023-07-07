<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: X-Requested-With");

class CCMSDataUpdate{

    public $conn = "";
    public $keyCloakUsersSQL = "";
    public $branchSQL = "  ";
    public $brandSQL = "";
    public $vendorSQL = "";
    public $manageUser = "";
    public $manageVendor = "";
    public $manageBranch = "";
    public $manageBrand = " ";
    public $contractExpirationSQL = "";

    //load users from keycloak
    function loadUsers(){
        $temp = $this->loadSQLData($this->keyCloakUsersSQL);
        $this->manageData($temp ,'users');
    }

    //load data from oracle prodm
    function loadBranch(){
    $temp = $this->loadOraData($this->branchSQL);
    $this->manageData($temp ,'branch');
    }

    //load data from oracle prodm
    function loadBrand(){
        $temp =  $this->loadOraData($this->brandSQL);
        $this->manageData($temp ,'brand');
    }

    //load data from oracle prodm
    function loadVendor(){
        $temp = $this->loadOraData($this->vendorSQL,'vendor');
        $this->manageData($temp ,'vendor');
    }

    //check expiration of the contract
    function checkExpiration(){
        $this->manageData(array('Test'),'expiration');
    }

    function loadOraData($sql,$action = null){
        
            $options = [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_CASE => PDO::CASE_LOWER,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_NUM,
            ];
            $datacontainer = array();
            $ob =  new stdClass();

            $myServer = 'xxxxxxxxx';
            $myDB = 'xxxx';
            $oci_uname = 'xxxx';
            $oci_pass = 'xxxx';

            $tns = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = ".$myServer.")(PORT = 1521)))(CONNECT_DATA=(SERVICE_NAME=".$myDB.")))";
            try {
                $conn = new PDO("oci:dbname=".$tns. ';charset=UTF8', $oci_uname, $oci_pass,$options);
                $sth = $conn->prepare($sql);
                $sth->execute();
                $datacontainer = $sth->fetchAll(PDO::FETCH_ASSOC);
                return $datacontainer;
            } catch(PDOException $e) {
                // echo 'ERROR: ' . $e->getMessage();
                $ob->requeststatus = 'oracle loading data failed';
                $ob->errorinfo = $e->getMessage();
                $returndata = json_encode($ob);
                echo $returndata;
            }
        }

    function loadSQLData($sql){
        $datacontainer = array();
        $ob =  new stdClass();
        
        $datacontainer = array();
        $ob =  new stdClass();
        $db = new PDO('xxxx;port=3307;dbname=xxxx;charset=utf8','xxxx','xxxx');
        $sth = $db->prepare($sql);

        if ($sth->execute()) {

            $datacontainer = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $datacontainer;
        } else {

            $ob->requeststatus = 'mysql loading data failed';
            $ob->errorinfo = $sth->errorInfo();
            $returndata = json_encode($ob);
            echo $returndata;
        }


    }

    function manageData($parameter,$key){

        try {
        //add begin transaction
        $datacontainer = array();
        $ob =  new stdClass();
        $tempParam = array();
        $sql = '';
        $db = new PDO('xxxx','xxxx','xxxx');
        $db->beginTransaction();
        for($x = 0;$x<count($parameter);$x++){
            
            if($key === 'branch'){
                $tempParam = array(":id" => $parameter[$x]['store_id'], ":branch_name" => $parameter[$x]['store_name'], ":operating_unit" => $parameter[$x]['operating_unit']);
                $sql = $this->manageBranch;
            }else if($key === 'brand'){
                $tempParam = array(":id" => $parameter[$x]['brandid'], ":brand_name" => $parameter[$x]['brandname']);
                $sql = $this->manageBrand;
            }else if($key === 'vendor'){
                $tempParam = array(":id" =>  $parameter[$x]['vendor_id'], ":vendor_name" =>  $parameter[$x]['vendor_name'],":fax" =>  $parameter[$x]['fax'],":email" =>  $parameter[$x]['email'], ":phone" =>  $parameter[$x]['phone'], ":owner" =>  $parameter[$x]['owner']);
                $sql = $this->manageVendor;
            }else if($key === 'expiration'){
                $tempParam =  array();
                $sql = $this->contractExpirationSQL;
            }else if($key === 'users'){
                $tempParam =  array(":keycloak_id" => $parameter[$x]['keycloak_id'],":username" => $parameter[$x]['username'],":first_name" => $parameter[$x]['first_name'],":last_name" => $parameter[$x]['last_name'],
                                    ":org_name"=> $parameter[$x]['org_name'],":department_id" => $parameter[$x]['department_id'],":job_desc" => $parameter[$x]['job_desc'],
                                    ":job_id" => $parameter[$x]['job_id'],":org_code" => $parameter[$x]['org_code'],":org_id" => $parameter[$x]['org_id'],
                                    ":origin" => $parameter[$x]['origin'] ,":position_id" => $parameter[$x]['position_id'],":email" => $parameter[$x]['email']);
                $sql = $this->manageUser;
            }
    
            // print_r($tempParam);
            // echo $sql;
            // return;
            // execute query!
            $sth = $db->prepare($sql);
            if($sth->execute($tempParam)){
                    // echo "success";
            }else{
                print_r($sth->errorInfo());
            }
        }
            echo $key." table is updated successfully!";
            $db->commit();
    } catch(PDOException $e) {
            echo "error";
            $db->rollBack();
    }
    
     }
}

// return a good response when success

//insert update data
$updater = new CCMSDataUpdate();
$updater->loadUsers();
// $updater->loadBrand();
// $updater->loadBranch();
// $updater->loadVendor();
// $updater->checkExpiration();
?>