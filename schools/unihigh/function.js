/**
 * Created by ying on 11/09/15.
 */

//Init
$(document).ready(function(){
    //Disable useless fields
    $('input#publisher_text').attr("disabled","");
    $('#year_text').attr("disabled","");
    $('#submit').attr("disabled","");
    //load subject data source
    var input = {"action":"init_load"};
    $.ajax({
        type:"post",
        url:'function.php',
        data:input,
        success:function(response){
            //get the list
            var init_list_data = JSON.parse(response);
            //load automcomplete for subject
            $('#subject_text').autocomplete({
                autoFocus:true,
                source:init_list_data,
                select:function(event,ui){
                    var input = {"action":"publisher_load","selected":ui.item.value};
                    $.ajax({
                        type:"post",
                        url:"function.php",
                        data:input,
                        success: function (response) {
                            var publisher_list_data = JSON.parse(response);
                            //unlock and load autocomplete for publisher
                            $('#publisher_text').autocomplete({
                                source:publisher_list_data,
                                autoFocus:true,
                                select: function (event, ui) {
                                    var selected_bundle = JSON.stringify({"subject":$('#subject_text').val(),"publisher":ui.item.value});
                                    var input = {"action":"year_load","selected":selected_bundle};
                                    $.ajax({
                                        type:"post",
                                        url:"function.php",
                                        data:input,
                                        success: function (response) {
                                            var year_list_data = JSON.parse(response);
                                            //load autocomplete for year
                                            $('#year_text').autocomplete({
                                                autoFocus:true,
                                                source:year_list_data,
                                                select: function () {
                                                    $('#submit').removeAttr('disabled');
                                                }
                                            }).removeAttr("disabled");
                                        },
                                        error: function (response) {
                                            createInformationalAlertWithTitleAndDelay("Error connecting to server",1700,false);
                                        }
                                    });
                                }
                            }).removeAttr("disabled");
                        },
                        error: function (response) {
                            createInformationalAlertWithTitleAndDelay("Error connecting to server",1700,false);
                        }
                    });
                }
            });

        },
        error:function(response){
            createInformationalAlertWithTitleAndDelay("Error connecting to server",1700,false);
        }
    });

});

//Single exam download form submit
$('#fetch_form').submit(function(e){
    e.preventDefault();
    var data = $(this).serializeArray();
    data.push({name:"action",value:"download_single"});
    $.ajax({
        type:"post",
        url:"function.php",
        data:data,
        success: function (response) {
            createInformationalAlertWithTitleAndDelay("Successfully downloaded! Enjoy your exam!",1700,true);
        },
        error: function (response) {
            createInformationalAlertWithTitleAndDelay("Error submitting! Please refresh and try again later",1700,false);
        }
    });
});

//Helper functions
function createInformationalAlertWithTitleAndDelay(title,delay,isSuccess){
    if (typeof alertify != "undefined"){
        alertify.set({delay:delay});
        if (isSuccess){
            alertify.success(title);
        }else{
            alertify.error(title);
        }
    }else{
        alert(title);
    }

}

