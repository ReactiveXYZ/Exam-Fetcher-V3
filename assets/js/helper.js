/**
 * Share exam buttom clicked            
 * @return {Object}  Prompt window  
 */

$('body').on('click','#share-exams',function(e){

    // Open modal
    $overlay.addClass('state-show');

    $modal.removeClass('state-leave').addClass('state-appear');

    // Generate link and put in the contents
    var data;

    if (mode == modeSet.SINGLE){

        data = regainData('single');

    }else if(mode == modeSet.BULK){

        data = regainData('bulk');

    }
    var dataString = JSON.stringify(data);

    var downloadURL = document.domain+"/site/interact.php?remote-download="+btoa(dataString);

    $('#generate-link').click(function (e) {

        window.prompt("Link is generated! Use Ctrl/Command+C to copy!",downloadURL);

        e.preventDefault();
    });
});

/**
 * Settings button clicked
 * @return {void}         
 */
$('#advanced-settings').on('click',function(event){

    // Open modal
    $overlay.addClass('state-show');

    $modal_settings.removeClass('state-leave').addClass('state-appear');

});

/**
 * Reload form button clicked
 * @return {void}      
 */
$('body').on('click','#reset-table',function(e){

    if (mode == modeSet.SINGLE){
        document.getElementById('single').innerHTML = '<form id="sform" method="post"> <div id="container"> <div class="checkboxes" style="display: inline-block;margin: 0 auto;width:100%"> <p align="center"> <label> <input type="checkbox" class="checkbox" name="single_paper_checked" checked/> Exams |</label> <label> <input type="checkbox" class="checkbox" name="single_report_checked" checked/> Assessment reports </label> </p></div><div id="field_div_id_0"> <h5> Enter your subject </h5> <input type="text" placeholder="Type a few characters and select a subject" name="field_div_id_0_subject" id="field_div_id_0_subject" class="form__input ui-autocomplete-input" autocomplete="off" required="required"> <h5> Enter year </h5> <input type="text" placeholder="Type a few characters and select a year" name="field_div_id_0_year" id="field_div_id_0_year" class="form__input" required="required"> <br></div></div><div align="center" id="singleBtns" style="margin-bottom: 30px"> <a class="btn paper paper-raise-flatten" id="addBtn" onclick="addField()">Add a new subject field</a> <a class="btn paper paper-raise-flatten" id="removeBtn" onclick="removeField()" style="display: none;">Remove a subject field</a> </div><input type="submit" id="submit" value="Click to view the exam!"/> <input type="hidden" name="counter" id="counter"/> <input type="hidden" name="mode_indicator" id="mode_indicator" value="single"> <input type="hidden" name="action" id="action" value="fetch"> </form>';
        //Reset field count
        fieldSet = 1;
    }

    if (mode == modeSet.BULK){
        
        document.getElementById('bulk').innerHTML = '<form id="bform" method="post"> <div class="checkboxes" style="display: inline-block;margin: 0 auto;width:100%"> <p align="center"> <label> <input type="checkbox" class="checkbox" name="bulk_paper_checked" checked/> Exams |</label> <label> <input type="checkbox" class="checkbox" name="bulk_report_checked" checked/> Assessment reports </label> </p></div><div style=""> <h5>Enter your subjects:</h5> <input id="bulk_subject" placeholder="Type a few characters and select a subject" name="bulk_subject" class="form__input" style="width: 100% !important;"/> <h6>Notice: For subjects, please enter the name of subject from the beginning:<br/> E.g. When searching for "English As Additional Language", you should start by typing "Eng.." instead of "EAL". </h6> <h5>Enter years:</h5> <input id="bulk_year" placeholder="Type a few characters and select a year" name="bulk_year" class="form__input" style="width:100%; !important;"/> <div id="quick_year_selector" style="display: none"> <h3 style="text-align: center"><strong>OR</strong></h3> <h5>Get exams from a consecutive number of years! E.g. All the way from 2002 to 2014! </h5> <h5>From Year: <input type="text" name="from-year" id="from-year" class="form__input" style="display: inline;width: 20%"/> To Year: <input type="text" name="to-year" id="to-year" class="form__input" style="display: inline; width: 20%;"> </div></div><input type="submit" id="submit" name="submit" value="Click to view the exams!" style="margin-top: 20px"> <input type="hidden" id="mode_indicator" name="mode_indicator" value="bulk"> <input type="hidden" name="action" id="action" value="fetch"> </form>';
        
        $('#bulk_subject').textext({
            plugins: 'autocomplete suggestions tags filter',
            suggestions: searchIndex
        });

        $('#bulk_year').textext({
            plugins: 'autocomplete suggestions tags filter',
            suggestions: years
        });
    }
    //Link source for autocomplete
    linkSource();
    
    e.preventDefault();
});

/**
 * Add favourite button clicked
 * @return {void}     Favourite editor show
 */
$('#fav-toggle').click(function(){
    $('#add-favourites').toggle();
});

/**
 * Add favourites to the panel      
 * @return {void}     Add subject to favourite
 */
$('#fav-add-btn').click(function(){
    //Push data to local storage
    var data;

    if (localStorage['favourites']){

        data = JSON.parse(localStorage['favourites']);
        data.push($('#add-fav-field').val());
        localStorage['favourites'] = JSON.stringify(data);

    }else{

        data = [];
        data[0] = $('#add-fav-field').val();
        localStorage['favourites'] = JSON.stringify(data);

    }

    $('#add-fav-field').val("");

    //reload
    refreshUIFromLocalStorage();
});

/**
 * Check if HTML5 download attribute is available
 * @return {Boolean}
 */
function isDownloadAttrAvailable(){

    var a  = document.createElement('a');

    if (typeof a.download != "undefined"){

        return true;

    }

    return false;
}



