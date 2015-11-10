<?php

namespace VCAA\exam\;

use VCAA\exam\Exam;

/**
 * 
 * Package and analyse exam instances
 * 
*/
class ExamFactory
{
	
	protected $exam_data_array = array();


	public function __construct()
	{		
		
	}

    /**
     * generate exam instance
     *
     * @param String $text Url grabbed from website , String $options Extra information
     * 
     * @return Exam $exam
     *  
     **/
    public function package_exam_instance_with_data($options)
    {
        $extra_options = $options["options"]?$options["options"]:null;

        $exam = new Exam($options["name"],$options["year"],$options["url"],$extra_options);
        
        $this->package_exam($exam);

    }

	/**
	 * Package exams into an analysable array of data
	 *
	 * @param VCAAExam\Exam $exam_instance [an exam instance] 
	 * 
	 * @return void
	 **/

	private function package_exam(Exam $exam_instance)
	{
		array_push($this->exam_data_array, $exam_instance);
	}

    /**
     * Group exams with certain rules
     *
     * @param null
     * 
     * @return array[subject][year]
     * 
     **/

    public function group()
    {
    	$output = array();
        
        //group by exam names

        foreach ($this->exam_data_array as Exam $exam){

        	if (!$output[$exam->getName()]) {

        		$output[$exam->getName()] = array();

        	}else{

        		array_push($output[$exam->getName()], $exam);

        	}
        }
        
        //group by exam years

        foreach ($output as $subject => $exams){

            $year_output = array();

        	foreach ($exams as $exam){

        		if (!$year_output[$exam->getYear()]) {

        			$year_output[$exam->getYear()] = array();
        		
        		}else{

        			array_push($year_output[$exam->getYear()], $exam);
        			
        		}
        	}
            
            $output[$subjects] = $year_output;
        }
    	
    	return $output;
    }



}
