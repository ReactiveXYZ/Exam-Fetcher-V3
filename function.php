<?php

require_once "vendor/autoload.php";

use VCAA\VCAAExamFetchController;
use VCAA\VCAAExamDownloader;

/*** GET METHODS ***/
if (isset($_GET['remotedownload'])){
    //retrieve data
    $downloadData = json_decode(base64_decode($_GET['remotedownload']),true);
    //download to zip
    VCAAExamDownloader::downloadToZip($downloadData);
}

/*** POST METHODS ***/
// Download
if ($_POST['action'] == "download-zip"){
    //retrieve data
    $downloadData = json_decode($_POST['download'],true);
    //download to zip for now
    VCAAExamDownloader::downloadToZip($downloadData);

}else if($_POST['action'] == "single-download"){
    $url = VCAAExamFetchController::getBaseURLToLoad().$_POST['download'];
    //download
    VCAAExamDownloader::downloadFile($url);
}
else if($_POST['action'] == "fetch")
// Normal fetching
{
    // retrieve mode indicator
    $modeIndicator = null; $paperChecked = false; $reportChecked = false;
    if (isset($_POST['modeIndicator'])){
        $modeIndicator = $_POST['modeIndicator'];
    }
    // retrieve checkbox values
    if (isset($_POST['paperChecked'])){
        $paperChecked = true;
    }
    if (isset($_POST['reportChecked'])){
        $reportChecked = true;
    }

    // construct controller
    $controller = null;
    // find subjects and years array
    $year_arr = null; $subj_arr = null;
    // --- for single -> construct away
    if ($modeIndicator == ExamFetchingMode::SINGLE){
        //Retrieve checkbox value
        $singleModePaperChecked = $_POST['singlePaperChecked'];
        $singleModeReportChecked = $_POST['singleReportChecked'];
        //Construct controller
        $controller = new VCAAExamFetchController($singleModePaperChecked,$singleModeReportChecked,$modeIndicator);
        echo $controller->output();
        exit();
    }
    // --- for bulk -> get values and construct
    if ($modeIndicator == ExamFetchingMode::BULK){
        //Retrieve checkbox value
        $bulkModePaperChecked = $_POST['bulkPaperChecked'];
        $bulkModeReportChecked = $_POST['bulkReportChecked'];
        //Construct controller
        $controller = new VCAAExamFetchController($bulkModePaperChecked,$bulkModeReportChecked,$modeIndicator);
        // get datas
        $subj_arr = $_POST['bulk_subject'];
        $year_arr = $_POST['bulk_year'];
        if (isset($_POST['from-year']) && isset($_POST['to-year'])){
            $from = $_POST['from-year']; $to = $_POST['to-year'];
            echo $controller->output($subj_arr,$year_arr,$from,$to);
        }else{
            echo $controller->output($subj_arr,$year_arr);
        }
        // output
        exit();
    }
}//send mail
elseif ($_POST['action'] == "sendmail"){
    // retrieve variables
    $from_email = $_POST['modal-from-email'];
    $to_email = $_POST['modal-to-email'];
    $message = $_POST['content-value'];
    // Send
    echo VCAAExamPageExtraOptions::sendEmail($from_email,$to_email,$message);
    exit();
}//reload cache
elseif ($_POST['action'] == "reloadCache"){
    //refresh
    echo VCAAExamPageExtraOptions::refreshHomePageCache();
}//print
elseif  ($_POST['action'] == "print"){
    //get url data
    $url = $_POST['url'];
    $pos = strrpos($url,'/')+1;
    $title = substr($url,$pos,strlen($url)-$pos);
    //download to server
    $copied = VCAAExamDownloader::downloadFileToServer($url,$title,'temp');
    $response = array();
    if ($copied){
        $response["status"] = "1";
        $response["dir"] = "/temp/".$title;
    }else{
        $response["status"] = "0";
        $response["dir"] = "";
    }
    echo json_encode($response);
}elseif ($_POST['action'] == "ext_download"){
    //get data
    $file = REPO_URL.$_POST['ext_selected'];
    $filename = $_POST['ext_subject'];
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



