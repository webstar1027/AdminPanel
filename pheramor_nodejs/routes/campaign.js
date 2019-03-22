var createsend = require('createsend-node');
var campaignAuth=require('./../config/campaignauth');
var api = new createsend({ apiKey: campaignAuth.apiKey });

function add_user(details){
    api.subscribers.addSubscriber(campaignAuth.listID, details,function (err, res) {
        if (err) {
            console.log(err);
            //callback(true,err);
        }
        /*else{
            callback(false,res);
        }*/
    });
}


module.exports.add_user=add_user;