<?php
require_once './.config.php';
require_once './vendor/autoload.php';

use \PhpOffice\PhpSpreadsheet\IOFactory as Excel;
class GetData
{
    private $test_file_dir;
    public function __construct(){
        $this->test_file_dir   = TEST_FILE_DIR."Unit/";
    }
    public function readData($sheet_name = null){
        $test_files = $this->test_file_dir.'data.xlsx';
        $data_obj  = Excel::load($test_files);
        $arr_sheets = []; 
        if($sheet_name){
            $arr_sheets[$sheet_name] = $data_obj->getSheetByName($sheet_name)->toArray();
        }
        else{
            $sheet_names = $data_obj->getSheetNames();
            foreach ($sheet_names as $key => $value) {
                $arr_sheets[$value] = $data_obj->getSheetByName($value)->toArray();
            }   
        }
        foreach ($arr_sheets as $key => $value) {
            $header = array_shift($arr_sheets[$key]);
            foreach ($arr_sheets[$key] as $k_row => $v_row) {
                foreach ($arr_sheets[$key][$k_row] as $key_v => $value_v) {
                    //$arr_sheets[$key][$k_row][$header[$key_v]] =$key_v;
                    $arr_sheets[$key][$k_row][$header[$key_v]] =$value_v;
                    unset($arr_sheets[$key][$k_row][$key_v]);
                }                
            }
        }
        return $arr_sheets;     
    }
}