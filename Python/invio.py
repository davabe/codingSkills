#MODULO DI INVIO DI DATI -MISURAIZONI- PROCESATI AL SERVER

import curl
import urllib
import json
import sqlite3 as lite
from time import sleep

def SEND():
    con = lite.connect('coxnico.db')

    with con:

        cur = con.cursor()
        cur.execute("SELECT * FROM Misure WHERE `check`=0")

        while True:

            row = cur.fetchone()

            if row == None:
                break

            else:
                #print row[0], row[1], row[2], row[3], row[4], row[5],row[6],row[7],row[8]
                #import invio
                INVIO(row[0],row[1],row[2],row[3],row[4],row[5],row[6],row[7])

def INVIO(tipo_mis, marca, modello,serialenum,seriale,unita,misura,data_ora):
    jsona = json.dumps({
            "type" : tipo_mis+";"+marca+";"+modello,
            "serial" : serialenum,
            "medicalSerial" : seriale ,
            "um" : unita ,
            "mea" : misura,
            "datetime" : data_ora
    })

    print jsona


    dato = urllib.urlencode({"json":jsona})

    movidas = curl.curl("http://ws.coxnico.com/receive.php", req_type="POST", data=dato)
   # print movidas.content
    if movidas.content is "DONE":
     import check
     check.CHECK(tipo_mis, marca, modello,serialenum,seriale,unita,misura,data_ora)
    else:
     print "NON HO POTUTO INVIARE LA MISURAZIONE"
     import pinLED
     pinLED.fail()


def INVIODB(tipo_mis, marca, modello,serialenum,seriale,unita,misura,data_ora):
    jsona = json.dumps({
            "type" : tipo_mis+";"+marca+";"+modello,
            "serial" : serialenum,
            "medicalSerial" : seriale ,
            "um" : unita ,
            "mea" : misura,
            "datetime" : data_ora
    })

    print jsona


    dato = urllib.urlencode({"json":jsona})

    movidas = curl.curl("http://ws.coxnico.com/receive.php", req_type="POST", data=dato)
    print movidas.content
    if movidas.content is "DONE":
        import checkDB
        checkDB.CHECKDB(tipo_mis, marca, modello,serialenum,seriale,unita,misura,data_ora)
        import pinLED
        pinLED.invioCompletato()
    else:
        print "NON HO POTUTO INVIARE LA MISURAZIONE"
        import pinLED
        pinLED.fail()
