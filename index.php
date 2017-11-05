<?php
/**
 * Created by PhpStorm.
 * User: Kokanovic
 * Date: 31/03/2017
 * Time: 19:02
 */
require_once 'Omega2lib.php';

$BayMax = new Omega2( FALSE ); //FALSE is no logging

function init($BayMax)
{
    //set off
    $BayMax->setRGBled();
    $BayMax->initRelay();
}



/*
 * Localized or adopted functions for small Kids use
 */

function postaviRGBled ( $value = "off" ) {GLOBAL $BayMax; $BayMax->setRGBled($value);}
function cekaj ( $value ) {GLOBAL $BayMax; $BayMax->wait($value); }
function prekidac ( $gpio, $value ) {GLOBAL $BayMax; $BayMax->writeRelay( $gpio, $value ); }

/*
 * -------------------
 * Example for reading 1-wire temperature sensor
 */


init($BayMax);

//phpinfo();
//die();
$temperature = $BayMax->read1Wtemperature("C");

if ( $temperature )
    echo "Temperatura: ".$temperature."C";
else
    echo "Nema ocitovanja temperature";
//die();


// --------------------------------------------------

for ( $i=1; $i<=1; $i++ )
{
    postaviRGBled("FF0000");
    prekidac(0, 1);
    cekaj(1);
    postaviRGBled("00FF00");
    prekidac(0, 0);
    cekaj(1);
    postaviRGBled("0000FF");
    prekidac(0, 1);
    cekaj(1);
    postaviRGBled();
    prekidac(0, 0);
}

$command = "fast-gpio set 1 0 > /dev/null & echo $!";
shell_exec( $command );

?>