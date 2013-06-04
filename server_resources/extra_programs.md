Instrucciones para hacer funcionar el servidor de actividad
********************************************

 * Instalar Node

 * Ejecutar "node app/node/server.js"
 	- **IMPORTANTE** Debe ejecutarse como servicio! (daemon)
 	    -> para iniciar como servicio: nohup node app/node/server.js &

 * En caso de errores,
 	** instalar npm (node package manager)
 	** Instalar "npm install socket.io"
 	** Verificar otra vez

 * TODO: Sincronizar con BBDD a través de Persistence


Software necesario para hacer funcionar la plataforma
********************************************


wkhtmltopdf //saca los informes en pdf

    Necesita esta libreria para funcionar en servidor
        #apt-get install xvfb
        #apt-get install wkhtmltopdf