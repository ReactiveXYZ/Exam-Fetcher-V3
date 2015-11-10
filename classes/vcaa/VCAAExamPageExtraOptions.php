<?php
namespace VCAA;
class VCAAExamPageExtraOptions{

    public static function sendEmail($from,$to,$message){

        $response = null;

        //validate from and to emails
        if (filter_var($from,FILTER_VALIDATE_EMAIL) && filter_var($to,FILTER_VALIDATE_EMAIL)){
            //set a header
            $header = 'From'.$from.'\r\n';
            $subject = "Hey, check out these VCAA exams!";
            //send
            mail($to,$subject,$message,$header);
            $response = "Successfully Sent :)";

        }else{
            $response = "Email validation failed. Please retry entering your email";
        }

        return $response;

    }


    public static function refreshHomePageCache(){
        //check if cache exists
        if (file_exists(TEMP_URL.'home.html')){
            // retrieve the file
            $file = file_get_html(VCAAExamController::getBaseURLToLoad(),false,VCAAExamController::getContext());
            // delete existing file
            unlink(TEMP_URL.'home.html');
            // load new file
            $fo = fopen(TEMP_URL.'home.html','w');
            fwrite($fo,$file);
            fclose($fo);
            return "Successfully refreshed";
        }else{
            return "Error! Cache does not exist!";
        }
    }


}