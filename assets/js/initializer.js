// ========== INITIALIZATION ===========
var searchIndex = ["Accounting", "Agricultural and Horticultural Studies", "Albanian", "Arabic", "Armenian", "Art", "Auslan", "Australian History", "Australian Politics", "Bengali", "Biology", "Bosnian", "Business(VCEVET)", "Business Management", "Chemistry", "Chinese First Language", "Chinese Second Language", "Chinese Second Language Advanced", "Classical Studies", "Community Services(VCEVET)", "Contemporary Australian Society", "Croatian", "Czech", "Dance", "Dance(VCEVET)", "Drama", "Dutch", "Economics", "Engineering Studies(VCEVET)", "English", "English as an Additional Language(EAL)", "English Language", "Environmental Science", "Equine Studies(VCEVET)", "Extended Investigation", "Filipino", "Financial Services(VCEVET)", "Food and Technology", "French", "Furnishing(VCEVET)", "Further Mathematics", "General Achievement Test(GAT)", "Geography", "German", "Greek", "Global Politics", "Health and Human Development", "Hebrew", "Hindi", "History", "Australian History", "Renaissance Italy", "Revolutions", "Hospitality(VCEVET)", "Hospitality", "Hospitality-Kitchen Operations", "Hungarian", "Indigenous Languages of Victoria", "Indonesian First Language", "Indonesian Second Language", "Industry and Enterprise", "Information Technology", "IT Applications", "Software Development", "Information and Communications Technology(VCEVET)", "Integrated Technologies(VCEVET)", "Interactive Digital Media(VCEVET)", "Italian", "Japanese First Language", "Japanese Second Language", "Khmer", "Korean First Language", "Korean Second Language", "Laboratory Skills(VCEVET)", "Latin", "Latvian", "Legal Studies", "Literature", "Lithuanian", "Macedonian", "Maltese", "Mathematics", "Further Mathematics", "Mathematical Methods(CAS)", "Media", "Music", "Certificate III in Music(VCEVET)", "CertificateIII in Music(Technical Production)(VCEVET)", "Music Investigation", "Music Performance", "Music Style and Composition", "Outdoor and Environmental Studies", "Persian", "Philosophy", "Physical Education", "Physics", "Polish", "Political Studies", "Portuguese", "Product Design and Technology", "Psychology", "Punjabi", "Religion and Society", "Revolutions", "Romanian", "Russian", "Serbian", "Sinhala", "Slovenian", "Sociology", "Spanish", "Specialist Mathematics", "Sport and Recreation(VCEVET)", "Studio Arts", "Swedish", "Systems Engineering", "Tamil", "Technology", "Product Design and Technology", "Texts and Traditions", "Theatre Studies", "Turkish", "Ukrainian", "Vietnamese", "Visual Communication Design", "Yiddish"];  
var years = ["2015","2014","2013","2012","2011","2010","2009","2008","2007","2006","2005","2004","2003","2002"];
var extractionList = {
    'Specialist Math Formula Sheet':'specialist-math-formula-sheet.pdf',
    'Chemistry Data Booklet' : 'chemistry-data-booklet.pdf',
    'Math Method Formula Sheet' : 'mathmethod-formula-sheet.pdf',
    'Physics Formula Sheet' : 'physics-formula-sheet.pdf'
};
var extractionData = [];
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
//preloader
var prelodr;
$(window).load(function() {

    //Initialize extraction data for autocomplete
    for (var key in extractionList){

        extractionData.push(key);

    }

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
            }, 600, function(){
                $('#pageslide').hide();
            } );
            $('body').animate({
                marginLeft: '0'
            }, 600 );

            $('.slideIt span').text('☰');
        }
        else{                                //else show it
            $('#pageslide').show().animate({
                left: '0'
            }, 600 );
            $('body').animate({
                marginLeft: window.innerWidth*0.20
            }, 600 );
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

    //Enable Tab for add new fields
    $('#field_div_id_' + (fieldSet-1) + '_year').on('keydown',function(e){
        var keyCode = e.keyCode || e.which;
        if (keyCode == 9){
            //Tab pressed
            addField();
        }
    });

    $('#bulk_subject').textext({
        plugins: 'autocomplete suggestions tags filter',
        suggestions: searchIndex
    });

    $('#bulk_year').textext({
        plugins: 'autocomplete suggestions tags filter',
        suggestions: years
    });

    //deal with mobile
    if (detectmob()) {

        //remove the button
        $('.slideIt').remove();
        
        $('body').css({
            "font-size":"30px"
        });

        $('.tabs li').css({
            "line-height":"75px"
        });

        $('.form__btn').css({
            "font-size":"16px"
        });

    };

    //Link source to input
    linkSource();
});

/**
 * Show preloader
 * @return {void}
 */
function showPreloader(text){
    //init preloader
    prelodr = new Prelodr({
        classPrefix: 'prelodr',
        show: function() {
            console.log('Show callback');
        },
        hide: function() {
            console.log('Hide callback');
        }
    });

    prelodr.in(text);

}

/**
 * Hide preloader
 * @return {void} 
 */
function hidePreloader(){
   prelodr.out();

}

/**
 * Link sources to the UI
 * @return {void} 
 */
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


    $('#ext_subject').autocomplete({
        source: extractionData,
        autoFocus:true
    });

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

     $('#ext_subject').autocomplete({
        source: extractionData,
        autoFocus:true
    });
}



/**
 * Create an information alert for user
 * @param  {String}  title    
 * @param  {Int}  delay     
 * @param  {Boolean} isSuccess 
 * @return {Object}            Alertify
 */
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

/**
 * Check if on mobile
 * @return {boolean} 
 */
function detectmob() { 
    if( navigator.userAgent.match(/Android/i)
        || navigator.userAgent.match(/webOS/i)
        || navigator.userAgent.match(/iPhone/i)
        || navigator.userAgent.match(/iPad/i)
        || navigator.userAgent.match(/iPod/i)
        || navigator.userAgent.match(/BlackBerry/i)
        || navigator.userAgent.match(/Windows Phone/i)
    ){
            return true;
    }
    else {

        return false;
  
  }

}
