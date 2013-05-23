var app = require('http').createServer(handler)
    , io = require('socket.io').listen(app)
    , fs = require('fs')
    , crypto = require('crypto');

var clients = {};
var clientIds = {};
app.listen(8052);

function handler(req, res) {
    fs.readFile(__dirname + '/index.html',
        function (err, data) {
            if (err) {
                res.writeHead(500);
                return res.end('Error loading index.html');
            }

            res.writeHead(200);
            res.end(data);
        });
}

io.sockets.on('connection', function (socket) {

    socket.on('check', function(){
        var count = clients.length;

        socket.emit('online', {users: count});
    });

    socket.on('message send', function (data) {
        console.log(data);
        console.log(clients);
        var reciverSocket = clients[data.reciver];
        var sender = clientIds[socket.id];
        io.sockets.socket(reciverSocket).emit('message send', {'sender':sender, 'message':data.message});
        console.log('MESSAGE SEND TO: '+reciverSocket);
    });

    socket.on('auth', function (data) {
        var client = data.id;
        clients[client] = socket.id;
        clientIds[socket.id] = client;
        //console.log("Activity Log: Auth");
        //socket.broadcast.emit('userin', {id:client});
        console.log('CLIENT LOGGED');


    });

    socket.on('quit', function () {
        var me = clientIds[socket.id];

        delete clientIds[socket.id];
    });

    socket.on('disconnect', function () {
        var me = clientIds[socket.id];

        delete clientIds[socket.id];
    });


});
