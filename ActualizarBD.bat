@ECHO OFF
ECHO =  =  =  =  =  =  =  =  =  =  =  =  =  =  =  =  =  =
ECHO = * Comprobar Actualizaciones de ControlEscolar    =
ECHO =  =  =  =  =  =  =  =  =  =  =  =  =  =  =  =  =  =
ECHO .
ECHO .
cd C:\xampp\htdocs\cescolar
ECHO * Comprobando Actualizaciones...
call git pull origin master
ECHO .
ECHO .
ECHO =  =  =  =  =  =  =  =  =  =  =  =  =  =  =
ECHO =   ¡PROYECTO ACTUALIZADO CORRECTAMENTE!  =
ECHO =  =  =  =  =  =  =  =  =  =  =  =  =  =  =
ECHO .
ECHO .
PAUSE
