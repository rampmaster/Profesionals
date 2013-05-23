//constants

var socketcall = io.connect(callRouteToSocket);
var canICall = true;
var call = null;
var callStreamingVideo = true;

var callhandler =
{
    init:function () {
        calladminnotify.default('init');

        callnotify.info('Cargando la plataforma...');
        callBoot();

    },
    login:function () {
        calladminnotify.default('login');
        callVideoconferenceToLoadingView(1);
        login(callMyId, callMyPass);
    },
    logoff:function () {
        calladminnotify.default('logoff');
        logoff();
        callVideoconferencetoLogOffState()
    },
    onLogin:function () {
        calladminnotify.default('on login');

        callnotify.default('Conectado a la plataforma');
        callVideoconferencetoLogInState();
        callVideoconferenceToCallState();
        me = flashphoner.getInfoAboutMe();
        socketCallHandler.sipRegister(me.login, me.domain);

        callVideoconferenceToLoadView(1);

    },
    onLogout:function () {
        calladminnotify.default('on logout');
        callnotify.danger('Desconectado de la plataforma');
        contacthandler.updateMyState(callPresenceOff);
        socketCallHandler.sipUnRegister(callMyId);
        callVideoconferencetoLogOffState();


    },
    callTo:function (user) {
        if (canICall) {
            calladminnotify.default('call to');

            callnotify.default('Su petición se está procesando....');
            call(user);
            callStatus = callStatusStarted;
            callVideoconferencetoLogOffState();//no dejo hacer nada mas mientras se procesa la llamada
            var opt =
            {
                receiver:user
            };
            socketcall.emit('call started', opt);
        } else {
            callnotify.danger('Ustéd no puede llamar');
        }

    },
    hangupCall:function (call) {
        calladminnotify.default('hang up');
        callnotify.danger('Finalizando sesión...');
        hangup(call.id);
        $("#answerButton").addClass('hide');
        amICalling = false;
        callStatus = null;
        socketcall.emit('call ended');

        rebootFlash();
        callVideoconferenceToCallState();
        contactshandler.unselectUser(call.anotherSideUser);
        callStatus = null;
    },
    callStarted:function () {
        callCallerId = currentCall.anotherSideUser;

        if(amICalling){
            setTimeout(function(){
                callhandler.videoFlush('OFF', true)}, 300);

            setTimeout(function(){
                callhandler.videoFlush('ON', true);
            }, 1000);

        }
        calladminnotify.default('call started');
        contactshandler.updateMyState(callPresenceBusy);

        callVideoconferenceStartTalking();//aviso al browser
        callVideoconferenceToHangupState();
        $("#answerButton").addClass('hide');
        callStatus = callStatusTalking;
        callActiveChat();
        callVideoconferenceToCallStateStarted();


    },
    callEnded:function (call) {

        amICalling = false;
        callhandler.resizeVideo(callVideoconferenceSwfDefaultWidth, callVideoconferenceSwfDefaultHeigt);
        contactshandler.updateMyState(callPresenceBusy);
        $("#answerButton").addClass('hide');
        rebootFlash();
        //rebootWindow();
        callVideoconferenceToCallState();
        contactshandler.unselectUser(call.anotherSideUser);
        callStatus = null;

        callVideoconferenceToHangupStateStarted();
        socketcall.emit('call ended');
        callDeactiveChat();
    },
    callReciving:function (call) {
        if (!amICalling) {
            calladminnotify.default('call reciving');

            callnotify.success('Llamada entrante');
            contactshandler.updateMyState(callPresenceBusy);
            callShowUser(call.anotherSideUser);
            callVideoconferenceToRingingState();
            //sonando l telfono
            //hago que salga el boton de responder


            $("#answerButton").removeClass('hide');
            callStatus = callStatusRinging;
            callVideoconferencetoLogOffState();//no dejo hacer nada mas mientras se procesa la llamada
        }
    },
    callHold:function (call) {
        callStreamingVideo = false;
        setStatusHold(call.id, true);
        $('#unholdButton').css('display','block');
        $('#holdButton').css('display','none');
    },
    callUnHold:function (call) {
        callStreamingVideo = true;
        setStatusHold(call.id, false);
        $('#holdButton').css('display','block');
        $('#unholdButton').css('display','none');
    },
    callTransfer:function (call, target) {
        transfer(currentCall, target);
    },
    onCallHold:function () {
        callStreamingVideo = false;
        callVideoconferenceToHoldState();
        $('#unholdButton').css('display','block');
        $('#holdButton').css('display','none');
    },
    onCallUnHold:function () {
        callStreamingVideo = true;
        $('#holdButton').css('display','block');
        $('#unholdButton').css('display','none');
    },
    resizeVideo:function (width, height, emit) {
        if (width > callVideoconferenceSwfDefaultWidth) {
            callVideoconferenceToFullScreen();
        } else {
            callVideoconferenceToNormalScreen();
        }


        $("#video_requestUnmuteDiv #jsSWFDiv").css("width", width).css('height', height);

        if (emit) {
            socketCallHandler.resizeWindow(width, height);
        }


    },
    videoFlush:function (status, emit) {

        if (currentCall != null) {


            if (status == true || status == 'ON') {
                sipStreamingVideo(true);
                if (emit) {
                    socketCallHandler.videoFlush('ON');
                }
                $('#unflushvideoButton').css('display', 'block');
                $('#flushvideoButton').css('display','none');
            }

            if (status == false || status == 'OFF') {
                sipStreamingVideo(false);
                if (emit) {
                    socketCallHandler.videoFlush('OFF');
                }
                $('#flushvideoButton').css('display', 'block');
                $('#unflushvideoButton').css('display','none');
            }
        }

    }

}

var contactshandler =
{
    updateState:function (user, state) {
        callContactChangePresence(user, state);
    },
    selectUser:function (user) {
        if (isLogged) {
            callCallerId = user;
            callnotify.default('Recuperando información del usuario...')
            callShowUser(user);
        } else {
            callnotify.danger('Hay un error con su registro :(');
            setTimeout('rebootWindow()', 15000)
        }

    },
    unselectUser:function (user) {
        callCallerId = null;
        callHideCaller(user);
    },
    updateMyState:function (state) {
        console.log('call presence: ' + state);
        socketCallHandler.changePresence(state);
    }

}

function callBoot() {
    optionsContacts = {
        timeout:10000,
        type:"GET",
        dataType:"JSON",
        url:callRouteToGetToken,
        contentType:"application/json",
        success:function (response) {

            socketCallHandler.registerOnNode(response);

        }
    };
    $.ajax(optionsContacts);
}

