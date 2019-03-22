var connection=require('./../config/mysql_conn');
var validation=require('./../validations/data-validations');
var status_codes=require('./../res_status/status-codes');
var moment=require('moment');
var gcmKey=require('./../config/gcmConfig');
var apn=require('apn');

const settings = {
        gcm: {id: gcmKey.and_key}
       /* apn: {token: {
            key:'./config/AuthKey_MF234SLT2Z.p8',
            keyId:gcmKey.keyId,
            teamId:gcmKey.teamId}
        },
       apn:{
           cert:'./config/cert.pem',
           key:'./config/key.pem'
       },
    production:true*/
};
var pushNotifications=require('node-pushnotifications');
const push = new pushNotifications(settings);

var options= {token: {
    key:'./config/AuthKey_MF234SLT2Z.p8',
    keyId:gcmKey.keyId,
    teamId:gcmKey.teamId},
    production:true
};
var apnProvider = new apn.Provider(options);

//In Home Screen Web Service
function update_device_token(tokenData,callback){
   connection.query("Select * from device_token where user_id=?",tokenData.user_id,
       function(err,token){
       if(err){
           console.log(err);
           callback(true,err);
       }
       else{
           if(token.length>0){
               connection.query("Update device_token set device_type=?, device_address=? where user_id=?",
                   [tokenData.device_type,tokenData.device_address,tokenData.user_id],
                   function(err,updated_token){
                   if(err){
                       console.log(err);
                       callback(true,err);
                   }
                   else{
                       callback(false,'');
                   }
               });
           }
           else{
               connection.query("Insert into device_token set ?",tokenData,function(err,new_token){
                   if(err){
                       console.log(err);
                       callback(true,err);
                   }
                   else{
                       callback(false,'');
                   }
               });
           }
       }
   });
}

function save_notifications(user_id,not_id,credits,callback){
    get_user_device(user_id,function(err,device){
        if(err){
            console.log(err);
            //res.status(200).json(status_codes.db_error);
            callback(true,err);
        }
        else{
            if(device.status=='1'){
                get_notification_setting(not_id,function(err,setting){
                    if(err){
                        console.log(err);
                       // res.status(200).json(status_codes.db_error);
                        callback(true,err);
                    }
                    else{
                        if(setting.status=='1'){
                            if(not_id==2){
                                setting.notification_message=setting.notification_message.replace("{Total}", credits);
                            }
                            var pushJson={
                                user_id:user_id,
                                title:setting.notification_title,
                                type:setting.notification_type,
                                msg:setting.notification_message,
                                device_type:device.device_type,
                                device_address:device.device_address
                            };
                            push_notifications(pushJson,function(err,msg){
                                if(err){
                                    console.log(err);
                                    //res.status(200).json(status_codes.db_error);
                                    callback(true,err);
                                }
                                else{
                                   if(msg=='Successfull'){
                                       var history={
                                           user_id:user_id,
                                           device_address:device.device_address,
                                           device_type:device.device_type,
                                           message:setting.notification_message,
                                           notification_type:setting.notification_type,
                                           is_read:'0',
                                           datetime:moment(new Date()).format("YYYY-MM-DD HH:mm:ss")
                                       };
                                       connection.query("Insert into push_notification_history set ?",history,
                                           function(err,push_history){
                                               if(err){
                                                   console.log(err);
                                                  // res.status(200).json(status_codes.db_error);
                                                   callback(true,err);
                                               }
                                               else{
                                                  // res.status(200).json(status_codes.notification_added);
                                                  callback(false,status_codes.notification_added);
                                               }
                                           });
                                   }
                                   else{
                                       //res.status(200).json(status_codes.notification_not_sent);
                                       callback(false,status_codes.notification_not_sent);
                                   }
                                }
                            });
                        }
                        else{
                            //res.status(200).json(status_codes.invalid_notification_type);
                            callback(false,status_codes.invalid_notification_type);
                        }
                    }
                });
            }
            else{
               // res.status(200).json(status_codes.token_not_found);
               callback(false,status_codes.token_not_found);
            }
        }
    });
}

