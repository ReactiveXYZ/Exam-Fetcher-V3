var HTMLSubstitute = "<h3 style='text-align: center'>Check out the exams downloaded in the table below! Or you can <b>DOWNLOAD THEM ALL</b> using the button below.</h3>" +
"<div align='center'> <a class='form__btn btn--info' id='download-zip'><strong>Download to zip</strong></a> <br/> <br/>  <a class='form__btn btn--info' id='download-file'><strong>Download by file</strong></a> <h3 style='text-align: center'>OR</h3> <a class='form__btn btn--success' id='share-exams'>Share these exams</a> <a class='form__btn btn--default' id='reset-table'>Reset the form</a> </div> <br/>";
// ================= Single Mode ===================

/**
 * Add an extra field in single mode
 */
function addField(){

    var subjectPlaceholder = "Type a few characters and select a subject";
    var yearPlaceholder = "Type a few characters and select a year";

    var form = document.getElementById("container");
    //create container
    var div = document.createElement("div");
    div.id = "field_div_id_"+fieldSet;

    div.innerHTML += "<h5>Enter your subject</h5>";
    //create subject field
    var subjectField = document.createElement("input");
    subjectField.type = "text";
    subjectField.name = "field_div_id_"+fieldSet+"_subject";
    subjectField.id = "field_div_id_"+fieldSet+"_subject";
    subjectField.className = "form__input";
    subjectField.placeholder = subjectPlaceholder;
    div.appendChild(subjectField);

    div.innerHTML += "<h5>Enter year</h5>";
    //create year field
    var yearField = document.createElement("input");
    yearField.type = "text";
    yearField.name = "field_div_id_" + fieldSet + "_year";
    yearField.id = "field_div_id_" + fieldSet + "_year";
    yearField.className = "form__input";
    yearField.placeholder= yearPlaceholder;
    div.appendChild(yearField);
    div.innerHTML += "<br/>";

    //create hidden counter
    form.appendChild(div);

    fieldSet++;

    //Reveal remove button
    var removeBtn = document.getElementById('removeBtn');
    removeBtn.style.display = "inline";

    //Reveal submit button
    var submitBtn = document.getElementById('submit');
    submitBtn.style.display = "inline";

    linkSource();
}

/**
 * Remove a subject field in single mode
 */
function removeField(){

    if (fieldSet == 1){
        var removeBtn = document.getElementById('removeBtn');
        removeBtn.style.display = "none";
        var submitBtn = document.getElementById('submit');
        submitBtn.style.display = "none";
    }
    var newestDiv = document.getElementById("field_div_id_"+(fieldSet-1));
    newestDiv.parentNode.removeChild(newestDiv);

    fieldSet--;
}

/**
 * Handle the submit of single mode form
 * @return {mixed} data to be analysed
 */
$("body").on('submit','#sform',function(e){
    //Add to recents
    addToRecentsBySubmit();

    $('#counter').val(fieldSet);

    showPreloader("Fetching...");

    var postData = $(this).serializeArray();

    var formURL = 'site/interact.php';

    $.ajax({
        url: formURL,
        type: "POST",
        data: postData,
        success:function(data){

            constructTableFromData("single",data);

        },
        error:function(){

            hidePreloader();

            console.log("ERROR");
            //error
            createInformationalAlertWithTitleAndDelay("Error! Please report!",1700,false);
        }
    });
    e.preventDefault();

});

// ================= Bulk Mode ======================
/**
 * Handle the submit of bulk mode form
 * @return {mixed}      data to be analysed
 */
$('body').on('submit','#bform',function(e){

    //Validate
    var subjectVal = $('input[type="hidden"][name="bulk_subject"]').val();

    var yearVal = $('input[type="hidden"][name="bulk_year"]').val();

    if (subjectVal.length <= 2 || yearVal.length <= 2){

        if ($('#from-year').val().length <1 || $('#to-year').val().length<1 ) {
            
            alert("Please fill in the required fields");
           
            e.preventDefault();
            
            return false;
        }

        showPreloader("Fetching...");

    }else{

        showPreloader("Fetching...");

    }

    //Add to recents
    addToRecentsBySubmit();

    //submit
    var formURL = 'site/interact.php';

    var postData = $(this).serializeArray();

    $.ajax({
        url: formURL,
        type: "POST",
        data: postData,
        success: function (data) {
            
            constructTableFromData("bulk",data);

        },
        error: function (data) {

            hidePreloader();

            console.log("ERROR");
            // Error
            createInformationalAlertWithTitleAndDelay("Error! Please report!",1700,false);
        }
    });

    e.preventDefault();

});

// ================= Extraction Mode ================
/**
 * Handle the submit of extraction mode form
 * @return {void}      [Trigger download of formula sheets]
 */
$('#eform').submit(function(e){

    //Get the matched url for extraction
    $('#ext_selected').val(extractionList[$('#ext_subject').val()]);

});

// ================= Post fetching actions ============
/**
 * Download one by one clicked            
 * @return {void} 
 */
$('body').on( "click" , "#download-file" , function(e){

    //check for support
    if (isDownloadAttrAvailable()){

        switch (mode) {

            case modeSet.SINGLE: {

                var singleForm = document.getElementById('single');

                var hiddenLinksInSingle = singleForm.getElementsByClassName('hidden-download');
                
                for (var i = 0; i < hiddenLinksInSingle.length; i++){
                    
                    hiddenLinksInSingle[i].click();
                
                }
            }
                break;

            case modeSet.BULK: {

                var bulkForm = document.getElementById('bulk');
                
                var hiddenLinksInBulk = bulkForm.getElementsByClassName('hidden-download');
                
                for (var j = 0; j < hiddenLinksInBulk.length; j++){
                    
                    hiddenLinksInBulk[j].click();
                
                }
            }
                break;
            default :
                break;
        }
    }else{
        alert ('Your browser does not support this download method! Please use the zip download instead');
    }

    e.preventDefault();
});


