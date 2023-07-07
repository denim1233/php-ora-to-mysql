<?php

namespace Tools;
use PDO;
use stdClass;
class Helper {

    public function manageSQLData($sql,$parameter = null){
        $datacontainer = array();
        $ob =  new stdClass();
        // $db = new PDO(Helper::LOAD_INI('SQLCON', 'dsn'));
        $db = new PDO(Helper::LOAD_INI('SQLCON', 'dsn'), Helper::LOAD_INI('SQLCON', 'username'), Helper::LOAD_INI('SQLCON', 'password'));

        $sth = $db->prepare($sql);

        if ($sth->execute($parameter)) {

            $ob->requeststatus = 'success';
            $returndata = json_encode($ob);
            echo $returndata;
        } else {
            ob_clean();
            $ob->requeststatus = 'mysql loading data failed';
            $ob->errorinfo = $sth->errorInfo();
            $ob->query = $sql;
            $returndata = json_encode($ob);
            echo $returndata;
        }
    }

    public function loadSQLData($sql,$parameter = null){
        $datacontainer = array();
        $ob =  new stdClass();
        $db = new PDO(Helper::LOAD_INI('SQLCON', 'dsn'), Helper::LOAD_INI('SQLCON', 'username'), Helper::LOAD_INI('SQLCON', 'password'));

        $sth = $db->prepare($sql);

        if ($sth->execute($parameter)) {
            $datacontainer = $sth->fetchAll(PDO::FETCH_ASSOC);
            return $datacontainer;
        } else {

            $ob->requeststatus = 'mysql loading data failed';
            $ob->errorinfo = $sth->errorInfo();
            $returndata = json_encode($ob);
            echo $returndata;
        }
    }

    public function manageContract($sql,$data,$compiled){

   

            oci_bind_by_name($compiled, ':CONTRACT_ID', intval($data['contract_id']));
            oci_bind_by_name($compiled, ':CONTRACT_NUMBER', $data['contract_number']);
            oci_bind_by_name($compiled, ':SUPPLIER_ID', $data['supplier_id']);
            oci_bind_by_name($compiled, ':STORE_NAME', $data['store_name']);
            oci_bind_by_name($compiled, ':SLOT_TYPE', $data['slot_type']);
            oci_bind_by_name($compiled, ':SLOT_SIZE', $data['slot_size']);
            // oci_bind_by_name($compiled, ':CONTRACT_STATUS', null);
            oci_bind_by_name($compiled, ':DATE_INSTALLED', strtoupper(date_format(date_create($data['date_installed']),"d-M-y")));
            oci_bind_by_name($compiled, ':CONTRACT_START_DATE', strtoupper(date_format(date_create($data['contract_start_date']),"d-M-y")));
            oci_bind_by_name($compiled, ':CONTRACT_END_DATE', strtoupper(date_format(date_create($data['contract_end_date']),"d-M-y")));
            oci_bind_by_name($compiled, ':REMARKS', $data['remarks']);
            oci_bind_by_name($compiled, ':MONTHLY_RENTAL', $data['monthly_rental']);
            oci_bind_by_name($compiled, ':CONTRACT_TOTAL_MONTHS', $data['contract_total_months']);
            // oci_bind_by_name($compiled, ':CONTRACT_PRICE', null);
            // oci_bind_by_name($compiled, ':PRODUCTION_COST', null);
            oci_bind_by_name($compiled, ':BRAND', $data['brand']);
            // oci_bind_by_name($compiled, ':NO_OF_PIECES', null);
            oci_bind_by_name($compiled, ':MATERIAL', $data['material']);
            oci_bind_by_name($compiled, ':MODE_OF_PAYMENT', $data['mode_of_payment']);
            oci_bind_by_name($compiled, ':TERMS_OF_CONTRACT', $data['temrs_of_contract']);
            oci_bind_by_name($compiled, ':PARENTCONTRACTNO', $data['parentcontractno']);
            oci_bind_by_name($compiled, ':CREATION_DATE', strtoupper(date_format(date_create($data['creation_date']),"d-M-y")));
            oci_bind_by_name($compiled, ':CREATED_BY', 1637);
            // oci_bind_by_name($compiled, ':LAST_UPDATE_LOGIN',null);
            // oci_bind_by_name($compiled, ':LAST_UPDATE_BY', null);
            // oci_bind_by_name($compiled, ':LAST_UPDATE_DATE', null);
            oci_bind_by_name($compiled, ':ATTRIBUTE1', $data['attribute1']);
            oci_bind_by_name($compiled, ':STORE_OU', $data['store_ou']);
            oci_bind_by_name($compiled, ':SUPPLIER_NAME', $data['supplier_name']);
            oci_bind_by_name($compiled, ':PROCESS_FLAG', 'N');
            oci_bind_by_name($compiled, ':CONTRACT_TYPE', $data['contract_type']);
            $x = oci_execute($compiled,OCI_DEFAULT);

    }

     function LOAD_INI($VAR_GROUP,$VAR_NAME){

        $ini_array = parse_ini_file(getcwd()."/app.ini", true );
        return $ini_array[$VAR_GROUP][$VAR_NAME];

    }


}
?>


<!-- $data = array(
            'contract_id' => $contractId, 'contract_number' => $contractNumber, 'supplier_id' => $supplierId,
            'store_name' => $storeName, 'slot_type' => $slotType,  'slot_size' => $slotSize, 'contract_status' => $contractStatus,
            'date_installed' => $dateInstalled, 'contract_start_date' => $contractStartDate, 'contract_end_date' => $contractEndDate,
            'remarks' => $remarks, 'monthly_rental' => $monthlyRental, 'contract_total_monthls' => $contractTotalMonths,
            'contract_price' => $contractPrice, 'production_cost' => $productionCost, 'brand' => $brand, 'no_of_pieces' => $noOfPiece,
            'material' => $material, 'mode_of_payment' => $modeOfPayment, 'terms_of_contract' => $termsOfContract, 'parent_contract_no' => $parentContractNo,
            'creation_date' => $creationDate, 'created_by' => $createdBy, 'last_update_login' => $lastUpdateLogin, 'last_update_date' => $lastUpdateDate,
            'attribute1' => $attribute1, 'store_ou' => $storeOu, 'supplier_name' => $supplierName, 'contract_type' => $contractType, 'contract_parent_id' => $contractParentId
            ); -->

            <!-- echo json_encode( $data ); -->