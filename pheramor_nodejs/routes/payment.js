var connection=require('./../config/mysql_conn');
var validation=require('./../validations/data-validations');
var status_codes=require('./../res_status/status-codes');
var moment=require('moment');

function getStripeKey(){
    connection.query("SELECT enable_sandbox,test_secret_key,live_secret_key FROM pheramor_general_setting",
        function(err,gen_setting){
        if(err)
            return err;
        else{
            var key;
            if(gen_setting[0].enable_sandbox==1)
                key=gen_setting[0].test_secret_key;
            else if(gen_setting[0].enable_sandbox==0)
                key=gen_setting[0].live_secret_key;
            return key;
        }
    });
}
var secret_key=getStripeKey();
//var stripe = require('stripe')(secret_key);
var stripe = require('stripe')('sk_test_Bid87wt43Qu5pSKMMDaQJ5sU');
function save_payment_data(req,res){
     var payData=req.body;
     if(!validation.isEmptyObject(payData)){
         if(validation.isFound(payData.user_id)){
             connection.query("Select email from pheramor_user where id=?",payData.user_id,
                 function (err,user) {
                     if(err){
                         console.log(err);
                         res.status(200).json(status_codes.db_error);
                     }
                     else{
                         var email=user[0].email;
                         connection.query("Select * from pheramor_user_card_info where user_id=?",payData.user_id,
                             function(err,card_info){
                                 if(err){
                                     console.log(err);
                                     res.status(200).json(status_codes.db_error);
                                 }
                                 else{
                                     var cust_id='',f_card='';
                                     var card={
                                         exp_month: payData.exp_date.split("/")[0],
                                         exp_year: payData.exp_date.split("/")[1],
                                         card_no:payData.card_no,
                                         cvv:payData.cvv,
                                         nameoncard:payData.nameoncard
                                     };
                                     if(card_info.length>0){
                                         cust_id=card_info[0].customer_id;
                                         if(validation.isFound(card_info[0].card_token)){
                                             f_card='N';
                                         }
                                         else{
                                             f_card='Y';
                                         }
                                         save_card_details(payData.user_id,cust_id,card,f_card,function(err,saved_card){
                                             if(err){
                                                 res.status(200).json({status:0,message:saved_card});
                                             }
                                             else{
                                                 res.status(200).json(saved_card);
                                             }
                                         });
                                     }
                                     else{
                                         stripe.customers.create({email:email},function(err, customer){
                                             if(err){
                                                 console.log(err.message);
                                                 res.status(200).json({status:0,message:err.message});
                                             }
                                             else{
                                                cust_id=customer.id;
                                                var c_info={
                                                    user_id:payData.user_id,
                                                    customer_id:cust_id,
                                                    created_date:moment(new Date()).format("YYYY-MM-DD HH:mm:ss")
                                                };
                                                connection.query("Insert into pheramor_user_card_info set ?",c_info,
                                                    function(err,res_card_info){
                                                        if(err){
                                                            console.log(err);
                                                            res.status(200).json(status_codes.db_error);
                                                        }
                                                        else{
                                                            save_card_details(payData.user_id,cust_id,card,'Y',function(err,saved_card){
                                                                if(err){
                                                                    res.status(200).json({status:0,message:saved_card});
                                                                }
                                                                else{
                                                                    stripe.charges.create({
                                                                        amount:(payData.amt)*100,
                                                                        currency: "usd",
                                                                        description: "Example charge",
                                                                        source: {
                                                                            object: 'card',
                                                                            exp_month: payData.exp_date.split("/")[0],
                                                                            exp_year: payData.exp_date.split("/")[1],
                                                                            number: payData.card_no,
                                                                            cvc: payData.cvv,
                                                                            name:payData.nameoncard
                                                                        }
                                                                    }, function(err, charge) {
                                                                        if(err){
                                                                            console.log(err.message);
                                                                            res.status(200).json({status:0,message:err.message});
                                                                        }
                                                                        else{
                                                                            res.status(200).json(charge);
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
                             });
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

function save_card_details(user_id,cust_id,card_info,f_card,callback){
   // console.log(f_card);
    stripe.customers.createSource(cust_id, {
        source: {
            object: 'card',
            exp_month:card_info.exp_month,
            exp_year: card_info.exp_year,
            number: card_info.card_no,
            cvc: card_info.cvv,
            name:card_info.nameoncard
        }},
        function(err, card) {
            if(err){
                console.log(err.message);
                callback(true,err.message);
            }
            else{
                var cardInfoJson={
                    cardholderName:card.name,
                    cardType:card.brand,
                    maskedNumber:"********"+card.last4,
                    card_token:card.id
                };
                if(f_card==='Y'){
                    connection.query("Update pheramor_user_card_info set ? where customer_id=?",[cardInfoJson,cust_id],
                        function(err,updated){
                            if(err){
                                console.log(err);
                                callback(true,err);
                            }
                            else{
                                callback(false,card);
                            }
                        });
                }
                else{
                    cardInfoJson.user_id=user_id;
                    cardInfoJson.customer_id=cust_id;
                    cardInfoJson.created_date=moment(new Date()).format("YYYY-MM-DD HH:mm:ss");
                    cardInfoJson.is_default='0';
                    connection.query("Insert into pheramor_user_card_info set ?",[cardInfoJson],
                        function(err,inserted){
                            if(err){
                                console.log(err);
                                callback(true,err);
                            }
                            else{
                                callback(false,card);
                            }
                        });
                }
            }
        });
}

module.exports.save_payment_data=save_payment_data;