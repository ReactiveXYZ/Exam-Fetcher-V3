
// ========== INITIALIZATION ===========
var searchIndex = ["Accounting","AgriculturalandHorticulturalStudies","Albanian","Arabic","Armenian","Art","Auslan","AustralianHistory","AustralianPolitics","Bengali","Biology","Bosnian","Business(VCEVET)","BusinessManagement","Chemistry","ChineseFirstLanguage","ChineseSecondLanguage","ChineseSecondLanguageAdvanced","ClassicalStudies","CommunityServices(VCEVET)","ContemporaryAustralianSociety","Croatian","Czech","Dance","Dance(VCEVET)","Drama","Dutch","Economics","EngineeringStudies(VCEVET)","English","EnglishasanAdditionalLanguage(EAL)","EnglishLanguage","EnvironmentalScience","EquineStudies(VCEVET)","ExtendedInvestigation","Filipino","FinancialServices(VCEVET)","FoodandTechnology","French","Furnishing(VCEVET)","FurtherMathematics","GeneralAchievementTest(GAT)","Geography","German","Greek","GlobalPolitics","HealthandHumanDevelopment","Hebrew","Hindi","History","AustralianHistory","RenaissanceItaly","Revolutions","Hospitality(VCEVET)","Hospitality","Hospitality-KitchenOperations","Hungarian","IndigenousLanguagesofVictoria","IndonesianFirstLanguage","IndonesianSecondLanguage","IndustryandEnterprise","InformationTechnology","ITApplications","SoftwareDevelopment","InformationandCommunicationsTechnology(VCEVET)","IntegratedTechnologies(VCEVET)","InteractiveDigitalMedia(VCEVET)","Italian","JapaneseFirstLanguage","JapaneseSecondLanguage","Khmer","KoreanFirstLanguage","KoreanSecondLanguage","LaboratorySkills(VCEVET)","Latin","Latvian","LegalStudies","Literature","Lithuanian","Macedonian","Maltese","Mathematics","FurtherMathematics","MathematicalMethods(CAS)","Media","Music","CertificateIIIinMusic(VCEVET)","CertificateIIIinMusic(TechnicalProduction)(VCEVET)","MusicInvestigation","MusicPerformance","MusicStyleandComposition","OutdoorandEnvironmentalStudies","Persian","Philosophy","PhysicalEducation","Physics","Polish","PoliticalStudies","Portuguese","ProductDesignandTechnology","Psychology","Punjabi","ReligionandSociety","RenaissanceItaly","Revolutions","Romanian","Russian","Serbian","Sinhala","Slovenian","Sociology","Spanish","SpecialistMathematics","SportandRecreation(VCEVET)","StudioArts","Swedish","SystemsEngineering","Tamil","Technology","ProductDesignandTechnology","FoodandTechnology","SystemsEngineering","TextsandTraditions","TheatreStudies","Turkish","Ukrainian","Vietnamese","VisualCommunicationDesign","Yiddish"];
var years = ["2014","2013","2012","2011","2010","2009","2008","2007","2006","2005","2004","2003","2002"];
var extractionList = {};var extractionData = [];
var fieldSet = 1;
var mode;
var modeSet = {
    SINGLE: 0,
    BULK: 1,
    EXTRACTION: 2
};
var localStorageCategories = {
    RECENTS: "recents",
    FAVS: "favourites"
}
// counter for result table
var dlCounter = 0;
var dlCounter2 = 0;
// changeable id for individual result
var id = 0;
// quickaccess checkbox
var qa_checkbox_val;
// category and item name for localstorage
var category_name,item_name;
// ========== MAIN SCRIPT ==========
$(document).ready(function(){
    //Enable tab control
    $(".tab ul.tabs").addClass('active').find('> li:eq(0)').addClass('current');

    $(".tab ul.tabs li a").click(function (g) {
        var tab = $(this).closest('.tab'),
            index = $(this).closest('li').index();

        tab.find('ul.tabs > li').removeClass('current');
        $(this).closest('li').addClass('current');

        tab.find('.tab_content').find('div.tabs_item').not('div.tabs_item:eq(' + index + ')').slideUp();
        tab.find('.tab_content').find('div.tabs_item:eq(' + index + ')').slideDown();

        //set mode
        mode = index;

        g.preventDefault();
    });

    //Enable background click to dismiss
    $(document).click(function(){
        isHovered = $("ul#contextMenuDLOptions").is(":hover") | $('ul#contextMenuAdvancedSettings').is(":hover");

        if (isHovered == false){
            $("ul.contextMenu").fadeOut("slow");
        }

        //Dismiss popover
        $('#del-popover').css({'display':'none'});

    });


    //Enable tooltip
    $(document).tooltip({
        position: { my: "right-15 center", at: "left center" }
    });

    //Enable modal
    $modal = $('#modal-exam');
    $modal_settings = $('#modal-settings');
    $overlay = $('.modal-overlay');


    $modal.bind('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e){
        if($modal.hasClass('state-leave')) {
            $modal.removeClass('state-leave');
        }
    });

    $('#close-exam').on('click', function(){
        $overlay.removeClass('state-show');
        $modal.removeClass('state-appear').addClass('state-leave');
    });

    $('#close-settings').on('click',function(){
        $overlay.removeClass('state-show');
        $modal_settings.removeClass('state-appear').addClass('state-leave').removeClass('state-leave');
    });

    //Enable slide menu control
    var slideWidth = $('#pageslide').outerWidth(); //grab width of the sliding menu so that this can be controlled in the css only

    $('.slideIt, #pageslide a.close').on("click", function(){
        $('.slideIt').toggleClass('active'); //toggle the active close vs open icon
        if($('#pageslide').is(':visible')) {  //if visible then hide it
            $('#pageslide').animate({
                left: '-'+slideWidth
            }, 400, function(){
                $('#pageslide').hide();
            } );
            $('body').animate({
                marginLeft: '0'
            }, 400 );

            $('.slideIt span').text('☰');
        }
        else{                                //else show it
            $('#pageslide').show().animate({
                left: '0'
            }, 400 );
            $('body').animate({
                marginLeft: window.innerWidth*0.05
            }, 400 );
        }
    });

    //Enable tag clicking
    $(document).on('click','.clickable-tags',function(e){
        //Find inner text
        $text = $(this).text();

        //Single mode
        if (mode == modeSet.SINGLE){
            //Find newest subject field
            $('#field_div_id_'+(fieldSet-1)+'_subject').val($text);
        }
        //Bulk Mode
        else if (mode == modeSet.BULK){
            // Update UI
            var div = document.getElementsByClassName('text-tags')[0];
            var innerDiv = document.createElement('div');
            innerDiv.className = "text-tag";
            var innerBtnDiv = document.createElement('div');
            innerBtnDiv.className = "text-button";
            var innerTextSpan = document.createElement('span');
            innerTextSpan.className = "text-label";
            innerTextSpan.innerHTML = $text;
            var innerRemoveAnchor = document.createElement('a');
            innerRemoveAnchor.className = "text-remove";
            innerBtnDiv.appendChild(innerTextSpan);
            innerBtnDiv.appendChild(innerRemoveAnchor);
            innerDiv.appendChild(innerBtnDiv);
            div.appendChild(innerDiv);

            //Update database
            var currentVal = $('input[type="hidden"][name="bulk_subject"]').val(); var data;
            if (currentVal == "[]"){
                data = currentVal.replace("]",'')+ '"' + $text + '"' + "]";
            }else{
                data = currentVal.replace("]",'')+","+ '"' + $text + '"' + "]";
            }
            $('input[type="hidden"][name="bulk_subject"]').val(data);
        }

        // NOT AVAILABLE FOR EXTRACTION MODE
    });

    //Enable tag hovering to show delete menu
    $(document).on('mouseenter','.clickable-tags', function (event) {
        if ($('#del-popover').length < 1){
            $('body').append('<div style="display: none" id="del-popover"><a id="del-button" style="text-align: center;">Delete</a></div>');
        }else{
            $('#del-popover').css({
                display:'block'
            });
        }
        $('#del-popover:before').css({
            top: event.pageY+10,
            left: event.pageX+7
        });
        $('#del-popover').css({
            'display':'block',
            top: event.pageY+10,
            left: event.pageX+7
        });
        //set category and item name
        if ($(this).closest('div').attr('id') == "tag-recents"){
            category_name = localStorageCategories.RECENTS;
        }else if ($(this).closest('div').attr('id') == "tag-favourites"){
            category_name = localStorageCategories.FAVS;
        }
        item_name = $(this).text();
    });

    $(document).on('mouseleave','#del-popover', function (){
        $('#del-popover').css({'display':'none'});
    });

    $(document).on('click','#del-button',function(){
        //Remove from localstorage
        removeItemInLocalStorage(category_name,item_name);
    });

    //Check recents and favourites content
    refreshUIFromLocalStorage();

    //Initial check for checkbox value
    if (parseInt($.cookie('qa_checked')) == 1){
        qa_checkbox_val = true;
        $('#quickaccess-toggle').prop('checked',true);
        $('a#toggle-auto-quickaccess').attr('title','Turned On');

    }else{
        qa_checkbox_val = false;
        $('a#toggle-auto-quickaccess').attr('title','Turned Off');

    }

    //Enable checkbox value change
    $('input#quickaccess-toggle').on('click',function(){
        $value = this.checked;
        qa_checkbox_val = $value;
        if (qa_checkbox_val == true){
            //enabled
            $value = 1;
            $('a#toggle-auto-quickaccess').attr('title','Turned On');
        }else{
            //Disabled
            $value = 0;
            $('a#toggle-auto-quickaccess').attr('title','Turned Off');

        }
        //Write to cookie
        $.cookie('qa_checked', $value, { expires: 9999999, path: '/' });
    });

    //Enable auto prompting for quickaccess
    $('#field_div_id_'+ (fieldSet-1)+ '_subject').on('focusin',function () {
        if (qa_checkbox_val){
            $('#pageslide').show().animate({
                left: '0'
            }, 400 );
            $('body').animate({
                marginLeft: window.innerWidth*0.05
            }, 400 );
        }
    });

    $('#field_div_id_'+ (fieldSet-1)+ '_subject').on('focusout',function () {
        if (qa_checkbox_val){
            var slideWidth = $('#pageslide').outerWidth();
            $('#pageslide').animate({
                left: '-'+slideWidth
            }, 400, function(){
                $('#pageslide').hide();
            } );
            $('body').animate({
                marginLeft: '0'
            }, 400 );

            $('.slideIt span').text('☰');
        }
    });

    //Enable Tab for add new fields
    $('#field_div_id_' + (fieldSet-1) + '_year').on('keydown',function(e){
        var keyCode = e.keyCode || e.which;
        if (keyCode == 9){
            //Tab pressed
            addField();
        }
    });

    stWidget.addEntry({
        "service":"sharethis",
        "element":document.getElementById('share_container'),
        "url":document.domain,
        "title":"Share exam fetcher to your friends!",
        "type":"large",
        "text":"Share this" ,
        "summary":"I just downloaded VCAA exams using exam fetcher! A convenient tool for fetching exam! Go check it out!"
    });

});


