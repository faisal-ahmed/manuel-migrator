<?php

require_once 'model_helper.php';

class Persistence extends model_helper
{
    function __construct()
    {
        parent::__construct();
    }

    function user_has_identity()
    {
        $username = $this->getPost('username');
        $password = $this->getPost('password');

        if ($username === 'admin' && $password === '123') {
            $this->session->set_userdata('login', true);
            return true;
        }
        return false;
    }

    function stepTwoLoadData()
    {
        $return = array();
        global $EXCLUDE_FIELDS;
        $fileName = $this->getPost('uploaded_file_name');
        $csv_column_name = $this->parseFile($fileName, 1);
        $module_column_name = array();
        $dataMigrationControllerObj = new ZohoDataSync();
        $response = $dataMigrationControllerObj->getFields(COMISION_MODULE);
        $xml = simplexml_load_string($response);
        $mendatoryArray = '';
        if ($dataMigrationControllerObj->errorFound($xml)) {
            return 'System Error! Please try again later or contact system admin.';
        } else if ($xml !== false) {
            foreach ($xml->section as $sectionKey => $sectionValue) {
                foreach ($sectionValue->FL as $key => $row) {
                    $temp_value = (string)$row['label'];
                    $dontAddThisField = 0;
                    foreach ($EXCLUDE_FIELDS as $exclude_fields_key => $exclude_fields_value) {
                        if (strpos($temp_value, $exclude_fields_value) !== FALSE) {
                            $dontAddThisField = 1;
                            break;
                        }
                    }
                    if ($dontAddThisField) {
                        continue;
                    }
                    $fieldKey = str_replace(' ', '_', $temp_value);
                    $mendatory = '';
                    if ($row['req'] == 'true') {
                        $mendatory = '<span style="color: red;font-size: 1.3em;font-weight: bolder;">*</span>';
                        if ($mendatoryArray != '') $mendatoryArray .= (',"' . $fieldKey . '"');
                        else $mendatoryArray .= ('"' . $fieldKey . '"');
                    }
                    $module_column_name[$fieldKey] = $temp_value . $mendatory;
                }
            }
        } else {
            return "Error! Please check your daily API limits at Zoho.";
        }

        $return['mendatoryArray'] = $mendatoryArray;
        $return['module_column_name'] = $module_column_name;
        $return['csv_column_name'] = $csv_column_name;
        $return['fileName'] = $fileName;

        return $return;
    }