$('body').on( "click" , "#download-zip" , function(e) {
    
    var json;    

    if (mode == modeSet.SINGLE){

        json = regainData('single');

    }else if(mode == modeSet.BULK){

        json = regainData('bulk');

    }

    manualPost(json,'download-zip');

});

// ================== Helper functions ================
/**
 * Analyse the JSON data and construct result table
 * @param  {String} table_name The ID of the result table 
 * @return {mixed}            UI Updated
 */
function constructTableFromData (table_name,data) {

    // Log the data
    document.getElementById('nonsense-'+table_name).innerText = data;
    
    var div = document.getElementById(table_name);
    div.innerHTML = HTMLSubstitute;

    var jsonObj = $.parseJSON(data);

    var tableResult = document.createElement('table');

    tableResult.id = "table-result-"+table_name;
    tableResult.style.width = "70%";
    tableResult.style.margin = "0 auto";
    tableResult.innerHTML += "<thead> <tr> <th>Subject</th> <th>Year</th> <th>Download links</th> </tr> </thead>";

    $.each(jsonObj,function(key,val){
        //Create table rows
        var tr = document.createElement('tr');

        var tdSubject = document.createElement('td');
        tdSubject.rowSpan = Object.keys(val).length+1;
        tdSubject.innerText = key;

        var tdReplacement = document.createElement('td');
        tdReplacement.className = "td-replacement";

        tr.appendChild(tdSubject);
        tr.appendChild(tdReplacement);

        tableResult.appendChild(tr);
                
        $.each(val,function(idx,vlu){

            var trEachYear = document.createElement('tr');

            var tdEachYear = document.createElement('td');

            tdEachYear.innerText = idx;

            trEachYear.appendChild(tdEachYear);

            var tdEachExam = document.createElement('td');
                    
            $.each(vlu,function(index,exam){

                var anchor = document.createElement('a');

                anchor.innerText = exam["title"];

                anchor.href = exam["url"];

                anchor.className = "downloadable";

                anchor.target = "_blank";

                tdEachExam.appendChild(anchor);

                var hiddenAnchor = document.createElement('a');

                hiddenAnchor.href = exam["url"];

                hiddenAnchor.style.display = "none";

                hiddenAnchor.className = "hidden-download";

                hiddenAnchor.setAttribute('download', exam["url"]);

                tdEachExam.appendChild(hiddenAnchor);

                tdEachExam.innerHTML += "<br/>";

            });

            trEachYear.appendChild(tdEachExam);

            tableResult.appendChild(trEachYear);
                  
            });

        });

    div.appendChild(tableResult);

    // Success
    createInformationalAlertWithTitleAndDelay("Success:)",1700,true);
    // Append style
    var style = "<style type='text/css'>" + "table{background:#fff;border-radius:3px;border-collapse:collapse;height:50px;margin:auto;max-width:960px;padding:5px;width:100%;box-shadow:0 5px 10px rgba(0,0,0,0.1);animation:float 5s infinite}th{color:#D5DDE5;background:#1b1e24;border-bottom:4px solid #9ea7af;border-right:1px solid #343a45;font-size:23px;font-weight:100;padding:24px;text-align:left;text-shadow:0 1px 1px rgba(0,0,0,0.1);vertical-align:middle}th:first-child{border-top-left-radius:3px}th:last-child{border-top-right-radius:3px;border-right:none}tr{border-top:1px solid #C1C3D1;border-bottom-:1px solid #C1C3D1;color:#666B85;font-size:16px;font-weight:400;text-shadow:0 1px 1px rgba(256,256,256,0.1)}tr:first-child{border-top:none}tr:last-child{border-bottom:none}tr:nth-child(odd) td{background:#EBEBEB}tr:last-child td:first-child{border-bottom-left-radius:3px}tr:last-child td:last-child{border-bottom-right-radius:3px}td{background:#FFF;padding:20px;text-align:left;vertical-align:middle;font-weight:300;font-size:18px;text-shadow:-1px -1px 1px rgba(0,0,0,0.1);border-right:1px solid #C1C3D1}td:last-child{border-right:0}"+"</style>";
            
    $("head").append(style);

    hidePreloader();

    $('#addBtn').fadeOut();

    $('#removeBtn').fadeOut();

    //Refresh UI
    refreshUIFromLocalStorage();

    //clean up mess
    $('.td-replacement').remove();
}

/**
 * Regain the data for download
 * @param  {String} tableID 
 * @return {String}         
 */
function regainData (tableID) {
    
    return document.getElementById('nonsense-'+tableID).innerText;

}

function manualPost (val,action) {

    var theForm, newInput1,newinput2;

    // Start by creating a <form>
    theForm = document.createElement('form');
    theForm.action = 'site/interact.php';
    theForm.method = 'post';
    
    // Next create the <input>s in the form and give them names and values
    newInput1 = document.createElement('input');
    newInput1.type = 'hidden';
    newInput1.name = 'download';
    newInput1.value = val;

    // Create an action indicator
    newinput2 = document.createElement('input');
    newinput2.type = "hidden";
    newinput2.name = 'action';
    newinput2.value = action;

    // Now put everything together...
    theForm.appendChild(newInput1);
    theForm.appendChild(newinput2);

    // ...add it to the DOM...
    document.getElementById('post-result-div').appendChild(theForm);
    // ...and submit it
    theForm.submit();

}




