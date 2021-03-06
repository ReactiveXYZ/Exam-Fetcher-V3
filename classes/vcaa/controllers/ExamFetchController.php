<?php 
namespace VCAA\controllers;

use VCAA\exam\Exam;
use VCAA\exam\ExamFactory;
//use VCAA\db\DatabaseRequest;

require_once('config.php');

require_once(LIB_URL."simple_html_dom.php");
require_once(LIB_URL."zipstream.php");

require_once(CLASS_URL."helper.php");

/**
* New in V3: Exam Fetch Controller		
*/					
class ExamFetchController

{
	protected $dom;
	
	private $context;

    protected static $base_url = "http://www.vcaa.vic.edu.au";

    private $is_paper; private $is_report; private $fetch_mode;

    private $factory;

    private $subject_options_conn;

    /**
     * Constructor
     * */
	public function __construct($is_exam_paper,$is_exam_report, $mode = -1)
	{
		//set scheme
		$this->is_paper = $is_exam_paper;
        $this->is_report = $is_exam_report;
        $this->fetch_mode = $mode;

        //generate context for UA
        $schema = array('http' => array('user_agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'));

        $this->context = stream_context_create($schema);

        //preload document
        $this->dom = file_get_html(TEMP_URL."home.html");

        //initialize exam factory
        $this->factory = new ExamFactory();

        //initialize subject options
        //$this->subject_options_conn = new DatabaseRequest('subject_options');

	}

    /**
     * Construct and exam array with group
     * */
    public function fetch_and_construct($subjects_array = array(),$year_array = array(),$from = null,$to = null)
    {

        if (!$this->dom) {
            exit();
        }

        // Single Mode
        if ($this->fetch_mode == "single") {
            
            $data = $this->single_mode_construct_subject_year_arrays();
                
            for ($i = 0; $i < count($data); $i++) { 

                if (isset($data[$i])) {

                    $single_subject = $data[$i];

                    $single_subject_year_array = $data[$single_subject];

                    $this->real_work((array)$single_subject , (array)$single_subject_year_array);
                }

            }
        }

        // Bulk Mode
        if ($this->fetch_mode == "bulk") {
            
            // The array input is predetermined to be a string
            $subjects_array = $this->textext_string_to_array($subjects_array);

            if ($from && $to) {
                
                $year_array = $this->year_with_start_and_end($from,$to);

            }
            else {

                $year_array = $this->textext_string_to_array($year_array);

            }

            $this->real_work($subjects_array,$year_array);

        }

        $output = $this->factory->getExamDataArray(true);

        return $output;

    }