    function importIntoZoho()
    {
        $mendatoryArray = $this->getPost('mendatoryArray');
        $zoho_column_matching = $this->getPostArray('zoho_column_matching');
        $duplicateCheck = $this->getPost('duplicateCheck');
        $fileName = $this->getPost('fileName');
        $zoho_module_name = COMISION_MODULE;
        $mendatoryArray = explode(',', $mendatoryArray);
        $xmlArray = $this->buildXmlArray($zoho_column_matching, $mendatoryArray, $fileName);
        $dataMigrationControllerObj = new ZohoDataSync();
        $error = '';
        $successMessage = '';
        $insertedCount = 0;
        $updatedCount = 0;
        $ignoredCount = 0;
        $ignoredDataToBuildCSV = array($this->buildIgnoredDataColumn($mendatoryArray, $zoho_column_matching, $fileName));
        $ignoredDataToBuildCSV1 = array($this->buildIgnoredDataColumn($mendatoryArray, $zoho_column_matching, $fileName, true));
        $currentTime = time();
        $dataProcessed = 0;
        foreach ($xmlArray as $bulkKey => $bulkRecords) {
            $updated = array();
            $inserted = array();
            $ignored = array();
            foreach ($bulkRecords as $key => $value) {
                if (!isset($value['Oportunidades_ID'])){
                    $ignored[] = $key;
                    unset($bulkRecords[$key]);
                }
            }
            $response = $dataMigrationControllerObj->insertRecords($zoho_module_name, $bulkRecords, "$duplicateCheck");
            $xml = simplexml_load_string($response);
            if ($xml !== false) {
                if (!$dataMigrationControllerObj->errorFound($xml)) {
                    foreach ($xml->result->row as $key => $insertedObject) {
                        if (isset($bulkRecords[$insertedObject['no'] - 1]['Oportunidades_ID'])) {
                            $xmlArray2 = array(
                                1 => array(
                                    'Comision' => $bulkRecords[$insertedObject['no'] - 1]['CustomModule5 Name'],
                                ),
                            );

                            $id = $bulkRecords[$insertedObject['no'] - 1]['Oportunidades_ID'];
                            $zohoUpdatePotential = $dataMigrationControllerObj->updateRecords(POTENTIAL_MODULE, $id, $xmlArray2);
                            $xml = simplexml_load_string($zohoUpdatePotential);
                            //$this->debug($xml);
                        }
                        if (trim($insertedObject->success->code) == 2000) {
                            $inserted[] = $insertedObject['no'];
                        } else if (trim($insertedObject->success->code) == 2001) {
                            $updated[] = $insertedObject['no'];
                        } else if (trim($insertedObject->success->code) == 2002) {
                            $ignored[] = $dataProcessed + $insertedObject['no'] - 1;
                        }
                    }
                    $ignored = array_merge($ignored, array_diff(range(1, count($bulkRecords)), array_merge($inserted, $updated)));
                }
                $insertedCount += count($inserted);
                $updatedCount += count($updated);
                $ignoredCount += count($ignored);
                if (count($ignored) > 0) {
                    sort($ignored);
                    $ignored = array_unique($ignored);
                    $rowsValue = $this->getDataOfRowsForReport($ignored, $zoho_module_name, $currentTime, $fileName);
                    foreach ($rowsValue as $row => $rowValue) {
                        $ignoredDataToBuildCSV[] = $rowValue[0];
                        $ignoredDataToBuildCSV1[] = $rowValue[1];
                    }
                }
            } else {
                $error = 'Something went wrong! Please try again later. Please check the Zoho authtoken/Zoho daily API limits/Your Internet connection.';
                break;
            }
            $dataProcessed += MAX_RECORD_TO_INSERT_VIA_insertRecords;
        }
        if ($insertedCount != 0 || $updatedCount != 0 || $ignoredCount != 0) {
            $successMessage = $insertedCount . " record(s) added successfully, ";
            $successMessage .= $updatedCount . " record(s) updated successfully";
            if ($ignoredCount) {
                $successMessage .= " and " . $ignoredCount . " record(s) ignored.";
            }
        } else if ($error !== '') {
            $successMessage = 'No Status Result but your request has been processed already.';
        }

        $this->array_to_csv_report_file($ignoredDataToBuildCSV);
        $this->array_to_csv_report_file($ignoredDataToBuildCSV1, true);

        $result = array();
        if ($successMessage != '') {
            $result['status'] = true;
            $result['importMessage'] = $successMessage;
        } else if ($error != '') {
            $result['status'] = false;
            $result['importMessage'] = $error;
        }

        return $result;
    }

    function buildIgnoredDataColumn($mendatoryArray, $zoho_column_matching, $fileName, $forReportDownload = false)
    {
        if ($forReportDownload === false) {
            $csvColumnForIgnoredData = array('Module Name', 'Migration Time');
        } else {
            $csvColumnForIgnoredData = array();
        }
        $csv_column_name = $this->parseFile($fileName, 1);

        foreach ($csv_column_name[0] as $key => $value) {
            $mendatory = '';
            if (($keyMatching = array_search($key, $zoho_column_matching)) !== FALSE) {
                if (in_array($keyMatching, $mendatoryArray)) {
                    $mendatory = "<span style='color: red;font-size: 1.3em;font-weight: bolder;'>*</span>";
                }
                $keyMatching = str_replace("_", " ", $keyMatching);
                $keyMatching = str_replace("CustomModule5", REPLACE_CustomModule5, $keyMatching);
                if ($forReportDownload === false) {
                    $csvColumnForIgnoredData[] = $keyMatching . $mendatory;
                } else {
                    $csvColumnForIgnoredData[] = $keyMatching;
                }
            }
        }

        return $csvColumnForIgnoredData;
    }

