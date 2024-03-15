<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="calendar.css">
    <title>Calendar</title>
</head>

<body>
    <?php
            session_start();
            include("navbar.php");
            // include database connection script
            include("database.php");
    ?>
    
    <div class="calendar">
        <div class="calendar-main">
            <button type="button" id="previous">
                <<<
            </button>
            <div class="month-year" id="month-yearnum"></div>
            <button type="button" id="future">
                >>>
            </button>
        </div>

        <div class="calendar-weeks">
            <div>Sunday</div>
            <div>Monday</div>
            <div>Tuesday</div>
            <div>Wednesday</div>
            <div>Thursday</div>
            <div>Friday</div>
            <div>Saturday</div>
        </div>
        <div class="calendar-days"></div>
    </div>

    <script src="calendar.js"></script>
</body>