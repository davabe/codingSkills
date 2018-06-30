import os

def invioCompletato():
    os.system("echo 10 > /sys/class/gpio/export")
    os.system("echo high > /sys/class/gpio/gpio10/direction")

def fail():
    os.system("echo 4 > /sys/class/gpio/export")
    os.system("echo high > /sys/class/gpio/gpio4/direction")
    os.system("echo 10 > /sys/class/gpio/export")
    os.system("echo high > /sys/class/gpio/gpio10/direction")

def cleanFail():
    os.system("echo low > /sys/class/gpio/gpio4/direction")
    os.system("echo low > /sys/class/gpio/gpio10/direction")


def bateria():
    #CERCARE COMMANDO PER VEDERE STATO BATTERIA 28,29,30,31 sono i pin per le tacche della batteria
    os.system("echo 12 > /sys/class/gpio/export")
    os.system("echo high > /sys/class/gpio/gpio12/direction")
    os.system("echo 13 > /sys/class/gpio/export")
    os.system("echo high > /sys/class/gpio/gpio13/direction")
    os.system("echo 14 > /sys/class/gpio/export")
    os.system("echo high > /sys/class/gpio/gpio14/direction")
    os.system("echo 15 > /sys/class/gpio/export")
    os.system("echo high > /sys/class/gpio/gpio15/direction")

def segnale(livello):
    if livello >= 1:
        os.system("echo 1 > /sys/class/gpio/export")
        os.system("echo high > /sys/class/gpio/gpio1/direction")
    if livello >= 2:
        os.system("echo 2 > /sys/class/gpio/export")
        os.system("echo high > /sys/class/gpio/gpio2/direction")
    if livello >= 3:
        os.system("echo 3 > /sys/class/gpio/export")
        os.system("echo high > /sys/class/gpio/gpio3/direction")
    if livello >= 4:
        os.system("echo 4 > /sys/class/gpio/export")
        os.system("echo high > /sys/class/gpio/gpio4/direction")

def prelievo():
    os.system("echo 6 > /sys/class/gpio/export")
    os.system("echo high > /sys/class/gpio/gpio6/direction")

def collegamento():
    os.system("echo 7 > /sys/class/gpio/export")
    os.system("echo high > /sys/class/gpio/gpio7/direction")

def invio():
    os.system("echo 9 > /sys/class/gpio/export")
    os.system("echo high > /sys/class/gpio/gpio9/direction")

def clean():
    os.system("for ((i=0; i<32; i++)); do echo \$i; echo in >/sys/class/gpio/gpio\$i/direction; echo \$i >/sys/class/gpio/unexport; done")
