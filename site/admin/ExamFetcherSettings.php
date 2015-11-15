<?php 
require_once ('../../../classes/vcaa/controllers/config.php');
require_once ('../../../vendor/autoload.php');

use VCAA\db\DatabaseRequest;
/**
* Extra settings for the VCAA fetcher site
*/
class ExamFetcherSettings
{
		
	public function __construct()
	{
		
	}

    /**
     * Trigger reloading of the home page HTML (usually once a year)
     * */
	public static function refresh_home_cache()
	{
        if (file_exists(TEMP_URL.'home.html')){

            $file = file_get_html("http://www.vcaa.vic.edu.au/pages/vce/exams/examsassessreports.aspx");

            unlink(TEMP_URL.'home.html');

            $fo = fopen(TEMP_URL.'home.html','w');
            fwrite($fo,$file);
            fclose($fo);

            return "Successfully refreshed";

        }else{

            return "Error! Cache does not exist!";

        }

	}

    /**
     * 
     * Enter maintanence mode of exam fetcher
     * 
     * */
    public static function enter_maintenance_mode(DatabaseRequest $request){

        if ($request->enter_maintenance_mode()) {
            
            return true;

        }

        return false;

    }

    /**
     *
     * Check if in maintanence
     * 
     */
    public static function check_if_in_maintance(DatabaseRequest $request)
    {
        
        if ($request->check_maintanence()) {
            
            return true;

        }

        return false;

    }

}