if __name__ == "__main__":
    print "Hello World"

#!/usr/bin/python

#STRINGA ASPETTATA
# Glu, 200, mg/dL,04,110101,1302

def MENARINI():
    import serial
    import time
    import seriale
    seriale = seriale.SERIALE()
#inizio menarini
    ser = serial.Serial(port='/dev/ttyUSB0',
       baudrate=9600,
       bytesize=serial.EIGHTBITS,
       parity=serial.PARITY_ODD,
       stopbits=serial.STOPBITS_ONE,
       timeout=5,
       xonxoff=False,
       rtscts=False,
       writeTimeout=None,
       dsrdtr=False)

    #DEFINES
    tipo_mis = "glucometro"
    marca = "Menarini"

    ## domande
    sysinfo = "\xA2"
    misura = "\x80"
    end = "\x0D"

#aqui hay tomate porque_sempiterno

    #SYSINFO AND SERIALE
    ser.write(sysinfo)
    ser.write(end)
    response = ser.readline()
    response = response.split(' ')
    print("Modello: " + response[6])
    modello = response[6]
    print("Prodotto: " + modello)
    global serialenum
    serialenum = response[4]
    print("Seriale: " + serialenum)

    #LEGGERE MISURAZIONI
    ser.write(misura)
    ser.write(end)
    response = ser.readline()
    response = response.split(' ')
    quanto = response[2]
    quanto = int(quanto)


    while response[0] != "\x04":

            response = ser.readline()
            response = response.split(',')
            misura = response[2]
            unita = response[3]
            data_ora = response[5]+"_"+response[6]+"00"
            print response
            import insertDB
            insertDB.DB(tipo_mis, marca, modello, serialenum,seriale,unita,misura,data_ora)



    import invio
    invio.SEND()
    #gestione errore
