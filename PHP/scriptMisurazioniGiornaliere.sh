#!/bin/bash

echo "Inizio esecuzione script controllo misurazioni pazienti"
echo ""
date +%T___%d-%m-%Y
echo ""

cd /var/www/coxnico/script/

php scriptMisurazioniGiornaliere.php 

echo ""
echo "Fine esecuzione script"
echo ""
date +%T___%d-%m-%Y
echo ""
echo "--------------------------------------------------"
echo ""
