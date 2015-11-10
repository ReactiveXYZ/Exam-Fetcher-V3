<?php
require ('vendor/autoload.php');

use VCAA\controllers\ExamFetchController;

if($_POST['action'] == "fetch")
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
    if ($modeIndicator == "single"){
        //Retrieve checkbox value
        $singleModePaperChecked = $_POST['singlePaperChecked'];
        $singleModeReportChecked = $_POST['singleReportChecked'];
        //Construct controller
        $controller = new ExamFetchController($singleModePaperChecked,$singleModeReportChecked,$modeIndicator);
        echo $controller->fetch_and_construct();
        exit();
    }
    // --- for bulk -> get values and construct
    if ($modeIndicator == "bulk"){
        //Retrieve checkbox value
        $bulkModePaperChecked = $_POST['bulkPaperChecked'];
        $bulkModeReportChecked = $_POST['bulkReportChecked'];
        //Construct controller
        $controller = new ExamFetchController($bulkModePaperChecked,$bulkModeReportChecked,$modeIndicator);
        // get datas
        $subj_arr = $_POST['bulk_subject'];
        $year_arr = $_POST['bulk_year'];
        if (isset($_POST['from-year']) && isset($_POST['to-year'])){
            $from = $_POST['from-year']; $to = $_POST['to-year'];
            echo $controller->fetch_and_construct($subj_arr,$year_arr,$from,$to);
        }else{
            echo $controller->fetch_and_construct($subj_arr,$year_arr);
        }
        // output
        exit();
    }
}