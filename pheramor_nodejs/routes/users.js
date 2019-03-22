var connection=require('./../config/mysql_conn');
var validation=require('./../validations/data-validations');
var status_codes=require('./../res_status/status-codes');
var encryption=require('./../data-encryption/encryption');
var moment=require('moment');
var randomstring=require('randomstring');
var reg_email=require('./../email_functions/registration-email');
var rn = require('random-number');
var geolib=require('geolib');
var campaign=require('./campaign');
var notification=require('./notifications');
var credit_set=require('./credits');



function new_member_signup(req,res){
    var memberData=req.body;
    if(validation.isEmptyObject(memberData)){
        res.status(200).json(status_codes.no_data_found);
    }
    else{
        if(!validation.isFound(memberData.email)){
            res.status(200).json(status_codes.email_missing);
        }
        else{
            if(!validation.isValidEmailId(memberData.email)){
                res.status(200).json(status_codes.invalid_email);
            }
            else{
                memberData.email=(memberData.email).toLowerCase();
                isUserExist(memberData.email,function(err,user){
                    if(err){
                        console.log(err);
                        res.status(200).json(status_codes.db_error);
                    }
                    else{
                        if(validation.isEmptyObject(user)){             //User can register
                            if(!validation.isFound(memberData.password)){
                                res.status(200).json(status_codes.password_missing);
                            }
                            else{
                                get_race_id(memberData.race,function(err,raceId){
                                    if(err){
                                        console.log(err);
                                        res.status(200).json(status_codes.db_error);
                                    }
                                    else{
                                        get_religion_id(memberData.religion,function(err,religionId){
                                            if(err){
                                                console.log(err);
                                                res.status(200).json(status_codes.db_error);
                                            }
                                            else{
                                                var encryted_pwd=encryption.encrypt_password(memberData.password);
                                                var userData={
                                                    activated:'1',
                                                    role_id:2,
                                                    role_name:'member',
                                                    email:memberData.email,
                                                    password:encryted_pwd,
                                                    created_by:2,
                                                    created_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss"),
                                                    updated_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss"),
                                                    is_agree:'1',
                                                    referrer_by:0,
                                                    login_type:memberData.login_type,
                                                    app_password:encryted_pwd,
                                                    is_deleted:'0',
                                                    enable_notification:'0',
                                                    social_profile_id:memberData.social_profile_id,
                                                    last_login_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss")
                                                };
                                                connection.query('INSERT INTO pheramor_user SET ?', userData,
                                                    function (error, user) {
                                                        if(error){
                                                            console.log(error);
                                                            res.status(200).json(status_codes.db_error);
                                                        }
                                                        else{
                                                            memberData.show_me=memberData.isShowMeMen+","+memberData.isShowMeWomen;
                                                            var user_profile={
                                                                user_id:user.insertId,
                                                                kit_ID:0,
                                                                first_name:memberData.first_name,
                                                                last_name:memberData.last_name,
                                                                dob:moment(new Date(memberData.dob)).format("YYYY-MM-DD"),
                                                                gender:memberData.gender,
                                                                show_me:memberData.show_me,
                                                                age_range:memberData.age_range,
                                                                zipcode:memberData.zipcode,
                                                                race:raceId,
                                                                religion:religionId,
                                                                updated_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss")
                                                            };
                                                            connection.query('INSERT INTO pheramor_user_profile SET ?',user_profile,
                                                                function (error, profile) {
                                                                    if(error){
                                                                        console.log(error);
                                                                        res.status(200).json(status_codes.db_error);
                                                                    }
                                                                    else{
                                                                        credit_set.get_credit_settings(function(err,setting){
                                                                           if(err){
                                                                               console.log(err);
                                                                               res.status(200).json(status_codes.db_error);
                                                                           }
                                                                           else{
                                                                               var credit={
                                                                                   user_id:user_profile.user_id,
                                                                                   credits:parseInt(setting.register_credit)+parseInt(setting.login_credit),
                                                                                   description:"Registration & First Login Credit",
                                                                                   created_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss")
                                                                               };
                                                                               connection.query("Insert Into pheramor_user_credits set ?",credit,function(err,credit_res){
                                                                                   if(err){
                                                                                       console.log(err);
                                                                                       res.status(200).json(status_codes.db_error);
                                                                                   }
                                                                                   else{
                                                                                       var token={
                                                                                           user_id:credit.user_id,
                                                                                           device_type:memberData.device_type,
                                                                                           device_address:memberData.device_address
                                                                                       };
                                                                                       notification.update_device_token(token,function(err,device){
                                                                                           if(err){
                                                                                               res.status(200).json(status_codes.db_error);
                                                                                           }
                                                                                           else{
                                                                                               notification.save_notifications(credit.user_id,2,credit.credits,function(err,push_not){
                                                                                                   if(err){
                                                                                                       console.log(err);
                                                                                                   }
                                                                                                   reg_email.send_registration_email(memberData);
                                                                                                   var campaignData={
                                                                                                       EmailAddress:memberData.email,
                                                                                                       Name:memberData.first_name+" "+memberData.last_name
                                                                                                   };
                                                                                                   campaign.add_user(campaignData);
                                                                                                   status_codes.account_created.id=user.insertId;
                                                                                                   status_codes.account_created.name=memberData.first_name+" "+memberData.last_name;
                                                                                                   res.status(200).json(status_codes.account_created);
                                                                                               });
                                                                                           }
                                                                                       });
                                                                                   }
                                                                               });
                                                                           }
                                                                        });
                                                                    }
                                                                });
                                                        }
                                                    });
                                            }
                                        });
                                    }
                                });
                            }
                        }
                        else{
                            res.status(200).json(status_codes.user_already_exist);
                        }
                    }
                });
            }
        }
    }
}

function get_race_id(race_name,callback){
    if(validation.isFound(race_name)){
        race_name=race_name.trim();
        connection.query("Select id from pheramor_race where name=? and is_deleted='0'" +
            " and status='1'",race_name,function(err,race){
            if(err){
                console.log(err);
                callback(true,err);
            }
            else{
                if(race.length>0){
                    callback(false,race[0].id);
                }
                else{
                    var newRace={
                        name:race_name,
                        created_by:1,
                        created_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss"),
                        updated_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss"),
                        is_deleted:'0',
                        status:'1'
                    };
                    connection.query("Insert into pheramor_race set ?",newRace,function(err,record){
                        if(err){
                            console.log(err);
                            callback(true,err);
                        }
                        else{
                            callback(false,record.insertId);
                        }
                    });
                }
            }
        });
    }
    else{
        callback(false,null);
    }
}

