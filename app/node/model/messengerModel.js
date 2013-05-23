var mongoose = require('mongoose');
mongoose.connect('localhost','activity');

var userDeviceSchema = mongoose.Schema({
    auth_token: String,
    device_token: String,
    vendor: String,
    connected_at: Date,
    user: Number
})
var userChatSchema = mongoose.Schema({
    created_at: Date,
    updated_at: Date,
    source: Number,
    target: Number,
    messages: [{ '$ref': String, '$id': mongoose.Schema.Types.ObjectId, '$db': String }]
})

var Devices = mongoose.model('userdevice', userDeviceSchema);

findUserAuthToken = function(token,success){
    console.log("finding..."+token);
	Devices.find({ 'auth_token': token },'auth_token user', function (err, model) {
        console.log("found somethink!..."+err);
        console.log(model);
        console.log("----");
  		success(model);
	})
}

insertMessage = function(message,success){
	Devices.find({token:token}, function (err, model) {
  		success(model);
	})
}

module.exports.mongoose = mongoose;
module.exports.findUserAuthToken = findUserAuthToken;
module.exports.insertMessage = insertMessage;