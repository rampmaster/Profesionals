var tls = require('tls')
  , io = require('socket.io').listen(app)
  , fs = require('fs')
  , crypto = require('crypto');

var options = {
  requestCert: true,
  key:  fs.readFileSync('/home/cert/medibaby/yourdomain.key'),
  cert: fs.readFileSync('/home/cert/nisa/medibaby.net.crt')
};

//var app = tls.createServer(options,handler);
var app = tls.createServer(options, function(cleartextStream) {
  console.log('server connected',
              cleartextStream.authorized ? 'authorized' : 'unauthorized');
  cleartextStream.write("welcome!\n");
  cleartextStream.setEncoding('utf8');
  cleartextStream.pipe(cleartextStream);
});
app.listen(8000, function() {
  console.log('server bound');
});

//Parse config file
var iniparser = require('iniparser');
//var config = iniparser.parseSync('./../config/parameters.ini');

//Load PersistenceJS ORM
//var persistence = require('persistencejs/persistence.min').persistence;
//var persistenceStore = require('persistencejs/lib/persistence.store.mysql');

//Configuring ORM
//persistenceStore.config(persistence, 'localhost', 3306, 'medibaby', 'root', 'pass');
//var session = persistenceStore.getSession();
function Client(data) {
  this.id = data.id;
}

Array.prototype.remove = function(e) {
  for (var i = 0; i < this.length; i++) {
    if (e == this[i]) { return this.splice(i, 1); }
  }
};

var clients = {};

//app.listen(444);

function handler (req, res) {
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

	socket.on('userlist',function(){
		socket.emit('userlist',{users:clients});
	})

	socket.emit('ready',{status:200});
	  socket.on('auth', function (data) {
	  	var client = data.id;
	  	clients[socket.id] = client;

  		socket.broadcast.emit('userin',{id:client});
  		
	  	
  	});
  	
  	socket.on('disconnect', function() {
  		var me = clients[socket.id];
  		socket.broadcast.emit('userout',{id:me});
		delete clients[socket.id];
  	});
  
  
});
