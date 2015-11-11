<?php

namespace VCAA\exam;

use VCAA\exam\Exam;

/**
 * 
 * Package and analyse exam instances
 * 
*/
class ExamFactory
{
	
	protected $exam_data_array = array();

    /*
     * @todo constructor injection of database request
     */
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

        $exam = new Exam($options["subject_name"],$options["title"],$options["year"],$options["url"],$extra_options);
        
        $this->package_exam_on_fly($exam);

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

    /** Package and group exams on the fly */

    private function package_exam_on_fly(Exam $exam_instance)
    {
        $this->group_on_fly($exam_instance);
    }

    /**
     * Group exam instances on the fly
     * 
     * @param  Exam   $exam Single Exam Instance
     * 
     * @return void
     */
    
    private function group_on_fly(Exam $exam)
    {
        if (!isset($this->exam_data_array[$exam->getSubjectName()])) {

            $this->exam_data_array[$exam->getSubjectName()] = array();

            if (!isset($this->exam_data_array[$exam->getSubjectName()][$exam->getYear()])) {
            
                $this->exam_data_array[$exam->getSubjectName()][$exam->getYear()] = array();

                array_push($this->exam_data_array[$exam->getSubjectName()][$exam->getYear()], $exam);
           
            }else{

                array_push($this->exam_data_array[$exam->getSubjectName()][$exam->getYear()], $exam);

            }

        }else{

            if (!isset($this->exam_data_array[$exam->getSubjectName()][$exam->getYear()])) {
            
                $this->exam_data_array[$exam->getSubjectName()][$exam->getYear()] = array();

                array_push($this->exam_data_array[$exam->getSubjectName()][$exam->getYear()], $exam);
           
            }else{
                
                array_push($this->exam_data_array[$exam->getSubjectName()][$exam->getYear()], $exam);

            }

        }


    }

    /**
     * Group exams with certain rules
     *
     * @param $option [set true to return a JSON representation]
     * 
     * @return array[subject][year]
     * 
     **/

    public function group($option)
    {
    	
        $output = array();

        //group by exam names

        foreach ($this->exam_data_array as $exam){

        	if (!isset($output[$exam->getSubjectName()])) {

        		$output[$exam->getSubjectName()] = array();

                array_push($output[$exam->getSubjectName()], $exam);

        	}else{

        		array_push($output[$exam->getSubjectName()], $exam);

        	}
        }
        
        //group by exam years

        foreach ($output as $subject => $exams){

            $year_output = array();

        	foreach ($exams as $exam){

        		if (!isset($year_output[$exam->getYear()])) {

        			$year_output[$exam->getYear()] = array();

                    array_push($year_output[$exam->getYear()], $exam);
        		
        		}else{

        			array_push($year_output[$exam->getYear()], $exam);
        			
        		}
        	}
            
            $output[$subject] = $year_output;
        }
    	
        //clean cache
        $this->exam_data_array = array();

    	return $output;
    }




    /**
     * Gets the value of exam_data_array.
     *
     * @param Boolean $option [set true to return JSON]
     *
     * @return mixed
     */
    public function getExamDataArray($option = false)
    {

        if ($option) {
            
            return json_encode($this->exam_data_array);

        }

        return $this->exam_data_array;
    }
}