function get_religion_id(religion_name,callback){
    if(validation.isFound(religion_name)){
        religion_name=religion_name.trim();
        connection.query("Select id from pheramor_religion where name=? and is_deleted='0'" +
            " and status='1'",religion_name,function(err,religion){
            if(err){
                console.log(err);
                callback(true,err);
            }
            else{
                if(religion.length>0){
                    callback(false,religion[0].id);
                }
                else{
                    var newReligion={
                        name:religion_name,
                        created_by:1,
                        created_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss"),
                        updated_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss"),
                        is_deleted:'0',
                        status:'1'
                    };
                    connection.query("Insert into pheramor_religion set ?",newReligion,function(err,record){
                        if(err){
                            console.log(err);
                            callback(true,err);
                        }
                        else{
                            callback(false,record.insertId);
                        }
                    });
                }
            }
        });
    }
    else{
        callback(false,null);
    }
}

function isUserExist(email,callback){
    email=email.trim();
    connection.query("Select * from pheramor_user where email='"+email+"'", function (err, rows) {
        if(err){
            console.log(err);
            callback(true,err);

        }else{
            if(rows.length>0){
                var user=rows[0];
                callback(false,user);
            }
            else{
                callback(false,{});
            }
        }
    });
}

function change_password(req,res){
    var userData=req.body;
    if(!validation.isEmptyObject(userData)){
        if(validation.isFound(userData.email)){
            if(validation.isValidEmailId(userData.email)){
                if(validation.isFound(userData.oldpassword) && validation.isFound(userData.password)){
                    isUserExist(userData.email,function(err,user){
                        if(err){
                            console.log(err);
                            res.status(200).json(status_codes.db_error);
                        }
                        else{
                            if(!validation.isEmptyObject(user)){             //User Exists
                               // console.log(encryption.encrypt_password(userData.oldpassword));
                               // console.log(user.password);
                                if(encryption.encrypt_password(userData.oldpassword)===user.password){
                                    var new_pwd=encryption.encrypt_password(userData.password);
                                    var updated_date=moment(new Date()).format("YYYY-MM-DD HH:mm:ss");
                                    connection.query("Update pheramor_user set password='"+new_pwd
                                        +"' , app_password='"+new_pwd+"' , updated_date='"+updated_date+"' " +
                                        "where email='"+userData.email+"'",function(err,rows){
                                        if(err){
                                            console.log(err);
                                            res.status(200).json(status_codes.db_error);
                                        }
                                        else{
                                            res.status(200).json(status_codes.password_updated);
                                        }
                                    });
                                }
                                else{
                                    res.status(200).json(status_codes.incorrect_password);
                                }
                            }
                            else{
                                res.status(200).json(status_codes.no_user_found);
                            }
                        }
                    });
                }
                else{
                    res.status(200).json(status_codes.password_missing);
                }
            }
            else{
                res.status(200).json(status_codes.invalid_email);
            }
        }
        else{
            res.status(200).json(status_codes.email_missing);
        }
    }
    else{
        res.status(200).json(status_codes.no_data_found);
    }
}

function forgot_password(req,res){
    var userData=req.body;
    if(validation.isEmptyObject(userData)){
        res.status(200).json(status_codes.no_data_found);
    }
    else{
        var email=userData.email;
        if(!validation.isFound(email)){
            res.status(200).json(status_codes.email_missing);
        }
        else{
            if(!validation.isValidEmailId(email)){
                res.status(200).json(status_codes.invalid_email);
            }
            else{
                email=email.toLowerCase();
                isUserExist(email,function(err,user){
                    if(err){
                        console.log(err);
                        res.status(200).json(status_codes.db_error);
                    }
                    else{
                        if(!validation.isEmptyObject(user)){             //User Exists
                            /*reg_email.forgot_password_email(user);
                            res.status(200).json(status_codes.password_link_sent);*/
                            var otp= randomstring.generate({length: 6,charset: 'numeric'});
                            save_otp(user.id,otp,function(err,result){
                                if(err){
                                    console.log(err);
                                    res.status(200).json(status_codes.db_error);
                                }
                                else{
                                    reg_email.forgot_password_email(user,otp);
                                    status_codes.otp_sent.id=user.id;
                                    res.status(200).json(status_codes.otp_sent);
                                }
                            });
                        }
                        else{
                            res.status(200).json(status_codes.no_user_found);
                        }
                    }
                });
            }
        }
    }
}