    function array_to_csv_report_file(array $data, $forReportDownload = false, $apiFile = null)
    {
        if ($forReportDownload === false) {
            $report_file_name = "report.csv";
        } else {
            $report_file_name = "reportForDownload.csv";
        }

        if ($apiFile !== null) {
            $report_file_name = "$apiFile.csv";
        }
        if (count($data) == 0) {
            return null;
        }

        $csv = '';
        $csv_handler = fopen(BASE_ABSULATE_PATH . 'static/' . $report_file_name, 'w');
        foreach ($data as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $value1 = '"' . $value1 . '"';
                if (!$key1) $csv .= $value1;
                else $csv .= ",$value1";
            }
            $csv .= "\n";
        }

        fwrite($csv_handler, $csv);
        fclose($csv_handler);
    }

    function getStaticData($fileName)
    {
        $fp = fopen(BASE_ABSULATE_PATH . "static/$fileName.csv", 'r') or die("can't open file");
        $return = array();
        $count = 0;

        while ($csv_line = fgetcsv($fp)) {
            if (!$count++ || trim($csv_line[1]) === '') continue;
            $temp_string = str_replace("zcrm_", "", $csv_line[0]);
            $return[$csv_line[1]] = $temp_string;
        }

        fclose($fp);

        return $return;
    }

    function buildXmlArray($zoho_column_matching, $mendatoryArray, $fileName)
    {
        $xmlMultipleArray = array();
        $start = 1;
        while (1) {
            $tempArray = $this->buildBulkData($zoho_column_matching, $start, $mendatoryArray, $fileName);
            $xmlMultipleArray[] = $tempArray['data'];
            $start = $tempArray['next_start'];
            if (count($tempArray['data']) == 0) {
                break;
            }
        }

        return $xmlMultipleArray;
    }

    function buildBulkData($keys, $start = 1, $mendatoryArray, $fileName)
    {
        $potential = $this->getStaticData('potential');
        $vendor = $this->getStaticData('vendor');
        $return = array(
            'data' => array(),
            'next_start' => 0
        );
        $count = 0;
        $recordCount = $start;
        $dataIndex = range($start, ($start + MAX_RECORD_TO_INSERT_VIA_insertRecords));
        $dataIndex[] = 0;

        $data = $this->parseFile($fileName, null, $dataIndex);

        foreach ($data as $csv_line_key => $csv_line) {
            if ($count >= $start) {
                $ret = array();
                $flag = 0;
                foreach ($keys as $key => $csvColumn) {
                    if ($csvColumn == '') continue;
                    $temp_string = $key;
                    $row_value = trim($csv_line[$csvColumn]);
                    if (in_array($temp_string, $mendatoryArray) && $row_value == '') {
                        $flag = 1;
                        break;
                    } else if ($row_value == '') {
                        continue;
                    }
                    $ret[str_replace('_', ' ', $temp_string)] = $row_value;
                    if (strtolower(str_replace('_', ' ', $temp_string)) === strtolower(POTENTIAL_SEARCH_BY_CUPS_FIELD_NAME) && isset($potential[$row_value])) {
                        $ret[POTENTIAL_SEARCH_BY_CUPS_FIELD_ID_NAME] = $potential[$row_value];
                    }
                    if (strtolower(str_replace('_', ' ', $temp_string)) === strtolower(VENDOR_SEARCH_BY_NAME_FIELD_NAME) && isset($vendor[$row_value])) {
                        $ret[VENDOR_SEARCH_BY_NAME_FIELD_ID_NAME] = $vendor[$row_value];
                    }
                }
                if (!$flag) {
                    $return['data'][$count] = $ret;
                    $recordCount++;
                }
                if ($recordCount >= (MAX_RECORD_TO_INSERT_VIA_insertRecords + $start)) {
                    break;
                }
            }
            $count++;
        }

        $return['next_start'] = $count + 1;

        return $return;
    }

    function getDataOfRowsForReport($rows, $zoho_module_name, $currentTime, $fileName)
    {
        $moduleName = str_replace("CustomModule5", REPLACE_CustomModule5, $zoho_module_name);
        $initialItem = array($moduleName, $currentTime);
        $return = array();
        $rowsData = $this->parseFile($fileName, null, $rows);
        foreach ($rowsData as $key => $value) {
            $ret = array();
            $ret[0] = array_merge($initialItem, $value);
            $ret[1] = $value;
            $return[] = $ret;
        }
        return $return;
    }

    function getExtentionFromFileName($fileName)
    {
        return substr($fileName, (strrpos($fileName, ".") + 1));
    }

    function parseFile($filename, $limit = null, $rows = null)
    {
        $extention = $this->getExtentionFromFileName($filename);
        if ($extention === 'xls') $objReader = new PHPExcel_Reader_Excel5();
        else if ($extention === 'xlsx') $objReader = new PHPExcel_Reader_Excel2007();

        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load(BASE_ABSULATE_PATH . "uploads/$filename");
        $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

        $csvArray = array();
        $count = 0;
        foreach ($rowIterator as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $rowIndex = $row->getRowIndex() - 1;
            $csvArray[$rowIndex] = array();

            if ($rows === null) {
                foreach ($cellIterator as $cell) {
                    $csvArray[$rowIndex][] = $cell->getCalculatedValue();
                }
            } else if (in_array($rowIndex, $rows)) {
                foreach ($cellIterator as $cell) {
                    $csvArray[$rowIndex][] = $cell->getCalculatedValue();
                }
            }
            if (isset($csvArray[$rowIndex])) {
                $flag = 0;
                foreach ($csvArray[$rowIndex] as $key => $value) {
                    if ($value != '') {
                        $flag = 1;
                        break;
                    }
                }
                if (!$flag) {
                    unset($csvArray[$rowIndex]);
                    $count--;
                }

                if (++$count === $limit) break;
            }
        }

        return $csvArray;
    }

    function saveAPI()
    {
        $id = $this->getPost('id');
        $cups = $this->getPost('cups');
        $vendorName = $this->getPost('vendorName');

        if ($cups != '') {
            $potentialData = $this->getFile('potential');
            $potentialData[] = array($id, $cups);
            $this->array_to_csv_report_file($potentialData, false, "potential");
        } else if($vendorName != '') {
            $vendorData = $this->getFile('vendor');
            $vendorData[] = array($id, $vendorName);
            $this->array_to_csv_report_file($vendorData, false, "vendor");
        }
    }

    function updatePotentialAPI()
    {
        $id = $this->getPost('id');
        $cups = $this->getPost('cups');

        if ($cups != '') {
            $zohoConnector = new ZohoDataSync();
            $xmlArray = array(
                1 => array(
                    'Comision' => $cups,
                ),
            );

            $zohoUpdatePotential = $zohoConnector->updateRecords(POTENTIAL_MODULE, $id, $xmlArray);
            $xml = simplexml_load_string($zohoUpdatePotential);
            $this->debug($xml);
        }
    }

    function getFile($fileName)
    {
        $fp = fopen(BASE_ABSULATE_PATH . "static/$fileName.csv", 'r') or die("can't open file");
        $return = array();

        while ($csv_line = fgetcsv($fp)) {
            $temp = array();
            for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                $temp[] = trim($csv_line[$i]);
            }
            $return[] = $temp;
        }

        fclose($fp);

        return $return;
    }

}

?>