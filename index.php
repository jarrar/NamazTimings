<?php
include 'namazTimes.php';
date_default_timezone_set('America/New_York');

$namaz = new NamazTime();
$namaz_times = $namaz -> get_namaz_times_for_today();
$today_date = date("M/d/y g:i a");

// if ($namaz -> is_daylight_savings()) {
	// echo "DST <br>";
// }

// foreach ($data as $key => $value) {
// # conversion into 12 Hr time
// $namaz_time = date('g:i a', strtotime($value));
// echo "{$key} : {$namaz_time} <br>";
// }
//?>

<!--table align="left">
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
</table-->
<style type="text/css">
	body {
		background-color: #F8F8F8;
	}

	td {
		color: #3D3D3D;
		font-family: tahoma;
		font-size: 12px;
	}

	.top {
		font-family: tahoma;
		color: #3D3D3D;
		font-size: 11px;
		font-weight: bold;
		text-decoration: none;
	}
	.top:hover {
		font-family: tahoma;
		color: #3D3D3D;
		font-size: 11px;
		font-weight: bold;
		text-decoration: underline;
	}

	table.namaz, th.namaz, td.namaz, tr.namaz {
		border: 0px solid black;
		border-collapse: collapse;
		font-family: tahoma;
		font-size: 12px;
	}

</style>

<!--?php print date('H'); ?-->
<table class="namaz" style="padding:0px">
	<tr><td align="left" class="namaz" width="150"><?php echo $today_date?></td></tr>
	<tr><td align="center" class="namaz">
	<table align="left" class="namaz" style="padding:0px">
		<?php

		// WILL use this mechanism to find if the date is different from when last we picked the times.
		$current = strtotime(date("Y-m-d"));
		$date = strtotime("2016-11-12");

		$datediff = $date - $current;
		$difference = floor($datediff / (60 * 60 * 24));
		
		print "<br>";
		if ($difference == 0) {
			print "$date : today";
		} else if ($difference > 1) {
			print '$date : Future Date';
		} else if ($difference > 0) {
			print '$date : tomarrow';
		} else if ($difference < -1) {
			print '$date : Long Back';
		} else {
			print '$date : yesterday';
		}

		foreach ($namaz_times as $key => $value) {

			echo "<TR align=\"right\" class=\"namaz\">";
			echo "<td class=\"namaz\">{$key}:</td>";
			// convert it into 12 Hour time format
			$namaz_time = date('g:i', strtotime($value));
			echo "<td class=\"namaz\" align=\"right\">{$namaz_time}</td>";
			echo "</TR>";
		}
		?>
		</table>
		</tr>
	</td>
	</tr>
</table
