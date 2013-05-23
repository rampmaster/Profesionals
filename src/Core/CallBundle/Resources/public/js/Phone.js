/*
 Copyright (c) 2011 Flashphoner
 All rights reserved. This Code and the accompanying materials
 are made available under the terms of the GNU Public License v2.0
 which accompanies this distribution, and is available at
 http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

 Contributors:
 Flashphoner - initial API and implementation

 This code and accompanying materials also available under LGPL and MPL license for Flashphoner buyers. Other license versions by negatiation. Write us support@flashphoner.com with any questions.
 */

var flashphoner;

// One call become two calls during TRANSFER case
// there is why we need at least two kinds of calls here
var holdedCall = null;
var currentCall = null;
var callee = '';
// not sure if "callee" is reserved word so I will use callee1 /Pavel
var callee1 = '';
var callerLogin = '';
var registerRequired;
var isLogged = true;
var amICalling = false;
var callCanICall = true;

var micVolume = 100;
var speakerVolume = 100;
var connectingViewBeClosed = false;
var traceEnabled = false;
var intervalId = -1;
var isMutedMicButton = false;
var isMutedSpeakerButton = false;
var proportion = 0.75;

var testInviteParameter = new Object;



// trace log to the console in the demo page
function trace(funcName, param1, param2, param3) {

    var today = new Date();
    // get hours, minutes and seconds
    var hh = today.getHours();
    var mm = today.getMinutes();
    var ss = today.getSeconds();

    // Add '0' if it < 10 to see 14.08.06 instead of 14.6.8
    hh = hh == 0 ? '00' : hh < 10 ? '0' + hh : hh;
    mm = mm == 0 ? '00' : mm < 10 ? '0' + mm : mm;
    ss = ss == 0 ? '00' : ss < 10 ? '0' + ss : ss;

    // set time 
    var time = hh + ':' + mm + ':' + ss;

    var div1 = div2 = '';


    // Check if we set params and set it ????? instead of 'undefined' if not, also set dividers equal to ', ' 
    if (typeof param1 == 'undefined') {
        param1 = '';
    }
    if (typeof param2 == 'undefined') {
        param2 = '';
    } else {
        var div1 = ', ';
    }
    if (typeof param3 == 'undefined') {
        param3 = '';
    } else {
        var div2 = ', ';
    }

    // Print message to console
    if (traceEnabled) {
        console.log(time + ' : ' + funcName + '' + '(' + param1 + div1 + param2 + div2 + param3 + ')');
    }

}

$(document).ready(function () {

});

function relogin()
{
    trace('relogin');
    var loginObject = new Object();
    loginObject.username = 'sip:' + getCookie('login') + '@' + getCookie('domain');
    loginObject.password = getCookie('pwd');
    loginObject.authenticationName =  getCookie('authName');
    loginObject.outboundProxy =  getCookie('outbound_proxy');

    var result = flashphoner.login(loginObject);
}

function login(user, password) {
    trace("login");

    var loginObject = new Object();
    loginObject.username = 'sip:' + user + '@' + callSipDomain;
    loginObject.password = password;
    loginObject.authenticationName = user;
    loginObject.outboundProxy = callSipOutboundProxy;

    var result = flashphoner.login(loginObject);

    if (result == 0) {
        setCookie("login", user);
        setCookie("authName", user);
        setCookie("pwd", password);
        setCookie("domain", callSipDomain);
        setCookie("outbound_proxy", callSipOutboundProxy);
    }
}


function logoff() {
    trace("logoff");
    flashphoner.logoff();
}

function call(number) {
    trace("call");
    if (isLogged) {
        if (isMuted() == 1) {
            intervalId = setInterval('if (isMuted() == -1){closeRequestUnmute(); clearInterval(intervalId);call();}', 500);
            requestUnmute();
        } else if (isMuted() == -1) {

            //antes de llamar compruebo si puedo hacerlo
            if (callCanICall != false) {
            amICalling = true;
                var result = flashphoner.call(number, 'Caller', true, testInviteParameter);
                if (result == 0) {
                    callVideoconferenceToHangupState();
                } else {
                    callnotify.danger('Proceso de llamada incorrecto');
                    openConnectingView("Callee number is wrong", 3000);
                }
            }else{
                callnotify.danger('No tienes créditos :(');

            }
        } else {

            callnotify.danger('El micrófono no esta conectado');
        }
    } else {
        rebootWindow();
    }
}

