<?php
if (isset($_GET['ym'])) {$ym = $_GET['ym'];}	# Get Prev and Next month
else {$ym = date('Y-m');}						# Default to month of system date
#--- Some vital working variables 2020/07/11 ---A
$calendarDay = 1; 										# Every month begins with date(1)
$calendarDate = date_create($ym . '-' . $calendarDay);	# Craft the date to be populated (fully formated date equivalent to the day to be populated)
#
$firstDate = $calendarDate;								# First date of the month - a Date-Object
$daysInMonth = date_format($firstDate, 't');			# Number of days in the month - a Number
$finalDate = date_create($ym .'-'. $daysInMonth);		# Last date of the month - a Date-Object
#
$maxCells = 42;											# Total number of boxes/cells in a tabular calendar
$openingBlanks = date_format($firstDate, 'w');			# Blank cells at the beginning of the FIRST WEEK (i.e. Sunday-Based: 0=Sun, 1=Mon,...,6=Sat)
$firstCell = $openingBlanks + 1;						# Cell to hold the FIRST day of the month
$finalCell = ($openingBlanks + $daysInMonth);			# Cell to hold the LAST day of the month
#
$monthName = date_format($firstDate, 'M Y');			# Craft the name of the month (including the year) - for display
$dateToday = date_create();								# Note system date - a Date-Object (for display)
$prevMonth = date('Y-m', mktime(0, 0, 0, date_format($firstDate, 'm') - 1, 1, date_format($firstDate, 'Y')));	# For page navigation
$nextMonth = date('Y-m', mktime(0, 0, 0, date_format($firstDate, 'm') + 1, 1, date_format($firstDate, 'Y')));	# For page navigation
#
$xWeekends = array(0,6); # 
$holidays = array('Jan 01', 'May 01', 'Jun 01', 'Oct 10', 'Oct 20', 'Dec 12', 'Dec 25', 'Dec 26'); # Known holidays
#-----------------------------------------------Z

#--- Building the Calendar Header 2020/07/11 ---#
$dayNames = array();	# List the 3-letter names of the days of the week
#
while(!in_array(date_format($calendarDate,'wD'), $dayNames))	# Fill the list with unique day-names (stop upon the first duplication)
{	$dayNames[] = date_format($calendarDate,'wD'); 									# Add day-name into the list
	$calendarDate = date_create($ym . '-' . (date_format($calendarDate,'d') +1 ));	# Increment the date by one (1) day
}
sort($dayNames); # Then, arrange the day-names in natural order
#
$kalHeader = "<table class='table table-bordered'>\n\t<tr>\n\t\t";# Start the Calendar Header Row
#
foreach($dayNames as $dayX3)
{	$showHoliday = ''; # Weekend display-style (blank for non-holidays)
	if (substr($dayX3,1,1)=='S') {$showHoliday = "style='color: red;'";}	# Weekend display-style (blank for non-holidays)
	$kalHeader .= "<th $showHoliday>".substr($dayX3,1).'</th>';	# Place the day-name in a header-cell
}
$kalHeader .= "\n\t</tr>";	# End the Calendar Header Row

#--- Building the Calendar Body 2020/07/11 -----#
$calendarDay = 1; $calendarDate = $firstDate; # Reset Calendar-driving variables
$kalBody = "\n\t<tr>\n\t\t";# Start of Tabular Calendar Body
#
for($cellCnt = 1; $cellCnt <= $maxCells; $cellCnt++)	# Make max(42) calendar cells (6-rows X 7-columns)
{	$showHoliday = '';	# Holiday or weekend display-style (blank for non-holidays)
	$showToday = '';		# Current-date display-style (blank for all other days)

	if ($cellCnt >= $firstCell && $cellCnt <= $finalCell)	# Populate a real calendar day
	{	if ( (in_array(date_format($calendarDate, 'w'), $xWeekends)) || (in_array(date_format($calendarDate, 'M d'), $holidays)) ) # Check holiday / weekend
		{	$showHoliday = "style='color: red;'";							# Holiday or weekend display-style (blank for non-holidays)
			if ((in_array(date_format($calendarDate, 'M d'), $holidays)) && date_format($calendarDate, 'D') == 'Sun')
			{	$nextMonday = date_create($ym . '-' . ($calendarDay + 1));	# Note holiday spilling-over into Monday
				$holidays[] = date_format($nextMonday, 'M d');				# Add to list of Holidays - for highlighting
			}
		}
		
    if (date_format($dateToday, 'Ymd') == date_format($calendarDate, 'Ymd'))  # Check current date 
    {   $showToday = "class='today'";} # Current-date display-style (blank for all other days)
	
		$kalBody .= "<td $showToday $showHoliday>".$calendarDay.'</td>';
		$calendarDay += 1;
		$calendarDate = date_create($ym . '-' . $calendarDay);	# Craft the date to be populated (fully formated date equivalent to the day to be populated)
	}
	else {	$kalBody .= '<td>&clubs;</td>';}							# Add a blank table cell (either before or after the real calendar days)

	if ($cellCnt < 42 && $cellCnt % 7 == 0) {$kalBody .= "\n\t</tr>\n\t<tr>\n\t\t";} # Start a new row (ie Week)
}
$kalBody .= "\n\t</tr>\n</table>\n";	# End of Tabular Calendar Body
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
						<h1><?php echo date_format($dateToday, 'l d Y');?></h1>
						<h1>
							<a href="?ym=<?php echo $prevMonth; ?>">&vltri;</a>
							<?php echo $monthName; ?>
							<a href="?ym=<?php echo $nextMonth; ?>">&vrtri;</a>
						</h1>
					</div>
<?php echo $kalHeader, $kalBody; # Drop the calendar/table components here 2020/07/11?>
				</div>
			</div>
		</div>
	</body>
</html>