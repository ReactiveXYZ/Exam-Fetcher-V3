<?php

namespace VCAA\controllers;

require_once ('config.php');

require_once(LIB_URL."zipstream.php");

/**
* Exam download handling
*/

class ExamDownloaderController
{
	/**
	 * Force download a single file
	 * @param  String $url [URL of the target file]
	 * @return mixed      the file
	 */
	public static function download_file($url)
	{
		set_time_limit(0);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $r = curl_exec($ch);

        curl_close($ch);

        header('Expires: 0'); // no cache
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
        header('Cache-Control: private', false);
        header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename="' . basename($url) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($r)); // provide file size
        header('Connection: close');

        echo $r;
	}

	/**
	 * Force download files into a zip folder
	 * @param  array $data [list of urls to add to zip]
	 * @return mixed       Zip Objects
	 */
	public static function download_to_zip($data)
	{
		
		// set cookie to notify the frontend	
		setcookie('fileLoading',true,time()+10,'/');

		// create ZipStream
		$zipStream = new ZipStream('Exams.zip');

        foreach ($data as $item){

            //Add files
            $filesInDirectory = $item["value"];

            foreach ($filesInDirectory as $file){

                //Download file
                $downloaded_file = file_get_contents($file);

                //Add to zip
                $zipStream -> add_file($item["key"]."/".basename($file),$downloaded_file);
            }
        }

        $zipStream->finish();

        // clean up
        ob_clean(); flush();
	}

	/**
	 * Download remote file to the server 
	 * @param  String $url      
	 * @param  $filename 
	 * @param  $fileDir  
	 * @return void          
	 */
	public static function download_file_to_server($url,$filename,$fileDir)
	{
		if (file_exists($filedir.'/'.$filename)) {
			
			return true;

		}

		try {
			$file = file_get_contents($url);

			$open_stream = fopen($fileDir.'/'.$filename, 'w');

			fwrite($fo, $file);

			fclose($fo);

		} catch (Exception $e) {
			
			error_log($e->getMessage());

			return false;

		}
		
		return true;
	
	}

	/**
	 * Force download file from the server
	 * @param  String $file     
	 * @param  String $filename
	 * @return void 
	 */
	public static function download_file_from_server($file,$filename)
	{
		
		header("Content-Type: application/octet-stream");
    	header("Content-Disposition: attachment; filename=" .$filename.'.pdf');
    	header("Content-Type: application/octet-stream");
    	header("Content-Type: application/download");
    	header("Content-Description: File Transfer");
    	header("Content-Length: " . filesize($file));

    	flush(); // this doesn't really matter.
    	
    	$fp = fopen($file, "r");
    	
    	while (!feof($fp))
    	{
        	
        	echo fread($fp, 65536);
        	
        	flush(); // this is essential for large downloads
    	
    	}
    	
    	fclose($fp);
	}

}