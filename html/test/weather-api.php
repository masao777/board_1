<?php
$weather_config = array();
$weather_array = array();

$weather_config = array(
    'appid' => '2f9097cdbe6af62eafc7c69bc83ef2d3',
    'cityid' => '1850144',
);
$weather_json = file_get_contents('https://api.openweathermap.org/data/2.5/weather?id='.$weather_config['cityid'].'&appid='.$weather_config['appid'].'&lang=ja&units=metric');
$weather_array = json_decode($weather_json, true);

$weather_icon = "https://openweathermap.org/img/wn/".$weather_array["weather"][0]["icon"]."@2x.png";
$weather_now = $weather_array["weather"][0]["description"];
$weather_place = $weather_array["name"];
$weather_temp = round($weather_array['main']['temp'], 1);
