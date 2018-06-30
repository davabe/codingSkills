#MODULO PER INSERIRE SUL DB LA MISSURA PRELEVATA

def DB(tipo_mis, marca, modello, serialenum,seriale, unita,misura,data_ora):
    import sqlite3 as lite
    try:
        con = lite.connect('coxnico.db')
        cur = con.cursor()
	cur.execute("SELECT * FROM Misure")

	while True:

	          row = cur.fetchone()
		  #print row[0], row[1], row[2], row[3], row[4], row[5],row[6],row[7]
	          if row == None:
			  break
		  else:
			print row[0], row[1], row[2], row[3], row[4], row[5],row[6],row[7]
	       	  	if data_ora > row[7]:
		  		cur.execute("INSERT INTO Misure VALUES(?,?,?,?,?,?,?,?,?);",(tipo_mis, marca, modello, serialenum,seriale,unita,misura,data_ora, '0'));
	                        con.commit()
				break

    except lite.Error, e:

        if con:
           con.rollback()

        print "Error %s:" % e.args[0]


    finally:

        if con:
           con.close()
