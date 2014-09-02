<?php
/**
 * Created by PhpStorm.
 * User: victoryland
 * Date: 5/26/14
 * Time: 10:33 PM
 */

$base_absolute_path = str_replace("system/", "", BASEPATH);

define("BASE_ABSULATE_PATH", $base_absolute_path);
define("STATIC_DIRECTORY_NAME", "static");
define("GENERAL_UPLOAD_DIRECTORY_NAME", "uploads");

//Max record count to insert via bulk insert
define("MAX_RECORD_TO_INSERT_VIA_insertRecords", 98);

//Zoho Modules Name
define("AUTH_TOKEN", "a7acb7c2902bb99f12cc64441704496a");
define("LEAD_MODULE", "Leads");
define("ACCOUNT_MODULE", "Accounts");
define("CONTACT_MODULE", "Contacts");
define("POTENTIAL_MODULE", "Potentials");
define("CAMPAIGN_MODULE", "Campaigns");
define("CASE_MODULE", "Cases");
define("SOLUTION_MODULE", "Solutions");
define("PRODUCT_MODULE", "Products");
define("PRICE_BOOK_MODULE", "PriceBooks");
define("QUOTE_MODULE", "Quotes");
define("INVOICE_MODULE", "Invoices");
define("SALES_ORDER_MODULE", "SalesOrders");
define("VENDOR_MODULE", "Vendors");
define("PURCHASE_ORDER_MODULE", "PurchaseOrders");
define("EVENT_MODULE", "Events");
define("TASK_MODULE", "Tasks");
define("CALL_MODULE", "Calls");
define("COMISION_MODULE", "CustomModule5");
define("POTENTIAL_SEARCH_BY_CUPS_FIELD_NAME", "Cups");
define("POTENTIAL_SEARCH_BY_CUPS_FIELD_ID_NAME", "Oportunidades_ID");
define("VENDOR_SEARCH_BY_NAME_FIELD_NAME", "Comercializadora");
define("VENDOR_SEARCH_BY_NAME_FIELD_ID_NAME", "Comercializadora_ID");

//Zoho Module
//array format == (Module name => Module title to show)
global $MODULE;
$MODULE = array(
    LEAD_MODULE => "Leads",
    ACCOUNT_MODULE => "Accounts",
    CONTACT_MODULE => "Contacts",
    POTENTIAL_MODULE => "Potentials",
    CAMPAIGN_MODULE => "Campaigns",
    CASE_MODULE => "Cases",
    SOLUTION_MODULE => "Solutions",
    PRODUCT_MODULE => "Products",
    PRICE_BOOK_MODULE => "Price Books",
    QUOTE_MODULE => "Quotes",
    INVOICE_MODULE => "Invoices",
    SALES_ORDER_MODULE => "Sales Orders",
    VENDOR_MODULE => "Vendors",
    PURCHASE_ORDER_MODULE => "Purchase Orders",
    EVENT_MODULE => "Events",
    TASK_MODULE => "Tasks",
    CALL_MODULE => "Calls"
);

//Holds the module name where multiple insertion is not possible
global $MULTIPLE_INSERT_NOT_ALLOWED_FOR_MODULE;
$MULTIPLE_INSERT_NOT_ALLOWED_FOR_MODULE = array(
    QUOTE_MODULE,
    SALES_ORDER_MODULE,
    INVOICE_MODULE,
    PURCHASE_ORDER_MODULE,
);

//Zoho Fields To Exclude For Data Migration
global $EXCLUDE_FIELDS;
$EXCLUDE_FIELDS = array(
    "SMOWNERID",
    "Oportunidades",
    "CustomModule5 Owner",
    "Created Time",
    "Created By",
    "Modified Time",
    "Modified By",
    "Last Activity Time",
);

global $MANDATORY_FIELD_FOR_MODULE;
$MANDATORY_FIELD_FOR_MODULE[ACCOUNT_MODULE] = array('Account Name');

/*    $testXmlArray = array(
        1 => array(
            'Subject' => 'TEST',
            'Due Date' => '2009-03-10',
            'Sub Total' => '48000.0',
            'Product Details' => array(
                'product' => array(
                    1 => array(
                        'Product Id' => '269840000000136287',
                        'Product Name' => 'prd1',
                        'Unit Price' => '10.0',
                    ),
                    2 => array(
                        'Product Id' => '269840000000136287',
                        'Product Name' => 'prd1',
                        'Unit Price' => '10.0',
                    ),
                    3 => array(
                        'Product Id' => '269840000000136287',
                        'Product Name' => 'prd1',
                        'Unit Price' => '10.0',
                    ),
                    4 => array(
                        'Product Id' => '269840000000136287',
                        'Product Name' => 'prd1',
                        'Unit Price' => '10.0',
                    ),
                ),
            ),
        ),
        2 => array(
            'Subject' => 'TEST',
            'Due Date' => '2009-03-10',
            'Sub Total' => '48000.0',
            'Product Details' => array(
                'product' => array(
                    1 => array(
                        'Product Id' => '269840000000136287',
                        'Product Name' => 'prd1',
                        'Unit Price' => '10.0',
                    ),
                    2 => array(
                        'Product Id' => '269840000000136287',
                        'Product Name' => 'prd1',
                        'Unit Price' => '10.0',
                    ),
                ),
            ),
        ),
    );*/

?>