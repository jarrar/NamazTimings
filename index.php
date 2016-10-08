<?php
include 'namazTimes.php';
date_default_timezone_set('America/New_York');

$namaz = new NamazTime();
$data = $namaz -> get_namaz_times_for_today();
$today = date("F j, Y, g:i a");

echo "Today's date: $today ";

if ($namaz -> is_daylight_savings()) {
	echo "DST <br>";
}

// foreach ($data as $key => $value) {
// # conversion into 12 Hr time
// $namaz_time = date('g:i a', strtotime($value));
// echo "{$key} : {$namaz_time} <br>";
// }
?>

<table align="left">
	<tr>
		<td>
		<table id="namazTable" cellSpacing="0" cellPadding="0" width="500" border="0">
			<TR>
				<?php
				foreach ($data as $key => $value) {
					echo "<td>{$key}</td>";
				}
				?>
			</TR>
			<tr>
				<?php
				foreach ($data as $key => $value) {
					# conversion into 12 Hr time
					$namaz_time = date('g:i a', strtotime($value));
					echo "<td>{$namaz_time}</td>";
				}
				?>
			</tr>
		</table>
</table>
