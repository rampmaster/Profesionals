var _mongo_server = "mongodb://localhost:27017/activity";
var Db = require('mongodb').Db;
var ObjectID = require('mongodb').ObjectID;

useMongo = function(callback){
    Db.connect(_mongo_server, function(err, db) {
        callback(db);
    });
}

useCollection = function(collection,callback){

    useMongo(function(db){
        var col = db.collection(collection);
        callback(col);
    })

}

findUserAuthToken = function(token,success){
    useCollection('userdevice',function(collection){
        collection.find({'auth_token':token}).toArray(function(err, devices) {
            if(devices.length==1){
                success(devices[0]);
            }
        })
    });
}

module.exports.findUserAuthToken = findUserAuthToken;