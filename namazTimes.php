<?php
class NamazTime {
	private $year = '';
	private $month = '';
	private $day = '';

	private $org_lat = '';
	private $org_long = '';

	const PRAYTIME_URL = "http://praytime.info/getprayertimes.php";
	// In DST -240 and when it is EST then -300
	const GMT_DST = "-240";
	const GMT_EST = "-300";

	function __construct() {
		$this -> year = date("Y");
		$this -> month = date("n");
		$this -> org_lat = '';
		$this -> org_long = '';

		$today = date("F j, Y, g:i a");
		$day = date("j");

		$this -> determin_coordinates();
	}

	// wanna get the address from
	private function make_geo_location_url() {
		return "http://maps.googleapis.com/maps/api/geocode/json?address=4130+Plum+Branch+dr,+cary,+NC,+27519&sensor=false";
	}

	private function latitude($data) {
		return $data['results'][0]["geometry"]["location"]["lat"];
	}

	private function longitude($data) {
		return $data['results'][0]["geometry"]["location"]["lng"];
	}

	private function get_coordinates_from_file() {
		$str = file_get_contents('location.json');
		$json = json_decode($str, true);

		$this -> org_lat = $json['latitude'];
		$this -> org_long = $json['longitude'];
	}

	private function get_coordinates_from_google() {
		$url = $this->make_geo_location_url();

		$json = file_get_contents($url);
		$data = json_decode($json, true);

		$this -> org_lat = $this -> latitude($data);
		$this -> org_long = $this -> longitude($data);

		$org_location = array("zip_code" => "27519", "latitude" => "$this->org_lat", "longitude" => "$this->org_long");
		$fp = fopen('location.json', 'w');
		fwrite($fp, json_encode($org_location));
		fclose($fp);
	}

	private function determin_coordinates() {
		if (file_exists('location.json')) {
			$this -> get_coordinates_from_file();
			return;
		}

		$this -> get_coordinates_from_google();
	}

	private function is_daylight_savings() {
		return date('I') == 1;
	}

	private function get_gmt_value() {
		$my_gmt = self::GMT_EST;

		if (self::is_daylight_savings()) {
			$my_gmt = self::GMT_DST;
		}

		return $my_gmt;
	}

	private function namaz_times($day = "all") {
		$days_option = "";
		if ($day != "all") {
			$days_option = "&d=$day";
		}

		$gmt = self::get_gmt_value();

		$url = self::PRAYTIME_URL . "?lat=$this->org_lat&lon=$this->org_long&gmt=$gmt&m=$this->month&y=$this->year&school=0$days_option";

		//print $url;
		$json = file_get_contents($url);
		$data = json_decode($json, true);
		return $data;
	}

	public function get_namaz_times_for_today() {
		$day = date("j");
		return $this -> namaz_times($day);
	}

	// this is not tested yet - Jarrar
	public function get_namaz_times_for_month() {
		return $this -> namaz_times();
	}

	public function next_namaz() {
		$namaz_times_24hr = $this -> get_namaz_times_for_today();
		$today_date = date("M/j/y g:i a");
	}

}
?>
