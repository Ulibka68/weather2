
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


//class OpenWeatherMapXML extends OpenWeatherMap
//{
//    /**
//     * @param string $answer The content returned by OpenWeatherMap.
//     *
//     * @return \SimpleXMLElement
//     * @throws OWMException If the content isn't valid XML.
//     */
//    private function parseXML($answer)
//    {
//        // Disable default error handling of SimpleXML (Do not throw E_WARNINGs).
//        libxml_use_internal_errors(true);
//        libxml_clear_errors();
//        try {
//            return new \SimpleXMLElement($answer);
//        } catch (\Exception $e) {
//            // Invalid xml format. This happens in case OpenWeatherMap returns an error.
//            // OpenWeatherMap always uses json for errors, even if one specifies xml as format.
//            $error = json_decode($answer, true);
//            if (isset($error['message'])) {
//                throw new OWMException($error['message'], isset($error['cod']) ? $error['cod'] : 0);
//            } else {
//                throw new OWMException('Unknown fatal error: OpenWeatherMap returned the following json object: ' . $answer);
//            }
//        }
//    }
//
//    public function getWeatherForecastXML($query, $units = 'imperial', $lang = 'en', $appid = '', $days = 1)
//    {
//        $this->weatherHourlyForecastUrl='https://ru.api.openweathermap.org/data/2.5/forecast?';
//        if ($days <= 5) {
//            $answer = $this->getRawHourlyForecastData($query, $units, $lang, $appid, 'xml');
//        } elseif ($days <= 16) {
//            $answer = $this->getRawDailyForecastData($query, $units, $lang, $appid, 'xml', $days);
//        } else {
//            throw new \InvalidArgumentException('Error: forecasts are only available for the next 16 days. $days must be 16 or lower.');
//        }
//        $xml = $this->parseXML($answer);
//        return $xml;
//    }
//}



// Language of data (try your own language here!):
$lang = 'ru';
$DTZ=new DateTimeZone('Europe/Moscow');

// Units (can be 'metric' or 'imperial' [default]):
$units = 'metric';

// Get OpenWeatherMap object. Don't use caching (take a look into Example_Cache.php to see how it works).
$owm = new OpenWeatherMap('a4b54ac1bc011d79f42efefcf2e86e1d');

$forecast = $owm->getWeatherForecastXML(554233, $units, $lang, 'a4b54ac1bc011d79f42efefcf2e86e1d', 1);
//die(0);

$owm->weatherUrl='https://ru.api.openweathermap.org/data/2.5/weather?';
$weather = $owm->getWeather(554233, $units, $lang);

$weather->lastUpdate->setTimezone($DTZ);

$curTime=$weather->lastUpdate->format('H:i');
$curTemp=strval(round($weather->temperature->now->getValue(),0)) . " °C";
$curDescr=$weather->weather->description;
$currIcon='./img/' . $weather->weather->icon . '.png';

$weather->sun->set->setTimezone($DTZ);
$curSunSet= $weather->sun->set->format("H:i:s");

$forecastTmp =[];
$a=$forecast->forecast->time[1] ;


$utctz = new \DateTimeZone('UTC');
$DTZ2=new DateTimeZone('+6');

foreach ($forecast->forecast->time as $weather) {
    $oneElem = [];
    $fromT=new DateTime($weather['from'],$utctz);
    $fromT->setTimezone($DTZ2);
    $oneElem['from'] =$fromT->format('d.m H:i');
    $temp=round(floatval($weather->temperature['value']),0);
    $oneElem['temperature']=strval( $temp) . " °C" ;
    $symNumber=intval($weather->symbol['number']); // 50
    $symDescription=strval($weather->symbol['name']); // легкий дождь
    $oneElem['description'] =$symDescription;
    $iconName=strval($weather->symbol['var']);
    $oneElem['imgSrc'] =   './img/' . $iconName . '.png';

    $forecastTmp[]=$oneElem;
}

/*
foreach ($forecast.forecast.time as $weather) {
    $oneElem = [];
    // Each $weather contains a Cmfcmf\ForecastWeather object which is almost the same as the Cmfcmf\Weather object.
    // Take a look into 'Examples_Current.php' to see the available options.

    $weather->time->from->setTimezone($DTZ);
    $oneElem['from'] =   $weather->time->from->format('d.m H:i');
    $oneElem['temperature'] =  strval( $weather->temperature->getValue()) . " °C" ;
//    $oneElem['temperature'] =  strval( round($weather->temperature->getValue(),0)) . " °C" ;
    $oneElem['imgSrc'] =   './img/' . $weather->weather->icon . '.png';
    $oneElem['description'] =  $weather->weather->description ;
    $forecastTmp[]=$oneElem;
}
*/
//die(0);

$loader = new Twig_Loader_Filesystem('templates');
//$twig = new Twig_Environment($loader, array('cache' => 'cache',));
$twig = new Twig_Environment($loader);

echo $twig->render('forecast.tmpl', array(
    'forecastTmp' => $forecastTmp,

    'curTime'=>$curTime,
    'curTemp'=>$curTemp,
    'curDescr'=>$curDescr,
    'currIcon'=>$currIcon,
    'curSunSet'=>$curSunSet

));



