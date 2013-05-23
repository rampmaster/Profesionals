var io = require('socket.io').listen(8050);
var db = require('./model/messengerHandler');

var clients = {};
var client_user = {};


function Client(options) {
  this.socket = options.socket;
  this.token = options.token;
  this.chat = options.chat;
  this.user = options.user;
  this.device = options.device;
}
var clients = [];

io.sockets.on('connection', function (socket) {
    socket.on('check', function(){
        var count = clients.length;

        socket.emit('online', {users: count});
    });
  socket.on('register', function (data) {
      console.log(data);
    //user = db.findUserAuthToken(token);
    var clientPush = function(model){
      var existingClient = clients.findByToken(data.token);
      if(existingClient){
        clients.remove(existingClient);
      }
      var client = new Client({
        socket:socket.id,
        chat:data.chat,
        token:data.token,
        user:model.user,
        device:data.device
      });

       var checkClientRegistered = clients.findByUserAndDevice(model.user, data.device);
        if(!checkClientRegistered)
        {

            clients.push(client);
            socket.emit('registered',{
                'client' : client
            });
        }else{
            //so = io.sockets.socket(checkClientRegistered.socket)

            socket.emit('not registered',{

                'reason' : 'Client already registered'
            });
        }

    }
    db.findUserAuthToken(data.token,clientPush);

    
  });
  socket.on('message send', function (data,callback) {

      client = clients.findBySocket(socket.id);
      if(client){

        data.user = client.user;
        console.log("PROCEDING TO INSERT")
        db.insertMessage(data,function(object,isDestinySource,previousNotifications){
          console.log("INSERT OK")
          console.log(data)
          var target = clients.findByUser(object.chat.target);
          var isUnreadByDestiny = false;
            for (var i = 0; i < clients.length; i++) {
                if (clients[i].user == object.chat.target || clients[i].user == object[0].sender) {
                    if(clients[i].socket != client.socket)
                    {
                        io.sockets.socket(clients[i].socket).emit('message sent',object);
                    }

                }
            }
          if(!target){
              isUnreadByDestiny = true;
              insertNotificationMessage(object, object.chat.target);
          }
            updateChat(object.chat.id,isUnreadByDestiny,isDestinySource,previousNotifications);
            socket.emit('message sent',object);

        })
      }else{
        console.log("HACK ATTEMPT: socket: "+socket.id+" is not registered and is trying to send a message");
      }
  });

   socket.on("quit", function() {
    var client = clients.findBySocket(socket.id);
    console.log(client);
            if(client){
              console.log(clients);
              clients = clients.remove(client);
            }

          });

   socket.on("disconnect", function() {
        // Check that the user has already joined successfully
            // Remove the client from the global list
            var client = clients.findBySocket(socket.id);
            if(client){
              clients = clients.remove(client);
            }
            
    });

});


Array.prototype.purge = function(e) {
  for (var i = 0; i < this.length; i++) {
    if (e == this[i]) { 
      this[i].chat = "";
    }
    return this;
  }
};


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

Array.prototype.findBySocket = function(e) {
  for (var i = 0; i < this.length; i++) {
    if (e == this[i].socket) { return this[i]; }
  }
  return false;
};

Array.prototype.findByUser = function(e) {
  for (var i = 0; i < this.length; i++) {
    if (e == this[i].user) { return this[i]; }
  }
  return false;
};

Array.prototype.findByToken = function(e) {
  for (var i = 0; i < this.length; i++) {
    if (e == this[i].token) { return this[i]; }
  }
  return false;
};

Array.prototype.findByUserAndDevice = function(u,d) {
    for (var i = 0; i < this.length; i++) {
        if (d == this[i].device && u == this[i].user) {
            return this[i];
        }
    }
    return false;
};