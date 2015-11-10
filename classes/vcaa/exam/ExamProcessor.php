<?php

namespace VCAA\exam;

use VCAA\exam\Exam;
use VCAA\db\DatabaseRequest;

/**
* VCAA Exam Processor
*/

class ExamProcessor
{
	
	public function __construct()
	{
		
	}	

	
	/**
	 * PDF Processor to cut off formula sheets etc
	 *
	 * @param PDF $source_file original PDF , Int $num_of_pages Number of last few pages to cut
	 * 
	 * @return PDF object
	 * 
	 **/
	public function cut_numbers_of_pages($subject_name)
	{
         
	}


	/**
	 * Generate Readable Exam Names Instead of the PDF filename
	 *
	 * @param String $source [Original filename]
	 * 
	 * @return String $new_name
	 * 
	 **/
	public function generate_exam_name($source)
	{	

	}


}