$(window).load(function() {
    //HIDE PRELOADER
    hidePreloader();

    //Initialize data for extraction mode;
    extractionList = {
        'Specialist Math Formula Sheet':'specialist-math-formula-sheet.pdf',
        'Chemistry Data Booklet' : 'chemistry-data-booklet.pdf',
        'Math Method Formula Sheet' : 'mathmethod-formula-sheet.pdf',
        'Physics Formula Sheet' : 'physics-formula-sheet.pdf'
    };
    //Initialize extraction data for autocomplete
    for (var key in extractionList){
        extractionData.push(key);
    }
    $('#ext_subject').autocomplete({
        source: extractionData,
        autoFocus:true
    });

    //prevent enter to submit in front form
    $(window).keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });
    //set mode
    mode = modeSet.SINGLE;
});
// ========== FRONT FORM ==================

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

    //Link sources
    $('#field_div_id_'+ fieldSet +'_subject').autocomplete({
        source:searchIndex,
        autoFocus:true
    });

    $('#filed_div_id' + fieldSet + '_year').autocomplete({
        source:years,
        autoFocus:true
    });

    fieldSet++;

    //Reassign click event
    $('#field_div_id_'+ (fieldSet-1)+ '_subject').focusin(function () {
        if (qa_checkbox_val){
            $('#pageslide').show().animate({
                left: '0'
            }, 400 );
            $('body').animate({
                marginLeft: window.innerWidth*0.05
            }, 400 );
        }
    });

    $('#field_div_id_'+ (fieldSet-1)+ '_subject').focusout(function () {
        if (qa_checkbox_val){
            var slideWidth = $('#pageslide').outerWidth();
            $('#pageslide').animate({
                left: '-'+slideWidth
            }, 400, function(){
                $('#pageslide').hide();
            } );
            $('body').animate({
                marginLeft: '0'
            }, 400 );

            $('.slideIt span').text('☰');
        }
    });

    //Re assign tab pressed
    $('#field_div_id_'+(fieldSet-1)+'_year').keydown(function (e) {
        var keyCode = e.keyCode || e.whichj ;
        if (keyCode == 9){
            //Tab pressed
            addField();
        }
    });

    //Reveal remove button
    var removeBtn = document.getElementById('removeBtn');
    removeBtn.style.display = "inline";

    //Reveal submit button
    var submitBtn = document.getElementById('submit');
    submitBtn.style.display = "inline";
}

