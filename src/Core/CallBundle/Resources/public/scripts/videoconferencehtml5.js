/*CONSTANTS*/

var callStatusStarted = 'STARTED';
var callStatusRinging = 'RINGING';
var callStatusTalking = 'TALKING';
var callStatusConected = 'CONNECTED';
var callStatusWaitingForAnotherSideReload = 'STARTED';


var callCallerId = null;
var callStatus = null;
var callVideoconferenceSwfDefaultWidth = 320;
var callVideoconferenceSwfDefaultHeigt = 240;


function rebootWindow() {
    if (currentCall == null) {
        //logoff();
        //relogin();
        window.location.reload();

    }
}

function rebootFlash() {

    if (flashphoner.getCurrentCall() == null) {
        callhandler.logoff();
        callhandler.login();
    }

}

function createCallView(call) {
    showCaller(call.anotherSideUser);
}

function removeCallView(call) {
    callHideCaller(call.anotherSideUser);

}


/* --------------- Additional functions ------------------- */


function callVideoconferenceToNormalScreen() {


}

function callVideoconferenceToLoadingView(view) {
    if (view == 1) {
        $(".callLoading1").removeClass('hide');
        $(".callLoading1 .progress").addClass("active")
    }
    else if (view == 2) {
        $(".callLoading2").removeClass('hide');
        $(".callLoading2 .progress").addClass("active")
    }
}

function callVideoconferenceToLoadView(view) {
    if (view == 1) {
        $(".callLoading1").addClass('hide');
        $(".callLoading1 .progress").removeClass("active")
    }
    else if (view == 2) {
        $(".callLoading2").addClass('hide');
        $(".callLoading2 .progress").removeClass("active");
    }
}
function callVideoconferenceToHoldState() {

}
function callVideoconferenceToUnHoldState() {

}
function callVideoconferencetoLogOffState() {
    trace("toLogOffState");
    $(".contactlist").parent().addClass('hide');
    $("input.search").parent().addClass("hide");
    //oculto la lista de usuarios
}

function callVideoconferencetoLogInState() {
    trace("toLogOffState");
    $(".contactlist").parent().removeClass('hide');
    $("input.search").parent().removeClass("hide");
    //oculto la lista de usuarios
}

function callVideoconferenceToHangupState() {
    trace("toHangupState");
    $("#callButton").attr('role', 'hang up');
    $("#callButton").html('Colgar');
    //$('#callButton').html('<div><img src='+iconColgar+' style="height: 17px;" height="17px"></div><p style="height: 12px; margin: 0px; margin-top: -7px;">Colgar</p>');
    /*$('#callButton').css('background', '#C00');*/
    $('#callButton').removeClass('call').addClass('hangup');
    $('#callButton').removeClass('green').addClass('red');
}

function callVideoconferenceToCallState() {
    trace("toCallState");
    $("#callButton").attr('role', 'call');
    $("#callButton").html('Llamar');
    //$('#callButton').html('<div><img src='+iconDescolgar+' style="height: 17px;" height="17px"></div><p style="height: 12px; margin: 0px; margin-top: -7px;">Llamar</p>');
    /*$('#callButton').css('background', '#090');*/
    $('#callButton').removeClass('hangup').addClass('call');
    $('#callButton').removeClass('red').addClass('green');

}

function callVideoconferenceToRingingState() {
    callVideoconferenceToHangupState();
    $("#answerButton").css('visibility', 'visible');

}

function callVideoconferenceToCallStateStarted() {
    //$("#fullscreenButton").css('display', 'block');
    //$("#unflushvideoButton").css('display', 'block');
    //$("#holdButton").css('display', 'block');
    $("#answerButton").css('visibility', 'hidden');
}

function callVideoconferenceToHangupStateStarted() {


    $("#fullscreenButton").css('display', 'none');
    $("#unflushvideoButton").css('display', 'none');
    $("#holdButton").css('display', 'none');
    $("#unholdButton").css('display', 'none');
    $("#flushvideoButton").css('display', 'none');
    $("#normalscreenButton").css('display', 'none');

}

