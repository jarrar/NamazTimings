<?php

require ('conf/Configuration.php');

class NamazTime implements Configuration {

    private $year = '';
    private $month = '';
    private $org_lat = '';
    private $org_long = '';

    const PRAYTIME_URL = "http://praytime.info/getprayertimes.php";

    function __construct() {
        $this->year = date("Y");
        $this->month = date("n");
        $this->org_lat = '';
        $this->org_long = '';

        $this->determin_coordinates();
    }

    private function get_url_encoded_address() {
        $address = self::ORG_ADDRESS_NUMBER . " " . self::ORG_ADDRESS_STREET . ", " . self::ORG_ADDRESS_CITY;
        $address = $address . ", " . self::ORG_ADDRESS_STATE . ", " . self::ORG_ADDRESS_ZIP;
        return urlencode($address);
    }

    private function make_geo_location_url() {
        $url = self::GOOGLE_MAPS_API_URL . $this->get_url_encoded_address() . "&sensor=false";
        return $url;
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

        $this->org_lat = $json['latitude'];
        $this->org_long = $json['longitude'];
    }

    private function get_coordinates_from_google() {
        $url = $this->make_geo_location_url();

        $json = file_get_contents($url);
        $data = json_decode($json, true);

        $this->org_lat = $this->latitude($data);
        $this->org_long = $this->longitude($data);

        $org_location = array("zip_code" => self::ORG_ADDRESS_ZIP, "latitude" => "$this->org_lat", "longitude" => "$this->org_long");
        $fp = fopen('location.json', 'w');
        fwrite($fp, json_encode($org_location));
        fclose($fp);
    }

    private function determin_coordinates() {
        if (file_exists('location.json')) {
            $this->get_coordinates_from_file();
            return;
        }

        $this->get_coordinates_from_google();
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
        return $this->namaz_times($day);
    }

    // this is not tested yet - Jarrar
    public function get_namaz_times_for_month() {
        return $this->namaz_times();
    }

    public function next_namaz() {
        $namaz_times_24hr = $this->get_namaz_times_for_today();
        $today_date = date("M/j/y g:i A");
    }

}

?>