    /**
     * The actual construction and grouping of exam instances
     * */
    private function real_work(array $subjects_array,array $year_array)
    {
        for ($i=0; $i < count($subjects_array) ; $i++) { 
            
            //Check if the exam can be processed
            //$needs_to_be_cut = $this->subject_options_conn->get_processed_document((string)$subjects_array[$i]);

            //Start working
            $subject_page = file_get_html($this->find_subject_page_url((string)$subjects_array[$i]),false,$this->context);

            foreach ( $subject_page->find('table[class=tablestyle4]') as $table){

                for ($j=0; $j < count($year_array); $j++) { 
                    
                    foreach ($table->find('tr') as $tr) {
                        
                        $year = preg_replace('/[^\00-\255]+/u', '', $tr->find('td',0)->innertext);

                        // Final Step
                        if ((string)$year_array[$j] == $year) {

                            // Check schema
                            $section_to_fetch = null; $exceptions = [];

                            // load section to fetch 
                            if ($this->is_paper && $this->is_report) {

                                $section_to_fetch = $tr;

                                // check unreleased content
                                $exam_section = $tr->find('td',1);

                                $report_section = $tr->find('td',2);

                                if ($this->check_has_unrelease_contents($exam_section)) {
                                    
                                    array_push($exceptions, "exam");

                                }

                                if ($this->check_has_unrelease_contents($report_section)) {
                                    
                                    array_push($exceptions, "report");

                                }

                            }
                            elseif ($this->is_paper) {

                                $section_to_fetch = $tr->find('td',1);

                                // check unreleased content
                                if ($this->check_has_unrelease_contents($section_to_fetch)) {
                                    
                                    array_push($exceptions, "exam");

                                }

                            }
                            elseif ($this->is_report){

                                $section_to_fetch = $tr->find('td',2);

                                // check unreleased content
                                if ($this->check_has_unrelease_contents($section_to_fetch)) {
                                    
                                    array_push($exceptions, "report");

                                }
                            }

                            //fetch and construct
                            foreach ($section_to_fetch->find('a') as $link){
                                //Collect data
                                $title = $this->generate_subject_title($link->href);

                                $link_url = $link->href;

                                $year_title = $year;

                                $subj_name = (string)$subjects_array[$i];

                                $options = array(

                                    'subject_name' => $subj_name,

                                    'title' => $title,

                                    'year' => $year_title,

                                    'url' => $this::$base_url.$link_url
                                );
                                
                                //Pack exam
                                $this->factory->package_exam_instance_with_data($options);
                            }

                            // load exceptions
                            foreach ($exceptions as $exception) {

                                $options = array(

                                    'subject_name' => $subj_name,

                                    'title' => $exception." not published",

                                    'year' => $year_title,

                                    'url' => "not_available"
                                );
                                
                                //Pack exam
                                $this->factory->package_exam_instance_with_data($options);                                

                            }


                        }

                    }

                }

            }

        }

    }

    /**
     * Check if the section has unreleased contents 
    **/ 
    private function check_has_unrelease_contents($html)
    {
        
        foreach ($html->find("text") as $text) {
            
            if (preg_match('/available/', $text)) {

                return true;

            }
            
        }

        return false;

    }

	/* *
	 * Get the page url of each subject
	 * */
    private function find_subject_page_url($subject_name)		
    {
    	foreach ($this->dom->find('a') as $element){

            $name = $element->innertext;

            if ($this->match($subject_name,$name)){

                return $this::$base_url.$element->href;

                break;
            }
        }
        return null;
    }

    /**
     * Filter two strings and compare  
     */
    private function match($input,$source)
    {
        $decodedSource = str_replace("\xc2\xa0",' ',html_entity_decode($source));

        $converted_input = str_replace(" ", '', $input);

        if (preg_replace('/\s+/','',$decodedSource) == $converted_input){

            return true;
        
        }
        return false;
    }


    /**
     * Check if the input and whats on the website are the same
     * */
    private function generate_subject_title($source_url)
    {
        $pos = strrpos($source_url,'/')+1;

        $title = substr($source_url,$pos,strlen($source_url)-$pos);

        return $title;
    }

    /**
     * For Single Mode:
     * construct two arrays of subjects and years retrieved from single mode 
     * */
    private function single_mode_construct_subject_year_arrays()
     {
        $numOfFields = get_post('counter');

        $collection = array();

        for ($i = 0; $i < $numOfFields; $i ++){

            $subject = get_post('field_div_id_'.$i.'_subject');

            $year = get_post('field_div_id_'.$i.'_year');

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

    /**
     * For Bulk Mode:
     * construct a list of years with start and end
     * */
    private function year_with_start_and_end($start,$end)
    {
    	$array = array();

        for ($i = $start; $i <= $end; $i++){

            array_push($array,$i);

        }
        return $array;
    }

    /**
     * For Bulk Mode:
     * Convert String to Array
     * */
    private function textext_string_to_array($string)
    {
        $replaceFrom = array("[","\"","]");

        $replaceTo = array("","","");

        $cleaned = str_replace($replaceFrom,$replaceTo,$string);

        $result = explode(",",$cleaned);

        return $result;
    }
    
    /**
     * Convert filename to file url
     * */
    private function convert_filename_to_url($filename)
    {
        return REPO_URL.'processed/'.$filename;
    }

}