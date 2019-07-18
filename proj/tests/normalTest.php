<?php
require_once './.config.php';
require_once './vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory as Excel;
use PHPUnit\Framework\TestCase;
use Colors\Display;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
class nomalTest extends TestCase
{
    public $test_file_dir, $test_class, $test_directory, $arr_file_to_test, $duplicated_key, $changed, $total_results,$success_cell, $err_cell;
    public static $curent_file, $data_obj, $location_last_column, $writer;
    public function setUp():void
    {
        $this->test_file_dir    = TEST_FILE_DIR;
        $this->test_directory   = LIB_DIR;
        $this->arr_file_to_test = [];
        $this->duplicated_key   = [];
    }
    public function testMain(){
        $all_file = $this->scanExcelFile($this->test_file_dir);
        $include_file = function ($file){
            $status = include_once $file;
            return $status;
        };
        $test_files = $this->scanTestFile();
        foreach ($all_file as $key => $file) {
            if($include_file($test_files[$file['filename']])){
                $this->test_class = $file['filename'];
                $this->executeTest($file['full_path']);
            }
        }
    }
    private function executeTest($file){
        self::$curent_file = $file;
        self::$data_obj  = Excel::load($file);
        $arr_sheets = []; 
        //  $cancel =0;
        $cancel =0;
        $sheet_names = self::$data_obj->getSheetNames();
        $class_name = $this->test_class;
        if(!in_array("constructor", $sheet_names, true)) {
            $class_name_instance = new $class_name();  
        }
        // convert sheet to array and filter guide sheet
        foreach ($sheet_names as $key => $value) {
            if(substr($value,0,1) == '#') {
                continue;
            }
            if($value != 'Guide')
            {
                $arr_sheets[$value] = self::$data_obj->getSheetByName($value)->toArray();                
            }
        }
      
        // Loop data sheet
        foreach ($arr_sheets as $key_sheet => $sheet) { 
            $status = [];
            // remove empty row
            foreach ($sheet as $key_rows => $value_rows) {
                if($this->checkNullRow($value_rows)){
                    unset($sheet[$key_rows]);
                }
            }
            // unset # column and get status 
            if($key_sheet !== "constructor"){
                foreach ($sheet as $key_rows => $value_rows) {
                    array_shift($sheet[$key_rows]);
                }
                foreach ($sheet as $key_rows => $value_rows) {
                    $status[] = array_shift($sheet[$key_rows]);
                }
            }
            array_shift($status);
            array_shift($status);
            // unset type column 
            if($key_sheet !== "constructor"){
                foreach ($sheet as $key_rows => $value_rows) {
                    array_shift($sheet[$key_rows]);
                }
            }
            $this->changed = false;
            $num_expected = 0;
            $arr_expected_name = [];
            $arr_variable_name = [];
            $headerData= array_shift($sheet);
            $header = array();
            foreach ($headerData as $key_header => $value_header) {
                if(is_null($value_header) || strtolower($value_header)=='test result'){
                    // unset($header[$key_header]);
                    //$header[$key_header] = $value_header;
                    ///array_splice($header,$key_header, 1);
                    continue;
                }  else {
                    array_push($header, $value_header);
                }
            }
            // write test result header
            $curentSheet = self::$data_obj->getSheetByName($key_sheet);
            self::$location_last_column = $curentSheet->getCellByColumnAndRow(count($headerData)+3, 1)->getColumn();
            $this->writeResult($key_sheet, 1);
            // get expected variable name from header
            foreach($header as $value){
                if(preg_match('/^Expected/',$value)){
                    $num_expected++;
                    $arr_expected_name[] = $value;
                }
                else{
                    $arr_variable_name[] = $value;
                }
            }
             // get type data
             $typeData = array_shift($sheet);
            foreach($sheet as $key_row => $row){
            
                if(is_null($status[$key_row])){
                    $input = [];
                    $expected = [];
                    // total result
                    $this->total_results = [];
                    $this->total_results['Success'] = 0;
                    $this->total_results['Fail'] = 0;
                    foreach ($row as $key_cell => $cell) {
                        // if(!isset($header[$key_cell])){
                        //     unset($sheet[$key_row][$key_cell]);
                        //     continue;
                        // }
                        if($key_cell < $num_expected){
                            if(preg_match('/^array/',trim($cell))){
                                $cell=$this->convertArray($cell);
                                $expected[$header[$key_cell]] = $cell;
                            }
                            else{
                                $cell = $this->convertSpecicalType($cell);
                                $expected[$header[$key_cell]] = $cell;
                            }
                        }
                        else {
                            $typeData[$key_cell] = $cell;
                        }
                    }
                    // get value of input
                        array_shift($typeData);
                        array_pop($typeData);
                        $length = count($typeData);
                        $i = 0;
                        $key_header = 0;
                        while($i < $length) {
                            if($i > $length) {
                                break;
                            }
                            $key_header ++;
                            $cell = $this->convertInputData($typeData[$i], $typeData[$i + 1]);
                            $input[$header[$key_header]] = $cell;
                            $i +=2;
                        }

                    
                    // locate reference variables
                    $reference= [];
                    foreach ($arr_variable_name as $key_variable_name => $value_variable_name) {
                        if(preg_match('/^&/',$value_variable_name)){
                            $reference[$key_variable_name] = $value_variable_name;
                        }
                    }
                    if($reference){
                        foreach ($reference as $key_reference => $value_reference) {
                            $input[$value_reference]=&$input[$value_reference];
                        }
                    }
                    $duplicated_key_expected = $this->check_duplicated_key($expected);
                    $this->duplicated_key = [];
                    $duplicated_key_input = $this->check_duplicated_key($input);
                    if(!empty($duplicated_key_expected) || !empty($duplicated_key_input) ){
                        $all_duplicated= '';
                        foreach ($duplicated_key_expected as $key_duplicated_key_expected => $value_duplicated_key_expected) {
                            $all_duplicated .= $value_duplicated_key_expected .',';
                        }
                        $all_duplicated = rtrim($all_duplicated,',');
                        echo Display::error("\n\nduplicated key:[".$all_duplicated. "] in line ".($key_row+3) ." inside sheet: \"$key_sheet\", file: \"" .substr(strrchr($file, "/"), 1) ."\":\n" );
                        print_r($expected);
                    }
                    else {
                        $params = array();
                        if($key_sheet === "constructor") {
                            foreach($input as $key => $value) {
                                array_push($params, $value);
                            }
                            $class_name_instance = new $class_name(...$params);  
                        }
                        else {
                            $result = call_user_func_array([$class_name_instance,$key_sheet], $input);
                            // var_dump($input);
                            try {
                                // $result with type array
                                $type = gettype($result);
                                if($type === "array") {
                                    foreach($expected[array_key_first($expected)] as $expect_key => $expected_value){
                                        $expected_value = $this->convertSpecicalType($expected_value);
                                        $this->assertEquals($expected_value, $result[$expect_key]);
                                    }
                                } elseif($type === "boolean") {
                                    $this->assertEquals($expected[array_key_first($expected)], $result);
                                }
                               
                                $this->total_results['Success'] += 1;

                                $cell = self::$data_obj->getSheetByName($key_sheet)->getCellByColumnAndRow(4,$key_row+3)->getCoordinate();
                                $this->success_cell[$key_sheet][] = $cell;
                                $this->writeResult($key_sheet, $key_row+3, $this->calculate_results());
                            } 
                            catch (Exception $e) {
                                echo "\n".Display::error(rtrim(rtrim($e->getMessage(),'.'),'.')  ."(File: \"" .substr(strrchr($file, "/"), 1) ."=>Sheet: \"$key_sheet\"=>Row: ".($key_row+2) .")");
                                $this->total_results['Fail'] += 1;
                                $cell = self::$data_obj->getSheetByName($key_sheet)->getCellByColumnAndRow(4,$key_row+3)->getCoordinate();
                                $this->err_cell[$key_sheet][] = $cell;
                                $this->writeResult($key_sheet, $key_row+3, $this->calculate_results());
                            }
                            if(count($arr_expected_name)>1){
                                array_shift($expected);
                                foreach ($expected as $key_expected => $value_expected) {
                                    $expected["&".ltrim(ltrim($key_expected,'Expected'))] = $value_expected;
                                    unset($expected[$key_expected]);
                                }
                                foreach ($expected  as $key_expected => $value_expected) {
                                    if(is_array($value_expected)){
                                        try {
                                            $this->assertArraySubset($value_expected, $input[$key_expected]);
                                            $this->total_results['Success'] += 1;
                                            $this->writeResult($key_sheet, $key_row+3, $this->calculate_results());
                                        }
                                        catch (Exception $e) {
                                            $this->total_results['Fail'] += 1;
                                            $cell = array_search("Expected ".ltrim($key_expected,'&'),$header) + 4;
                                            $cell = self::$data_obj->getSheetByName($key_sheet)->getCellByColumnAndRow($cell,$key_row+3)->getCoordinate();
                                            $this->err_cell[$key_sheet][] = $cell;
                                            $this->writeResult($key_sheet, $key_row+3, $this->calculate_results());
                                            $message = $e->getMessage();
                                            $str1 = strstr($message,'(',true);
                                            $str2 = ltrim($message,$str1);
                                            echo "\n".Display::error($str1);
                                            echo $str2;
                                            echo Display::error("(File: \"" .substr(strrchr($file, "/"), 1) ."=>Sheet: \"$key_sheet\"=>Row: ".($key_row+3) .")");
                                        }
                                    }
                                    else{
                                        try {
                                            $this->total_results['Success'] += 1;
                                            $this->writeResult($key_sheet, $key_row+3, $this->calculate_results());
                                        } 
                                        catch (Exception $e) {
                                            echo "\n".Display::error(rtrim($e->getMessage(),'.')  ."(File: \"" .substr(strrchr($file, "/"), 1) ."=>Sheet: \"$key_sheet\"=>Row: ".($key_row+3) .")");
                                            $this->total_results['Fail'] += 1;
                                            $cell = array_search("Expected ".ltrim($key_expected,'&'),$header) + 4;
                                            $cell = self::$data_obj->getSheetByName($key_sheet)->getCellByColumnAndRow($cell,$key_row+3)->getCoordinate();
                                            $this->err_cell[$key_sheet][] = $cell;
                                            $this->writeResult($key_sheet, $key_row+3, $this->calculate_results());
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                else{
                    $this->writeResult($key_sheet, $key_row+3, 'Skip');
                }
            }
            if($this->changed){
                $this->saveChange();
            }
            foreach ($status as $key => $value) {
                if(!is_null($value)){
                    $cancel++;
                }
            }
        }
        if($cancel>0){
            echo Display::warning("\nSkip $cancel");
        }
    }
    private function scanExcelFile($dir){
        $files = scandir($dir);
        foreach($files as $k =>$file){
            if(!is_file($dir.$file) || pathinfo($dir.$file)['extension'] != 'xlsx'
                || (pathinfo($dir.$file)['extension'] == 'xlsx' && substr($file,0,2)=='~$'))
            {                
                    unset($files[$k]);
            }
            else{
                $files[$k] = pathinfo($dir.$file);
                $files[$k]['full_path'] = $dir.$file;
            }
        }
        return array_values($files);
    }
    private function scanTestFile($dir = null){
        if(is_null($dir)){
            $dir = $this->test_directory;
        }
        $files = scandir($dir);
        foreach($files as $k =>$file){
            if(is_file($dir.$file) && isset(pathinfo($dir.$file)['extension']) && pathinfo($dir.$file)['extension'] == 'php'){
                $this->arr_file_to_test[pathinfo($dir.$file)['filename']] = $dir.$file;
            }
            elseif(is_dir($dir.$file) && $file != '.' && $file != '..'){
                $this->scanTestFile($dir.$file.'/');
            }
        }
        return $this->arr_file_to_test;
    }
    private function trimSpace($input){
        $explode = explode('=>',$input);
        foreach ($explode as $key => $value) {
            $explode[$key] = trim($value);
        }
        return implode("=>",$explode);
    }    
    private function convertArray($input) {
        if(gettype($input) === "string") {
            if(preg_match('/\"*\"/', $input) || preg_match('/\'*\'/', $input)) {
                $input = trim($input, '"');
                $input = trim($input, "'");
            }
            $input = trim($input);
        }
        if(is_numeric($input)){
            if(preg_match("/[.]+/",$input)){
                $input= (double)$input;
            }
            else{
                $input= (int)$input;
            }
        }
        elseif ($input === 'true' || $input=='false') {$input = (bool)$input;}
        elseif (strtoupper($input)==='NULL')                    {$input = null;}
        elseif ('(float)'.ltrim($input,'(float)') === $input )   {$input = (float)ltrim($input, '(float)');}
        elseif ('(double)'.$input === ltrim($input,'(double)'))  {$input = (double)ltrim($input, '(double)');}
        if(preg_match('/^array/',trim($input))){
            if( rtrim(ltrim($input, 'array('),')') !== ""){
                $convert_value = substr(trim($input),7,-2);
                $convert_value = explode('","',$convert_value);
                $count = count($convert_value);
                for($i=0; $i< $count; $i++){
                    if(preg_match('/"=>"array\(*/', $convert_value[$i])){
                        for($j = $i;$j< $count; $j++){
                            if(preg_match('/"\)$/',$convert_value[$j])) 
                                break;
                            $convert_value[$j] = $convert_value[$j] .'","' .$convert_value[$j+1];
                            unset($convert_value[$j+1]);
                            $convert_value = array_values($convert_value);
                            $j--;
                            $count = count($convert_value);
                        }
                    }
                }
                $convert_value_result = [];
                foreach ($convert_value as $key_convert_value => $value_convert_value) {
                    if(preg_match('/^array/',explode('"=>"', $value_convert_value)[1])){
                        $convert = ltrim($convert_value[$key_convert_value],explode('"=>"',$value_convert_value)[0].'"=>"');
                        $convert_value[$key_convert_value] = $this->convertArray($convert);
                        $new_key = explode('"=>"', $value_convert_value)[0];
                        $new_value = $convert_value[$key_convert_value];
                    }
                    else{
                        $new_key = explode('"=>"', $value_convert_value)[0];
                        $new_value = explode('"=>"', $value_convert_value)[1];
                    }
                    if(isset($convert_value_result[$new_key])){
                        $convert_value_result['++'.$new_key] = $new_value;
                        $convert_value_result['DUPLICATED_KEY'] = $new_key;
                    }
                    else{
                        $convert_value_result[$new_key] = $new_value;
                    }
                }
                $input = $convert_value_result;
                return $input;
            }
            else{
                return [];
            }
        }
    }

    private function calculate_results(){
        if( $this->total_results['Fail'] != 0){
            return 'Fail';
        }
        else{
            return 'Success';
        }
    }

    private function check_duplicated_key (&$array) {
        if(array_key_exists('DUPLICATED_KEY' ,$array)){
            $this->duplicated_key[] = $array['DUPLICATED_KEY'];
            unset($array['DUPLICATED_KEY']);
        }
        foreach ($array as $key_array => $value_array) {
            if(is_array($value_array)){
                $this->check_duplicated_key($array[$key_array]);
            }
        }
        return $this->duplicated_key;
    }    
    private function checkNullRow($row){
        $result = true;
        foreach ($row as $key => $value) {
            if(!is_null($value)){
                $result = false;
                break;
            }
        }
        return $result;
    }

    // Convert special type such as null, bool...
    private function convertSpecicalType($input) {
        if(gettype($input) === "string") {
            if(preg_match('/\"*\"/', $input) || preg_match('/\'*\'/', $input)) {
                $input = trim($input, '"');
                $input = trim($input, "'");
                $input = trim($input);
            }
        }
        if (strtoupper($input)==='NULL') { $input = null; }
        elseif($input==='"true"' || $input==='"false"'|| $input==="'true'" || $input==="'false'") {
            $input = trim($input, '"');
            $input = trim($input, "'");
        }
        elseif ('(float)'.ltrim($input,'(float)') === $input ) {
                $input = (float)ltrim($input, '(float)'); 
            }
        elseif ('(double)'.$input === ltrim($input,'(double)')) { 
            $input = (double)ltrim($input, '(double)'); 
        }
        elseif($input===null){
            $input = "";
        }
        return $input;
    }

    private function convertInputData($val, $type){
        $checkVal = gettype($val);
        $checkType = gettype($type);
        if(strtoupper($type) === "INT" || strtoupper($type) === "INTEGER" ) {
            $val = (int) $val;
        }
        elseif(strtoupper($type) === "DOUBLE" || strtoupper($type) === "FLOAT") {
            $val = (double) $val;
        }
        elseif(strtoupper($type) === "STRING") {
            $val = (string) $val;
        }elseif(strtoupper($type) === "BOOL" || strtoupper($type) === "BOOLEAN"){
            if(strtoupper($val) === "NULL") {
                $val = null;
                $val = (boolean) $val;
            }else {
                $val = (boolean) $val;
            }
            
        }elseif($type === null) {
            $val = "";
        }elseif(strtoupper($type) === "NULL"){
            $val = null;
        }elseif(strtoupper($type) === "ARRAY"){
            $val=$this->convertArray($val);
        }
        return $val;
    }

    private function convertOject($cell, &$classInstance) {
        if(ltrim($cell, 'new ')) {
            $convert_value = ltrim($cell, 'new ');
            $new_convert_value = explode('(', $convert_value);
            $class_name = array_shift($new_convert_value);
            $class_attrs = ltrim(rtrim(array_shift($new_convert_value),')"'), '"');
            $array_attr = explode('","',$class_attrs);
            $array_params = array();
            foreach($array_attr as $param) {
                $arg = $this->convertSpecicalType($param);
                array_push($array_params, $arg);
            }
            // intinalize object with dynamic parameter;
            $classInstance  = new $class_name(...$array_params);
        }
    }

    private function writeResult($sheet_name, $row, $type = null){
        $arr_color = ['ok' => Color::COLOR_GREEN, 'fail' => Color::COLOR_RED, 'skip'=> Color::COLOR_DARKYELLOW];
        if($type){
            switch (strtolower($type)) {
                case 'success':
                    $content = 'Success';
                    $color = $arr_color['ok'];
                    break;
                case 'fail':
                    $content = 'Fail';
                    $color = $arr_color['fail'];
                    break;
                case 'skip':
                    $content = 'Skip';
                    $color = $arr_color['skip'];
                    break;
            }
        }
        else{
            $content = 'Test result';
            $color = Color::COLOR_BLACK;
        }
        $curentSheet = self::$data_obj->getSheetByName($sheet_name);
        $curentSheet->setCellValue(self::$location_last_column.$row, $content);
        $curentSheet->getStyle(self::$location_last_column.$row)->getFont()->getColor()->setARGB($color);
        try {
            self::$writer = Excel::createWriter(self::$data_obj, "Xlsx");
            $this->changed = true;
        } 
        catch (Exception $e) {
            echo $e->getMessage();            
            echo Display::error("\nMake sure you closed this file");exit;
        }
    }

    private function saveChange(){
        if(!empty($this->err_cell)){
            foreach ($this->err_cell as $key => $value) {
                $curentSheet = self::$data_obj->getSheetByName($key);
                foreach ($value as $k => $v) {
                    $curentSheet->getStyle($v)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('f25757');
                }
            }
        }
        if(!empty($this->success_cell)){
            // $this->success_cell
            foreach ($this->success_cell as $key => $value) {
                $curentSheet = self::$data_obj->getSheetByName($key);
                foreach ($value as $k => $v) {
                    $curentSheet->getStyle($v)->getFill()->setFillType(Fill::FILL_NONE)->getStartColor()->setARGB('#ffffff');
                }
            }
        }
        self::$writer->save(self::$curent_file);
    }
}