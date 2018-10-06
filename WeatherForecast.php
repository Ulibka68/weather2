<!--<div id="openweathermap-widget-15"></div>
<script>window.myWidgetParam ? window.myWidgetParam : window.myWidgetParam = [];
    window.myWidgetParam.push({
        id: 15,
        cityid: '554233',
        appid: 'e6be26754ffd0fdf8b6ac70aeb8e0418',
        units: 'metric',
        containerid: 'openweathermap-widget-15',
    });
    (function () {
        var script = document.createElement('script');
        script.async = true;
        script.charset = "utf-8";
        script.src = "//openweathermap.org/themes/openweathermap/assets/vendor/owm/js/weather-widget-generator.js";
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(script, s);
    })();</script>-->



<?php
/**
 * OpenWeatherMap-PHP-API — A php api to parse weather data from http://www.OpenWeatherMap.org .
 *
 * @license MIT
 *
 * Please see the LICENSE file distributed with this source code for further
 * information regarding copyright and licensing.
 *
 * Please visit the following links to read about the usage policies and the license of
 * OpenWeatherMap before using this class:
 *
 * @see http://www.OpenWeatherMap.org
 * @see http://www.OpenWeatherMap.org/terms
 * @see http://openweathermap.org/appid
 */

use Cmfcmf\OpenWeatherMap;

require 'vendor/autoload.php';

// Language of data (try your own language here!):
$lang = 'ru';
$DTZ=new DateTimeZone('Europe/Moscow');

// Units (can be 'metric' or 'imperial' [default]):
$units = 'metric';

// Get OpenWeatherMap object. Don't use caching (take a look into Example_Cache.php to see how it works).
$owm = new OpenWeatherMap('a4b54ac1bc011d79f42efefcf2e86e1d');

// Example 1: Get forecast for the next 5 days for Berlin.
$forecast = $owm->getWeatherForecast('Korolev, RU', $units, $lang, '', 1);
$weather = $owm->getWeather('Korolev, RU', $units, $lang);
$weather->lastUpdate->setTimezone($DTZ);
//$weather->lastUpdate->add(new DateInterval('PT4H'));
echo "Температура сейчас :" . $weather->lastUpdate->format('d.m.Y H:i : ') . $weather->temperature . "<br /><br>\n";
echo "<img src='./img/" . $weather->weather->icon . ".png'>" . "<br />\n";


echo "<H1>City: " . $forecast->city->name . "<br></H1>>\n";
$forecast->lastUpdate->setTimezone($DTZ);
echo "LastUpdate: " . $forecast->lastUpdate->format('d.m.Y H:i');
echo "<br />\n";

//    date_default_timezone_set('Europe/Moscow');
$forecast->sun->set->setTimezone($DTZ);
echo " Заход солнца : " . $forecast->sun->set->format("H:i:s");
echo "<br />\n";
echo "<br />\n";

foreach ($forecast as $weather) {
    // Each $weather contains a Cmfcmf\ForecastWeather object which is almost the same as the Cmfcmf\Weather object.
    // Take a look into 'Examples_Current.php' to see the available options.
    $weather->time->day->setTimezone($DTZ);
    $weather->time->from->setTimezone($DTZ);
    $weather->time->to->setTimezone($DTZ);
    echo "Weather forecast at " . $weather->time->day->format('d.m.Y') . " from " . $weather->time->from->format('H:i') . " to " . $weather->time->to->format('H:i');
    echo "<br />\n";
    echo $weather->temperature . "<br />\n";
    echo "Размер осадков мм : " . $weather->precipitation->getFormatted() . "<br />\n";
    echo $weather->precipitation->getDescription() . "<br />\n";

//    echo "<img src='" . $weather->weather->getIconUrl() . "'>" . "<br />\n";
//    echo $weather->weather->icon . "<br />\n";
    echo "<img src='./img/" . $weather->weather->icon . ".png'>" . "<br />\n";

    echo $weather->weather->description . "<br />\n";
//    echo "Sun rise: " . $weather->sun->rise->format('d.m.Y H:i (e)');
    echo "<br />\n";
    echo "---";
    echo "<br />\n";
}