function push_notifications(data,callback){
    console.log(data.msg);
    if(data.device_type==='an'){
        const notification = {
            title:data.title, // REQUIRED
            body:{"type":data.type,msg:data.msg}, // REQUIRED
            custom: {
                receiverId: data.user_id
            },
            priority: 'high'
        };

        /*if(data.device_type==='ios'){
            notification.topic='com.pheramor';
            notification.body=data.msg
        }*/

        var registrationTokens = [];
        registrationTokens.push(data.device_address);
        push.send(registrationTokens, notification,function (err, result)  {
            if (err) {
                console.log(err);
                callback(true,err);
            } else {
                console.log(result[0].message);
                callback(false,"Successfull");
            }
        });
    }
    else if(data.device_type==='ios'){
        var notification = new apn.Notification({
            //alert: "Hello, world!",
            sound: "chime.caf",
            //mutableContent: 1,
            payload: {
                "sender": "node-apn"
            }
        });
        notification.body=data.msg;
        notification.topic='com.pheramor';
        apnProvider.send(notification, data.device_address).then(function(result,err){
            if(err){
                console.log(err);
                callback(true,err);
            }
            else{
                console.log(result);
                callback(false,"Successfull");
            }
        });
    }

}

function get_notification_setting(not_type_id,callback){
    connection.query("Select * from pheramor_notification where id=? and status='1'",not_type_id,
        function(err,setting){
           if(err){
               console.log(err);
               callback(true,err);
           }
           else{
               callback(false,setting[0]);
           }
        });
}

function get_user_device(user_id,callback){
    connection.query("Select * from device_token where user_id=?",user_id,
        function(err,device){
            if(err){
                console.log(err);
                callback(true,err);
            }
            else{
                if(device.length>0){
                    device[0].status='1';
                    callback(false,device[0]);
                }
                else{
                    callback(false,status_codes.token_not_found);
                }
            }
        });
}

function get_notifications_list(req,res){
    var user_id=req.body.user_id;
    var pageNum=req.body.pageNum;
    var not_perPage=60;
    var offset = not_perPage*(pageNum-1);
    var nextPage;
    if(validation.isFound(user_id)){
        connection.query("SELECT count(*) as not_count from push_notification_history where user_id=?",user_id,
            function(err,total_not){
                if(err){
                    console.log(err);
                    res.status(200).json(status_codes.db_error);
                }
                else{
                    if(total_not.length>0){
                        var not_count=total_not[0].not_count;
                        if(offset<not_count){
                            connection.query("Select * from push_notification_history where user_id=? order by id desc limit ? offset ?",
                                [user_id,not_perPage,offset],
                                function(err,notifications){
                                    if(err){
                                        console.log(err);
                                        res.status(200).json(status_codes.db_error);
                                    }
                                    else{
                                        notifications.map(function(name){
                                            name.datetime=moment(name.datetime, "YYYY-MM-DD HH:mm:ss").valueOf();
                                        });
                                        if((pageNum*not_perPage)<not_count)
                                            nextPage=1;
                                        else
                                            nextPage=0;
                                        var finalRes={
                                            pageNum:pageNum,
                                            notifications:notifications,
                                            next:nextPage,
                                            status:1,
                                            message:"Notifications Retrieved Successfully"
                                        };
                                        res.status(200).json(finalRes);
                                    }
                                });
                        }
                        else{
                            res.status(200).json(status_codes.no_data_found);
                        }
                    }
                    else{
                        res.status(200).json(status_codes.no_data_found);
                    }
                }
            });
    }
    else{
        res.status(200).json(status_codes.no_user_found);
    }
}

/*function not_test(req,res){
    var notification = new apn.Notification({
        alert: "Hello, world!",
        sound: "chime.caf",
        mutableContent: 1,
        payload: {
            "sender": "node-apn"
        }
    });
    notification.body="Hello, world! Body";
    notification.topic='com.pheramor';
    apnProvider.send(notification, req.body.deviceToken).then(function(err,result){
        if(err){
            console.log(err);
            res.status(200).json(err);
        }
        else{
            console.log(err);
            res.status(200).json(result);
        }
    });
}*/

module.exports.update_device_token=update_device_token;
module.exports.save_notifications=save_notifications;
module.exports.get_notifications_list=get_notifications_list;
module.exports.push_notifications=push_notifications;
//module.exports.not_test=not_test;