var callnotify = {
    success: function(msg)
    {
        $('.center.notifications').notify({
            message: { text: msg }, type: 'greengloss'
        }).show();
    },
    default:function(msg){
        $('.center.notifications').notify({
            message: { text: msg }, type: 'blackgloss'
        }).show();
    },
    danger: function(msg){
        $('.center.notifications').notify({
            message: { text: msg }, type: 'redgloss'
        }).show();
    },
    info: function(msg){
        $('.center.notifications').notify({
            message: { text: msg }, type: 'bluegloss'
        }).show();
    },
    unhidden: function(msg){
        $('.center.notifications').notify({
            message: { text: msg }, type: 'redgloss',
            fadeOut: { enabled: false }
        }).show();
    }
}

var calladminnotify = {
  default: function(msg){
    console.log(msg);
  },
  danger: function(msg)
  {
      console.log(msg);
  }

};