var connection=require('./../config/mysql_conn');
var encryption=require('./../data-encryption/encryption');
var fbAuth=require('./../config/fbauth');
var FacebookStrategy = require('passport-facebook').Strategy;
var LocalStrategy = require('passport-local').Strategy;
var moment=require('moment');
var notification=require('./notifications');
var credit_set=require('./credits');
var status_codes=require('./../res_status/status-codes');

module.exports = function(passport) {
    passport.serializeUser(function(user, done) {
        done(null, user);
    });
    passport.deserializeUser(function(user, done) {
        done(null, user);
    });
    passport.use('local-login',new LocalStrategy({
            usernameField : 'email',
            passwordField : 'password',
            passReqToCallback : true
        },
        function(req,email, password, done) {
           connection.query("Select * from pheramor_user where email=?",email,function (err, user) {
                if(err)
                {
                    return done(err);
                }
                if(!user.length)
                {
                    return done(null,false);
                }
                if(user[0].password!==encryption.encrypt_password(password) || user[0].activated!=='1')
                {
                    return done(null,false);
                }
                if(user[0].is_deleted==='2')
                {
                   return done(null,status_codes.deactive_by_admin);
                }
                connection.query("Select * from pheramor_user_profile where user_id=?",user[0].id,function (err, profile) {
                    if(err)
                    {
                        return done(err);
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
                                    return done(err);
                                }
                                else{
                                    connection.query("Update pheramor_user set last_login_date='"+credit.created_date+"'"+
                                        " where id="+credit.user_id,function(err,updated_user){
                                        if(err){
                                            return done(err);
                                        }
                                        else{
                                            user=user[0];
                                            user.name=profile[0].first_name+" "+profile[0].last_name;
                                            user.status=1;
                                            user.message="Login Successfull";
                                            return done(null, user);
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
                            if(hours>12){
                                credit_set.get_credit_settings(function(err,setting){
                                    if(err){
                                        return done(err);
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
                                                return done(err);
                                            }
                                            else{
                                                connection.query("Update pheramor_user set last_login_date='"+credit.created_date+"'"+
                                                    " where id="+credit.user_id,function(err,updated_user){
                                                    if(err){
                                                        return done(err);
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
                                                            return done(null, user);
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
                                        return done(err);
                                    }
                                    else{
                                        user=user[0];
                                        user.name=profile[0].first_name+" "+profile[0].last_name;
                                        user.status=1;
                                        user.message="Login Successfull";
                                        return done(null, user);
                                    }
                                });
                            }
                      //  }
                    }
                });
            });
        }
    ));

    /*passport.use('facebook',new FacebookStrategy({
            'clientID'      : fbAuth.clientID,
            'clientSecret'  :fbAuth.clientSecret,
            'callbackURL'   : fbAuth.callbackURL
        },
        // facebook will send back the token and profile
        function(token, refreshToken, profile, done) {
            process.nextTick(function() {
                console.log(JSON.stringify(profile));
                var query;
                if(profile.emails.length){
                    query="Select * from pheramor_user where email='"+profile.emails[0].value+"'";
                }
                else{
                    query="Select * from pheramor_user where social_profile_id='"+profile.id+"'";
                }
                connection.query(query, function(err, user) {
                    if (err)
                        return done(err);
                    if (user.length) {
                        user=user[0];
                        return done(null, user); // user found, return that user
                    }
                    else {
                        var newUser={
                            activated:'1',
                            role_id:2,
                            role_name:'member',
                            email:profile.emails[0].value,
                            created_by:2,
                            created_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss"),
                            updated_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss"),
                            is_agree:'1',
                            referrer_by:0,
                            login_type:'facebook',
                            is_deleted:'0',
                            enable_notification:'0',
                            social_profile_id:profile.id
                        };
                        connection.query('INSERT INTO pheramor_user SET ?', newUser, function (error, results) {
                            if(error){
                                return done(error);
                            }
                            else{
                                var newUser_profile={
                                    user_id:results.insertId,
                                    kit_ID:0,
                                    first_name:profile.name.givenName,
                                    last_name:profile.name.familyName,
                                   // dob:moment(new Date(memberData.dob)).format("YYYY-MM-DD"),
                                    gender:profile.gender,
                                    //show_me:memberData.show_me,
                                    //zipcode:memberData.zipcode,
                                    updated_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss"),
                                    facebook:profile.profileUrl
                                };
                                connection.query('INSERT INTO pheramor_user_profile SET ?', newUser_profile, function (error, results) {
                                    if(error){
                                        return done(error);
                                    }
                                    else{
                                        //Save Data in Social Data Table Is Left
                                        return done(null, newUser);
                                    }
                                });
                            }
                        });
                    }
                });
            });
        }));*/
};