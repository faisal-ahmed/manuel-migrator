<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Victoryland
 * Date: 3/22/14
 * Time: 1:56 AM
 * To change this template use File | Settings | File Templates.
 */

require_once (BASEPATH . '../application/libraries/Utilities.php');
require_once (BASEPATH . '../additional_library/PHPExcel.php');
require_once (BASEPATH . '../additional_library/zoho_library/utils_zoho_request.php');

class model_helper  extends CI_Model{
    //TODO: Uncomment the line no. 731 of System/Core/Input which is exit('Disallowed Key Characters.');
    function __construct()
    {
        parent::__construct();
    }

    function debug($debugArray){
        echo "<pre>";
        print_r($debugArray);
        echo "</pre>";
    }

    function getPost($attr, $filter = true) {
        return trim($this->input->get_post($attr, $filter));
    }

    function getPostArray($attr, $filter = true) {
        return $this->input->get_post($attr, $filter);
    }
}