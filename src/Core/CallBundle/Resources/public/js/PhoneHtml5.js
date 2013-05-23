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

var isLogged = true;
var amICalling = false;
var callCanICall = true;
var myPhoneInfo;
var session = null;
var openTokApiKey = '29258852';


var micVolume = 100;
var speakerVolume = 100;
var connectingViewBeClosed = false;
var traceEnabled = false;
var intervalId = -1;
var isMutedMicButton = false;
var isMutedSpeakerButton = false;
var proportion = 0.75;

TB.addEventListener("exception", exceptionHandler);



function phoneRegistered(login){
    myPhoneInfo = login;
}


function call(user){
    if(currentCall != null){
        //estoy en una llamada, asi que la quiero responder

    }else{
        //quiero llamar
        amICalling = true;
        socketCallHandler.callTo(user);

    }

}

function hangUp(){
    if(currentCall != null){

        session.disconnect();
        socketCallHandler.hangUp();

    }
}

function toHangUp(){
    currentCall = null;
    amICalling = false;
    callStatus = null;
    callCallerId = null;
}

function answer(){
    socketCallHandler.hookOff();
}
//Notify

function notifyCall(call){
    //registro la llamada entrante

    currentCall = call;
    if(call.caller == callMyId){
        //estoy llamando yo
        amICalling = true;

    }else if(call.receiver == callMyId){
        //me estan llamandoç
        amICalling = false;
        callhandler.callReciving(call);

    }

}

function getInfoAboutMe(){
    var opt = {
        login: myPhoneInfo.user,
        protocol: myPhoneInfo.protocol,
        presence: myPhoneInfo.presence
    }

    return opt;
}

function startCallWebRtc(sessionId, tokenId){

    session = TB.initSession(sessionId); // Replace with your own session ID. See https://dashboard.tokbox.com/projects
    session.addEventListener("sessionConnected", sessionConnectedHandler);
    session.addEventListener("streamCreated", streamCreatedHandler);
    session.connect(openTokApiKey, tokenId); // Replace with your API key and token. See https://dashboard.tokbox.com/projects
}



function sessionConnectedHandler(event) {
    subscribeToStreams(event.streams);
    publisher = TB.initPublisher(openTokApiKey, 'publisher');
    session.publish(publisher);
}

function streamCreatedHandler(event) {
    subscribeToStreams(event.streams);
}

function subscribeToStreams(streams) {
    for (var i = 0; i < streams.length; i++) {
        var stream = streams[i];
        if (stream.connection.connectionId != session.connection.connectionId) {
            session.subscribe(stream, 'videoconferenceHtml5');
        }
    }
}

function exceptionHandler(event) {
    alert(event.message);
}


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