
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

$forecast = $owm->getWeatherForecast('Korolev, RU', $units, $lang, '', 1);
//die(0);

$weather = $owm->getWeather('Korolev, RU', $units, $lang);

$weather->lastUpdate->setTimezone($DTZ);

$curTime=$weather->lastUpdate->format('H:i');
$curTemp=$weather->temperature;
$curDescr=$weather->weather->description;
$currIcon='./img/' . $weather->weather->icon . '.png';

$forecast->sun->set->setTimezone($DTZ);
$curSunSet= $forecast->sun->set->format("H:i:s");

$forecastTmp =[];

foreach ($forecast as $weather) {
    $oneElem = [];
    // Each $weather contains a Cmfcmf\ForecastWeather object which is almost the same as the Cmfcmf\Weather object.
    // Take a look into 'Examples_Current.php' to see the available options.

    $weather->time->from->setTimezone($DTZ);
    $oneElem['from'] =   $weather->time->from->format('H:i');
    $oneElem['temperature'] =   $weather->temperature ;
    $oneElem['imgSrc'] =   './img/' . $weather->weather->icon . '.png';
    $oneElem['description'] =  $weather->weather->description ;
    $forecastTmp[]=$oneElem;
}

//die(0);

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader, array(
    'cache' => 'cache',
));

echo $twig->render('forecast.tmpl', array(
    'forecastTmp' => $forecastTmp,

    'curTime'=>$curTime,
    'curTemp'=>$curTemp,
    'curDescr'=>$curDescr,
    'currIcon'=>$currIcon,
    'curSunSet'=>$curSunSet

));



