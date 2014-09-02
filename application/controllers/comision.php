<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Victoryland
 * Date: 3/22/14
 * Time: 1:45 AM
 * To change this template use File | Settings | File Templates.
 */

require_once 'controller_helper.php';

class comision extends controller_helper{
    function __construct() {
        parent::__construct();
        $this->checkLogin();
    }

    function index(){
        redirect('comision/comisionUploadStepOne', 'refresh');
    }

    function comisionUploadStepOne(){
        $importMessage = $this->session->flashdata('importMessage');
        if (trim($importMessage) !== '') {
            $this->addViewData('success', array($importMessage));
        }
        $this->loadview('stepOne', 'comision');
    }

    function api(){
        if ($this->input->get_post('security_token') === 'manuel_comision') {
            $this->persistence->saveAPI();
        }
    }

    function comisionUploadStepTwo(){
        if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->input->get_post('action') === 'step3') {
            $result = $this->persistence->importIntoZoho();
            if ($result['status'] !== false) {
                $this->session->set_flashdata('importMessage', $result['importMessage']);
                redirect('comision/comisionUploadStepOne', 'refresh');
            } else {
                $this->addViewData('error', array($result));
            }
        }

        $viewData = $this->persistence->stepTwoLoadData();
        $this->addViewData('mapData', $viewData);
        $this->loadview('stepTwo', 'comision');
    }

    function sampleFileDownload(){
        if (file_exists(BASE_ABSULATE_PATH . 'static/sample.xls')) {
            header('Content-Description: File Transfer');
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename=' . date("Y-m-d_H.i.s_") . 'sample.xls');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize(BASE_ABSULATE_PATH . 'static/sample.xls'));
            readfile(BASE_ABSULATE_PATH . 'static/sample.xls');
        }
        ob_end_flush();
        die;
    }
}