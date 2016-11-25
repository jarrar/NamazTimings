<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 1920 Hwy-54,Suite 150, Durham, NC, 27713
 * @author jjaffari
 */
interface Configuration {

    const ORG_TIME_ZONE = "America/New_York";
    const GMT_DST = "-240";
    const GMT_EST = "-300";
    const ORG_ADDRESS_NUMBER = "1920";
    const ORG_ADDRESS_STREET = "Hwy-54,Suite 150";
    const ORG_ADDRESS_CITY = "Durham";
    const ORG_ADDRESS_STATE = "NC";
    const ORG_ADDRESS_ZIP = "27713";
// Google address bar


    const GOOGLE_MAPS_API_URL = "http://maps.googleapis.com/maps/api/geocode/json?address=";

}
