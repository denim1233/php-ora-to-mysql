<?php

namespace Slot;

class SlotController{
     function manageData(){
        // $db = oci_connect( "appsro", "appsro", "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.8.192)(PORT = 1521)))(CONNECT_DATA=(SID=DEV)))" );


      
        // $compiled = oci_parse($db $sql);
        // oci_bind_by_name($compiled, ':SLOT_ID', $slotId);
        // oci_bind_by_name($compiled, ':STORE_ID', $storeId);
        // oci_bind_by_name($compiled, ':STORE_NAME', $storeName);
        // oci_bind_by_name($compiled, ':CONTRACT_ID', $contractId);
        // oci_bind_by_name($compiled, ':SLOT_TYPE', $slotType);
        // oci_bind_by_name($compiled, ':SLOT_SIZE', $slotSize);
        // oci_bind_by_name($compiled, ':CONTRACT_NUMBER', $contractNumber);
        // oci_bind_by_name($compiled, ':CONTRACT_START_DATE', $contractStartDate);
        // oci_bind_by_name($compiled, ':SLOT_STATUS', $slotStatus);
        // oci_bind_by_name($compiled, ':SLOT_POSITION', $slotPosition);
        // oci_bind_by_name($compiled, ':CREATION_DATE', $creationDate);
        // oci_bind_by_name($compiled, ':CREATED_BY', $createdBy);
        // oci_bind_by_name($compiled, ':LAST_UPDATE_LOGIN', $lastUpdateLogin);
        // oci_bind_by_name($compiled, ':LAST_UPDATE_BY', $lastUpdateBy);
        // oci_bind_by_name($compiled, ':LAST_UPDATE_DATE', $lastUpdateDate);
        // oci_bind_by_name($compiled, ':SLOT_OPERATING_UNIT', $slotOperatingUnit);
        // oci_bind_by_name($compiled, ':PCF_NUMBER', $pcfNumber);
        // oci_bind_by_name($compiled, ':SLOT_AVAILABITIY', $slotAvailability);
        // oci_bind_by_name($compiled, ':SLOT_SUPPLIER_NAME', $slotSupplierName);
        // oci_bind_by_name($compiled, ':SLOT_SUPPLIER_ID', $slotSupplierId);
        // oci_execute($compiled);

    }

     function getData(){

        echo "get slot data";
        return;

    }

}

?>

    <!-- $slotId = $_POST['slot_id'];
    $storeId = $_POST['store_id'];
    $storeName = $_POST['store_name'];
    $contractId = $_POST['contract_id'];
    $slotType = $_POST['slot_type'];
    $slotSize = $_POST['slot_size'];
    $contractNumber = $_POST['contract_number'];
    $contractStartDate = strtoupper(date_format(date_create($_POST['contract_start_date']),"d-M-y"));
    $slotStatus = $_POST['slot_status'];
    $slotPosition = $_POST['slot_position'];
    $creationDate = strtoupper(date_format(date_create($_POST['creation_date']),"d-M-y"));
    $createdBy = $_POST['created_by'];
    $lastUpdateLogin = $_POST['last_update_login'];
    $lastUpdateBy = $_POST['last_update_by'];
    $lastUpdateDate = strtoupper(date_format(date_create($_POST['last_update_date']),"d-M-y"));
    $slotOperatingUnit = $_POST['slot_operating_unit'];
    $pcfNumber = $_POST['pcf_number'];
    $slotAvailability = $_POST['slot_availability'];
    $slotSupplierName = $_POST['slot_supplier_name'];
    $slotSupplierId = $_POST['slot_supplier_id']; -->