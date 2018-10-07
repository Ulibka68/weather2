<?php
/**
 * Created by PhpStorm.
 * User: Gayrat
 * Date: 06.10.2018
 * Time: 18:25
 */

include 'xmlTest1.php';

$forecast = new SimpleXMLElement($xmlstr);

$t0=$forecast->forecast->time[0];

$from=$t0['from'];

$utctz = new \DateTimeZone('UTC');


foreach ($forecast->forecast->time as $weather) {
    $fromT=new DateTime($weather['from'],$utctz);
    $temp=floatval($weather->temperature['value']);
    $symNumber=intval($weather->symbol['number']); // 50
    $symDescription=strval($weather->symbol['name']); // легкий дождь
    $iconName=strval($weather->symbol['var']); // легкий дождь

}