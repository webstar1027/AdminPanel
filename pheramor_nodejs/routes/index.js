var express = require('express');
var router = express.Router();
var users=require('./users');
var status_codes=require('../res_status/status-codes.json');
var passport=require('passport');
var deeplink = require('node-deeplink');
var fb=require('./facebookdata');
var notification=require('./notifications');
var payment=require('./payment');
var gallery=require('./gallery');

//GET home page
router.get('/', function(req, res) {
   // res.render('index',{title:"Demo"});
  res.status(200).json(status_codes.working);
});

router.post('/user/emailcheck',users.email_check);
//SignUp
router.post('/user/signup',users.new_member_signup);
//Login With Email
router.post('/user/login',passport.authenticate('local-login',{
    failureRedirect : '/login/fail'
}),function(req, res) {
    if(req.user.is_deleted==='1'){
        users.activate_account(req.user.id,function(err){
              if(err){
                  res.status(200).json(status_codes.db_error);
              }
              else{
                  res.status(200).json(req.user);
              }
        });
    }
    else{
        res.status(200).json(req.user);
    }
});

router.get('/login/fail', function(req, res) {
    res.status(200).json(status_codes.incorrect_credentials);
});

//Change Password
router.post('/user/changepassword',users.change_password);

//Forgot Password
router.post('/user/forgotpassword',users.forgot_password);
router.post('/user/setpassword',users.set_password);

//My Profile
router.post('/user/profile',users.profile_info);

//User Wait List
router.post('/user/waitlist',users.user_waitlist);

//Credit Earned
router.post('/user/creditearned',users.user_credit);

//Cafe
router.post('/cafelist',users.cafe_list);
router.post('/user/checkin',users.user_cafe_checkin);

//Events
router.post('/createevents',users.create_events);
router.post('/eventlist',users.event_list);
router.post('/user/events/checkin',users.user_event_checkin);

router.post('/user/facebook/login',users.fb_login);
router.post('/user/facebook/data',fb.getFbData);
router.post('/facebook/events',fb.get_public_events);
router.post('/redeemdiscount',users.redeem_discount);
router.post('/user/notifications',notification.get_notifications_list);
router.post('/user/logout',users.logout_user);
router.post('/race',users.race_list);
router.post('/religion',users.religion_list);

router.post('/user/delete',users.deactivate_account);
router.post('/user/payment',payment.save_payment_data);
router.post('/user/gallery',gallery.user_gallery);
router.post('/user/profile/set',gallery.set_profile_picture);
router.post('/user/gallery/delete',gallery.delete_picture);
router.post('/user/gallery/upload',gallery.upload_picture);
router.post('/subscriptionlist',users.subscription_list);


module.exports = router;
