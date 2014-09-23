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
    }

    function index(){
        $this->checkLogin();
        redirect('comision/comisionUploadStepOne', 'refresh');
    }

    function comisionUploadStepOne(){
        $this->checkLogin();
        $importMessage = $this->session->flashdata('importMessage');
        if (trim($importMessage) !== '') {
            $this->addViewData('success', array($importMessage));
        }
        $this->loadview('stepOne', 'comision');
    }

    function api(){
        if ($this->input->get_post('security_token') === 'manuel_comision') {
            $this->persistence->saveAPI();
        } else if ($this->input->get_post('security_token') === 'manuel_potential_lookup') {
            $this->persistence->updatePotentialAPI();
        }
    }

    function comisionUploadStepTwo(){
        $this->checkLogin();
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
        $fileName = $this->input->get_post('file');
        $fileName = ($fileName === 'report') ? "reportForDownload.csv" : "sample.xls";
        $this->checkLogin();
        if (file_exists(BASE_ABSULATE_PATH . "static/$fileName")) {
            header('Content-Description: File Transfer');
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename=' . date("Y-m-d_H.i.s_") . $fileName);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize(BASE_ABSULATE_PATH . "static/$fileName"));
            readfile(BASE_ABSULATE_PATH . "static/$fileName");
        }
        ob_end_flush();
        die;
    }
}