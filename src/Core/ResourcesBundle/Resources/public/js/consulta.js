$(function(){
  
  $("#consultaTabbable > div").css("display","none");



})
 function initCallInterface(call){

 	//pizarra
 	document.ontouchmove = function(event){
  		event.preventDefault();
	}


 	//interface events
    $(".consultaWrapper").css("display","none");
    $("#consultaTabbable > div#conferencia").css("display","block");
    $("#consultaTabbable ul li a[href=#conferencia]").parent().addClass("active");
    $("#consultaTabbable").attr("data-session",call.session);
    //init call interface
    $("#consultaTabbable ul li a").click(function(evt){
    	var target = evt.delegateTarget.hash;
    	$("#consultaTabbable > div").css("display","none");
    	$("#consultaTabbable > div"+target).css("display","block");
    	$("#consultaTabbable ul li .depth.active").removeClass('active');
		$("#consultaTabbable ul li a[href="+target+"]").parent().addClass("active");

    	evt.preventDefault();
    });

canvasApp(call.session);

  }

  function canvasSupport(){

	return Modernizr.canvas;

}

//AquÃ­ englobo todo lo relacionado con la aplicaciÃ³n canvas.
function canvasApp(tokenSession) {

	//Si el navegador soporta canvas inicio la app.
	if(canvasSupport()){

		var theCanvas = document.getElementById("mipizarra"),
			context = theCanvas.getContext("2d"),
			buttonClean = document.getElementById("limpiarpizarra");
			var _slate_server = 'http://'+window.location.host+':2025';
			console.log("SLATE SERV")
			console.log(_slate_server)
			slateSocket = io.connect(_slate_server);
		window.slateSession = tokenSession;
		init();

	}

	function init(){

		//Dibujo la pizarra sin nada en su interior.
		clean();

		var click = false, //Cambia a true si el usuario esta pintando
			block = false; //Cambia a true si hay otro usuario pintando

		/* Las variables click y block funcionan de forma que cuando un usuario esta dibujando, 
		los demÃ¡s deben esperar a que este termine el trazo para poder dibujar ellos */

		function clean(){
			context.fillStyle = "rgb(95, 153, 104)";
			
			 

			context.fillRect(0,0,theCanvas.width,theCanvas.height);
		}

		//Se inicia al trazo en las coordenadas indicadas.
		function startLine(e){
			context.beginPath();
			context.strokeStyle = "#fff";
			context.lineCap = "round";
			context.lineWidth = 5;
			context.moveTo(e.clientX - theCanvas.offsetLeft, e.clientY - theCanvas.offsetTop);
		}

		//Se termina el trazo.
		function closeLine(e){
			context.closePath();
		}

		//Dibujamos el trazo recibiendo la posiciÃ³n actual del ratÃ³n.
		function draw(e){

			context.lineTo(e.clientX - theCanvas.offsetLeft, e.clientY - theCanvas.offsetTop);
			context.stroke();

		}

		function touchXY(e) {
            if (!e)
                e = event;
            e.preventDefault();
            return {
            	clientX: e.targetTouches[0].pageX,
            	clientY: e.targetTouches[0].pageY,
            }
        }

		//Usamos la librerÃ­a slateSocket.io para comunicarnos con el servidor mediante webslateSockets
		slateSocket.on('connect', function(){
			slateSocket.emit("suscribe",{session:window.slateSession});

			parseTouchEvent = function(e){
					
					switch(e.type){
						case 'touchmove':
							var coords = touchXY(e);
							moveEmitEvent(coords)
							break;
						case 'touchstart':
							var coords = touchXY(e);
							startEmitEvent(coords)
							break;
						case 'touchend':
							var coords = {
								clientX:e.changedTouches[0].pageX,
								clientY:e.changedTouches[0].pageY
							}
							endEmitEvent(coords)
							break;
					}
					e.preventDefault();
					//startEmitEvent(newEvent);

			}

			//Al darle click al botÃ³n limpiar enviamos orden de devolver la pizarra a su estado inicial.
			//buttonClean.addEventListener("click",function(){

			//	if(!block){
			//		slateSocket.emit('clean',true);
			//	}

			//},false);

			//Al clickar en la pizarra enviamos el punto de inicio del trazo
			var startEmitEvent = function(e){
				console.log("EMITING START")
				if(!block){
					slateSocket.emit('startLine',{clientX : e.clientX, clientY : e.clientY,session:window.slateSession});
					click = true;
					startLine(e);
				}

			}
			theCanvas.addEventListener("mousedown",startEmitEvent,false);
			theCanvas.addEventListener("touchstart",parseTouchEvent,false);

			//Al soltar el click (dentro o fuera del canvas) enviamos orden de terminar el trazo
			var endEmitEvent = function(e){
				if(!block){
					slateSocket.emit('closeLine',{clientX : e.clientX, clientY : e.clientY,session:window.slateSession});
					click = false;
					closeLine(e);
				}

			}

			theCanvas.addEventListener("mouseup",endEmitEvent,false);
			theCanvas.addEventListener("touchend",parseTouchEvent,false);

			//Al mover el ratÃ³n mientras esta clickado enviamos coordenadas donde continuar el trazo.
			var moveEmitEvent = function(e){
				if(click){
					if(!block){
						slateSocket.emit('draw',{clientX : e.clientX, clientY : e.clientY,session:window.slateSession});
						draw(e);
					}
				}

			}
			
			theCanvas.addEventListener("mousemove",moveEmitEvent,false);
			theCanvas.addEventListener("touchmove",parseTouchEvent,false);


			//Recibimos mediante webslateSockets las ordenes de dibujo

			slateSocket.on('down',function(e){
				if(!click){
					block = true;
					startLine(e);
				}
			});

			slateSocket.on('up',function(e){
				if(!click){
					block = false;
					closeLine(e);
				}
			});

			slateSocket.on('move',function(e){
				if(block){
					draw(e);
				}
			});
			
			slateSocket.on('clean',clean);
			
		});

	}


}