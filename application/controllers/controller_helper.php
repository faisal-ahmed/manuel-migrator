<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Victoryland
 * Date: 3/22/14
 * Time: 1:49 AM
 * To change this template use File | Settings | File Templates.
 */

ini_set("allow_url_include", true);

require_once (BASEPATH . '../application/libraries/Utilities.php');

class controller_helper extends CI_Controller{

    private $viewData;
    private $isTopMenu;

    function __construct() {
        parent::__construct();
        $this->viewData = array(
            'error_page_url' => $this->errorPageUrl(),
            'new_left_submenu' => array(),
        );
        $this->isTopMenu = false;
        $this->load->model('persistence');
        $this->load->helper(array('form'));
        $this->load->library('form_validation');
    }

    function loadview($content_template, $folderPath = null) {
        $content_path = ($folderPath !== null) ? "$folderPath/$content_template" : "$content_template";
        $login = $this->getSessionAttr('login');
        $this->load->view("layout/header", $this->viewData);
        if ($login) {
            $this->load->view("layout/top_menu", $this->viewData);
            $this->load->view("layout/content_header", $this->viewData);
        }
        $this->load->view($content_path, $this->viewData);
        if ($login) {
            $this->load->view("layout/content_footer", $this->viewData);
        }
        $this->load->view("layout/footer", $this->viewData);
    }

    function debug($debugArray){
        echo "<pre>";
        print_r($debugArray);
        echo "</pre>";
    }

    function errorPageUrl(){
        return (BASEPATH . '../application/views/error.php');
    }

    function addViewData($key, $value){
        $this->viewData[$key] = $value;
    }

    function setTopMenu($value){
        $this->isTopMenu = (bool)$value;
    }

    function getViewData($key){
        if (isset($this->viewData[$key])) {
            return $this->viewData[$key];
        }
        return false;
    }

    function checkLogin(){
        $login = $this->getSessionAttr('login');
        if ($login !== true) {
            redirect('login', 'refresh');
        }
    }

    function alreadyLoggedIn(){
        $login = $this->getSessionAttr('login');
        if ($login === true) {
            redirect('comision', 'refresh');
        }
    }

    function getSessionAttr($attr) {
        if ($this->session->userdata("$attr") ) {
            return $this->session->userdata("$attr");
        }
        return false;
    }

    function getErrors($errorString){
        $return = array();
        $errors = explode('</p>', $errorString);
        foreach ($errors as $key => $value) {
            $error = substr($value, strpos($value, '<p>') + 3);
            if ($error == '') {
                continue;
            }
            $return[] = $error;
        }
        return $return;
    }

    function loadPagination($controllerFunction, $totalRows = 200, $perPage = 50) {
        $this->load->library('pagination');

        $config['base_url'] = base_url() . "index.php/$controllerFunction/";
        $config['total_rows'] = $totalRows;
        $config['per_page'] = $perPage;
        $config['num_links'] = 2;
        $config['full_tag_open'] = '<div class="pagination right">';
        $config['full_tag_close'] = '</div>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config['cur_tag_open'] = '<a href="#" class="active">';
        $config['cur_tag_close'] = '</a>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';

        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }
}