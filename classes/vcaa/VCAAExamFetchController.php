<?php
namespace VCAA;
define("BASE_URL",$_SERVER['DOCUMENT_ROOT']."/");
define("LIB_URL",BASE_URL.'libs/');
define("TEMP_URL",BASE_URL.'temp/');
define("REPO_URL",BASE_URL.'repository/');

require_once LIB_URL."simple_html_dom.php";
require_once LIB_URL."zipstream.php";

class VCAAExamFetchController {

    /***** Variables *****/

    // DOM (reusable)
    private $dom;
    // Generated array
    private $examArray;
    // Base urls
    private static $baseURL = "http://www.vcaa.vic.edu.au";
    private static $baseURLToLoad = "http://www.vcaa.vic.edu.au/pages/vce/exams/examsassessreports.aspx";
    //Context with UA
    private static $context;

    /**
     * @return resource
     */
    public static function getContext()
    {
        return self::$context;
    }
    /**
     * @return string
     */
    public static function getBaseURLToLoad()
    {
        return self::$baseURLToLoad;
    }

    //Exam data
    private $exam_paper; private $exam_report; private $mode;

    /***** Constructor *****/

    public function __construct($paper,$report,$mode = -1){
        //Set scheme
        $this->exam_paper = $paper;
        $this->exam_report = $report;
        $this->mode = $mode;
        //Preload context
        $options  = array('http' => array('user_agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'));
        self::$context  = stream_context_create($options);
        //Preload document
        $this->dom = file_get_html(TEMP_URL."home.html");
    }

    /***** Public Methods *****/
    //output JSON for HTML form ([subject name]:[title:url] )
    public function output($subjects_array = array(),$year_array = array(),$from = null,$to = null){
        // Check dom
        if (!$this->dom){
            exit();
        }
        $outArray = array();
        // Mode derivation
        if ($this->mode == ExamFetchingMode::BULK){
            $subjects_array = $this->bulkStringToArray($subjects_array);
            $year_array = $this->bulkStringToArray($year_array);
            if ($from && $to){
                $year_array = $this->constructYearArrayWithStartEnd($from,$to);
            }
            $outArray = $this->loopThroughSubjects($subjects_array,$year_array);

        }elseif ($this->mode == ExamFetchingMode::SINGLE){
            //TODO::FIX SINGLE ARRAY FETCHED FOR MULTIPLE YEARS PROBLEM
            $data = $this->constructDataInSingleMode();
            for ($i = 0; $i < count($data); $i ++){
                $singleSubject = $data[$i];
                $singleSubjectYearArray = $data[$singleSubject];
                error_log("Subject:".print_r($singleSubject,true));
                error_log("Subject years:".print_r($singleSubjectYearArray,true));
                if (count($outArray) > 0){
                    $newSubject = $this->loopThroughSubjects((array)$singleSubject,$singleSubjectYearArray);
                    $outArray = array_merge($outArray,$newSubject);
                }else{
                    $outArray = $this->loopThroughSubjects((array)$singleSubject,$singleSubjectYearArray);
                }
            }
        }
        //loop through subjects

        return json_encode($outArray);
    }

