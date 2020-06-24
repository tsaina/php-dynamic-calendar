<?php

//check if current time is set
if (isset($_GET['ct'])) {
    $ct = $_GET['ct'];
} else {
    $ct = date('Y-m');
}

$timestamp = strtotime($ct . '-1');



//Get what today is 
$today = date('Y-m-j', time());

//Get the number of days in the current month
$day_count = date('t');

//0:Sun 1:Mon 3:Tue
$weekDays = date('w', mktime(0, 0, 0, date('m'), 1, date('Y')));


//Create calender
$weeks = array();
$week = '';

 
// Add empty cell
$week .= str_repeat('<td></td>', $weekDays);

//if the day equals today's date, add a class in the <td> for called today (css class)
//otherwise just add the day and then close <td>
//then add one more week to calendar if the condition to add an extra week evaluates to true
for ($day = 1; $day <= $day_count; $day++, $weekDays++) {

    $date = $day;

    if ($today == $date) {
        $week .= '<td class="today">' . $day;
    } else {
        $week .= '<td>' . $day;
    }
    $week .= '</td>';

    // End of the week OR End of the month
    if ($weekDays % 7 == 6 || $day == $day_count) {

        if ($day == $day_count) {
            // Add empty cell
            $week .= str_repeat('<td></td>', 6 - ($weekDays % 7));
        }

        $weeks[] = "<tr>" . $week . "</tr>\n";

        // Prepare for new week
        $week = '';
    }
}

$holidays = array("Jan 01", "May 01", "Jun 01",
                 "Oct 10", "Oct 20", "Dec 12", "Dec 25", "Dec 26");

$myDate = date_create("2020-01-01");
$yDays = date('z', strtotime('2020-12-31'));

                for ($i=0; $i < $yDays; $i++) { 
                    
                     //$newDate = date_add($myDate, date_interval_create_from_date_string("$i days"));
                     $newDate = date_add(date_create("2020-01-01"), date_interval_create_from_date_string("$i days"));
                     if (in_array(date_format($newDate, 'M d'), $holidays)) 
                        echo date_format($newDate, "Y/m/d"), " $i days", ' H ', date_format($newDate, 'M d'), '<br>';
                   // else
                      //  echo date_format($newDate, "Y/m/d"), " $i days", ' D ', date_format($newDate, 'M d'), " yd=$yDays", '<br>';
                     
                 }
        

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>css calendar</title>

    <link rel="stylesheet" href="style2.css">
</head>

<body>
    <div class="container">
        <div class="calendar">
            <div class="front">
                <div class="currentDate">
                    <h1>
                        <?php
                        $today = date('l d Y', time());
                        echo $today;
                        ?>
                    </h1>
                    <h1>
                    <form >
                        <?php
                        
                        ?>
                    </form>    
                    </h1>
                </div>
                <table class="table table-bordered">
                    <tr>
                        <th>SUN</th>
                        <th>MON</th>
                        <th>TUE</th>
                        <th>WED</th>
                        <th>THUR</th>
                        <th>FRI</th>
                        <th>SAT</th>

                        <?php
                            strtotime('D', $timestamp);
                            echo date('D');

                        ?>

                    </tr>

                    <?php
                    foreach ($weeks as $week) {
                        echo $week;
                    }
                    ?>
                </table>
            </div>
            <div class="link">
                <h3> < </h3>
                <p> <?php echo date('M Y', time());?> </p>
                <h3> > </h3>
            </div>
        </div>

    </div>
    </div>
    </div>
</body>

</html>