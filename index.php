<?php
    require ('vendor/autoload.php');

    require_once ('site/admin/ExamFetcherSettings.php');

    require_once ('site/announcements.php');

    use VCAA\db\DatabaseRequest;

    // Check if in maintanence
    $site_option_conn = new DatabaseRequest('site_options');

    if (ExamFetcherSettings::check_if_in_maintance($site_option_conn)) {
        
        $is_maintanence = true;

    }else{

        $is_maintanence = false;

    }

    // Initialize announcement instance
    $announcement = new Announcements();

    // Retrieve the data from announcement system
    $data = $announcement->receive_announcement();

?>

<?php 
    
    if (!$is_maintanence) {

?>
<html>
    <head>
        <title>Exam Fetcher (VCAA)</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <meta name="description" content="VCAA exam fetcher is an easy web tool that is used to fetch VCAA past exam papers." />
        <meta name="keywords" content="Exam, Fetcher, VCAA, FIStudio, web, tools, easy, fast" />
        <meta name="author" content="metatags generator">
        <meta name="robots" content="index, follow">
        <meta name="revisit-after" content="3 month">
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link rel="stylesheet" href="assets/lib/jquery-ui.css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <script src="assets/lib/jquery-2.1.3.min.js"></script>
        <script src="assets/lib/jquery-ui.js"></script>

        <!-- ICONS -->
        <link rel="apple-touch-icon" sizes="57x57" href="assets/img/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="assets/img/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="assets/img/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="assets/img/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="assets/img/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="assets/img/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="assets/img/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/img/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="assets/img/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon-16x16.png">

        <link rel="manifest" href="assets/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <!-- Text Core Plugin -->
        <link rel="stylesheet" href="assets/lib/textext.core.css" />
        <link rel="stylesheet" href="assets/lib/textext.plugin.autocomplete.css" />
        <link rel="stylesheet" href="assets/lib/textext.plugin.tags.css" />
        <script src="assets/lib/textext.core.js"></script>
        <script src="assets/lib/textext.plugin.autocomplete.js"></script>
        <script src="assets/lib/textext.plugin.tags.js"></script>
        <script src="assets/lib/textext.plugin.suggestions.js"></script>
        <script src="assets/lib/textext.plugin.filter.js"></script>
        
        <!--Others-->
        <script src="assets/lib/jquery.cookie.js"></script>
        <script src='assets/lib/foundation.min.js'></script>
        <script src="assets/lib/alert.js"></script>
        
        <link rel="stylesheet" href="assets/css/normalize.css">
        <link rel="stylesheet" href="assets/css/style.css" />
        <style>
        </style>
    </head>
    <body>
        <div class="centered grid__col--10" id="main" style="margin-top: 5%">
            <h1 style="text-align: center">Welcome to VCAA Exam Fetcher V3 (Omega).</h1>
            <h3 style="text-align: center"><a id="advanced-settings" style="cursor: pointer;"> <i class="fa fa-cogs"></i> Settings</a></h3>

            <!-- Modal Announcement -->
            <div class="modal-frame" id="modal-news">
                <div class="modal">
                    <div class="modal-inset">
                        <div class="button close-modal" id="close-news">Close</div>
                        <div class="modal-body">
                            <h3>Some latest news !</h3>
                            <br/>
                            <div id="news-content">
                                <?php echo $data; ?>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Share Exams -->
            <div class="modal-frame" id="modal-exam">
                <div class="modal">
                    <div class="modal-inset">
                        <div class="button close-modal" id="close-exam">Close</div>
                        <div class="modal-body">
                            <h3>Generate a download link!</h3>
                            <button id="generate-link" style="color: #0b0b0b">GENERATE!</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal settings -->
            <div class="modal-frame" id="modal-settings">
                <div class="modal">
                    <div class="modal-inset">
                        <div class="button close-modal" id="close-settings">Close</div>
                        <div class="modal-body">
                            <h3>Advanced settings(Use With Caution!):</h3>
                            <label><a class="btn paper" id="toggle-auto-quickaccess" > <i class="fa fa-square-o"></i> Toggle auto QuickAccess (Beta) <input type="checkbox" class="checkbox" name="quickaccess-toggle" id="quickaccess-toggle" style="display: none"  /></a></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-frame" id="modal-share">
                <div class="modal">
                    <div class="modal-inset">
                        <div class="button close-modal" id="close-exam">Close</div>
                    </div>
                </div>
            </div>
            <div class="modal-overlay"></div>

            <!--- Floating Buttons -->
            <div id="container-floating">
                <div title="Unihigh Login (Comming Soon)" class="nd1 nds" id="unihigh-login">
                    <p class="letter"><i style="margin-top: 30%" class="fa fa-sign-in"></i></p>
                </div>
                <div id="floating-button">
                    <p class="plus_float">+</p>
                    <img class="edit" src="http://ssl.gstatic.com/bt/C3341AA7A1A076756462EE2E5CD71C11/1x/bt_compose2_1x.png">
                </div>
            </div>

            <!-- Slide Menu & Tag-Cloud -->
            <div class="slideItWrapper">
                <a href="#modal" class="slideIt">
                <span class="open">open</span>
                <span class="close">close</span>
                </a>
            </div>
            <aside id="pageslide">
                <div id="quick-access">
                    <h3>Recent Searches</h3>
                    <div id="recents">
                        <div class="tag-cloud" id="tag-recents">
                        </div>
                    </div>
                    <h3>Favourites | <<a id="fav-toggle">Click me:)</a>></h3>
                    <div class="tag-cloud" id="tag-favourites">
                    </div>
                    <div id="add-favourites" style="width: 70%; height: 70%; margin: 0 auto; font-size: 12px; text-align: center; display: none">
                        <input type="text" class="form__input" id="add-fav-field" placeholder="Load more for quick access!" />
                        <a class="form__btn" id="fav-add-btn">Add to list</a>
                    </div>
                </div>
            </aside>
            <div class="tab">
                <!-- Tab switches -->
                <div class="centered">
                    <ul class="tabs"  id="tab-switch">
                        <li><a href="#">Single mode</a></li>
                        <li><a href="#">Bulk mode</a></li>
                        <li><a href="#">Extraction mode</a></li>
                    </ul>
                </div>
                <!-- Tab Contents -->
                <div class="tab_content">
                    <div class="tabs_item" id="single_mode_tab">
                        <div class="single" id="single">
                            <form id="sform" method="post">
                                <div id="container">
                                    <div class="checkboxes" style="display: inline-block;margin: 0 auto;width:100%">
                                        <p align="center">
                                            <label>
                                            <input type="checkbox" class="checkbox" name="single_paper_checked" checked /> Exams |</label>
                                            <label>
                                            <input type="checkbox" class="checkbox" name="single_report_checked" checked/> Assessment reports </label>
                                        </p>
                                    </div>
                                    <div id="field_div_id_0">
                                        <h5>
                                            Enter your subject
                                        </h5>
                                        <input value="" type="text" placeholder="Type a few characters and select a subject" name="field_div_id_0_subject" id="field_div_id_0_subject" class="form__input ui-autocomplete-input" autocomplete="off" required="required">
                                        <h5>
                                            Enter year
                                        </h5>
                                        <input value="" type="text" placeholder="Type a few characters and select a year" name="field_div_id_0_year" id="field_div_id_0_year" class="form__input" required="required">
                                        <br>
                                    </div>
                                </div>
                                <div align="center" id="singleBtns" style="margin-bottom: 30px">
                                    <a class="btn paper paper-raise-flatten" id="addBtn" onclick="addField()">Add a new subject field</a>
                                    <a class="btn paper paper-raise-flatten" id="removeBtn" onclick="removeField()" style="display: none;">Remove a subject field</a>
                                </div>
                                <input type="submit" id="submit" value="Click to view the exam!" />
                                <input type="hidden" name="counter" id="counter" />
                                <input type="hidden" name="mode_indicator" id="mode_indicator" value="single">
                                <input type="hidden" name="action" id="action" value="fetch">
                            </form>
                        </div>
                    </div>
                    <div class="tabs_item" id="bulk_mode_tab">
                        <div class="bulk" id="bulk">
                            <form id="bform" method="post">
                                <div class="checkboxes" style="display: inline-block;margin: 0 auto;width:100%">
                                    <p align="center">
                                        <label>
                                        <input type="checkbox" class="checkbox" name="bulk_paper_checked" checked /> Exams |</label>
                                        <label>
                                        <input type="checkbox" class="checkbox" name="bulk_report_checked" checked/> Assessment reports </label>
                                    </p>
                                </div>
                                <div style="">
                                    <h5>Enter your subjects:</h5>
                                    <input id="bulk_subject" placeholder="Type a few characters and select a subject" name="bulk_subject" class="form__input" style="width: 100% !important;" />
                                    <h6>Notice: For subjects, please enter the name of subject from the beginning:<br/>
                                        E.g. When searching for "English As Additional Language", you should start by typing "Eng.." instead of "EAL".
                                    </h6>
                                    <h5>Enter years:</h5>
                                    <input id="bulk_year" placeholder="Type a few characters and select a year" name="bulk_year" class="form__input" style="width:100%; !important;" />
                                    <div id="quick_year_selector" style="display: none">
                                        <h3 style="text-align: center"><strong>OR</strong></h3>
                                        <h5>Get exams from a consecutive number of years! E.g. All the way from 2002 to 2014! </h5>
                                        <h5>
                                        From Year: <input type="text" name="from-year" id="from-year" class="form__input" style="display: inline;width: 20%" /> To Year: <input type="text" name="to-year" id="to-year" class="form__input" style="display: inline; width: 20%;">
                                    </div>
                                </div>
                                <input type="submit" id="submit" name="submit" value="Click to view the exams!" style="margin-top: 20px">
                                <input type="hidden" id="mode_indicator" name="mode_indicator" value="bulk">
                                <input type="hidden" name="action" id="action" value="fetch">
                            </form>
                        </div>
                    </div>
                    <div class="tabs_item" id="extraction_mode_tab">
                        <div class="extraction">
                            <h3>Extraction mode provides a single gateway for extracting exam appendices, including formula sheet, data boolets etc... This database is
                                constantly updating. If you want your subject's extraction to be added, please contact <a href="mailto:service@fistudio.net">service@fistudio.net</a> 
                            </h3>
                            <form id="eform" method="post" action="site/interact.php">
                                <div>
                                    <h5>Enter a subject:</h5>
                                    <input id="ext_subject" placeholder="Type a few characters and select an extraction" name="ext_subject" class="form__input" style="width:100% !important;" required="required">
                                    <input type="hidden" name="ext_selected" id="ext_selected">
                                    <input type="hidden" name="action" value="fetch">
                                    <input type="hidden" name="mode_indicator" value="extraction">
                                    <input type="submit" id="submit" value="Click to download now">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div id="post-result-div"></div>
            <div id="nonsense-single" hidden></div>
            <div id="nonsense-bulk" hidden></div>
            <iframe id="pdf-frame" style="display: none">
            </iframe>
            <h5 style="text-align: center;"> This tool is under FISTUDIO &copy |Easy Exam Fetching Experience From 2002 - <?php echo date("Y")-1 ?> | We use cookie to enhance your fetching experience.</h5>
            <h5 style="text-align: center"> <a href="http://fistudio.net/?p=371" target="_blank" style="text-decoration: underline">Future Developments and change logs</a> | Report a bug to <a style="text-decoration: underline" href=mailto:service@fistudio.net>FISTUDIO</a> </h5>
        </div>

        <script type="text/javascript" src="assets/lib/prelodr-1.0.5.min.js"></script>
        <script type="text/javascript" src="assets/js/str_replace.js" ></script>
        <script type="text/javascript" src="assets/js/initializer.js"></script>
        <script type="text/javascript" src="assets/js/helper.js"></script>
        <script type="text/javascript" src="assets/js/localStorage.js"></script>
        <script type="text/javascript" src="assets/js/formHandler.js"></script>
        <script>
            //tracking
            setInterval(function() {

                if ($.cookie("fileLoading")) {
                    // Remove cookie
                    $.removeCookie('fileLoading', {
                        path: '/'
                    });
                    // Success
                    createInformationalAlertWithTitleAndDelay("Success!", 1700, true);
                }

            }, 1000);

            //news fetching
            var newsContent = document.getElementById('news-content').innerHTML;

            if (parseInt(newsContent) != "0") {

                // Trigger pop up
                $modal_news = $('#modal-news');

                $overlay_news = $('.modal-overlay');

                $modal_news.bind('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e){
                    
                    if($modal_news.hasClass('state-leave')) {
                    
                        $modal_news.removeClass('state-leave');
                    
                    }
                });

                $('#close-news').on('click', function(){

                    $overlay_news.removeClass('state-show');

                    $modal_news.removeClass('state-appear').addClass('state-leave');
                });

                // Open modal
                $overlay_news.addClass('state-show');

                $modal_news.removeClass('state-leave').addClass('state-appear');

            };

        </script>
    </body>
</html>

<?php } else { ?>

    Sorry, we are in maintanence right now. Please come back later!

<?php } ?>