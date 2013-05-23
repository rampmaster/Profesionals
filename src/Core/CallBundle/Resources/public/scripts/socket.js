//calls events

socketcall.on('call waiting', function(data){
    calladminnotify.default('call waiting');
    callnotify.unhidden('Hubo un problema con su petición, por favor vuelva a intentarlo');
    /*
    callStatus = callStatusWaitingForAnotherSideReload;
    callnotify.default('Su petición continua procesándose');
    console.log('Llamada a la espera');

    setTimeout(function(){
        callnotify.default('Su petición continua procesándose');
    }, 10000);

    setTimeout(function(){
        if(callStatus == callStatusWaitingForAnotherSideReload)
        {
            callnotify.danger('Petición cancelada');
            socketcall.emit('call ended');
            setTimeout(function(){
               rebootWindow();
            }, 3000);
        }
    }, 30000);
    */
});


socketcall.on('call reload', function(data){
    calladminnotify.default('call reload');
    if(callStatus == callStatusWaitingForAnotherSideReload)
    {
        callStatus = callStatusStarted;
        call = data.call;

        if (call.caller == callMyId) {
            callhandler.callTo(call.receiver);
        }
        if(call.receiver == callMyId)
        {
            callhandler.callTo(call.caller);
        }

    }
});


socketcall.on('call processing', function(data){
    calladminnotify.default('call processing, so call connected');
        callStatus = callStatusConected;
});


socketcall.on('call process', function(call){
    //console.log(call);
    calladminnotify.default('call process');
    if(currentCall != null)
    {
        calladminnotify.default('call started!');
        var opt = { receiver: currentCall.anotherSideUser };
        socketcall.emit('call started', opt);
    }else{
        calladminnotify.default('waiting me');
        socketcall.emit('call wait me', call);
        rebootFlash();


        if(call.caller == callMyId)
        {
            another = call.receiver;

        }else{
            another = call.caller;

        }

        var callback = function(user){
            callnotify.unhidden('El usuario '+user.name+' '+user.surname+ ' ha realizado una llamada fallida');
        }
        callContaGetUserInfo(another, callback);



    }
});


socketcall.on('call ended', function(data){
    calladminnotify.default('call ended');
    if(currentCall != null)
    {
        callhandler.hangupCall(currentCall);
    }
});

socketcall.on('call resize', function(data){
    calladminnotify.default('call resized');

    if(currentCall != null)
    {
        callhandler.resizeVideo(data.width, data.height, false);
    }
});

socketcall.on('call video flush', function(data){
    calladminnotify.default('call video flush: '+ data.status);

    if(currentCall != null)
    {
        callhandler.videoFlush(data.status, false);
    }

});


//contacts events
socketcall.on('user status updated', function (data) {

    contactshandler.updateState(data.status, data.client.user);

});

socketcall.on('registered', function (data) {

    //me registro en sip
    callhandler.login();
    $.each(data.clientlist, function (client) {
        contactshandler.updateState(data.clientlist[client].presence, data.clientlist[client].user);
    });

});

socketcall.on('not registered', function(data){
    callnotify.danger('Hubo un problema al registrarse en el servidor');
    callnotify.danger('Razón :' + data.reason);
});




var socketCallHandler = {
    changePresence:function (presence) {
        socketcall.emit('change status', { status:presence })
    },
    registerOnNode:function(token)
    {
        data = {
            token: token,
            device: 'webphone',
            presence: callPresenceOff,
            user: callMyId
        };
        socketcall.emit('register', data);
    },
    sipRegister: function(login, server){
        request = {
            login: login,
            server:server
        }

        socketcall.emit('on sip registered', request);
    },
    sipUnRegister:function(id)
    {
        socketcall.emit('on sip unregistered', {id: id});
    },
    resizeWindow: function(width, height){
        socketcall.emit('call resize', {width: width, height: height });
    },
    videoFlush: function(status){
        socketcall.emit('call video flush', {status: status});
    }


};