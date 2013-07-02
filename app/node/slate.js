var io = require('socket.io').listen(2025);

function Client(options) {
    this.socket = options.socket;
    this.session = options.token;
    return this;
}

var Clients = [];

io.sockets.on('connection', function(socket) {


	socket.on('suscribe', function (data) {
        var opt = {
            socket: socket.id,
            session: data.session,
        }
        var client = new Client(opt);
        Clients.push(client);
        socket.join(data.session)
    });
	/* Cuando un usuario realiza una acción en el cliente,
	   recibos los datos de la acción en concreto y 
	   envío a todos los demás las coordenadas */

	socket.on('startLine',function(e){
		var client = Clients.findBySocket(socket.id);
		io.sockets.in(e.session).emit('down',e);
	});

	socket.on('closeLine',function(e){
		var client = Clients.findBySocket(socket.id);
		io.sockets.in(e.session).emit('up',e);
	});

	socket.on('draw',function(e){
		var client = Clients.findBySocket(socket.id);
		io.sockets.in(e.session).emit('move',e);
	});

	socket.on('clean',function(){
		var client = Clients.findBySocket(socket.id);
		io.sockets.in(e.session).emit('clean',true);
	});

	socket.on('disconnect',function(){
		var client = Clients.findBySocket(socket.id);
		socket.leave(client.session)
		Clients = Clients.removeBySocket(socket.id);
	})

});

Array.prototype.removeBySocket = function (e) {
    var r = [];
    for (var i = 0; i < this.length; i++) {
        if (e == this[i].socket) {
            console.log("REMOVED: " + this[i]);
        } else {
            r.push(this[i]);
        }

    }

    return r;
};

Array.prototype.findBySocket = function (e) {
    for (var i = 0; i < this.length; i++) {
        if (e == this[i].socket) {
            return this[i];
        }
    }
    return false;
};