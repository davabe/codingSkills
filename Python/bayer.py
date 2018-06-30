import serial
import curl
import urllib
import json
import sqlite3 as lite
import sys


def BAYER():


    # imposto la porta
    ser = serial.Serial(port='/dev/ttyUSB0',
       baudrate=9600,
       bytesize=serial.SEVENBITS,
       parity=serial.PARITY_EVEN,
       stopbits=serial.STOPBITS_ONE,
       timeout=3,
       xonxoff=True,
       rtscts=False,
       writeTimeout=None,
       dsrdtr=False)

    # apro porta
    #ser.open()

    # conferma la porta usata

    print "\nPorta "+ ser.portstr +" aperta, prelevando dati dal dispositivo...\n"
    data = "\x06"

    while data[0] != "\x04":
            # invio la stringa
            ser.write("\x06")
            # leggo
            data = ser.readline()
            data = data.split('|')

            if len(data)>4:

                    if "Glucos" not in data[2]:
                       if "Bay" in data[4]:
                                    splito = data[4].split('^')
                                    if "7150P" in splito[2]:
                                        print bcolors.WARNING+"Bayer CONTOUR XT - model no."+splito[0]+" | serial number: "+splito[2]+bcolors.ENDC
                                        marca = "Bayer"
                                        modello = "Contour XT"
                                        serialenum = splito[2]
                                        tipo_mis = "glucometro"
                                    elif "3950" in splito[2]:
                                        print bcolors.WARNING+"Bayer DEX - model no."+splito[0]+" | serial number: "+splito[2]+bcolors.ENDC
                                    elif "3883" in splito[2]:
                                        print bcolors.WARNING+"Bayer ELITE - model no."+splito[0]+" | serial number: "+splito[2]+bcolors.ENDC
                                    elif "6116" in splito[2]:
                                        print bcolors.WARNING+"Bayer BREEZE - model no."+splito[0]+" | serial number: "+splito[2]+bcolors.ENDC
                                    elif "6115" in splito[2]:
                                        print bcolors.WARNING+"Bayer BREEZE - model no."+splito[0]+" | serial number: "+splito[2]+bcolors.ENDC
                                    elif "7160" in splito[2]:
                                        print bcolors.WARNING+"Bayer CONTOUR next EZ - model no."+splito[0]+" | serial number: "+splito[2]+bcolors.ENDC
                                    elif "7600" in splito[2]:
                                        print bcolors.WARNING+"Bayer CONTOUR Plus - model no."+splito[0]+" | serial number: "+splito[2]+bcolors.ENDC
                                    else:
                                        print bcolors.WARNING+"Bayer GENERIC - model no."+splito[0]+" | serial number: "+splito[2]+bcolors.ENDC
                                    print "\n ---- Iniziando sequenza di lettura delle misurazioni ----\n"
                    else:
                        if len(data)>10:
                            a = data[11]
                            fecha = a[2:8]
                            hora = a[8:12]
                            anno = fecha[:2]
                            mese = fecha[2:4]
                            giorno = fecha[4:6]
                            ora = hora[:2]
                            min = hora[2:4]
                            fechas = giorno+"/"+mese+"/"+anno
                            oras = ora+":"+min+"h"
                            data_ora = '20'+anno+mese+giorno+"_"+ora+min+"00"
                            if "Glucose" in data[2]:
                                print bcolors.OKBLUE+"Misura glucosio - Misurazione numero: "+bcolors.OKGREEN+data[1]+bcolors.OKBLUE+" | tipo:"+bcolors.OKGREEN+data[2].replace("^","",3)+bcolors.OKBLUE+" | misura:"+bcolors.OKGREEN+data[3]+bcolors.OKBLUE+" | tipo dati:"+bcolors.OKGREEN+data[4].replace("^P"," sul plasma")+bcolors.OKBLUE+" | ora e data:"+bcolors.OKGREEN+giorno+"/"+mese+"/"+anno+" "+ora+":"+min+"h"+bcolors.ENDC
                                misura = format(float(data[3]), '.1f')
                                unita = data[4].replace("^P","")

                    import insertDB
                    insertDB.DB(tipo_mis, marca, modello, serialenum,seriale,unita,misura,data_ora)

                            #elif "GlucoseA2" in data[2]:
                            #    print bcolors.OKBLUE+"Media del glucosio 14gg - Misurazione numero: "+bcolors.OKGREEN+data[1]+bcolors.OKBLUE+" | tipo:"+bcolors.OKGREEN+data[2].replace("^","",3)+bcolors.OKBLUE+" | misura:"+bcolors.OKGREEN+data[3]+bcolors.OKBLUE+" | tipo dati:"+bcolors.OKGREEN+data[4].replace("^P"," sul plasma")+bcolors.OKBLUE+" | ora e data:"+bcolors.OKGREEN+fecha+" "+hora+bcolors.ENDC

                       #     print bcolors.FAIL+"\n Attenzione misurazione erronea no."+data[1]+" | tipo:"+data[2].replace("^","",3)+" | misura:"+data[3]+" | tipo dati:"+data[4].replace("^P","")+bcolors.ENDC
                   #     elif "GlucoseA3" in data[2]:

                   #     elif "GlucoseA4" in data[2]:
    # chiudo la porta
    ser.close()

    print "\n Porta chiusa, prelevamento avvenuto con successo. \n\n Preparazione per invio dati alla piattaforma...\n"
    #FINITO IL PRELEVAMENTO ED INSERIMENTO SUL PROPRIO DATABASE INTERNO DELLE MISURAZIONI, INVIO DEI DATI AL SERVER
    import invio
    invio.SEND()
