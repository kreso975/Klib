#!/usr/bin/php -q
<?php
/**
 * Created by PhpStorm.
 * User: Kokanovic
 * Date: 05/04/2017
 * Time: 23:25
 */

require_once './Omega2lib.php';

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

/********Socket Server*********************/

set_time_limit (0);

// Set the ip and port we will listen on
$address = '192.168.1.50';
$port = 6789;

// Create a TCP Stream socket
$sock = socket_create(AF_INET, SOCK_STREAM, 0); // 0 for  SQL_TCP

// Bind the socket to an address/port
socket_bind($sock, 0, $port) or die('Could not bind to address');  //0 for localhost

// Start listening for connections
socket_listen($sock);

//loop and listen
do {
    /* Accept incoming  requests and handle them as child processes */
    $client =  socket_accept($sock);

    //Handshake
    $secKey = $headers['Sec-WebSocket-Key'];
    $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
    $upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
        "Upgrade: websocket\r\n" .
        "Connection: Upgrade\r\n" .
        "WebSocket-Origin: ".$address."\r\n" .
        "WebSocket-Location: ws://$address:$port/deamon.php\r\n".
        "Sec-WebSocket-Accept:$secAccept\r\n\r\n";
    socket_write($client,$upgrade,strlen($upgrade));

    do {
        $message = "";
        $response = "";

        // Read the input  from the client – 1024000 bytes
        $input =  socket_read($client, 1024000);

        // Strip all white  spaces from input - just an sample for testing purposes
        $output =  preg_replace("[ \t\n\r]","",$input)."\0";
        $message = explode('=',$output);

        if( count($message) == 2 )
        {
            echo $message[0];
            if ( $message[0] == "temp" )
            {
                $temperature = $BayMax->read1Wtemperature("C");

                if ( $temperature )
                    $response = "Temperatura: ".$temperature."C\n";
                else
                    $response = "Nema ocitovanja temperature";
            }

            if ( $message[0] == "led" )
            {
                init($BayMax);

                for ( $i=1; $i<=1; $i++ )
                {
                    // Just on of board multi LED and Led connected to Relay expansion
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
            }

        }
        else $response='NEW:0\n';

        // Display output  back to client
        socket_write($client, $response);
        //socket_close($client);

    }  while (true);

    socket_close($client);

} while (true);

// Close the master sockets
socket_close($sock);
?>