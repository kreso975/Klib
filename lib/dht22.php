<?php
/**
 * Created by PhpStorm.
 * User: Kokanovic
 * Date: 09/10/2017
 * Time: 00:55
 */

/*
 *  dht11_string.c:
 *  Simple test program to test the wiringPi functions
 *  DHT11 test
 */

#include <wiringPi.h>

#include <stdio.h>
#include <stdlib.h>
#include <stdint.h>
#define DHTPIN	7
#define samples  20000



main()
{
FILE *f1, *fopen();
   char testbit, dht11_dat[samples];
   int i;

   /* pull pin down for 18 milliseconds */
   wiringPiSetup();
   delay (500);
   pinMode( DHTPIN, OUTPUT );
   delay (500);
   digitalWrite( DHTPIN, LOW );
   delay( 18 );
   /* then pull it up for 40 microseconds */
   digitalWrite( DHTPIN, HIGH );
   delayMicroseconds( 40 );
   /* prepare to read the pin */
   pinMode( DHTPIN, INPUT );

   /*read 40 bits of dht11 by sampling low and high state of the bytes*/
   /*then use python to convert the samples into bytes*/
   for (i=0;i<samples; i++)
   {
       if (digitalRead(DHTPIN))
       {
           testbit = '1';
       }
       else
       {
           testbit = '0';
       }
       dht11_dat[i] = testbit;
   }


   f1 = fopen("dht11.dat", "w");

   for (i = 0; i <samples ; i++)
   {
       /*fprintf(f1,(char) dht11_dat[i]);*/
       /*fputs(*dht11_dat[1],f1);*/
       fprintf (f1,"%c",dht11_dat[i]);
   }
   fclose(f1);
   return(0);
}