$("body").on('submit','#sform',function(e){
    //Add to recents
    addToRecentsBySubmit();

    $('#counter').val(fieldSet);
    showPreloader();

    var postData = $(this).serializeArray();
    var formURL = 'function.php';

    $.ajax({
        url: formURL,
        type: "POST",
        data: postData,
        success:function(data){
            // Remove fields
            var div = document.getElementById('single');
            div.innerHTML = "<h3 style='text-align: center'>Check out the exams downloaded in the table below! Or you can <b>DOWNLOAD THEM ALL</b> using the button below.</h3>" +
            "<div align='center'> <a class='form__btn btn--info' id='download-zip'><strong>Download all to zip</strong></a>  <h3 style='text-align: center'>OR</h3> <a class='form__btn btn--success' id='share-exams'>Share these exams</a> <a class='form__btn btn--default' id='reset-table'>Reset the form</a> </div> <br/>";
            console.log(data);
            var jsonObj = $.parseJSON(data);

            var singleTableResult = document.createElement('table');
            singleTableResult.id = "table-result-single";
            singleTableResult.style.width = "70%";
            singleTableResult.style.margin = "0 auto";
            singleTableResult.innerHTML += "<thead> <tr> <th>Subject</th> <th>Download Links</th> </tr> </thead>";
            singleTableResult.title = "Right click to download 'em all :)";

            $.each(jsonObj,function(key,val){
                //Create table rows
                var tr = document.createElement('tr');
                var tdSubject = document.createElement('td');
                tdSubject.innerText = key;
                tr.appendChild(tdSubject);
                var tdDownloadLinks = document.createElement('td');
                tdDownloadLinks.style.float = "right";

                $.each(val,function(idx,vlu){

                    var link = document.createElement('a');
                    link.href = vlu;
                    link.innerHTML = idx;
                    link.className = "downloadable";
                    link.id = "downloadable-single-"+dlCounter;
                    link.setAttribute("target","_blank");
                    tdDownloadLinks.appendChild(link);

                    // Append more option link
                    var moreOptions = document.createElement('a');
                    moreOptions.className = "plus";
                    moreOptions.id = dlCounter;
                    moreOptions.innerHTML = "<i></i>";
                    tdDownloadLinks.appendChild(moreOptions);

                    tdDownloadLinks.innerHTML += "<br/>";

                    var hiddenLink = document.createElement('a');
                    hiddenLink.href = vlu;
                    hiddenLink.innerHTML = idx + "</br>";
                    hiddenLink.className = "hiddenDownload";
                    hiddenLink.id = "hidden-download-single-"+dlCounter;
                    hiddenLink.style.display = "none";
                    hiddenLink.setAttribute("download",vlu);
                    tdDownloadLinks.appendChild(hiddenLink);

                    dlCounter ++;
                });

                tr.appendChild(tdDownloadLinks);
                tr.innerHTML += "<br/>";
                singleTableResult.appendChild(tr);
            });

            div.appendChild(singleTableResult);

            // Bind Context Menu to SingleTableResult
            bindContextMenuToElementWithID("contextMenuDLOptions","table-result-single");

            // Success
            createInformationalAlertWithTitleAndDelay("Success:)",1700,true);
            // Append style
            var style = "<style type='text/css'>" + "table{background:#fff;border-radius:3px;border-collapse:collapse;height:50px;margin:auto;max-width:600px;padding:5px;width:80%;box-shadow:0 5px 10px rgba(0,0,0,0.1);animation:float 5s infinite}th{color:#D5DDE5;background:#1b1e24;border-bottom:4px solid #9ea7af;border-right:1px solid #343a45;font-size:23px;font-weight:100;padding:24px;text-align:left;text-shadow:0 1px 1px rgba(0,0,0,0.1);vertical-align:middle}th:first-child{border-top-left-radius:3px}th:last-child{border-top-right-radius:3px;border-right:none}tr{border-top:1px solid #C1C3D1;border-bottom-:1px solid #C1C3D1;color:#666B85;font-size:16px;font-weight:400;text-shadow:0 1px 1px rgba(256,256,256,0.1)}tr:first-child{border-top:none}tr:last-child{border-bottom:none}tr:nth-child(odd) td{background:#EBEBEB}tr:last-child td:first-child{border-bottom-left-radius:3px}tr:last-child td:last-child{border-bottom-right-radius:3px}td{background:#FFF;padding:20px;text-align:left;vertical-align:middle;font-weight:300;font-size:18px;text-shadow:-1px -1px 1px rgba(0,0,0,0.1);border-right:1px solid #C1C3D1}td:last-child{border-right:0}"+"</style>";
            $("head").append(style);

            hidePreloader();
            $('#addBtn').fadeOut();
            $('#removeBtn').fadeOut();

            //Refresh UI
            refreshUIFromLocalStorage();
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

//Remove a subject field
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


//link to source
$('#field_div_id_0_subject').autocomplete({
    source: searchIndex,
    autoFocus:true
});
$('#field_div_id_0_year').autocomplete({
    source: years,
    autoFocus:true
});


/* ========================== BACK FORM =========================== */

$('#bulk_subject').textext({
    plugins: 'autocomplete suggestions tags filter',
    suggestions: searchIndex
});

$('#bulk_year').textext({
    plugins: 'autocomplete suggestions tags filter',
    suggestions: years
});

$('#add-fav-field').autocomplete({
    source: searchIndex,
    autoFocus:true
});

$('#from-year').autocomplete({
    source: years,
    autoFocus:true
});

$('#to-year').autocomplete({
    source: years,
    autoFocus:true
});

$('#bulk_year').focusin(function(e){
    $('#quick_year_selector').slideDown();
});


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
        showPreloader();
    }else{
        showPreloader();
    }

    //Add to recents
    addToRecentsBySubmit();

    var formURL = 'function.php';
    var postData = $(this).serializeArray();
    $.ajax({
        url: formURL,
        type: "POST",
        data: postData,
        success: function (data) {
            // Remove fields
            var div = document.getElementById('bulk');
            div.innerHTML = "<h3 style='text-align: center'>Check out the exams downloaded in the table below! Or you can <b>DOWNLOAD THEM ALL</b> using the button below.</h3>" +
            "<div align='center'> <a class='form__btn btn--info' id='download-zip'><strong>Download all to zip</strong></a>  <h3 style='text-align: center'>OR</h3> <a class='form__btn btn--success' id='share-exams'>Share these exams</a> <a class='form__btn btn--default' id='reset-table'>Reset the form</a> </div> <br/>";
            console.log(data);
            var jsonObj = $.parseJSON(data);

            var bulkTableResult = document.createElement('table');
            bulkTableResult.id = "table-result-bulk";
            bulkTableResult.style.width = "70%";
            bulkTableResult.style.margin = "0 auto";
            bulkTableResult.innerHTML += "<thead> <tr> <th>Subject</th> <th>Download Links</th> </tr> </thead>";
            bulkTableResult.title = "Right click to download 'em all :)";

            $.each(jsonObj, function (key, val) {
                //Create table rows
                var tr = document.createElement('tr');
                var tdSubject = document.createElement('td');
                tdSubject.innerText = key;
                tr.appendChild(tdSubject);
                var tdDownloadLinks = document.createElement('td');
                tdDownloadLinks.style.float = "right";
                $.each(val, function (idx, vlu) {
                    var link = document.createElement('a');
                    link.href = vlu;
                    link.innerHTML = idx;
                    link.className = "downloadable";
                    link.id = "downloadable-bulk-" + dlCounter2;
                    link.setAttribute("target", "_blank");
                    tdDownloadLinks.appendChild(link);

                    // Append more option link
                    var moreOptions = document.createElement('a');
                    moreOptions.className = "plus";
                    moreOptions.innerHTML = "<i></i>";
                    moreOptions.id = dlCounter2;

                    tdDownloadLinks.appendChild(moreOptions);
                    tdDownloadLinks.innerHTML += "<br/>";

                    var hiddenLink = document.createElement('a');
                    hiddenLink.href = vlu;
                    hiddenLink.innerHTML = idx + "</br>";
                    hiddenLink.id = "hidden-download-bulk-"+dlCounter2;
                    hiddenLink.className = "hiddenDownload";
                    hiddenLink.style.display = "none";
                    hiddenLink.setAttribute("download", vlu);
                    tdDownloadLinks.appendChild(hiddenLink);
                    dlCounter2++;

                });

                tr.appendChild(tdDownloadLinks);
                tr.innerHTML += "<br/>";
                bulkTableResult.appendChild(tr);

            });

            div.appendChild(bulkTableResult);

            //Bind context menu to bulkTableResult
            bindContextMenuToElementWithID("contextMenuDLOptions","table-result-bulk");

            //Append table style
            var style = "<style type='text/css'>" + "table{background:#fff;border-radius:3px;border-collapse:collapse;height:50px;margin:auto;max-width:600px;padding:5px;width:80%;box-shadow:0 5px 10px rgba(0,0,0,0.1);animation:float 5s infinite}th{color:#D5DDE5;background:#1b1e24;border-bottom:4px solid #9ea7af;border-right:1px solid #343a45;font-size:23px;font-weight:100;padding:24px;text-align:left;text-shadow:0 1px 1px rgba(0,0,0,0.1);vertical-align:middle}th:first-child{border-top-left-radius:3px}th:last-child{border-top-right-radius:3px;border-right:none}tr{border-top:1px solid #C1C3D1;border-bottom-:1px solid #C1C3D1;color:#666B85;font-size:16px;font-weight:400;text-shadow:0 1px 1px rgba(256,256,256,0.1)}tr:first-child{border-top:none}tr:last-child{border-bottom:none}tr:nth-child(odd) td{background:#EBEBEB}tr:last-child td:first-child{border-bottom-left-radius:3px}tr:last-child td:last-child{border-bottom-right-radius:3px}td{background:#FFF;padding:20px;text-align:left;vertical-align:middle;font-weight:300;font-size:18px;text-shadow:-1px -1px 1px rgba(0,0,0,0.1);border-right:1px solid #C1C3D1}td:last-child{border-right:0}" + "</style>";
            $("head").append(style);

            // Success
            createInformationalAlertWithTitleAndDelay("Success:)",1700,true);
            hidePreloader();

            //Refresh UI
            refreshUIFromLocalStorage();

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
// =================== EXTRACTION FORM ===================
$('#eform').submit(function(e){
    //Get the matched url for extraction
    $('#ext_selected').val(extractionList[$('#ext_subject').val()]);
});

// =================== EXTRA FUNCTIONS ==================
// Link sources
function linkSource(){
    //link to source
    $('#field_div_id_'+(fieldSet-1)+'_subject').autocomplete({
        source: searchIndex,
        autoFocus:true
    });
    $('#field_div_id_'+(fieldSet-1)+'_year').autocomplete({
        source: years,
        autoFocus:true
    });


    $('#add-fav-field').autocomplete({
        source: searchIndex,
        autoFocus:true
    });

    $('#from-year').autocomplete({
        source: years,
        autoFocus:true
    });

    $('#to-year').autocomplete({
        source: years,
        autoFocus:true
    });


    $('#bulk_year').focusin(function(e){
        $('#quick_year_selector').slideDown();
    });


    $('#field_div_id_' + (fieldSet-1) + '_year').on('keydown',function(e){
        var keyCode = e.keyCode || e.which;
        if (keyCode == 9){
            //Tab pressed
            addField();
        }
    });



}


// focus for both subject fields

$('#bulk_subject').focusin(function (){
    if (qa_checkbox_val){
        $('#pageslide').show().animate({
            left: '0'
        }, 400 );
        $('body').animate({
            marginLeft: window.innerWidth*0.05
        }, 400 );
    }
});

$('#bulk_subject').focusout(function(){
    if (qa_checkbox_val){
        var slideWidth = $('#pageslide').outerWidth();
        $('#pageslide').animate({
            left: '-'+slideWidth
        }, 400, function(){
            $('#pageslide').hide();
        } );
        $('body').animate({
            marginLeft: '0'
        }, 400 );

        $('.slideIt span').text('☰');
    }
});

// more options
$("body").on('mouseover','a.plus',function(e){
    //show popoup
    e.preventDefault();
    $('ul#contextMenuMoreOptions').show().css({
        top: event.pageY+5,
        left: event.pageX
    });
    //get the id number
    id = $(this).attr('id');
});

$('#download-this-one').click(function(e){
    e.preventDefault();
    if (isDownloadAttrAvailable()) {
        switch (mode) {
            case modeSet.SINGLE:
            {
                document.getElementById('hidden-download-single-'+id).click();
            }
                break;

            case modeSet.BULK:
            {
                document.getElementById('hidden-download-bulk-'+id).click();

            }
                break;
            default :
                break;
        }
    }else{
        var url;
        switch (mode) {
            case modeSet.SINGLE:
            {
                url = document.getElementById('hidden-download-single-'+id).href;
            }
                break;

            case modeSet.BULK:
            {
                url = document.getElementById('hidden-download-bulk-'+id).href;

            }
                break;
            default :
                break;
        }
        postResultData(url,'single-download');
    }

});

var firstPrint = true;
// print
$('#print-this-one').click(function(e){
    // get url
    var url;
    switch (mode) {
        case modeSet.SINGLE:
        {
            url = document.getElementById('hidden-download-single-'+id).href;
        }
            break;

        case modeSet.BULK:
        {
            url = document.getElementById('hidden-download-bulk-'+id).href;

        }
            break;
        default :
            break;
    }
    //ajax it
    var data = {"action":"print","url":url};
    var formURL = "function.php";
    showPreloader();
    $.ajax({
        url:formURL,
        type:"post",
        data:data,
        success:function(response){
            var data = JSON.parse(response);
            if (data["status"] == "1"){
                //success
                document.getElementById('pdf-frame').src = data["dir"];
                // start print action
                document.getElementById('pdf-frame').focus();
                if (firstPrint){
                    setTimeout(function(e){
                        document.getElementById('pdf-frame').contentWindow.print();
                    },1000);
                    firstPrint = false;
                }else{
                    document.getElementById('pdf-frame').contentWindow.print();
                }

            }else{
                alert("failed. Cannot print. Please report");
            }
            hidePreloader();
        }
    });
});

// download actions
$('#download-file').click(function(e){
    //check for support
    if (isDownloadAttrAvailable()){

        switch (mode) {
            case modeSet.SINGLE: {
                var singleForm = document.getElementById('single');
                var hiddenLinksInSingle = singleForm.getElementsByClassName('hiddenDownload');
                for (var i = 0; i < hiddenLinksInSingle.length; i++){
                    hiddenLinksInSingle[i].click();
                }
            }
                break;

            case modeSet.BULK: {
                var bulkForm = document.getElementById('bulk');
                var hiddenLinksInBulk = bulkForm.getElementsByClassName('hiddenDownload');
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

$('body').on('click','#download-zip',function(e){

    var table; var data;

    if (mode == modeSet.SINGLE){
        data = analyseTable('table-result-single');
    }else if(mode == modeSet.BULK){
        data = analyseTable('table-result-bulk');
    }
    var dataString = JSON.stringify(data);

    //Post for now
    postResultData(dataString,"download-zip");

    e.preventDefault();
});

$('body').on('click','#share-exams',function(e){

    // Open modal
    $overlay.addClass('state-show');
    $modal.removeClass('state-leave').addClass('state-appear');

    // Generate link and put in the contents
    var data;
    if (mode == modeSet.SINGLE){
        data = analyseTable('table-result-single');
    }else if(mode == modeSet.BULK){
        data = analyseTable('table-result-bulk');
    }
    var dataString = JSON.stringify(data);
    var downloadURL = document.domain+"/function.php?remotedownload="+btoa(dataString);

    //Add entry if i can
    if ($('#st_fb').children().length < 1){
        stWidget.addEntry({
            "service":"email",
            "element":document.getElementById('st_email'),
            "url":downloadURL,
            "title":"Share exams to facebook!",
            "type":"large",
            "summary":"Here's the link attached! Click to download now!"
        });
        stWidget.addEntry({
            "service":"facebook",
            "element":document.getElementById('st_fb'),
            "url":downloadURL,
            "title":"Share exams to your friends' email!",
            "type":"large",
            "summary":"Here's the link attached! Click to download now!"
        });

    }

    $('#generate-link').click(function (e) {
        window.prompt("Link is generated! Use Ctrl+C to copy!",downloadURL);
        e.preventDefault();
    });
});

// Post share exam email form
$('#share-email-form').submit(function(e){
    //Append spinner
    document.getElementById('progress').innerHTML = "<div class='spinner-email'></div>";

    //Put div contents in the val
    $('#content-value').val(document.getElementById('modal-content').innerHTML);
    //Prepare
    var formURL = "function.php";
    var data = $(this).serializeArray();
    //Ajax it
    $.ajax({
        url:formURL,
        type: "POST",
        data: data,
        success: function (response) {
            if (response){
                document.getElementById('progress').innerHTML = response;
            }else{
                document.getElementById('progress').innerHTML = "Empty Response";
            }
        },
        error: function(){
            document.getElementById('progress').innerHTML = "Failed to send! Please try again";
        }
    });

    e.preventDefault();
});

//Assign click to advanced settings
$('#advanced-settings').on('click',function(event){
    // Open modal
    $overlay.addClass('state-show');
    $modal_settings.removeClass('state-leave').addClass('state-appear');
});

//Reload cache clicked
$('#reload-home-cache').click(function(e){

    var formURL = "function.php";
    var data = {"action":"reloadCache"};

    $.ajax({
        url:formURL,
        type:"POST",
        data:data,
        success:function(response){
            createInformationalAlertWithTitleAndDelay(response,1000,true);
        },
        error: function(response){
            createInformationalAlertWithTitleAndDelay(response,1000,false);
        }
    });
});

//Reload table
$('body').on('click','#reset-table',function(e){
    if (mode == modeSet.SINGLE){
        document.getElementById('single').innerHTML = '<form id="sform" method="post"> <div id="container"> <div class="checkboxes" style="display: inline-block;margin: 0 auto;width:100%"> <p align="center"> <label> <input type="checkbox" class="checkbox" name="singlePaperChecked" checked/> Exams |</label> <label> <input type="checkbox" class="checkbox" name="singleReportChecked" checked/> Assessment reports </label> </p></div><div id="field_div_id_0"> <h5> Enter your subject </h5> <input type="text" placeholder="Type a few characters and select a subject" name="field_div_id_0_subject" id="field_div_id_0_subject" class="form__input ui-autocomplete-input" autocomplete="off" required="required"> <h5> Enter year </h5> <input type="text" placeholder="Type a few characters and select a year" name="field_div_id_0_year" id="field_div_id_0_year" class="form__input" required="required"> <br></div></div><div align="center" id="singleBtns" style="margin-bottom: 30px"> <a class="btn paper paper-raise-flatten" id="addBtn" onclick="addField()">Add a new subject field</a> <a class="btn paper paper-raise-flatten" id="removeBtn" onclick="removeField()" style="display: none;">Remove a subject field</a> </div><input type="submit" id="submit" value="Click to view the exam!"/> <input type="hidden" name="counter" id="counter"/> <input type="hidden" name="modeIndicator" id="modeIndicator" value="0"> <input type="hidden" name="action" id="action" value="fetch"> </form>';
        //Reset field count
        fieldSet = 1;
    }
    if (mode == modeSet.BULK){
        document.getElementById('bulk').innerHTML = '<form id="bform" method="post"> <div class="checkboxes" style="display: inline-block;margin: 0 auto;width:100%"> <p align="center"> <label> <input type="checkbox" class="checkbox" name="bulkPaperChecked" checked/> Exams |</label> <label> <input type="checkbox" class="checkbox" name="bulkReportChecked" checked/> Assessment reports </label> </p></div><div style=""> <h5>Enter your subjects:</h5> <input id="bulk_subject" placeholder="Type a few characters and select a subject" name="bulk_subject" class="form__input" style="width: 100% !important;"/> <h6>Notice: For subjects, please enter the name of subject from the beginning:<br/> E.g. When searching for "English As Additional Language", you should start by typing "Eng.." instead of "EAL". </h6> <h5>Enter years:</h5> <input id="bulk_year" placeholder="Type a few characters and select a year" name="bulk_year" class="form__input" style="width:100%; !important;"/> <div id="quick_year_selector" style="display: none"> <h3 style="text-align: center"><strong>OR</strong></h3> <h5>Get exams from a consecutive number of years! E.g. All the way from 2002 to 2014! </h5> <h5>From Year: <input type="text" name="from-year" id="from-year" class="form__input" style="display: inline;width: 20%"/> To Year: <input type="text" name="to-year" id="to-year" class="form__input" style="display: inline; width: 20%;"> </div></div><input type="submit" id="submit" name="submit" value="Click to view the exams!" style="margin-top: 20px"> <input type="hidden" id="modeIndicator" name="modeIndicator" value="1"> <input type="hidden" name="action" id="action" value="fetch"> </form>';
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

//Toggle fav panel
$('#fav-toggle').click(function(){
    $('#add-favourites').toggle();
});

//Add stuff to favourite
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

//Analyse the result table and make up JSON data
function analyseTable(tableID){
    var data = [];
    var table = document.getElementById(tableID);
    var rows = table.children;
    for (var i =1; i<rows.length;i++){
        var columns = rows[i].children;
        var subjectName = columns[0].innerText;
        var subjectLinks = columns[1];
        var subjectLinksArray = [];
        for (var j =0;j<subjectLinks.children.length;j++){
            if (subjectLinks.children[j].className == "downloadable"){
                subjectLinksArray.push(subjectLinks.children[j].href);
            }
        }
        data.push({
            key: subjectName,
            value: subjectLinksArray
        });
    }
    return data;
}

//Post data
function postResultData (val,action) {
    var theForm, newInput1,newinput2;
    // Start by creating a <form>
    theForm = document.createElement('form');
    theForm.action = 'function.php';
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

//Get the value in v
function getBulkData(){
    //Get value
    var value = $('input[type="hidden"][name="bulk_subject"]').val();
    var needsToBeCleaned = ["[","]","\""];
    for (var i = 0; i < needsToBeCleaned.length; i ++){
        needsToBeCleaned = str_replace(needsToBeCleaned[i],'',value);
    }
    console.log(needsToBeCleaned.toString());
    return needsToBeCleaned.split(',');
}

// Bind context menu to element
function bindContextMenuToElementWithID(idcontextmenu,idelement){
    $("#"+idelement).bind("contextmenu",function(event){
        event.preventDefault();
        $('ul#'+idcontextmenu).show().css({
            top: event.pageY+15,
            left: event.pageX+10
        });
    });
}

// check the availbility of download attribute
function isDownloadAttrAvailable(){
    var a  = document.createElement('a');
    if (typeof a.download != "undefined"){
        return true;
    }
    return false;
}

// manual create alert
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

// show/hide preloader
function showPreloader(){
    $('#preloader').fadeIn();
    $overlay = $('.modal-overlay');
    $overlay.addClass('state-show');
}

function hidePreloader(){
    $('#preloader').fadeOut();
    $overlay = $('.modal-overlay');
    $overlay.removeClass('state-show');

}

// ================== Local File storage manipulation -> For recent searches and quick access
function removeItemInLocalStorage(category,item_name){
    var generatedArray = [];
    if (category == localStorageCategories.RECENTS){
        var localRecArray = JSON.parse(localStorage['recents']);
        for (var i = 0; i < localRecArray.length; i ++){
            if (item_name == localRecArray[i]){
                localRecArray.splice(i,1);
                break;
            }
        }
        generatedArray = JSON.stringify(localRecArray);
        localStorage['recents'] = generatedArray;
    }

    if (category == localStorageCategories.FAVS){
        console.log("called?");
        var localFavArray = JSON.parse(localStorage['favourites']);
        for (var j = 0; j < localFavArray.length; j ++){
            console.log(item_name + " =? " + localFavArray[j]);
            if (item_name == localFavArray[j]){
                console.log("matched?");
                localFavArray.splice(j,1);
                break;
            }
        }
        generatedArray = JSON.stringify(localFavArray);
        localStorage['favourites'] = generatedArray;
    }
    refreshUIFromLocalStorage();
}

function refreshUIFromLocalStorage(){
    var placeholder_array = [];
    //Check recents
    if (localStorage['recents'] && JSON.parse(localStorage['recents']).length>0){
        //Get data
        var recents_array = JSON.parse(localStorage['recents']);
        //Push to HTML
        var tag_ul_re = document.createElement('ul');

        var count;
        if (recents_array.length >=6){
            count = recents_array.length - 5;
        }else{
            count = 0;
        }

        for (var i = recents_array.length-1; i >= count; i --){
            var tag_li_re = document.createElement('li');
            var tag_a_re = document.createElement('a');
            tag_a_re.className = "clickable-tags";
            var tag_a_span_re = document.createElement('span');
            tag_a_re.appendChild(tag_a_span_re);
            tag_a_re.innerHTML += recents_array[i];
            tag_li_re.appendChild(tag_a_re);
            tag_ul_re.appendChild(tag_li_re);
        }
        //Clear old UI
        document.getElementById('tag-recents').innerHTML = "";

        document.getElementById('tag-recents').appendChild(tag_ul_re);
        document.getElementById('tag-recents').innerHTML+="<br/>";
    }else{
        localStorage['recents'] = JSON.stringify(placeholder_array);
        document.getElementById('tag-recents').innerHTML = "<h6>No Recents Here! Go and make some searches</h6>";
    }

    //Check favourites
    if (localStorage['favourites'] && JSON.parse(localStorage['favourites']).length >0 ){
        //Clean
        document.getElementById('tag-favourites').innerHTML = "";
        //Get data
        var favourite_array = JSON.parse(localStorage['favourites']);
        //Push to HTML
        var tag_ul_fa = document.createElement('ul');
        for (var j = 0; j < favourite_array.length; j ++){
            var tag_li_fa = document.createElement('li');
            var tag_a_fa = document.createElement('a');
            tag_a_fa.className = "clickable-tags";
            var tag_a_span_fa = document.createElement('span');
            tag_a_fa.appendChild(tag_a_span_fa);
            tag_a_fa.innerHTML += favourite_array[j];
            tag_li_fa.appendChild(tag_a_fa);
            tag_ul_fa.appendChild(tag_li_fa);
        }
        //Clear old UI
        document.getElementById('tag-favourites').innerHTML = "";

        document.getElementById('tag-favourites').appendChild(tag_ul_fa);
    }else{
        localStorage['favourites'] = JSON.stringify(placeholder_array);
        document.getElementById('tag-favourites').innerHTML = "<h6>Hey, add some now to the favourite panel!</h6>"
    }
}

function addToRecentsBySubmit(){
    var limit = 6; var generatedArray = []; var currentRecents = JSON.parse(localStorage['recents']);
    //In single mode
    if (mode == modeSet.SINGLE){
        if (fieldSet+ currentRecents.length > limit){
            //exceeding limit
            for (var i =0; i < fieldSet; i ++){
                currentRecents.push($('#field_div_id_'+i+'_subject').val());
            }
            for (var j = currentRecents.length-1; j > currentRecents.length-limit-1; j --){
                generatedArray.push(currentRecents[i]);
            }
        }else{
            //under limit
            for (var i =0; i < fieldSet; i ++){
                currentRecents.push($('#field_div_id_'+i+'_subject').val());
            }
            generatedArray = currentRecents;
        }

    }else if(mode == modeSet.BULK){
        if (getBulkData().length + currentRecents.length > limit){
            //exceeding the limit
            for (var m = 0; m < getBulkData().length ; m++ ){
                currentRecents.push(getBulkData()[m]);
            }
            for (var n = currentRecents.length-1; n > currentRecents.length-limit-1; n --){
                generatedArray.push(currentRecents[n]);
            }

        }else{
            //under limit
            for (var m = 0; m < getBulkData().length ; m++ ){
                currentRecents.push(getBulkData()[m]);
            }
            generatedArray = currentRecents;
        }
    }
    localStorage['recents'] = JSON.stringify(generatedArray);

}