var connection=require('./../config/mysql_conn');

function get_credit_settings(callback){
    connection.query("Select * from pheramor_credit_setting", function(err,setting){
            if(err){
                console.log(err);
                callback(true,err);
            }
            else{
                callback(false,setting[0]);
            }
        });
}

module.exports.get_credit_settings=get_credit_settings;