<?php
$restapi = true;
$error = "Az open-meteo.com API-ja jelenleg nem működik.";
$lat = "47.4984";
$lon = "19.0408";

$url = "https://api.open-meteo.com/v1/forecast?latitude=$lat&longitude=$lon&hourly=temperature_2m,cloudcover,windspeed_10m,winddirection_10m,precipitation,rain,weathercode&daily=weathercode,temperature_2m_max,temperature_2m_min,sunrise,sunset,precipitation_sum,windspeed_10m_max,winddirection_10m_dominant&current_weather=true&timezone=Europe%2FBerlin";

if ($getFile = file_get_contents($url)) {
    $adatok = json_decode($getFile, false);

    /************************************ save json file **************************************/
    $varos = "Budapest";
    $most = date("Y_m_d_H_i");
    file_put_contents("json/$varos-$most.json", $getFile);

    /******************************************* current ********************************************/
    $current['temperature'] = round($adatok->current_weather->temperature);
    $current['windspeed'] = round($adatok->current_weather->windspeed);
    $current['winddirection'] = $adatok->current_weather->winddirection;
    $current['weathercode'] = $adatok->current_weather->weathercode;
    $current['date'] = substr($adatok->current_weather->time, 0, 10);
    $current['time'] = substr($adatok->current_weather->time, 11, 5);
    $current['sunrise'] = substr($adatok->daily->sunrise[0], 11, 5);
    $current['sunset'] = substr($adatok->daily->sunset[0], 11, 5);
    $timeCurrent = $adatok->current_weather->time;
    $sunriseToday = $adatok->daily->sunrise[0];
    $sunsetToday = $adatok->daily->sunset[0];
    if ($sunriseToday <= $timeCurrent && $timeCurrent < $sunsetToday) {
        $current['icon'] = $adatok->current_weather->weathercode . 'd';
    } else {
        $current['icon'] = $adatok->current_weather->weathercode . 'n';
    }

    /******************************************* daily ********************************************/
    $daily = [];
    for ($i = 0; $i < count($adatok->daily->time); $i++) {
        $date = $adatok->daily->time[$i];
        //$month = substr($date, 5, 2);
        $day = new stdClass;
        //$day->date = substr($date, 8, 2);
        $day->date = $adatok->daily->time[$i];
        $day->maxTemp = round($adatok->daily->temperature_2m_max[$i]);
        $day->minTemp = round($adatok->daily->temperature_2m_min[$i]);
        $day->icon = $adatok->daily->weathercode[$i] . 'd';
        $day->sunrise = substr($adatok->daily->sunrise[$i], 11, 5);
        $day->sunset = substr($adatok->daily->sunset[$i], 11, 5);
        $day->windspeed = round($adatok->daily->windspeed_10m_max[$i]);
        $day->winddirection = $adatok->daily->winddirection_10m_dominant[$i];
        $day->precipitation_sum = $adatok->daily->precipitation_sum[$i];
        array_push($daily, $day);
    }

    /******************************************* hourly ********************************************/
    $firstHour = (int)substr($adatok->current_weather->time, 11, 2) + 1;
    //$lastHour = count($adatok->hourly->time);
    $lastHour = $firstHour + 24;
    $hourly = [];
    for ($i = $firstHour; $i < $lastHour; $i++) {
        $date = $adatok->hourly->time[$i];
        $hour = new stdClass;
        $hour->time = substr($date, 11, 5);
        $hour->temp = round($adatok->hourly->temperature_2m[$i]);
        //nappali vagy éjszakai ikon legyen az adott órában
        $hourIndex = bcdiv($i, 24, 0);
        $sunrise = $adatok->daily->sunrise[$hourIndex];
        $sunset = $adatok->daily->sunset[$hourIndex];
        if ($sunrise <= $date && $date < $sunset) {
            $hour->icon = $adatok->hourly->weathercode[$i] . 'd';
        } else {
            $hour->icon = $adatok->hourly->weathercode[$i] . 'n';
        }
        $hour->cloudcover = $adatok->hourly->cloudcover[$i];
        $hour->windspeed = round($adatok->hourly->windspeed_10m[$i]);
        $hour->winddirection_10m = $adatok->hourly->winddirection_10m[$i];
        $hour->precipitation = $adatok->hourly->precipitation[$i];
        array_push($hourly, $hour);
    }
    // echo '<pre>';
    // print_r($adatok);
    // echo '</pre>';
} else {
    $restapi = false;    
}