function save_otp(user_id,otp,callback){
    connection.query("Select * from pheramor_user_otp_verify where user_id=?",user_id,
        function(err,user){
            if(err){
                console.log(err);
                callback(true,err);
            }
            else{
                otp=encryption.encrypt_password(otp);
                var time=moment(new Date()).format("YYYY-MM-DD HH:mm:ss");
                if(user.length>0){
                    connection.query("Update pheramor_user_otp_verify set OTP=?, datetime=? where user_id=?",
                        [otp,time,user_id],
                        function(err,updated_otp){
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
                    var otpData={
                        user_id:user_id,
                        OTP:otp,
                        datetime:time
                    };
                    connection.query("Insert into pheramor_user_otp_verify set ?",otpData,function(err,new_otp){
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

function check_otp(user_id,otp,callback){
    connection.query("Select * from pheramor_user_otp_verify where user_id=?",user_id,
        function(err,otpInfo){
            if(err){
                console.log(err);
                callback(true,err);
            }
            else{
                otp=encryption.encrypt_password(otp);
                if(otpInfo[0].OTP===otp){
                    var current_time=moment(new Date()).format("YYYY-MM-DD HH:mm:ss");
                    var valid_to=moment(new Date(otpInfo[0].datetime)).format("YYYY-MM-DD HH:mm:ss");
                    var duration = moment.duration(moment(current_time).diff(valid_to));
                    var hours = duration.asHours();
                    if(hours<1){
                        callback(false,status_codes.otp_match);
                    }
                    else{
                        callback(false,status_codes.otp_expired);
                    }
                }
                else{
                    callback(false,status_codes.wrong_otp);
                }
            }
    });
}

function clear_otp(user_id,callback){
    connection.query("Update pheramor_user_otp_verify set OTP=? where user_id=?",
        ['',user_id],
        function(err,updated_otp){
            if(err){
                console.log(err);
                callback(true,err);
            }
            else{
                callback(false,'');
            }
        });
}

function set_password(req,res){
    var set_pwd_data=req.body;
    if(validation.isEmptyObject(set_pwd_data)){
        res.status(200).json(status_codes.no_data_found);
    }
    else{
        if(validation.isFound(set_pwd_data.user_id)){
            if(validation.isFound(set_pwd_data.otp)){
                if(validation.isFound(set_pwd_data.password)){
                    check_otp(set_pwd_data.user_id,set_pwd_data.otp,function(err,otp_state){
                        if(err){
                            console.log(err);
                            res.status(200).json(status_codes.db_error);
                        }
                        else{
                            if(otp_state.status===1){
                                var encryt_pwd=encryption.encrypt_password(set_pwd_data.password);
                                var updated_date=moment(new Date()).format("YYYY-MM-DD HH:mm:ss");
                                connection.query("Update pheramor_user set password='"+encryt_pwd
                                    +"' , app_password='"+encryt_pwd+"' , updated_date='"+updated_date+"' " +
                                    "where id='"+set_pwd_data.user_id+"'",function(err,rows){
                                    if(err){
                                        console.log(err);
                                        res.status(200).json(status_codes.db_error);
                                    }
                                    else{
                                        clear_otp(set_pwd_data.user_id,function(err,result){
                                            if(err){
                                                console.log(err);
                                                res.status(200).json(status_codes.db_error);
                                            }
                                            else{
                                                res.status(200).json(status_codes.password_reset);
                                            }
                                        });
                                    }
                                });
                            }
                            else{
                                res.status(200).json(otp_state);
                            }
                        }
                    });
                }
                else{
                    res.status(200).json(status_codes.password_missing);
                }
            }
            else{
                res.status(200).json(status_codes.otp_missing);
            }
        }
        else{
            res.status(200).json(status_codes.no_user_found);
        }
    }
}

function profile_info(req,res) {
   var userData=req.body;
   if(validation.isFound(userData.email)){
      if(validation.isValidEmailId(userData.email)){
          connection.query("Select user.id,user.email,profile.* from pheramor_user user inner join " +
              "pheramor_user_profile profile on user.id=profile.user_id " +
              "where user.email=?",userData.email,function(err,result){
              if(err){
                  res.status(200).json(status_codes.db_error);
              }
              else{
                  res.status(200).json(result);
                  /*facebook.getFbData('EAADy2H7G05MBACZA1vfSsQkjN2pveNGKq1ogH4AsMOmoYXXQI7TLyuAfLGvPMRoFqbO66DcGEEQk6RCQmpu2fF32I2XYOivJSSRBZCq3j33LImr0fnlUZC0i7u383aOLbrCSMaZAimdqQHtnwAd3gzqpq0sSb3cipmtZC2tim3JdfmJns09I53ZChX3SC7neyZATOtdEHJiZBQZDZD',function(err,fbData){
                      if(err){
                          res.status(200).json(status_codes.fb_error);
                      }
                      else{
                          result[0].Facebook=fbData;
                          res.status(200).json(result);
                      }
                  });*/
              }
          });
      }
      else{
         res.status.json(status_codes.invalid_email);
      }
   }
   else{
      res.status(200).json(status_codes.email_missing);
   }
}

function user_waitlist(req,res){
    var gen = rn.generator({
        min:  100,
        max:  10000,
        integer: true
    });
    var no_of_users=gen();
    res.status(200).json({no_of_users:no_of_users});
}

function cafe_list(req,res){
    var user_id=req.body.user_id;
    if(validation.isFound(user_id)) {
        connection.query("Select * from pheramor_user_cafe_checkins where user_id=? and is_checked_In=1",user_id,
        function(err,checkin){
            if (err) {
                console.log(err);
                res.status(200).json(status_codes.db_error);
            }
            else{
                var cafe_id='';
                if(checkin.length>0){
                    cafe_id=checkin[0].cafe_id;
                }
                connection.query("Select * from pheramor_cafe where status='1'", function (err, cafe) {
                    if (err) {
                        console.log(err);
                        res.status(200).json(status_codes.db_error);
                    }
                    else {
                        cafe_gallery(function(err,cafeGalleryJson){
                            if (err) {
                                console.log(err);
                                res.status(200).json(status_codes.db_error);
                            }
                            else{
                                cafe.map(function(cafe_name){
                                        for(key in cafeGalleryJson){
                                            if(cafe_name.id==key){
                                                cafe_name.images=cafeGalleryJson[key];
                                                break;
                                            }
                                        }
                                        if(cafe_name.images===undefined)
                                            cafe_name.images=[];
                                        if(cafe_name.id==cafe_id){
                                            cafe_name.isCheckedIn='Y';
                                        }
                                        else{
                                            cafe_name.isCheckedIn='N';
                                        }
                                });
                                res.status(200).json({status: 1, message: "Cafe List Retrieved Successfully", cafe: cafe});
                            }
                        });
                    }
                });
            }
        });
    }
    else{
        res.status(200).json(status_codes.no_user_found);
    }
}

function cafe_gallery(callback){
   connection.query("Select * from pheramor_cafe_gallery",function(err,gallery){
       if(err){
           console.log(err);
           callback(true,err);
       }
       else{
           var imageJson={};
           gallery.map(function(image){
               imageJson[image.cafe_id]=image.cafe_id;
           });
           for(key in imageJson){
               var imageArr=new Array();
               gallery.map(function(image){
                   if(key==image.cafe_id){
                       imageArr.push(image.image);
                   }
                   imageJson[key]=imageArr;
               });
           }
          // res.status(200).json(imageJson);
           callback(false,imageJson);
       }
   });
}

function email_check(req,res){
    var email=req.body.email;
    if(validation.isFound(email)){
        isUserExist(email,function(err,user){
            if(err){
                res.status(200).json(status_codes.db_error);
            }
            else{
                if(validation.isEmptyObject(user)){
                    res.status(200).json(status_codes.email_ok);
                }
                else{
                    if(user.is_deleted==='0')
                       res.status(200).json(status_codes.user_already_exist);
                    if(user.is_deleted==='1')
                        res.status(200).json(status_codes.deactive_by_user);
                    if(user.is_deleted==='2')
                        res.status(200).json(status_codes.deactive_by_admin);
                }
            }
        });
    }
    else{
        res.status(200).json(status_codes.email_missing);
    }
}

function user_cafe_checkin(req,res){
    var checkin_data=req.body;
    if(!validation.isEmptyObject(checkin_data)){
        if(validation.isFound(checkin_data.user_id)){
           connection.query("Select id,title,longitude,latitude from pheramor_cafe where id=?",checkin_data.cafe_id,
               function(err,cafe){
                if(err){
                    console.log(err);
                    res.status(200).json(status_codes.db_error);
                }
                else{
                    var user_loc={
                        latitude:checkin_data.lat,
                        longitude:checkin_data.long
                    };
                    var dist=geolib.getDistance({latitude:cafe[0].latitude,longitude:cafe[0].longitude},user_loc);
                    console.log("dist=="+dist);
                    if(dist<=500){
                        connection.query("Select * from pheramor_user_cafe_checkins where user_id=? and is_checked_In=1",checkin_data.user_id,
                            function(err,checkins){
                                if(err){
                                    console.log(err);
                                    res.status(200).json(status_codes.db_error);
                                }
                                else{
                                    if(checkins.length>0){
                                        if(checkins[0].cafe_id==checkin_data.cafe_id){
                                            var current_time=moment(new Date()).format("YYYY-MM-DD HH:mm:ss");
                                            var last_checkin=moment(new Date(checkins[0].check_in_time)).format("YYYY-MM-DD HH:mm:ss");
                                            var duration = moment.duration(moment(current_time).diff(last_checkin));
                                            var hours = duration.asHours();
                                            if(hours>12){
                                                checkout_user('cafe',checkins[0].id,function(err,check_out_info){
                                                    if(err){
                                                        console.log(err);
                                                        res.status(200).json(status_codes.db_error);
                                                    }
                                                    else{
                                                        checkin_user('cafe',checkin_data.user_id,checkin_data.cafe_id,cafe[0].title,'Y',function(err,check_res){
                                                            if(err){
                                                                console.log(err);
                                                                res.status(200).json(status_codes.db_error);
                                                            }
                                                            else{
                                                                res.status(200).json(status_codes.checkin_successfull);
                                                            }
                                                        });
                                                    }
                                                });
                                            }
                                            else{
                                                res.status(200).json(status_codes.already_checkedIn);
                                            }
                                        }
                                        else{
                                            connection.query("SELECT * FROM pheramor_user_cafe_checkins where user_id=? and cafe_id=? order by id desc limit 1",
                                                [checkin_data.user_id,checkin_data.cafe_id],function(err,lastCheckout){
                                                    if(err){
                                                        console.log(err);
                                                        res.status(200).json(status_codes.db_error);
                                                    }
                                                    else{
                                                        var cr_e='';
                                                        if(lastCheckout.length>0){
                                                            var current_time=moment(new Date()).format("YYYY-MM-DD HH:mm:ss");
                                                            var lastcheckout=moment(new Date(lastCheckout[0].check_in_time)).format("YYYY-MM-DD HH:mm:ss");
                                                            var duration = moment.duration(moment(current_time).diff(lastcheckout));
                                                            var hours = duration.asHours();
                                                            if(hours>12)
                                                                cr_e='Y';
                                                            else
                                                                cr_e='N';
                                                        }
                                                        else{
                                                            cr_e='Y';
                                                        }
                                                        checkout_user('cafe',checkins[0].id,function(err,check_out_info){
                                                            if(err){
                                                                console.log(err);
                                                                res.status(200).json(status_codes.db_error);
                                                            }
                                                            else{
                                                                checkin_user('cafe',checkin_data.user_id,checkin_data.cafe_id,cafe[0].title,cr_e,function(err,check_res){
                                                                    if(err){
                                                                        console.log(err);
                                                                        res.status(200).json(status_codes.db_error);
                                                                    }
                                                                    else{
                                                                        res.status(200).json(status_codes.checkin_successfull);
                                                                    }
                                                                });
                                                            }
                                                        });
                                                    }
                                                });
                                        }
                                    }
                                    else{
                                        checkin_user('cafe',checkin_data.user_id,checkin_data.cafe_id,cafe[0].title,'Y',function(err,check_res){
                                            if(err){
                                                console.log(err);
                                                res.status(200).json(status_codes.db_error);
                                            }
                                            else{
                                                res.status(200).json(status_codes.checkin_successfull);
                                            }
                                        });
                                    }
                                }
                            });
                    }
                    else{
                        res.status(200).json(status_codes.cafe_checkin_fail);
                    }
                }
           });
        }
        else{
            res.status(200).json(status_codes.no_user_found);
        }
    }
    else{
        res.status(200).json(status_codes.no_data_found);
    }
}

function checkin_user(type,user_id,id,title,credit_eligible,callback){
    var check_info={
        user_id:user_id,
        check_in_time:moment(new Date()).format("YYYY-MM-DD HH:mm:ss"),
        is_checked_In:1
    },checkin_query="";
    if(type=='cafe'){
        check_info.cafe_id=id;
        checkin_query="Insert into pheramor_user_cafe_checkins set ?"
    }
    else if(type=='event'){
        check_info.event_id=id;
        checkin_query="Insert into pheramor_user_event_checkins set ?"
    }
    connection.query(checkin_query,check_info,function(err,results1){
        if(err){
            console.log(err);
            callback(true,err);
        }
        else{
            if(credit_eligible=='Y'){
                credit_set.get_credit_settings(function(err,setting){
                    if(err){
                        console.log(err);
                        callback(true,err);
                    }
                    else{
                        var credits={
                            user_id:user_id,
                            checkin_id:results1.insertId,
                            description:"Credits Earn For CheckIn in "+title,
                            created_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss")
                        };
                        if(type=='cafe'){
                            credits.cafe_id=id;
                            credits.credits=setting.cafe_credit;
                        }
                        else if(type=='event'){
                            credits.event_id=id;
                            credits.credits=setting.event_credit;
                        }
                        connection.query("Insert into pheramor_user_credits set ?",credits,
                            function(err,result2){
                                if(err){
                                    console.log(err);
                                    callback(true,err);
                                }
                                else{
                                    notification.save_notifications(user_id,2,credits.credits,function(err,push_not){        //For Credits
                                        if(err){
                                            console.log(err);
                                        }
                                        callback(false,'');
                                    });
                                }
                            });
                    }
                });
            }
            else{
                callback(false,'');
            }
        }
    });
}

function checkout_user(type,id,callback){
    var query="";
    if(type=='cafe'){
        query="Update pheramor_user_cafe_checkins set is_checked_In=0 where id=?";
    }
    else if(type=='event'){
        query="Update pheramor_user_event_checkins set is_checked_In=0 where id=?";
    }
    connection.query(query,id, function(err,result2){
            if(err){
                console.log(err);
                callback(true,err);
            }
            else{
                callback(false,'');
            }
        });
}


function create_events(req,res){
   var event_data=req.body;
   if(!validation.isEmptyObject(event_data)){
        event_data.created_date=moment(new Date()).format("YYYY-MM-DD HH:mm:ss");
        connection.query("Insert into pheramor_events set ?",event_data,function(err,events){
            if(err){
                res.status(200).json(status_codes.db_error);
            }
            else{
                res.status(200).json(status_codes.event_created);
            }
        });
   }
   else{
       res.status(200).json(status_codes.no_data_found);
   }
}

function event_list(req,res){
    var pageNum=req.body.pageNum;
    var eventsPerPage=20;
    var offset = eventsPerPage*(pageNum-1);
    var nextPage;
    var user_id=req.body.user_id;
    if(validation.isFound(user_id)) {
        connection.query("Select * from pheramor_user_event_checkins where user_id=? and is_checked_In=1",user_id,
            function(err,checkin){
                if (err) {
                    console.log(err);
                    res.status(200).json(status_codes.db_error);
                }
                else{
                    var event_id='';
                    if(checkin.length>0){
                        event_id=checkin[0].event_id;
                    }
                    connection.query("SELECT count(*) as eventCount from pheramor_events where status='1'",
                        function(err,totalEvents){
                        if(err){
                            console.log(err);
                            res.status(200).json(status_codes.db_error);
                        }
                        else{
                            if(totalEvents.length>0){
                                var eventCount=totalEvents[0].eventCount;
                                if(offset<eventCount){
                                    var current_date=moment(new Date()).format("YYYY-MM-DD HH:mm:ss");
                                    connection.query("Select * from pheramor_events where status='1' and end_date>'"+current_date+
                                        "' order by start_date limit "+eventsPerPage+" offset "+offset,
                                        function(err,events){
                                            if(err){
                                                console.log(err);
                                                res.status(200).json(status_codes.db_error);
                                            }
                                            else{
                                                event_gallery(function(err,eventGalleryJson){
                                                    if (err) {
                                                        console.log(err);
                                                        res.status(200).json(status_codes.db_error);
                                                    }
                                                    else{
                                                        if((pageNum*eventsPerPage)<eventCount)
                                                            nextPage=1;
                                                        else
                                                            nextPage=0;
                                                        events.map(function(event_name){
                                                            event_name.duration = moment.duration(moment(event_name.end_date).diff(event_name.start_date));
                                                            var days = (event_name.duration).asDays();
                                                            if(days<1)
                                                                event_name.duration =parseInt((event_name.duration).asHours())+" Hours";
                                                            else
                                                                event_name.duration =parseInt(days)+" Days";
                                                            event_name.start_date=moment(event_name.start_date).format("MM/DD/YY hh:mm A");
                                                            event_name.end_date=moment(event_name.end_date).format("MM/DD/YY hh:mm A");

                                                            //Event Gallery
                                                            for(key in eventGalleryJson){
                                                                if(event_name.id==key){
                                                                    event_name.images=eventGalleryJson[key];
                                                                    break;
                                                                }
                                                            }
                                                            if(event_name.images===undefined)
                                                                event_name.images=[];
                                                            if(event_name.id==event_id){
                                                                event_name.isCheckedIn='Y';
                                                            }
                                                            else{
                                                                event_name.isCheckedIn='N';
                                                            }
                                                        });
                                                        var finalRes={
                                                            pageNum:pageNum,
                                                            events:events,
                                                            next:nextPage,
                                                            status:1,
                                                            message:"Events Retrieved Successfully"
                                                        };
                                                        res.status(200).json(finalRes);
                                                    }
                                                });
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
            });
    }
    else{
        res.status(200).json(status_codes.no_user_found);
    }
}

function event_gallery(callback){
    connection.query("Select * from pheramor_event_gallery",function(err,gallery){
        if(err){
            console.log(err);
            callback(true,err);
        }
        else{
            var imageJson={};
            gallery.map(function(image){
                imageJson[image.event_id]=image.event_id;
            });
            for(key in imageJson){
                var imageArr=new Array();
                gallery.map(function(image){
                    if(key==image.event_id){
                        imageArr.push(image.image);
                    }
                    imageJson[key]=imageArr;
                });
            }
            // res.status(200).json(imageJson);
            callback(false,imageJson);
        }
    });
}

function user_credit(req,res){
    var user_id=req.body.user_id;
    if(validation.isFound(user_id)){
        var token=req.body;
        notification.update_device_token(token,function(err,device){
            if(err){
                res.status(200).json(status_codes.db_error);
            }
            else{
                getTotalCredit(user_id,function(err,credits){
                    if(err){
                        res.status(200).json(status_codes.db_error);
                    }
                    else{
                        getCount(user_id,function(err,countData){
                            if(err){
                                res.status(200).json(status_codes.db_error);
                            }
                            else{
                                var resp={
                                    totalUser:countData.totalUser,
                                    userCount:countData.userCount,
                                    credits:credits,
                                    pheramorLive:'FEBRUARY 2018',
                                    status:'1',
                                    message:"Successfull"
                                };
                                res.status(200).json(resp);
                            }
                        });
                        /*connection.query("Select max(id) as users from pheramor_user",function(err,totalUser){
                            if(err){
                                console.log(err);
                                res.status(200).json(status_codes.db_error);
                            }
                            else{
                                var resp={
                                    totalUser:totalUser[0].users,
                                    userCount:user_id,
                                    credits:credits,
                                    status:'1',
                                    message:"Successfull"
                                };
                                res.status(200).json(resp);
                            }
                        });*/
                    }
                });
           }
        });
    }
    else{
        res.status(200).json(status_codes.no_user_found);
    }
}

function getTotalCredit(user_id,callback){
    connection.query("Select sum(credits) as credits FROM pheramor_user_credits where user_id=?" +
        " group by user_id",user_id,function (err, credits){
        if(err){
            console.log(err);
            callback(true,err);

        }else{
            if(credits.length){
                callback(false,credits[0].credits);
            }
            else{
                callback(false,0);
            }
        }
    });
}

function fb_login(req,res){
    var profileId=req.body.profileId;
    var email=req.body.email;
    if(email===undefined)
        email='';
    if(validation.isFound(profileId)){
        connection.query("Select * from pheramor_user where social_profile_id=? or email=?",[profileId,email],
            function(err, user) {
               if(err){
                   console.log(err);
                   res.status(200).json(status_codes.db_error);
               }
               else{
                   if(user.length>0){
                       connection.query("Select * from pheramor_user_profile where user_id=?",user[0].id,function(err,profile){
                           if(err){
                               console.log(err);
                               res.status(200).json(status_codes.db_error);
                           }
                           else{
                               /*if(user[0].last_login_date===null){
                                   var credit={
                                       user_id:user[0].id,
                                       credits:32,
                                       description:"Registration & First Login Credit",
                                       created_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss")
                                   };
                                   connection.query("Insert Into pheramor_user_credits set ?",credit,function(err,credit_res){
                                       if(err){
                                           console.log(err);
                                           res.status(200).json(status_codes.db_error);
                                       }
                                       else{
                                           connection.query("Update pheramor_user set last_login_date='"+credit.created_date+"'"+
                                               " where id="+credit.user_id,function(err,updated_user){
                                               if(err){
                                                   console.log(err);
                                                   res.status(200).json(status_codes.db_error);
                                               }
                                               else{
                                                   user=user[0];
                                                   user.status=1;
                                                   user.message="Login Successfull";
                                                   res.status(200).json(user);
                                               }
                                           })
                                       }
                                   });
                               }
                               else{*/
                                   //Credit on 12 hours Interval
                                   var current_time=moment(new Date()).format("YYYY-MM-DD HH:mm:ss");
                                   var last_login=moment(new Date(user[0].last_login_date)).format("YYYY-MM-DD HH:mm:ss");
                                   var duration = moment.duration(moment(current_time).diff(last_login));
                                   var hours = duration.asHours();
                                   // console.log(hours);
                                   if(hours>12){
                                       credit_set.get_credit_settings(function(err,setting){
                                           if(err){
                                               console.log(err);
                                               res.status(200).json(status_codes.db_error);
                                           }
                                           else{
                                               var credit={
                                                   user_id:user[0].id,
                                                   credits:setting.login_credit,
                                                   description:"Login Credits",
                                                   created_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss")
                                               };
                                               connection.query("Insert Into pheramor_user_credits set ?",credit,function(err,credit_res){
                                                   if(err){
                                                       console.log(err);
                                                       res.status(200).json(status_codes.db_error);
                                                   }
                                                   else{
                                                       connection.query("Update pheramor_user set last_login_date='"+credit.created_date+"'"+
                                                           " where id="+credit.user_id,function(err,updated_user){
                                                           if(err){
                                                               console.log(err);
                                                               res.status(200).json(status_codes.db_error);
                                                           }
                                                           else{
                                                               notification.save_notifications(credit.user_id,2,setting.login_credit,function(err,push_not){
                                                                   if(err){
                                                                       console.log(err);
                                                                   }
                                                                   user=user[0];
                                                                   user.name=profile[0].first_name+" "+profile[0].last_name;
                                                                   user.status=1;
                                                                   user.message="Login Successfull";
                                                                   res.status(200).json(user);
                                                               });
                                                           }
                                                       })
                                                   }
                                               });
                                           }
                                       });
                                   }
                                   else{
                                       var date=moment(new Date()).format("YYYY-MM-DD HH:mm:ss");
                                       connection.query("Update pheramor_user set last_login_date='"+date+"'"+
                                           " where id="+user[0].id,function(err,updated_user){
                                           if(err){
                                               console.log(err);
                                               res.status(200).json(status_codes.db_error);
                                           }
                                           else{
                                               user=user[0];
                                               user.name=profile[0].first_name+" "+profile[0].last_name;
                                               user.status=1;
                                               user.message="Login Successfull";
                                               res.status(200).json(user);
                                           }
                                       });
                                   }
                             //  }
                           }
                       });
                   }
                   else{
                      res.status(200).json(status_codes.no_user_found);
                   }
               }
            });
    }
    else{
        res.status(200).json(status_codes.no_data_found);
    }
}

function redeem_discount(req,res){
    var codeData=req.body;
    if(!validation.isEmptyObject(codeData)){
         if(validation.isFound(codeData.user_id)){
             if(validation.isFound(codeData.code)){
                  connection.query("Select code,credits,is_used from discount_code where code=? and is_deleted='0'" +
                      " and status='1'",codeData.code,function(err,code_res){
                      if(err){
                          console.log(err);
                          res.status(200).json(status_codes.db_error);
                      }
                      else{
                          if(code_res.length>0){
                              if(code_res[0].is_used===0){
                                  var creditData={
                                      user_id:codeData.user_id,
                                      credits:code_res[0].credits,
                                      description:"Discount Code- "+code_res[0].code,
                                      created_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss")
                                  };
                                  connection.query("Insert into pheramor_user_credits set ?",creditData,
                                      function(err,credit_res){
                                          if(err){
                                              console.log(err);
                                              res.status(200).json(status_codes.db_error);
                                          }
                                          else{
                                              connection.query("Update discount_code set is_used=1 where code=?",codeData.code,
                                                  function(err,updated_code){
                                                      if(err){
                                                          console.log(err);
                                                          res.status(200).json(status_codes.db_error);
                                                      }
                                                      else{
                                                          getTotalCredit(codeData.user_id,function(err,total_credits){
                                                              if(err){
                                                                  res.status(200).json(status_codes.db_error);
                                                              }
                                                              else{
                                                                  notification.save_notifications(codeData.user_id,2,creditData.credits,function(err,push_not){
                                                                      if(err) {
                                                                          console.log(err);
                                                                      }
                                                                      status_codes.code_redeemed.totalCredits=total_credits;
                                                                      res.status(200).json(status_codes.code_redeemed);
                                                                  });
                                                              }
                                                          });
                                                      }
                                                  })
                                          }
                                      });
                              }
                              else{
                                  res.status(200).json(status_codes.already_used_code);
                              }
                          }
                          else{
                              redeem_promotional_code(codeData,function(err,promo_code){
                                  if(err){
                                      console.log(err);
                                      res.status(200).json(status_codes.db_error);
                                  }
                                  else{
                                      res.status(200).json(promo_code);
                                  }
                              });
                          }
                      }
                  });
             }
             else{
                 res.status(200).json(status_codes.discount_code_missing);
             }
         }
         else{
             res.status(200).json(status_codes.no_user_found);
         }
    }
    else {
        res.status(200).json(status_codes.no_data_found);
    }
}

function redeem_promotional_code(codeData,callback){
    connection.query("Select * from promotional_discount_code where code=? and is_deleted='0' and activated='1'",codeData.code,
        function(err,code_res){
        if(err){
            console.log(err);
            callback(true,err);
        }
        else{
            if(code_res.length>0){
                var current_date=moment(new Date()).format("YYYY-MM-DD");
                code_res[0].valid_from=moment(new Date(code_res[0].valid_from)).format("YYYY-MM-DD");
                code_res[0].valid_to=moment(new Date(code_res[0].valid_to)).format("YYYY-MM-DD");
                if(current_date>=code_res[0].valid_from && current_date<=code_res[0].valid_to){
                    connection.query("Select * from promotional_discount_used where user_id=? and code_name=?",
                        [codeData.user_id,codeData.code],function(err,use_history){
                            if(err){
                                console.log(err);
                                callback(true,err);
                            }
                            else{
                                if(!use_history.length){
                                    var creditData={
                                        user_id:codeData.user_id,
                                        credits:code_res[0].promo_credits,
                                        description:"Promotional Code- "+code_res[0].code,
                                        created_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss")
                                    };
                                    connection.query("Insert into pheramor_user_credits set ?",creditData,
                                        function(err,credit_res){
                                            if(err){
                                                console.log(err);
                                                callback(true,err);
                                            }
                                            else{
                                                var use_history={
                                                    user_id:codeData.user_id,
                                                    code_id:code_res[0].id,
                                                    code_name:code_res[0].code,
                                                    created_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss")
                                                };
                                                connection.query("Insert into promotional_discount_used set ?",use_history,
                                                    function(err,history){
                                                        if(err){
                                                            console.log(err);
                                                            callback(true,err);
                                                        }
                                                        else{
                                                            getTotalCredit(codeData.user_id,function(err,total_credits){
                                                                if(err){
                                                                    console.log(err);
                                                                    callback(true,err);
                                                                }
                                                                else{
                                                                    notification.save_notifications(codeData.user_id,2,creditData.credits,function(err,push_not){
                                                                        if(err) {
                                                                            console.log(err);
                                                                        }
                                                                        status_codes.code_redeemed.totalCredits=total_credits;
                                                                        callback(false,status_codes.code_redeemed);
                                                                    });
                                                                }
                                                            });
                                                        }
                                                    })
                                            }
                                        });
                                }
                                else{
                                    callback(false,status_codes.already_used_code);
                                }
                            }
                        });
                }
                else{
                    callback(false,status_codes.code_expired);
                }
            }
            else{
                callback(false,status_codes.invalid_code);
            }
        }
    });
}

function  getCount(user_id,callback) {
    var min=0,usercount=parseInt(8500+parseInt(user_id)), query='';
    connection.query("Select last_count from pheramor_register_count where user_id=?",user_id,
        function(err,users){
            if(err){
                console.log(err);
                callback(true,err);
            }
            else{
                if(users.length>0){
                    if(parseInt(users[0].last_count)>usercount)
                        min=parseInt(users[0].last_count);
                    else
                        min=usercount;
                }
                else{
                    min=usercount;
                }
                var gen = rn.generator({
                    min:  min,
                    max:  11525,
                    integer: true
                });
                var no_of_users=parseInt(gen());
                if(users.length>0)
                    query="Update pheramor_register_count set last_count="+no_of_users+" where user_id="+user_id;
                else{
                    query="Insert Into pheramor_register_count (user_id,last_count) values ("+user_id+","+no_of_users+")";
                }
                connection.query(query,function(err,result){
                    if(err){
                        console.log(err);
                        callback(true,err);
                    }
                    else{
                        callback(false,{totalUser:no_of_users,userCount:usercount});
                       // res.status(200).json({totalUser:no_of_users,userCount:usercount});
                    }
                });
            }
        });
}

function user_event_checkin(req,res){
    var checkin_data=req.body;
    if(!validation.isEmptyObject(checkin_data)){
        if(validation.isFound(checkin_data.user_id)){
            connection.query("Select id,title,longitude,latitude,start_date,end_date from pheramor_events where status='1' and id=?",checkin_data.event_id,
                function(err,event){
                    if(err){
                        console.log(err);
                        res.status(200).json(status_codes.db_error);
                    }
                    else{
                        var current_time=moment(new Date()).format("YYYY-MM-DD HH:mm:ss");
                        /*event[0].start_date=moment(new Date(event[0].start_date)).format("YYYY-MM-DD HH:mm:ss");
                        event[0].end_date=moment(new Date(event[0].end_date)).format("YYYY-MM-DD HH:mm:ss");*/
                        event[0].start_date=moment(new Date(event[0].start_date)).format("YYYY-MM-DD");
                        event[0].end_date=moment(new Date(event[0].end_date)).format("YYYY-MM-DD HH:mm:ss");
                        if(event[0].start_date<=current_time){
                            if(event[0].end_date>=current_time){
                                var user_loc={
                                    latitude:checkin_data.lat,
                                    longitude:checkin_data.long
                                };
                                var dist=geolib.getDistance({latitude:event[0].latitude,longitude:event[0].longitude},user_loc);
                                console.log("event dist=="+dist);
                                if(dist<=500){
                                    connection.query("Select * from pheramor_user_event_checkins where user_id=? and is_checked_In=1",checkin_data.user_id,
                                        function(err,checkins){
                                            if(err){
                                                console.log(err);
                                                res.status(200).json(status_codes.db_error);
                                            }
                                            else{
                                                if(checkins.length>0){
                                                    if(checkins[0].event_id==checkin_data.event_id){
                                                        var current_time=moment(new Date()).format("YYYY-MM-DD HH:mm:ss");
                                                        var last_checkin=moment(new Date(checkins[0].check_in_time)).format("YYYY-MM-DD HH:mm:ss");
                                                        var duration = moment.duration(moment(current_time).diff(last_checkin));
                                                        var hours = duration.asHours();
                                                        if(hours>12){
                                                            checkout_user('event',checkins[0].id,function(err,check_out_info){
                                                                if(err){
                                                                    console.log(err);
                                                                    res.status(200).json(status_codes.db_error);
                                                                }
                                                                else{
                                                                    checkin_user('event',checkin_data.user_id,checkin_data.event_id,event[0].title,'Y',function(err,check_res){
                                                                        if(err){
                                                                            console.log(err);
                                                                            res.status(200).json(status_codes.db_error);
                                                                        }
                                                                        else{
                                                                            res.status(200).json(status_codes.checkin_successfull);
                                                                        }
                                                                    });
                                                                }
                                                            });
                                                        }
                                                        else{
                                                            res.status(200).json(status_codes.event_already_checkedIn);
                                                        }
                                                    }
                                                    else{
                                                        connection.query("SELECT * FROM pheramor_user_event_checkins where user_id=? and event_id=? order by id desc limit 1",
                                                            [checkin_data.user_id,checkin_data.event_id],function(err,lastCheckout){
                                                                if(err){
                                                                    console.log(err);
                                                                    res.status(200).json(status_codes.db_error);
                                                                }
                                                                else{
                                                                    var cr_e='';
                                                                    if(lastCheckout.length>0){
                                                                        var current_time=moment(new Date()).format("YYYY-MM-DD HH:mm:ss");
                                                                        var lastcheckout=moment(new Date(lastCheckout[0].check_in_time)).format("YYYY-MM-DD HH:mm:ss");
                                                                        var duration = moment.duration(moment(current_time).diff(lastcheckout));
                                                                        var hours = duration.asHours();
                                                                        if(hours>12)
                                                                            cr_e='Y';
                                                                        else
                                                                            cr_e='N';
                                                                    }
                                                                    else{
                                                                        cr_e='Y';
                                                                    }
                                                                    checkout_user('event',checkins[0].id,function(err,check_out_info){
                                                                        if(err){
                                                                            console.log(err);
                                                                            res.status(200).json(status_codes.db_error);
                                                                        }
                                                                        else{
                                                                            checkin_user('event',checkin_data.user_id,checkin_data.event_id,event[0].title,cr_e,function(err,check_res){
                                                                                if(err){
                                                                                    console.log(err);
                                                                                    res.status(200).json(status_codes.db_error);
                                                                                }
                                                                                else{
                                                                                    res.status(200).json(status_codes.checkin_successfull);
                                                                                }
                                                                            });
                                                                        }
                                                                    });
                                                                }
                                                        });
                                                    }
                                                }
                                                else{
                                                    checkin_user('event',checkin_data.user_id,checkin_data.event_id,event[0].title,'Y',function(err,check_res){
                                                        if(err){
                                                            console.log(err);
                                                            res.status(200).json(status_codes.db_error);
                                                        }
                                                        else{
                                                            res.status(200).json(status_codes.checkin_successfull);
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                }
                                else{
                                    res.status(200).json(status_codes.event_checkin_fail);
                                }
                            }
                            else{
                                res.status(200).json(status_codes.event_expired);
                            }
                        }
                        else{
                            res.status(200).json(status_codes.event_not_started);
                        }
                    }
                });
        }
        else{
            res.status(200).json(status_codes.no_user_found);
        }
    }
    else{
        res.status(200).json(status_codes.no_data_found);
    }
}

function logout_user(req,res){
    var user_id=req.body.user_id;
    if(validation.isFound(user_id)){
        var token={
            user_id:user_id,
            device_type:'',
            device_address:''
        };
        notification.update_device_token(token,function(err,device){
            if(err){
                console.log(err);
                res.status(200).json(status_codes.db_error);
            }
            else{
                res.status(200).json(status_codes.logout);
            }
        });
    }
    else{
        res.status(200).json(status_codes.no_user_found);
    }
}

function race_list(req,res){
    connection.query("Select id,name from pheramor_race where status='1'",function(err,list){
        if(err){
            console.log(err);
            res.status(200).json(status_codes.db_error);
        }
        else{
            res.status(200).json({status:'1',message:"Race List Retrieved Successfully",race:list});
        }
    });
}

function religion_list(req,res){
    connection.query("Select id,name from pheramor_religion where status='1'",function(err,list){
        if(err){
            console.log(err);
            res.status(200).json(status_codes.db_error);
        }
        else{
            res.status(200).json({status:'1',message:"Religion List Retrieved Successfully",religion:list});
        }
    });
}

function subscription_list(req,res){
   connection.query("Select * from pheramor_subscription where is_deleted='0' and subscription_status=1",
       function(err,list){
           if(err){
               console.log(err);
               res.status(200).json(status_codes.db_error);
           }
           else{
               res.status(200).json({
                   status:1,
                   message:"Subscription List Retrieved Successfully",
                   subscriptions:list
               });
           }
       });
}

function deactivate_account(req,res){
    var user_id=req.body.user_id;
    if(validation.isFound(user_id)){
        connection.query("Update pheramor_user set is_deleted='1' where id=?",user_id,
            function(err,updated){
                if(err){
                    console.log(err);
                    res.status(200).json(status_codes.db_error)
                }
                else{
                    res.status(200).json(status_codes.acc_deleted);
                }
            });
    }
    else{
        res.status(200).json(status_codes.no_user_found);
    }
}

function activate_account(user_id,callback){
    connection.query("Update pheramor_user set is_deleted='0' where id=?",user_id,
        function(err,updated){
            if(err){
                console.log(err);
                callback(true);
            }
            else{
                callback(false);
            }
        });
}

module.exports.new_member_signup=new_member_signup;
module.exports.change_password=change_password;
module.exports.forgot_password=forgot_password;
module.exports.set_password=set_password;
module.exports.profile_info=profile_info;
module.exports.user_waitlist=user_waitlist;
module.exports.user_credit=user_credit;
module.exports.cafe_list=cafe_list;
module.exports.cafe_gallery=cafe_gallery;
module.exports.email_check=email_check;
module.exports.user_cafe_checkin=user_cafe_checkin;
module.exports.create_events=create_events;
module.exports.event_list=event_list;
module.exports.fb_login=fb_login;
module.exports.redeem_discount=redeem_discount;
module.exports.getCount=getCount;
module.exports.user_event_checkin=user_event_checkin;
module.exports.logout_user=logout_user;
module.exports.race_list=race_list;
module.exports.religion_list=religion_list;
module.exports.subscription_list=subscription_list;
module.exports.deactivate_account=deactivate_account;
module.exports.activate_account=activate_account;

