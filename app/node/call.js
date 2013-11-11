var ioWebRtc = require('socket.io').listen(2021);


//open tok tools
var OpenTok = require('opentok');
var key = '29258852';    // Replace with your API key
var secret = 'b8e1ce4d1a29322af8aa4ac983e0c8ebb2cd0631';  // Replace with your API secret
var opentok = new OpenTok.OpenTokSDK(key, secret);
var location = '127.0.0.1'; // use an IP or 'localhost'


var clients = [];

/*CALL*/

/*CONSTANTS*/

var sipStatusRegistered = 'REGISTERED';
var sipStatusUnRegistered = 'UNREGISTERED';
var sipStatusWaiting = 'WAITING';
var sipStatusConected = 'CONECTED';
var sipStatusConecting = 'CONECTING';
var sipStatusRinging = 'RINGING';
var sipStatusCalling = 'CALLING';

var protocolSip = 'SIP';
var protocolWebRtc = 'WebRTC';

/*STUFF*/
var calls = [];
var callswaiting = [];
var sipRegistered = []

function Client(options) {
    this.socket = options.socket;
    this.token = options.token;
    this.presence = options.presence;
    this.user = options.user;
    this.device = options.device;
    this.protocol = options.protocol;
    return this;
}


function Call(options) {
    this.caller = options.caller;
    this.receiver = options.receiver;
    this.status = options.status;
    this.callerStatus = options.callerStatus;
    this.receiverStatus = options.receiverStatus;
    this.protocol = options.protocol;

}

function ClientSip(options) {
    this.socket = options.client.socket;
    this.user = options.client.user;
    this.token = options.client.token;
    this.device = options.client.device;
    this.login = options.login;
    this.server = options.server;
    this.status = options.status;
}

function CallWebRtc(options) {
    this.caller = options.caller;
    this.receiver = options.receiver;
    this.callerSocket = options.callerSocket;
    this.callerToken = options.callerToken;
    this.receiverSocket = options.receiverSocket;
    this.receiverToken = options.receiverToken;
    this.status = options.status;
    this.callerStatus = options.callerStatus;
    this.senderStatus = options.senderStatus;
    this.session = options.session;


}

//sockets


function updateClientStatusPresence(client, status) {
    console.log(client);

    ioWebRtc.sockets.emit('user status updated', { client: client, status: status });
    console.log('Client status updated');
}

ioWebRtc.on('connection', function (socket) {

    function checkProtocol(client) {
        if (client.protocol == protocolWebRtc) {
            return true;
        } else {
            return false;
        }
    }

    /*
     Me registro en el socket

     @param
     login, device (web ,ios, android), token
     */
    socket.on('register', function (data) {

        var opt = {
            socket: socket.id,
            presence: 'ON',
            user: data.login,
            device: data.device,
            protocol: protocolWebRtc,
        }

        var client = new Client(opt);

        clients.push(client);

        socket.emit('registered', { login: client } );
        socket.emit('user list', clients);
        console.log(clients);

        updateClientStatusPresence(client, 'ON');

        //compruebo si existe una llamada anterior, y la elimino

        var calltest = calls.findByUserInCall(client.user);

        if(calltest){
            console.log(calls);
            console.log('Eliminando llamada atrasada');
            calls = calls.remove(calltest);
            console.log(calls);
        }

    });

    /*
     Llamo a alguien

     param:
     target (id del usuario)
     */
    socket.on('call to', function (data) {

        //primero me fijo si esta conectado en el socket y si es
        var sender = clients.findBySocket(socket.id);
        var receiver = clients.findByUserAndProtocol(data.target, protocolWebRtc);

        //compruebo si la a la que llamo esta en el protocolo correcto
        if (!checkProtocol(receiver)) {

            socket.emit('call fail', {reason: 'target protocol incorrect'});
            return false;
        }
        //check if
        //he encontrado el usuario y esta conectado con nosotros
        console.log('Iniciando la llamada');
        //inicio una llamada
        var opt = {
            caller: sender.user,
            receiver: receiver.user,
            callerSocket: sender.socket,
            receiverSocket: receiver.socket,
            status: sipStatusRinging,
            callerStatus: sipStatusCalling,
            receiverStatus: sipStatusRinging,
            protocol: protocolWebRtc
        }


            var call = new CallWebRtc(opt);

        calls.push(call);

        updateClientStatusPresence(sender, 'BUSY');
        updateClientStatusPresence(receiver, 'BUSY');

        ioWebRtc.sockets.socket(receiver.socket).emit('ringing', {call: call});


    });

    /*
     Descuelgo una llamada siendo el que la recive

     param
     */
    socket.on('hook off', function (data) {

        var client = clients.findBySocket(socket.id);

        var call = calls.findByUserReceiver(client.user);
        if (!call) {
            //no existe la llamada
            return false;
        }

        //cambio los parametros de la llamada
        call.receiverStatus = sipStatusCalling;
        call.status = sipStatusConecting;


        var sessionGetted = function(sessionId){

            console.log('SEssion id');
            console.log(sessionId);
            call.session = sessionId;

            var tokenCaller = opentok.generateToken({session_id: call.session, role: OpenTok.RoleConstants.PUBLISHER, connection_data: "userId:" + call.caller});
            var tokenReceiver = opentok.generateToken({session_id: call.session, role: OpenTok.RoleConstants.PUBLISHER, connection_data: "userId:" + call.receiver});

            call.callerToken = tokenCaller;
            call.receiverToken = tokenReceiver;


            var index = calls.findIndexByUserInCall(client.user);
            calls[index] = call;

            //aviso por node para empezar a publicar, estos enviaran su informacion al evento connect call

            ioWebRtc.sockets.socket(call.receiverSocket).emit('call start', {call: call});
            //voya esperar 1 segundo, para que le de tiempo a digerir la informacion
            ioWebRtc.sockets.socket(call.callerSocket).emit('call start', { call: call});
        }

        //genero lo que necesito de open tok
        opentok.createSession(location, {}, function (result) {
            // Do things with sessionId
            sessionGetted(result);
        });

    });

    /*
     Colgar la llamada en la que estoy activo

     */
    socket.on('hang up', function (data) {
        //me busco en una llamada

        var client = clients.findBySocket(socket.id);

        var call = calls.findByUserInCall(client.user);

        //finalizo la llamada

        var anotherSideClient = 0;
        if (client.user == call.receiver) {
            anotherSideClient = clients.findByUser(call.caller);
        } else {
            anotherSideClient = clients.findByUser(call.receiver);
        }
        ioWebRtc.sockets.socket(anotherSideClient.socket).emit('close call', {call: call});
        ioWebRtc.sockets.socket(client.socket).emit('close call', {call: call});


        //nuevo estado de los clientes

        client.presence = 'ON';
        anotherSideClient.presence = 'ON';

        indexClient = clients.findIndexByUser(client.user);
        indexAnother = clients.findIndexByUser(anotherSideClient.user);

        clients[indexClient] = client;
        clients[indexAnother] = anotherSideClient;

        updateClientStatusPresence(client, 'ON');
        updateClientStatusPresence(anotherSideClient, 'ON');

        //termino la llamaada

        calls = calls.remove(call);

    });

    /*
     Recibo la lista de personas conectadas

     */
    socket.on('retrieve user list', function (data) {

        socket.emit('user list', clients);
    });


    socket.on('disconnect', function (data) {

        var client = clients.findBySocket(socket.id);

        var call = calls.findByUserInCall(client.user);

        if(call){

            console.log('Eliminando llamada atrasada');
            //envio señal de que se acaba



            var anotherSideClient = 0;
            if (client.user == call.receiver) {
                anotherSideClient = clients.findByUser(call.caller);
            } else {
                anotherSideClient = clients.findByUser(call.receiver);
            }
            ioWebRtc.sockets.socket(anotherSideClient.socket).emit('close call', {call: call});

            //nuevo estado de los clientes
            anotherSideClient.presence = 'ON';

            indexAnother = clients.findIndexByUser(anotherSideClient.user);

            clients[indexAnother] = anotherSideClient;

            updateClientStatusPresence(anotherSideClient, 'ON');

            calls = calls.remove(call);

        }

        if (client != null) {

            updateClientStatusPresence(client, 'OFF');
            clients = clients.remove(client);


        }
    });

});




