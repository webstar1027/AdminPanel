var graph=require('fbgraph');
var validation=require('./../validations/data-validations');
var status_codes=require('./../res_status/status-codes');
var fbAuth=require('./../config/fbauth');

function extendUserToken(token,callback){
    graph.extendAccessToken({
        "access_token":token,
        "client_id":fbAuth.clientID,
        "client_secret":fbAuth.clientSecret
    }, function (err, facebookRes) {
        if(err){
            console.log(err);
            callback(true,err);
        }
        else{
            console.log(facebookRes);
            callback(false,facebookRes.access_token);
        }
    });
}

function getFbData(user_access_token,callback){
    graph.setAccessToken(user_access_token);
    graph.get('/me?fields=id,name,picture,about,quotes,albums,music,movies,likes,photos,posts,videos,sports,' +
            'events{name,picture,place,description,start_time,end_time,rsvp_status},television',
        function(err, fb_res) {
            if(err){
                console.log(err);
                callback(true,err);
            }
            else{
                callback(false,fb_res);
            }
    });
}

function get_public_events(req,res){
   var app_access_token=fbAuth.clientID+"|"+fbAuth.clientSecret;
   graph.setAccessToken(app_access_token);
   //var event_id=req.body.id;
    graph.get('/'+fbAuth.page_id+'/events?fields=name,picture,place,description,start_time,end_time',
        function(err, fb_res) {
            res.status(200).json(fb_res);
    });
}

module.exports.extendUserToken=extendUserToken;
module.exports.getFbData=getFbData;
module.exports.get_public_events=get_public_events;