function reDrawContentVideoconference() {
    //vuelvo a dibujar la capa que contiene la videoconf
    console.log('REDIBUJANDO');

    setTimeout(function () {


        if ($("#videoconferenceHtml5").length > 0) {
            return true;
        }
        var prot = $("#videoconferenceHtml5Prototype").clone();
        var prot2 = $("#publisherPrototype").clone();

        prot.attr('id', 'videoconferenceHtml5');
        prot.removeClass('hide');

        prot2.attr('id', 'publisher');
        prot2.removeClass('hide');

        $("#contentVideoconferencehtml5RemoteCamera").append(prot);
        $("#contentVideoconferencehtml5MyCamera").append(prot2);
    }, 4000);
}

function enableHoldButton() {
    trace("enableHoldButton");
    var button = $('#holdButton');

}

function disableHoldButton() {
    trace("disableHoldButton");
    var button = $('#holdButton');

}


function openSettingsView() {
    trace("openSettingsView");

}
function closeSettingsView() {
    trace("closeSettingsView");
    getElement('settingsDiv').style.visibility = "hidden";
}

function getElement(str) {
    return document.getElementById(str);
}

/* ----- VIDEO ----- */

function openVideoView() {
    trace("openVideoView");
    if (isMuted() == -1) {
        viewVideo();
        $('#video_requestUnmuteDiv').removeClass().addClass('videoDiv');
    } else {
        requestUnmute();
        intervalId = setInterval('if (isMuted() == -1){closeRequestUnmute(); clearInterval(intervalId); openVideoView();}', 500);
    }
}

function closeVideoView() {
    trace("closeVideoView");
    $('#video_requestUnmuteDiv').removeClass().addClass('closed');
}

/*-----------------*/


/*-----------------*/
/* ----- TRANSFER ----- */
function openTransferView(call) {
    trace("openTransferView");

    if (call.state != STATE_HOLD) {
        setStatusHold(call.id, true);
    }

}

function closeTransferView() {
    trace("closeTransferView");

}

/* ---------------------------------------------------- */

/*
 This functions need to show window with the Adobe security panel when
 is ask allow use devices. This functions change size of window where swf is located.
 Sometimes this window use to show 'Request view', sometimes - to show 'Video view'
 */
function requestUnmute() {
    trace("requestUnmute");

    $('#video_requestUnmuteDiv').removeClass().addClass('securityDiv');


    $('#requestUnmuteText').show();


    viewAccessMessage();
}

function closeRequestUnmute() {
    trace("closeRequestUnmute");
    $('#video_requestUnmuteDiv').removeClass().addClass('closed');
    getElement('jsSWFDiv').style.top = "20px";
}
/* ------------------------- */

// functions closeView is simplifying of many close....View functions
function close(element) {
    element.css('visibility', 'hidden');
}


/* --------------------- On document load we do... ------------------ */
$(function () {


    //Bind click on different buttons
    $("#callButton").click(function () {

        if ($("#callButton").attr('role') == 'call') {
            callhandler.callTo(callCallerId);
        } else {

            callhandler.hangupCall(currentCall);
        }
    });

    $("#transferCansel").click(function () {
        closeTransferView();
    });


    $('#transferOk').click(function () {
        if (currentCall.anotherSideUser != userCalling && userCalling > 0) {
            transfer(currentCall.id, userCalling);
        }
    });

    $('#answerButton').click(function () {
        answer();
        //callhandler.callStarted(currentCall);
    });
    $('#hangupButton').click(function () {
        hangup(currentCall.id);
        callhandler.callEnded(currentCall);
    });
    $('#holdButton').click(function () {
        callhandler.callHold(currentCall);
    });
    $('#unholdButton').click(function () {
        callhandler.callUnHold(currentCall);
    });

    $('#unflushvideoButton').click(function () {
        callhandler.videoFlush(false, true);
    });

    $('#flushvideoButton').click(function () {
        callhandler.videoFlush(true, true);
    });


    $('#fullscreenButton').click(function () {
        callhandler.resizeVideo(callVideoconferenceSwfDefaultWidth * 2, callVideoconferenceSwfDefaultHeigt * 2);
        $('#fullscreenButton').css('display', 'none');
        $('#normalscreenButton').css('display', 'block');

    });

    $('#normalscreenButton').click(function () {
        callhandler.resizeVideo(callVideoconferenceSwfDefaultWidth, callVideoconferenceSwfDefaultHeigt);
        $('#normalscreenButton').css('display', 'none');
        $('#fullscreenButton').css('display', 'block');
    });


});
