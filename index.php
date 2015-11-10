<html>
<head>
    <title>Exam Fetcher Beta</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta name="description" content="VCAA exam fetcher is an easy web tool that is used to fetch VCAA past exam papers." />
    <meta name="keywords" content="Exam, Fetcher, VCAA, FIStudio, web, tools, easy, fast" />
    <meta name="author" content="metatags generator">
    <meta name="robots" content="index, follow">
    <meta name="revisit-after" content="3 month">
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="assets/jquery-ui.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <script src="assets/jquery-2.1.3.min.js"></script>
    <script src="assets/jquery-ui.js"></script>

    <!-- ICONS ---->
    <link rel="apple-touch-icon" sizes="57x57" href="assets/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="assets/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="assets/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="assets/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon-16x16.png">
    <link rel="manifest" href="assets/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- Text Core Plugin -->
    <link rel="stylesheet" href="assets/textext.core.css" />
    <link rel="stylesheet" href="assets/textext.plugin.autocomplete.css" />
    <link rel="stylesheet" href="assets/textext.plugin.tags.css" />
    <script src="assets/textext.core.js"></script>
    <script src="assets/textext.plugin.autocomplete.js"></script>
    <script src="assets/textext.plugin.tags.js"></script>
    <script src="assets/textext.plugin.suggestions.js"></script>
    <script src="assets/textext.plugin.filter.js"></script>

    <script src="assets/jquery.cookie.js"></script>

    <!--Others-->
    <script src='assets/foundation.min.js'></script>
    <script src="assets/alert.js"></script>

    <link rel="stylesheet" href="assets/style.css" />


    <style>

    </style>
</head>

