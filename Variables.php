<?php

class Variables{
    public $sqlUpdateStg = "
    update 
        xxch_contracts_stg
    set
        oracle_staging_tbl = 'copied'
    where 
        contract_id = :CONTRACTID
    "; 
    public $sqlGetStagingData = "SELECT * FROM xxch_contracts_stg WHERE is_interfaced is null and mode_of_payment != 'Free of charge'";
    public $sqlExtractStagingData = "
                                    insert into xxch_contracts_stg
                                    SELECT 
                                    cmc.id,
                                    cmc.contract_number,
                                    cmc.lesse,
                                    cms.branch_id store_id,
                                    cmb.branch_name store_name,
                                    cmt.type_name,
                                    CONCAT(cmts.length,'x',cmts.width ,' ',com.uom_name) as size,
                                    null contract_status,
                                    cmc.installation_date date_installed,
                                    cmc.start_date contract_start_date,
                                    cmc.end_date contract_end_date,
                                    cmc.remarks,
                                    (SELECT monthly_rental from cms_rental where id = cmc.rental_id) AS monthly_rental,
                                    cmc.no_of_months contract_total_months,
                                    cmc.contract_price contract_price,
                                    null production_cost,
                                    cmbb.brand_name,
                                    null no_of_pieces,
                                    cmt.material,
                                    cmop.mode_of_payment,
                                    cmc.terms_of_contract,
                                    null ar_number,
                                    null ar_status,
                                    cmc.remarks inhouse_remarks,
                                    null old_con_no,
                                    cmc.parent_number,
                                    cmc.created_at,
                                    1367 created_by,
                                    null last_update_login,
                                    cmc.updated_by last_update_by,
                                    cmc.updated_at last_update_date,
                                    (SELECT status_name from cms_status where id = cmc.approval_status) AS attribute1,
                                    null attribute2,
                                    null attribute3,
                                    null attribute4,
                                    null attribute5,
                                    null attribute6,
                                    null attribute7,
                                    null attribute8,
                                    null attribute9,
                                    null attribute10,
                                    null attribute11,
                                    null attribute12,
                                    null attribute13,
                                    null attribute14,
                                    null attribute15,
                                    cmb.operating_unit store_ou,
                                    cmv.vendor_name,
                                    'N' process_flag,
                                    null error_message,
                                    (SELECT status_name from cms_status where id = cmc.contract_type) AS contract_type,
                                    null parent_contract_id,
                                    null is_interfaced,
                                    null
                                    FROM cms_contract cmc
                                    inner join cms_slot cms on cms.id = cmc.slot_id
                                    inner join cms_branch cmb on cmb.id = cms.branch_id
                                    inner join cms_type cmt on cmt.id = cms.type_id
                                    inner join cms_type_size cmts on cmts.id = cms.type_id
                                    inner join cms_uom com on com.id = cmt.uom_id
                                    inner join cms_brand cmbb on cmbb.id = cmc.brand_id
                                    inner join cms_modeofpayment cmop on cmop.id = cmc.mode_of_payment
                                    inner join cms_vendors cmv on cmv.id = cmc.lesse
                                    where cmc.approval_status = 6 and is_staged = 0;

                                update cms_contract
                                set 
                                    is_staged = 1
                                where 
                                    is_staged = 0
                                and 
                                    approval_status = 6;";

    public $oraContractInsert = '
    INSERT INTO APPS.XXCH_CONTRACTS
    (CONTRACT_ID,CONTRACT_NUMBER,SUPPLIER_ID,STORE_NAME,SLOT_TYPE,SLOT_SIZE,DATE_INSTALLED,CONTRACT_START_DATE,CONTRACT_END_DATE,
    REMARKS,MONTHLY_RENTAL,CONTRACT_TOTAL_MONTHS,CONTRACT_PRICE,BRAND,MATERIAL,MODE_OF_PAYMENT,TEMRS_OF_CONTRACT,
    PARENTCONTRACTNO, CREATION_DATE, CREATED_BY,ATTRIBUTE1,STORE_OU,SUPPLIER_NAME,CONTRACT_TYPE,CONTRACT_PARENT_ID)
    VALUES(:CONTRACT_ID,:CONTRACT_NUMBER,:SUPPLIER_ID,:STORE_NAME,:SLOT_TYPE,:SLOT_SIZE,:DATE_INSTALLED,:CONTRACT_START_DATE,:CONTRACT_END_DATE,
    :REMARKS,:MONTHLY_RENTAL,:CONTRACT_TOTAL_MONTHS,:CONTRACT_PRICE,:BRAND,:MATERIAL,:MODE_OF_PAYMENT,:TERMS_OF_CONTRACT,
    :PARENTCONTRACTNO,:CREATION_DATE,:CREATED_BY,:ATTRIBUTE1,:STORE_OU,:SUPPLIER_NAME,:CONTRACT_TYPE,:CONTRACT_PARENT_ID)';

