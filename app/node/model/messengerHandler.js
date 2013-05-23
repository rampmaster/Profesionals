var _mongo_server = "mongodb://localhost:27017/activity";
var Db = require('mongodb').Db;
    var ObjectID = require('mongodb').ObjectID;

useMongo = function(callback){
  try{
   Db.connect(_mongo_server, function(err, db) {
      try{
        console.log(err)
        callback(db);
      }catch(e){
        console.error('Error USING Mongo');
        console.log(err)
        console.log(e);    
      }
    });
  }catch (e){
    console.error('Error Connecting Mongo');
    console.log(e);
  }
}

useCollection = function(collection,callback){
    try{
        useMongo(function(db){            
          var col = db.collection(collection);
          callback(col,db);
        });
    }catch (e){
        console.error('ERROR Using Collection');
        console.log(e);
    }

}

findUserAuthToken = function(token,success){
  useCollection('userdevice',function(collection,db){
    collection.find({'auth_token':token}).toArray(function(err, devices) {            
        if(devices.length==1){
          success(devices[0]);
        }
        db.close();
    })   
  });
}

insertMessage = function(message,success){

  //preparamos la insercion
 var insert = function(chatTarget,isDestinySource,previousNotifications){
  
  useCollection('usermessage',function(collection,db){
    collection.insert({
      'message': message.message,
      'format' : message.format,
      'chat_id': message.chat,
      'sender': message.user,
      'reciver': chatTarget,
      'created_at' : new Date(),
      'read' : false,

    },function(err, dbMessage) {
      if (err) console.log("Message Error: "+err.message);
      dbMessage.chat = {target:chatTarget,id:message.chat};
      if(typeof(previousNotifications) == "undefined") previousNotifications=0;
      success(dbMessage,isDestinySource,previousNotifications);
      db.close();
    })
  })
  }
   
  //comprobamos si estamos en el chat e insertamos
  useCollection('userchat',function(collection,db){
    collection.find({_id:new ObjectID(message.chat)}).toArray(function(err, chats) {            
      if(err){
        console.log("ERROR Retriving chat_id:"+message.chat)
      }
      if(chats.length>0){
        chat = chats[0];
        if(chat.source == message.user){
          insert(chat.target,false,chat.targetNotifications);
        }
        if(chat.target == message.user){
          insert(chat.source,true,chat.sourceNotifications);
        }

      }
      db.close();
        
    })   
  });
}

updateChat = function(chatId,isUnreadByDestiny,isDestinySource,previousNotifications){
  useCollection('userchat',function(collection,db){
    if(isUnreadByDestiny){
      if(isDestinySource){
        var set = {
          'sourceNotifications': parseInt(parseInt(previousNotifications)+1),
          'updated_at': new Date()
        }
      }else{
        var set = {
          'targetNotifications': parseInt(parseInt(previousNotifications)+1),
          'updated_at': new Date()
        }
      }
    }else{
      var set = {
        'updated_at': new Date()
      }
    }
    console.log("UPDATING")
    console.log(chatId)
    console.log(set)
    collection.update({_id: new ObjectID(chatId)}, {$set: set}, {safe:true},
        function(err) {
          db.close();
          if (err) console.warn(err.message);
          else console.log('chat updated');
        });
    })

}

insertNotificationMessage = function(message, targetId)
{
  console.log(message[0]._id);
    useCollection('notifications', function(collection,db){
        collection.insert({
            'author': targetId,
            'class': 'CoreUserBundle:usermessage',
            'classId': message[0]._id,
            'date': new Date(),
            'action': 'messenger_new_message',
            'watched': false,
            'push': true,
            'push_sent_at': null,
            'push_message': 'Dice: '+message[0].message,
            'crawled': false
        },function(){
          db.close();
        })
    });

    useCollection('notifications', function(collection,db){
        collection.insert({
            'author': targetId,
            'class': 'CoreUserBundle:userchat',
            'classId': message[0].chat_id,
            'date': new Date(),
            'action': 'messenger_new_message',
            'watched': false
        },function(){
          db.close();
        })
    });
}


module.exports.findUserAuthToken = findUserAuthToken;
module.exports.insertMessage = insertMessage;