Array.prototype.remove = function (e) {
    var r = [];
    for (var i = 0; i < this.length; i++) {
        if (e == this[i]) {
            console.log("REMOVED: " + this[i]);
        } else {
            r.push(this[i]);
        }

    }

    return r;
};

Array.prototype.removeCallByUser = function (e) {
    var r = [];
    for (var i = 0; i < this.length; i++) {
        if (e == this[i].caller) {
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

Array.prototype.findByUser = function (e) {
    for (var i = 0; i < this.length; i++) {
        if (e == this[i].user) {
            return this[i];
        }
    }
    return false;
};

Array.prototype.findIndexByUser = function (e) {
    for (var i = 0; i < this.length; i++) {
        if (e == this[i].user) {
            return i;
        }
    }
    return false;
};


Array.prototype.findByUserAndDevice = function (u, d) {
    for (var i = 0; i < this.length; i++) {
        if (d == this[i].device && u == this[i].user) {
            return this[i];
        }
    }
    return false;
};

Array.prototype.findByUserAndProtocol = function (u, d) {
    for (var i = 0; i < this.length; i++) {
        if (d == this[i].protocol && u == this[i].user) {
            return this[i];
        }
    }
    return false;
};

Array.prototype.findByCaller = function (c) {
    for (var i = 0; i < this.length; i++) {
        if (c == this[i].caller) {
            return this[i];
        }
    }
    return false;
};

Array.prototype.findByReceiver = function (c) {
    for (var i = 0; i < this.length; i++) {
        if (c == this[i].receiver) {
            return this[i];
        }
    }
    return false;
};

Array.prototype.findByUserCaller = function (c) {
    for (var i = 0; i < this.length; i++) {
        if (c == this[i].caller) {
            return this[i];
        }
    }
    return false;
};

Array.prototype.findIndexByUserCaller = function (c) {
    for (var i = 0; i < this.length; i++) {
        if (c == this[i].caller) {
            return i;
        }
    }
    return false;
};

Array.prototype.findByUserReceiver = function (r) {
    for (var i = 0; i < this.length; i++) {
        if (r == this[i].receiver) {
            return this[i];
        }
    }
    return false;
};
Array.prototype.findIndexByUserReceiver = function (r) {
    for (var i = 0; i < this.length; i++) {
        if (r == this[i].receiver) {
            return i;
        }
    }
    return false;
};


Array.prototype.findByUserInCall = function (u) {
    for (var i = 0; i < this.length; i++) {
        if (u == this[i].caller || u == this[i].receiver) {
            return this[i];
        }
    }
    return false;
};

Array.prototype.findIndexByUserInCall = function (u) {
    for (var i = 0; i < this.length; i++) {
        if (u == this[i].caller || u == this[i].receiver) {
            return i;
        }
    }
    return false;
};
