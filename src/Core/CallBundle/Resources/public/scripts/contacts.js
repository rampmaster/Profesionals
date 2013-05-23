var callPresenceOn = 'ON';
var callPresenceOff = 'OFF';
var callPresenceBusy = 'BUSY';
String.prototype.trunc =
    function(n){
        return this.substr(0,n-1)+(this.length>n?'&hellip;':'');
    };
$(function () {

    $("div.contactlist.consulta ul.list li").on('click', function () {

        if($(this).attr('data-status') == 'ON')
        {
            $("div.contactlist.consulta ul.list li").removeClass('selected');
            $(this).addClass('selected');
            contactshandler.selectUser($(this).attr('data-id'));
        }else{
            callnotify.default('Usuario no disponible');
        }

    });
    $("input.contactlistsearch").keyup(function () {
        var val = $(this).val().toLowerCase();
        val = val.split(' ').join('\\ ');
        if (val.length > 0) {
            $("div.contactlist.consulta ul.list li[class!=hide]").css("display", "none");
            $("div.contactlist.consulta ul.list li[data-fullname*=" + val + "][class!=hide]").css("display", "block");
        } else {
            $("div.contactlist.consulta ul.list li[class!=hide]").css("display", "block");
        }

    });
    callVideoconferencetoLogOffState();

});

function callShowUser(target) {
    $("#callUsers").html('');
    callVideoconferenceToLoadingView(2);
    $("#infoUser").css("display", "none");
    $("#callUser").css("display", "none");
    if (target != callMyId) {
        $.post(callRouteToRetrieveInfoUser, { id:target },
            function (data) {

                var obj = jQuery.parseJSON(data);
                $(".showProfileCallerImage").attr('src', obj.profile_image);

                var role = null
                if (obj.isDoctor) {
                    role = "Doctor";
                } else if (obj.isSchool) {
                    role = "Escuela";
                } else if (obj.isPatient) {
                    role = "Paciente";
                } else if (obj.isOperator) {
                    role = "Operadora"
                }
                var content = obj.fullname.trunc(19)+"<br><a href='mailto:"+obj.email+"'>"+obj.email.trunc(19)+ "</a>";
                if(obj.hospital != undefined){
                    content = content + '<br/><a target="_blanck" href="https://maps.google.es/maps?q='+obj.hospital.split(" ").join("+")+'">'+obj.hospital.trunc(19)+'</a>';
                }
                $("#infoUser .second").html(content);
                $("#infoUser .first img").attr("src", obj.profile_image);
                $("#infoUser").fadeIn();
                $("#infoUser").removeClass('hide');
                $("#callUser").fadeIn();
                $("#callUser").removeClass('hide');
                callVideoconferenceToLoadView(2)

            });
    }
}

function callHideCaller(user) {
    tmpl = $("#callUser");
    tmpl.fadeOut();
    tmpl.addClass('hide');

    tmpl.find(".second").html("");
    tmpl.find(".showProfileCallerImage").attr('src', '');

    $("div.contactlist.consulta ul.list li[data-id=" + user + "]").removeClass('selected');
    $("#infoUser").addClass("hide");
    $("#infoUser").css("display", "none");


}

/*
 * Changing state of user
 * Posible status
 */
function callContactChangePresence(status, user) {
    console.log('status: ' + status + '. user :' + user);

    var state = $("div.contactlist.consulta ul.list li[data-id='" + user + "']");

    if (status == callPresenceOn) {
        clean();
        state.find('span').html('ON');
        state.find('span').addClass('label-success');
        state.attr('data-status', 'ON');
        if (callNumContacts > callContactMiniumNumUsersToHide) {
            $("div.contactlist.consulta ul.list li[data-id='" + user + "']").removeClass("hide");
            search();
        }




    }

    if (status == callPresenceBusy) {
        clean();
        state.find('span').html('OCUPADO');
        state.find('span').addClass('label-info');
        state.attr('data-status', 'BUSY');
        if (callNumContacts > callContactMiniumNumUsersToHide) {
            $("div.contactlist.consulta ul.list li[data-id='" + user + "']").removeClass("hide");
            search();
        }

    }

    if (status == callPresenceOff) {
        clean();
        state.find('span').html('OFF');
        state.find('span').addClass('label-important');
        state.attr('data-status', 'OFF');
        if (callNumContacts > callContactMiniumNumUsersToHide) {
            $("div.contactlist.consulta ul.list li[data-id='" + user + "']").addClass("hide");
            search();
        }

    }

    function search(){
        var val = $("input.contactlistsearch").val().toLowerCase();
        val = val.split(' ').join('\\ ');
        if (val.length > 0) {
            $("div.contactlist.consulta ul.list li").css("display", "none");
            $("div.contactlist.consulta ul.list li[data-fullname*=" + val + "][class!=hide]").css("display", "block");
        } else {
            $("div.contactlist.consulta ul.list li[class!=hide]").css("display", "block");
        }
    }

    function clean() {
        state.find('span').removeClass('label-important').removeClass('label-success').removeClass('label-info');
    }

}

function callContaGetUserInfo(user, callback) {
    var check = $("div.contactlist.consulta ul.list li[data-id='" + user + "']");

    if (check.length > 0) {

        r = {
            name:check.attr('data-name'),
            surname:check.attr('data-surname'),
            id:check.attr('data-id'),
            thumb:check.attr('data-image'),
            role:check.attr('data-role')
        }

        if(callback != null)
        {
            callback(r);
        }
        return r;
    }
    else {
        //hago la peticion por ajax

            $.post(callRouteToRetrieveInfoUser, { id:user },
                function (data) {

                    var obj = jQuery.parseJSON(data);
                    var role = null
                    if (obj.isDoctor) {
                        role = "Doctor";
                    } else if (obj.isSchool) {
                        role = "Escuela";
                    } else if (obj.isPatient) {
                        role = "Paciente";
                    } else if (obj.isOperator) {
                        role = "Operadora"
                    }

                    r = {
                        name: obj.name,
                        surname:obj.surname,
                        id:user,
                        thumb: obj.profile_image,
                        role: role
                    }

                    if(callback != null)
                    {
                        callback(r);
                    }
                return r;
        });



    }


}