function answer(callId) {
    trace("answer", callId);
    if (isMuted() == 1) {
        intervalId = setInterval('if (isMuted() == -1){closeRequestUnmute(); clearInterval(intervalId);answer(currentCall.id);}', 500);
        requestUnmute();
    } else if (isMuted() == -1) {
        flashphoner.answer(callId, true);
    } else {
        alert("El microfono no esta conectado");
    }
}

function hangup(callId) {
    trace("hangup", callId);
    flashphoner.hangup(callId);
}

function setStatusHold(callId, isHold) {
    trace("setStatusHold", callId, isHold);
    flashphoner.setStatusHold(callId, isHold);
    disableHoldButton();
}

function transfer(callId, target) {
    trace("transfer", callId, target);
    flashphoner.transfer(callId, target);
}

function isMuted() {
    var isMute = flashphoner.isMuted();
    return isMute;
}

function viewVideo() {
    trace("viewVideo");
    flashphoner.viewVideo();

}

function changeRelationMyVideo(relation) {
    trace("changeRelationMyVideo", relation);
    flashphoner.changeRelationMyVideo(relation);


}

function getMicVolume() {
    trace("getMicVolume");
    return flashphoner.getMicVolume();
}
function getVolume() {
    trace("getVolume");
    return flashphoner.getVolume();
}

function saveMicSettings() {
    trace("saveMicSettings");
    flashphoner.setVolume(speakerVolume);
    flashphoner.setMicVolume(micVolume);
    closeSettingsView();
}

function setCookie(key, value) {
    trace("setCookie", key, value);
    flashphoner.setCookie(key, value);
}

function getCookie(key) {
    trace("getCookie", key);
    return flashphoner.getCookie(key);
}

function getVersion() {
    trace("getVersion");
    return flashphoner.getVersion();
}

// WSP-1869
function setProperty(key, value) {
    trace("setProperty", key, value);
    flashphoner.setProperty(key, value);
}

function getInfoAboutMe() {
    trace("getInfoAboutMe");
    return flashphoner.getInfoAboutMe();
}

function sipStreamingVideo(value)
{
    if(value == true)
    {
        flashphoner.setSendVideo(true);
    }

    if(value == false)
    {
        flashphoner.setSendVideo(false);
    }

}


/* ------------------ Notify functions ----------------- */

function addLogMessage(message) {
    trace(message);
}

function notifyFlashReady() {
    openVideoView();
    callhandler.init();

}

function notifyRegisterRequired(registerR) {
    registerRequired = registerR;
}

function notifyCloseConnection() {
    trace("notifyCloseConnection");
    currentCall = null;
    isLogged = false;
    callhandler.onLogout();
}

function notifyConnected() {
    trace("notifyConnected");
    //You can set speex quality here
    flashphoner.setSpeexQuality(6);


}

function notifyRegistered() {

    trace("notifyRegistered");
    callerLogin = getInfoAboutMe().login;
    isLogged = true;
    callhandler.onLogin();


}

function notifyBalance(balance) {
    console.log('balance:');
    console.log(balance);
}

function notify(call) {

    trace("notify", call); //: callId " + call.id + " --- " + call.anotherSideUser);
    if (currentCall.id == call.id) { //if we have some call now and notify is about exactly our call
        currentCall = call;
        if (call.state == STATE_FINISH) {
            amICalling = false;
            callhandler.callEnded(call);

            // if that hangup during transfer procedure?
            if (holdedCall != null) {
                currentCall = holdedCall; //holded call become current
                holdedCall = null; //make variable null
                createCallView(currentCall);
            } else {
                callVideoconferenceToCallState();
            }

            // or this just usual hangup during the call
        } else if (call.state == STATE_HOLD) {
            socketSetBusy();
            $("#answerButton").addClass('hide');
            //llamada en espera

        } else if (call.state == STATE_TALK) {

            callhandler.callStarted(call);

        } else if (call.state == STATE_RING) {

            callhandler.callReciving(call);

        }
    } else if (holdedCall.id == call.id) {
        if (call.state == STATE_FINISH) {
            amICalling = false;
            callhandler.callStarted(call);

            /* that mean if
             - user1 call user2
             - user2 transfer to user3
             - user3 just thinking (not answer, not hangup)
             - user2 hangup during him thinking
             then we delete old holded call from user1 memory
             */
            holdedCall = null;
        }

        enableHoldButton();
    }
}

