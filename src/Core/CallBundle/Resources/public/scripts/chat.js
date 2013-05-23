var callchatsocket = io.connect(callRouteToSocketChat);

callchatsocket.on('message send', function (data) {
    if(callCallerId == data.sender){
        callChatAppendMessage(data.message,false,data.sender);
    }
});

function callDeactiveChat() {
    $("#callchat .chat-history").html('');
    $("#callchat .chat").fadeOut();
    callchatsocket.emit('quit');
}

function callActiveChat() {
    $("#callchat .chat").fadeIn();
    callchatsocket.emit('auth', {id: callMyId });

}

function callChatAppendMessage(message, own, userid) {
    calladminnotify.default('appending message  '+ message + ". TO: "+userid);
    if (own == true) {
        $(".chat-history").append("<div class='own'>" + message + "<span class='label'>Vd.</span></div>");
    }
    else {
        user = callContaGetUserInfo(userid, null);
        $(".chat-history").append("<div><span class='label'>" + user.name + " "+ user.surname+ "</span>" + message + "</div>");
    }
    var objDiv = document.getElementById("chat-history");
    objDiv.scrollTop = objDiv.scrollHeight;
}

$(function () {

    $('#callchatuploadForm').ajaxForm({
        type:'POST',
        beforeSubmit:function () {

            if (callCallerId == 0) {
                return false;
            }

        },
        success:function (response, status) {
            if (status == "success") {
                var message = response;
                var id = callCallerId;


                callchatsocket.emit('message send', {'reciver':id, 'message':message});
                callChatAppendMessage(message, true, '');
            } else {
                alert("Error, no se pudo enviar el archivo");
            }
        }
    });


    $("#callchatinput").keyup(function (e) {
        var message = $(this).val();
        //alert(e.keyCode);
        if (e.keyCode == 13) {
            //check if it's a call in progress
                var id = callCallerId;

            if (callCallerId != 0) {
                callchatsocket.emit('message send', {'reciver':id, 'message':message});
                callChatAppendMessage(message, true, '');
                $(".input-prepend #callchatinput").val(' ');
            } else {
                callnotify.danger("Su mensaje no ha sido enviado porque el sistema de mensajería solamente funciona durante las llamadas.")
            }
        }

    });
});