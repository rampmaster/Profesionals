socketcall.on('registered', function(response){
    callhandler.onLogin(response.login);
});

socketcall.on('ringing', function(response){
    //me llaman
    notifyCall(response.call);
});

socketcall.on('call start', function(response){
    //empieza una llamada
    console.log('Empieza una llamada');
    console.log(response);
    currentCall = response.call;
    if(currentCall.caller == callMyId){
        startCallWebRtc(currentCall.session, currentCall.callerToken);
        callCallerId = currentCall.receiver;
    }

    if(currentCall.receiver == callMyId){
        callCallerId = currentCall.caller;
        startCallWebRtc(currentCall.session, currentCall.receiverToken);
    }

    callhandler.callStarted();

});

socketcall.on('close call', function(response){
    //se cuelga la llamada
    console.log('colgando la llamada');
    hangUp();
    toHangUp();
    callhandler.callEnded();



});

socketcall.on('user list', function(response){
    //lista de usuarios

    $.each(response, function(res){
       var user = response[res];
        contactshandler.updateState(user.presence, user.user);
    });

});

socketcall.on('call fail', function(res){
    console.log('fallo en la llamada');
    console.log(res);
});

socketcall.on('user status updated', function(response){
    //usuario actualizado
    contactshandler.updateState(response.status, response.client.user);
});

var socketCallHandler = {
    changePresence:function (presence) {
        socketcall.emit('change status', { status:presence })
    },
    registerOnNode: function(){

        socketcall.emit('register', { login: callMyId, device: 'web'})
    },
    callTo: function(user){
        console.log(user);
        socketcall.emit('call to', { target: user });
    },
    hookOff: function(){
        console.log('descolgando');
        socketcall.emit('hook off');
    },
    hangUp: function(){
        console.log('colgar llamada');
        socketcall.emit('hang up');
    }
}