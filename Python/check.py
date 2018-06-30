#MODULO PER METTERE IL FLAG DI INVIATO SUL DATABASE INTERNO DEL DISPOSITIVO

def CHECK(tipo_mis, marca, modello,serialenum,seriale, unita,misura,data_ora):
    import sqlite3 as lite
    try:
        con = lite.connect('coxnico.db')

        cur = con.cursor()

        cur.execute("UPDATE Misure SET `check`=1 WHERE `tipo_mis`=? AND `marca`=? AND `modello`=? AND `serialenum`=? AND `seriale`=? AND `unita`=? AND `misura`=? AND `data_ora`=?;",(tipo_mis, marca, modello,serialenum,seriale,unita,misura,data_ora));

        con.commit()

    except lite.Error, e:

        if con:
           con.rollback()

        print "Error %s:" % e.args[0]


    finally:

        if con:
           con.close()
