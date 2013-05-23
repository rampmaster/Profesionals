//constants

var socketcall = io.connect(callRouteToSocket);
var canICall = true;
var call = null;
var callStreamingVideo = true;

var callhandler =
{
    init:function () {
        calladminnotify.default('init');

        //callnotify.info('Cargando la plataforma...');
        callBoot();

    },
    logoff:function () {
        calladminnotify.default('logoff');
        logoff();
        callVideoconferencetoLogOffState()
    },
    onLogin:function (login) {
        calladminnotify.default('on login');

        //callnotify.default('Conectado a la plataforma');
        phoneRegistered(login);
        callVideoconferencetoLogInState();
        callVideoconferenceToCallState();
        callVideoconferenceToLoadView(1);

    },
    onLogout:function () {
        calladminnotify.default('on logout');
        //callnotify.danger('Desconectado de la plataforma');
        contacthandler.updateMyState(callPresenceOff);
        socketCallHandler.sipUnRegister(callMyId);
        callVideoconferencetoLogOffState();


    },
    callTo:function (user) {
        call(user);
        if (canICall && currentCall == null) {
            calladminnotify.default('call to');

            //callnotify.default('Su petición se está procesando....');

            callStatus = callStatusStarted;
            callVideoconferencetoLogOffState();//no dejo hacer nada mas mientras se procesa la llamada
        } else {
            //callnotify.danger('Ustéd no puede llamar');
        }

    },
    hangupCall:function (call) {

        calladminnotify.default('hang up');
        //callnotify.danger('Finalizando sesión...');
        hangUp();
    },
    callStarted:function () {
        if(callMyId == currentCall.caller){
            callCallerId = currentCall.receiver;
        }else{
            callCallerId = currentCall.caller;
        }

        contactshandler.updateMyState(callPresenceBusy);
        callVideoconferenceToHangupState();
        $("#answerButton").addClass('hide');
        callStatus = callStatusTalking;
        //callActiveChat();
        callVideoconferenceToCallStateStarted();


    },
    callEnded:function () {

        contactshandler.updateMyState(callPresenceOn);
        $("#answerButton").addClass('hide');
        callVideoconferenceToCallState();
        contactshandler.unselectUser(callCallerId);

        callVideoconferenceToHangupStateStarted();
        callVideoconferencetoLogInState();
        callVideoconferenceToCallState();
        callVideoconferenceToLoadView(1);


        reDrawContentVideoconference();

    },
    callReciving:function (call) {
        if (!amICalling) {
            console.log(call);
            calladminnotify.default('call reciving');

            //callnotify.success('Llamada entrante');
            contactshandler.updateMyState(callPresenceBusy);
            callShowUser(call.caller);
            callVideoconferenceToRingingState();
            //sonando l telfono
            //hago que salga el boton de responder


            $("#answerButton").removeClass('hide');
            callStatus = callStatusRinging;
            callVideoconferencetoLogOffState();//no dejo hacer nada mas mientras se procesa la llamada
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
            //callnotify.default('Recuperando información del usuario...')
            callShowUser(user);
        } else {
            //callnotify.danger('Hay un error con su registro :(');
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
    socketCallHandler.registerOnNode();
    console.log('conectando');
}