    public $oraContractUpdate = '
    UPDATEpos
    XXCH_CONTRACTS
    SET
    CONTRACT_NUMBER = :CONTRACT_NUMBER,
    SUPPLIER_ID = :SUPPLIER_ID,
    STORE_NAME = :STORE_NAME,
    SLOT_TYPE = :SLOT_TYPE,
    SLOT_SIZE = :SLOT_SIZE,
    CONTRACT_STATUS = :CONTRACT_STATUS,
    DATE_INSTALLED = :DATE_INSTALLED,
    CONTRACT_START_DATE = :CONTRACT_START_DATE,
    CONTRACT_END_DATE = :CONTRACT_END_DATE,
    REMARKS = :REMARKS,
    MONTHLY_RENTAL = :MONTHLY_RENTAL,
    CONTRACT_TOTAL_MONTHS = :CONTRACT_TOTAL_MONTHS,
    CONTRACT_PRICE = :CONTRACT_PRICE,
    PRODUCTION_COST = :PRODUCTION_COST,
    BRAND = :BRAND,
    NO_OF_PIECES = :NO_OF_PIECES,
    MATERIAL = :MATERIAL,
    MODE_OF_PAYMENT = :MODE_OF_PAYMENT,
    TERMS_OF_CONTRACT = :TERMS_OF_CONTRACT,
    PARENTCONTRACTNO = :PARENTCONTRACTNO,
    CREATION_DATE = :CREATION_DATE,
    CREATED_BY = :CREATED_BY,
    LAST_UPDATE_LOGIN  = :LAST_UPDATE_LOGIN,
    LAST_UPDATE_BY = :LAST_UPDATE_BY,
    LAST_UPDATE_DATE = :LAST_UPDATE_DATE,
    ATTRIBUTE1 = :ATTRIBUTE1,
    STORE_OU = :STORE_OU,
    SUPPLIER_NAME = :SUPPLIER_NAME,
    CONTRACT_TYPE = :CONTRACT_TYPE,
    CONTRACT_PARENT_ID = :CONTRACT_PARENT_ID
    WHERE 
    CONTRACT_ID =  :CONTRACT_ID
    ';

    public $oraSlotInsert = '
    INSERT INTO XXCH_SLOTS
    (SLOT_ID,STORE_ID,STORE_NAME,CONTRACT_ID,SLOT_TYPE,SLOT_SIZE,CONTRACT_NUMBER,CONTRACT_START_DATE,SLOT_STATUS,SLOT_POSITION,CREATION_DATE,CREATED_BY,
    LAST_UPDATE_LOGIN,LAST_UPDATE_BY,LAST_UPDATE_DATE,SLOT_OPERATING_UNIT,PCF_NUMBER,SLOT_AVAILABITIY,SLOT_SUPPLIER_NAME,SLOT_SUPPLIER_ID)
    VALUES
    (:SLOT_ID,:STORE_ID,:STORE_NAME,:CONTRACT_ID,:SLOT_TYPE,:SLOT_SIZE,:CONTRACT_NUMBER,:CONTRACT_START_DATE,:SLOT_STATUS,:SLOT_POSITION,:CREATION_DATE,:CREATED_BY,
    :LAST_UPDATE_LOGIN,:LAST_UPDATE_BY,:LAST_UPDATE_DATE,:SLOT_OPERATING_UNIT,:PCF_NUMBER,:SLOT_AVAILABITIY,:SLOT_SUPPLIER_NAME,:SLOT_SUPPLIER_ID);
    ';

    public $oraSlotUpdate = '
    UPDATE
        XXCH_SLOTS
    SET
        STORE_ID = :STORE_ID,
        STORE_NAME = :STORE_NAME,
        CONTRACT_ID = :CONTRACT_ID,
        SLOT_TYPE = :SLOT_TYPE,
        SLOT_SIZE = :SLOT_SIZE,
        CONTRACT_NUMBER = :CONTRACT_NUMBER,
        CONTRACT_START_DATE = :CONTRACT_START_DATE,
        SLOT_STATUS = :SLOT_STATUS,
        SLOT_POSITION = :SLOT_POSITION,
        CREATION_DATE = :CREATION_DATE,
        CREATED_BY = :CREATED_BY,
        LAST_UPDATE_LOGIN = :LAST_UPDATE_LOGIN,
        LAST_UPDATE_BY = :LAST_UPDATE_BY,
        LAST_UPDATE_DATE = :LAST_UPDATE_DATE,
        SLOT_OPERATING_UNIT = :SLOT_OPERATING_UNIT,
        PCF_NUMBER = :PCF_NUMBER,
        SLOT_AVAILABITIY = :SLOT_AVAILABITIY,
        SLOT_SUPPLIER_NAME = :SLOT_SUPPLIER_NAME,
        SLOT_SUPPLIER_ID = :SLOT_SUPPLIER_ID
    WHERE 
        SLOT_ID = :SLOT_ID
    ';

}

?>
 