<body>
<div id="preloader">
    <div class="body">
  	<span>
      	<span></span>
      	<span></span>
      	<span></span>
      	<span></span>
    </span>
        <div class="base">
            <span></span>
            <div class="face"></div>
        </div>
    </div>
    <div class="longfazers">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<div class="centered grid__col--8" id="main" style="margin-top: 5%">
    <h1 style="text-align: center">Welcome to VCAA Exam Fetcher V2.2.5 (Gamma).</h1>
    <h3 style="text-align: center"><a id="advanced-settings" style="text-decoration: underline; cursor: pointer;"> <i class="fa fa-cogs"></i> Advanced Settings</a></h3>

    <!-- Context Menu Download options -->
    <ul class="contextMenu" id="contextMenuDLOptions" hidden>
        <li><a id="download-file"><i class="fa fa-files-o"></i> Download To File</a></li>
        <li><a id="download-zip"><i class="fa fa-file-archive-o"></i> Download To Zip </a></li>
        <li><a id="share-exams"><i class="fa fa-share-square-o"></i> Share these exams </a></li>
        <li><a id="reset-table"><i class="fa fa-cog"></i> Reset </a> </li>
    </ul>

    <!--  Context Menu Advanced Settings -->
    <ul class="contextMenu" id="contextMenuAdvancedSettings" hidden>
        <li><a id="reload-home-cache" title="Use it when you have errors fetching your exam"><i class="fa fa-exclamation-triangle"></i> Reload Cache </a></li>
        <li><a id="toggle-auto-quickaccess" title="Tick it to present quick access panel when typing"> <i class="fa fa-square-o"></i> Enable auto quickaccess <input type="checkbox" class="checkbox" name="quickaccess-toggle" id="quickaccess-toggle" style="display: inline"> </a></li>
    </ul>

    <!-- Context Menu More Options -->
    <ul class="contextMenu" id="contextMenuMoreOptions" hidden>
        <li><a id="download-this-one"><i class="fa fa-download"></i> Download this one</a></li>
        <li><a id="print-this-one"><i class="fa fa-print"></i> Print this one</a></li>
    </ul>

    <!-- Modal Share Exams -->
    <div class="modal-frame" id="modal-exam">
        <div class="modal">
            <div class="modal-inset">
                <div class="button close-modal" id="close-exam">Close</div>
                <div class="modal-body">
                    <h3>You can share the exam with following options.</h3>
                    <span id="st_fb"></span>
                    <span id="st_email" ></span>
                    <h3>Or Generate a download link!</h3>
                    <button id="generate-link" style="color: #0b0b0b">GENERATE!</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-frame" id="modal-settings">
        <div class="modal">
            <div class="modal-inset">
                <div class="button close-modal" id="close-settings">Close</div>
                <div class="modal-body">
                    <h3>Advanced settings(Use With Caution!):</h3>
                    <a class="btn paper" id="reload-home-cache"><i class="fa fa-exclamation-triangle"></i> Reload Cache </a><br/><br/>
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

        <div title="Share exam fetcher" class="nd3 nds" id="site_share">
            <p class="letter" id="share_container"></p>
        </div>
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
                                            <input type="checkbox" class="checkbox" name="singlePaperChecked" checked /> Exams |</label>
                                        <label>
                                            <input type="checkbox" class="checkbox" name="singleReportChecked" checked/> Assessment reports </label>
                                    </p>
                                </div>
                                <div id="field_div_id_0">
                                    <h5>
                                        Enter your subject
                                    </h5>
                                    <input type="text" placeholder="Type a few characters and select a subject" name="field_div_id_0_subject" id="field_div_id_0_subject" class="form__input ui-autocomplete-input" autocomplete="off" required="required">
                                    <h5>
                                        Enter year
                                    </h5>
                                    <input type="text" placeholder="Type a few characters and select a year" name="field_div_id_0_year" id="field_div_id_0_year" class="form__input" required="required">
                                    <br>
                                </div>
                            </div>

                            <div align="center" id="singleBtns" style="margin-bottom: 30px">
                                <a class="btn paper paper-raise-flatten" id="addBtn" onclick="addField()">Add a new subject field</a>
                                <a class="btn paper paper-raise-flatten" id="removeBtn" onclick="removeField()" style="display: none;">Remove a subject field</a>
                            </div>

                            <input type="submit" id="submit" value="Click to view the exam!" />

                            <input type="hidden" name="counter" id="counter" />

                        <input type="hidden" name="modeIndicator" id="modeIndicator" value="0">

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
                                    <input type="checkbox" class="checkbox" name="bulkPaperChecked" checked /> Exams |</label>
                                <label>
                                    <input type="checkbox" class="checkbox" name="bulkReportChecked" checked/> Assessment reports </label>
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
                                <h5>From Year: <input type="text" name="from-year" id="from-year" class="form__input" style="display: inline;width: 20%" /> To Year: <input type="text" name="to-year" id="to-year" class="form__input" style="display: inline; width: 20%;">
                            </div>
                        </div>
                        <input type="submit" id="submit" name="submit" value="Click to view the exams!" style="margin-top: 20px">
                        <input type="hidden" id="modeIndicator" name="modeIndicator" value="1">
                        <input type="hidden" name="action" id="action" value="fetch">
                    </form>
                </div>
            </div>

            <div class="tabs_item" id="extraction_mode_tab">
                <div class="extraction">
                    <h3>Extraction mode provides a single gateway for extracting exam appendices, including formula sheet, data boolets etc... This database is
                    constantly updating. If you want your subject's extraction to be added, please contact <a href="mailto:service@fistudio.net">service@fistudio.net</a> </h3>
                    <form id="eform" method="post" action="function.php">
                        <div>
                            <h5>Enter a subject:</h5>
                            <input id="ext_subject" placeholder="Type a few characters and select an extraction" name="ext_subject" class="form__input" style="width:100% !important;" required="required">
                            <input type="hidden" name="ext_selected" id="ext_selected">
                            <input type="hidden" name="action" value="ext_download" >
                            <input type="submit" id="submit" value="Click to download now">
                        </div>
                    </form>
                </div>
            </div>

        </div>


    </div>

    <div id="post-result-div"></div>

    <iframe id="pdf-frame" style="display: none">

    </iframe>

    <h5 style="text-align: center;"> This tool is under FISTUDIO&copy |Easy Exam Fetching Experience From 2002 - <?php echo date("Y")-1 ?> | We use cookie to enhance your fetching experience.</h5>
    <h5 style="text-align: center"> <a href="http://fistudio.net/?p=371" target="_blank" style="text-decoration: underline">Future Developments and change logs</a> | Report a bug to <a style="text-decoration: underline" href=mailto:service@fistudio.net>FISTUDIO</a> </h5>

</div>
<script src="assets/php.js" type="text/javascript"></script>
<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "7bbc3094-02cb-416d-a3b2-513f8c5c2b05"});</script>
<script src="function.js" type="text/javascript"></script>
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
</script>
</body>

</html>