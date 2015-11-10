<?php

?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Unihigh Exam Fetcher (Alpha)</title>
    <script src="../../assets/jquery-2.1.3.min.js"></script>
    <script src="../../assets/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.0/js/materialize.min.js"></script>
    <script src="../../assets/jquery.cookie.js"></script>
    <script src="../../assets/foundation.min.js"></script>
    <script src="../../assets/alert.js"></script>

    <link href="../../assets/jquery-ui.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.0/css/materialize.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="indigo lighten-5">
    <div class="container">
        <h2 class="center-align" style="font-weight: 200">Welcome to Unihigh Exam Fetcher (Alpha)</h2>
        <div class="center-align">
            <a class="waves-effect waves-teal btn-flat">View A List Of Available Exams</a>
        </div>
        <form id="fetch_form" class="col s12 card-panel">
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">subject</i>
                    <input type="text" placeholder="Search for a subject" class="validate" id="subject_text" name="subject_text">
                    <label for="subject_text">Subject</label>
                </div>
                <div class="input-field col s12">
                    <i class="material-icons prefix">library_books</i>
                    <input type="text" placeholder="Search for a publisher" class="validate" id="publisher_text" name="publisher_text">
                    <label for="publisher_text">Publisher</label>
                </div>
                <div class="input-field col s12">
                    <i class="material-icons prefix">today</i>
                    <input type="text" placeholder="Search for a year" class="validate" id="year_text" name="year_text">
                    <label for="year_text">Year</label>
                </div>
            </div>
            <div class="row center-align">
                <button id="submit" type="submit" name="submit" class="btn waves-effect waves-light">Get the exam ! <i class="material-icons right">send</i></button>
            </div>
        </form>
        <div class="row">
            <div class="center-align">This tool is under FIStudio&copy</div>
        </div>
    </div>
<script src="function.js"></script>
</body>
</html>