function notifyCallbackHold(call, isHold) {
    trace("notifyCallbackHold", call, isHold);//callId - " + call.id + "; isHold - " + isHold);
    if (currentCall != null && currentCall.id == call.id) {
        currentCall = call;
    }
}

function notifyCost(cost) {
    console.log('cost:');
    console.log(cost);

}

function notifyError(error) {

    trace("notifyError", error);

    if (error == CONNECTION_ERROR) {
        openInfoView("Can`t connect to server.", 3000, 30);

    } else if (error == AUTHENTICATION_FAIL) {
        openInfoView("Register fail, please check your SIP account details.", 3000, 30);
        window.setTimeout("logoff();", 3000);
        registredError();

    } else if (error == USER_NOT_AVAILABLE) {
        openInfoView("User not available.", 3000, 30);
        registredError();

        /*  Deprecated error

         else if (error == TOO_MANY_REGISTER_ATTEMPTS) {
         openInfoView("Connection error", 3000, 30);
         toLoggedOffState();
         */

    } else if (error == LICENSE_RESTRICTION) {
        openInfoView("You trying to connect too many users, or license is expired", 3000, 90);

    } else if (error == LICENSE_NOT_FOUND) {
        openInfoView("Please specify license in the flashphoner.properties (flashphoner.com/license)", 5000, 90);

    } else if (error == INTERNAL_SIP_ERROR) {
        openInfoView("Unknown error. Please contact support.", 3000, 60);
        registredError();

    } else if (error == REGISTER_EXPIRE) {
        openInfoView("No response from VOIP server during 15 seconds.", 3000, 60);

    } else if (error == SIP_PORTS_BUSY) {
        openInfoView("SIP ports are busy. Please open SIP ports range (30000-31000 by default).", 3000, 90);
        connectingViewBeClosed = true;
        window.setTimeout("logoff();", 3000);

    } else if (error == MEDIA_PORTS_BUSY) {
        openInfoView("Media ports are busy. Please open media ports range (31001-32000 by default).", 3000, 90);

    } else if (error == WRONG_SIPPROVIDER_ADDRESS) {
        openInfoView("Wrong domain.", 3000, 30);
        connectingViewBeClosed = true;
        window.setTimeout("logoff();", 3000);

    } else if (error == CALLEE_NAME_IS_NULL) {
        openInfoView("Callee name is empty.", 3000, 30);

    } else if (error == WRONG_FLASHPHONER_XML) {
        openInfoView("Flashphoner.xml has errors. Please check it.", 3000, 60);
    }

    closeConnectingView();
    callVideoconferencetoLogOffState();
}

function notifyVideoFormat(call) {
    trace("notifyVideoFormat", call);

    if (call.streamerVideoWidth != 0) {
        proportionStreamer = 0.75;
        if (proportionStreamer != 0) {
            changeRelationMyVideo(proportionStreamer);
        }
    }


    if (!call.playerVideoHeight == 0) { //that mean if user really send me video
        proportion = call.playerVideoHeight / call.playerVideoWidth; //set proportion of video picture, else it will be = 0
    }

}

function notifyOpenVideoView(isViewed) {
    trace("notifyOpenVideoView", isViewed);
    if (isViewed) {
        openVideoView();
    } else {
        closeVideoView();
    }
}


function notifyAddCall(call) {
    trace("notifyAddCall", call); // call.id, call.anotherSideUser

    if (currentCall != null && call.incoming == true) {
        hangup(call.id);
    } else if (currentCall != null && call.incoming == false) {
        setStatusHold(currentCall.id, true);
        holdedCall = currentCall;
        currentCall = call;
        //createCallView(currentCall);
    } else {
        currentCall = call;
        //createCallView(currentCall);
        if (call.incoming == true) {
            callhandler.callReciving(call);
        }
    }


}


function notifyRemoveCall(call) {
    trace("notifyRemoveCall", call); // call.id
    if (currentCall != null && currentCall.id == call.id) {
        currentCall = null;
        removeCallView(call)
    }
}

/* ----------------------------------------------------------------------- */