    private function loopThroughSubjects($subjects_array = array(),$year_array = array()){
        $innerArray = array(); $outArray = array();
        for ($i = 0 ; $i < count($subjects_array); $i ++){
            //Retrieve DOM
            $subjectDom = file_get_html($this->findGeneralSubjectURL((string)$subjects_array[$i]),false,self::$context);
            //Get Exam Table from DOM
            foreach ($subjectDom->find('table[class=tablestyle4]') as $table){
                //loop through years now
                for ($j = 0; $j < count($year_array); $j++){
                    //Find Table row
                    foreach ($table->find('tr') as $tr){
                        //Get Year
                        $year = preg_replace('/[^\00-\255]+/u', '', $tr->find('td',0)->innertext);
                        //Compare year
                        if ((string)$year_array[$j] == $year){
                            $title = null;
                            $subj_name = null;
                            $yearTitle = null;
                            $link_url = null;
                            // both exam & report
                            if ($this->exam_paper && $this->exam_report){
                                // all exam links
                                foreach ($tr->find('a') as $examLink){
                                    $title = $this->generateTitle($examLink->href);
                                    $link_url = $this::$baseURL.$examLink->href;
                                    //add to inner array
                                    $innerArray[$title] = $link_url;
                                }
                            }elseif ($this->exam_paper){
                                // only exam paper
                                $paperSection = $tr->find('td',1);
                                foreach ($paperSection->find('a') as $paperLink){
                                    $title = $this->generateTitle($paperLink->href);
                                    $link_url = $this::$baseURL.$paperLink->href;
                                    //add to inner array
                                    $innerArray[$title] = $link_url;
                                }
                            }elseif ($this->exam_report){
                                // only exam report
                                $reportSection = $tr->find('td',2);
                                foreach ($reportSection->find('a') as $reportLink){
                                    $title = $this->generateTitle($reportLink->href);
                                    $link_url = $reportLink->href;
                                    //add to inner array
                                    $innerArray[$title] = $link_url;
                                }
                            }


                        }
                    }
                }
            }
            //Set outer array
            $outArray[$subjects_array[$i]] = $innerArray;
            $innerArray = null;
        }
        return $outArray;

    }

    /**
     * @return mode
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    //find Subject Link in the collection page
    private function findGeneralSubjectURL($subject_name){
        foreach ($this->dom->find('a') as $element){
            $name = $element->innertext;
            if ($this->match($subject_name,$name)){
                return $this::$baseURL.$element->href;
                break;
            }
        }
        return null;
    }

    // Match subject name with cleaned source
    private function match($input,$source){
        $decodedSource = str_replace("\xc2\xa0",' ',html_entity_decode($source));
        if (preg_replace('/\s+/','',$decodedSource) == $input){
            return true;
        }
        return false;
    }

    // Generate Title
    private function generateTitle($href){
        //slice URL
        $pos = strrpos($href,'/')+1;
        $slicedURL = substr($href,$pos,strlen($href)-$pos);
        return $slicedURL;
    }

    // (for bulk mode only) convert string to array
    private function bulkStringToArray($string){
        $replaceFrom = array("[","\"","]");
        $replaceTo = array("","","");
        $cleaned = str_replace($replaceFrom,$replaceTo,$string);
        $result = explode(",",$cleaned);

        return $result;
    }

    // (for bulk mode only) construct year array from start to end
    private function constructYearArrayWithStartEnd($from,$to){
        $array = array();
        for ($i = $from; $i <= $to; $i++){
            array_push($array,$i);
        }
        return $array;
    }

    // (for single mode only) construct subjects array
    private function constructArraysInSingleMode(){
        // get number of fields
        $numOfFields = $_POST['counter'];
        // loop through and construct
        $subjectsArray = array(); $yearsArray = array();
        for ($i = 0; $i < $numOfFields; $i ++){
            // get values
            $subject = $_POST['field_div_id_'.$i.'_subject'];
            $year = $_POST['field_div_id_'.$i.'_year'];
            // push to array
            array_push($subjectsArray,$subject);
            array_push($yearsArray,$year);
        }
        // add to collection
        $collection = array();
        $collection[0] = $subjectsArray;
        $collection[1] = $yearsArray;
        return $collection;

    }

    private function constructDataInSingleMode(){
        // get num of fields
        $numOfFields = $_POST['counter'];
        // generating
        $collection = array();
        for ($i = 0; $i < $numOfFields; $i ++){
            // get values
            $subject = $_POST['field_div_id_'.$i.'_subject'];
            $year = $_POST['field_div_id_'.$i.'_year'];

            if (!in_array($subject,$collection)){
                array_push($collection,$subject);
            }
            if (!isset($collection[$subject])){
                $tmp = [$year];
                $collection[$subject] = $tmp;
            }else{
                $tmp = $collection[$subject];
                if (!in_array($year,$tmp)){
                    array_push($tmp,$year);
                }
                $collection[$subject] = $tmp;
            }
        }
        return $collection;
    }
}