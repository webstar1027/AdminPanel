var connection=require('./../config/mysql_conn');
var validation=require('./../validations/data-validations');
var status_codes=require('./../res_status/status-codes');
var thumb = require('thumbnail');
var ftp_config=require('../config/ftp_config');
var formidable=require('formidable');
var upload_path=require('../config/imageUploadPath');
var moment=require('moment');
var randomstring=require('randomstring');

var Client = require('ssh2').Client;
var options={
    host:ftp_config.staging.host,
    port:ftp_config.staging.port,
    user:ftp_config.staging.user,
    password:ftp_config.staging.password
};

function user_gallery(req,res){
    var user_id=req.body.user_id;
    if(validation.isFound(user_id)){
        connection.query("Select id,name,is_profile from pheramor_user_gallery where user_id=?",user_id,
            function(err,gallery){
                if(err){
                    console.log(err);
                    res.status(200).json(status_codes.db_error);
                }
                else{
                    res.status(200).json({
                        status:1,
                        message:"User Gallery Retrieved Successfully",
                        images:gallery
                    });
                }
            });
    }
    else{
        res.status(200).json(status_codes.no_user_found);
    }
}

function set_profile_picture(req,res){
    var user_id=req.body.user_id;
    var image_id=req.body.image_id;
    if(validation.isFound(user_id)){
        if(validation.isFound(image_id)){
            connection.query("Update pheramor_user_gallery set is_profile=1 where user_id=? and id=?",
                [user_id,image_id],function(err,u_gallery){
                    if(err){
                        console.log(err);
                        res.status(200).json(status_codes.db_error);
                    }
                    else{
                        connection.query("Select name from pheramor_user_gallery where user_id=? and id=?",
                            [user_id,image_id],function(err,gallery){
                                if(err){
                                    console.log(err);
                                    res.status(200).json(status_codes.db_error);
                                }
                                else{
                                    var conn = new Client();
                                    conn.on('ready', function() {
                                        console.log('Client :: ready');
                                        conn.sftp(function(err, sftp) {
                                            if (err) {
                                                console.error(err);
                                                res.status(200).json(status_codes.conn_err);
                                            }
                                            else{
                                                var file=gallery[0].name.substr(gallery[0].name.lastIndexOf("/")+1);
                                                sftp.fastGet(ftp_config.staging.root+gallery[0].name,'./temp_uploads/'+file,function(err){
                                                    if (err) {
                                                        console.error(err);
                                                        res.status(200).json(status_codes.conn_err);
                                                    }
                                                    else{
                                                        var thumbnail = new thumb('./temp_uploads/','./temp_uploads/');
                                                        thumbnail.ensureThumbnail(file,200,200, function (err, filename) {
                                                            if(err){
                                                                console.log(err);
                                                                res.status(200).json(status_codes.thumb_err);
                                                            }
                                                            else{
                                                                console.log('./temp_uploads/'+filename);
                                                                sftp.fastPut('./temp_uploads/'+filename,upload_path.staging_path+"thumbnails/"+filename,function(err){
                                                                    if (err) {
                                                                        console.error(err);
                                                                        res.status(200).json(status_codes.conn_err);
                                                                    }
                                                                    else{
                                                                        connection.query("Update pheramor_user_profile set image=? where user_id=?",[upload_path.staging_path+"thumbnails/"+filename,user_id],
                                                                            function(err,updated){
                                                                                if(err){
                                                                                    console.log(err);
                                                                                    res.status(200).json(status_codes.db_error);
                                                                                }
                                                                                else{
                                                                                    res.status(200).json(status_codes.profile_set);
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
                                    }).connect(options);
                                }
                            });
                    }
                });
        }
        else{
            res.status(200).json(status_codes.image_missing);
        }
    }
    else{
        res.status(200).json(status_codes.no_user_found);
    }
}

function delete_picture(req,res){
    var user_id=req.body.user_id;
    var image_id=req.body.image_id;
    var is_profile=req.body.is_profile;
    if(validation.isFound(user_id)){
        if(validation.isFound(image_id)){
            connection.query("delete from pheramor_user_gallery where user_id=? and id=?",
                [user_id,image_id],function(err,deleted){
                    if(err){
                        console.log(err);
                        res.status(200).json(status_codes.db_error);
                    }
                    else{
                        if(is_profile===1){
                            connection.query("Update pheramor_user_profile set image='' where user_id=?",user_id,
                                function(err,updated){
                                    if(err){
                                        console.log(err);
                                        res.status(200).json(status_codes.db_error);
                                    }
                                    else{
                                        res.status(200).json(status_codes.image_deleted);
                                    }
                                });
                        }
                        else{
                            res.status(200).json(status_codes.image_deleted);
                        }
                    }
                });
        }
        else{
            res.status(200).json(status_codes.image_missing);
        }
    }
    else{
        res.status(200).json(status_codes.no_user_found);
    }
}

function upload_picture(req,res){
    var user_id;
    var form = new formidable.IncomingForm();
    form.parse(req, function(err, fields, files) {
        user_id=fields.user_id;
        //res.writeHead(200, {'content-type': 'text/plain'});
      //  res.write('Upload received :\n');
        //res.end(util.inspect({fields: fields, files: files}));
    });
    form.on('end', function(fields, files) {
        var temp_path = this.openedFiles[0].path;
        var file_name = this.openedFiles[0].name;
        var ext=file_name.split(".")[1];
        if(ext==='jpg'|| ext==='jpeg' || ext==='png'){
            var new_location=ftp_config.staging.root+upload_path.staging_path;
            //console.log(new_location);
            var conn = new Client();
            conn.on('ready', function() {
                console.log('Client :: ready');
                conn.sftp(function(err, sftp) {
                    if (err) {
                        console.error(err);
                        res.status(200).json(status_codes.conn_err);
                    }
                    else{
                        var new_file_name=Date.now()+"_"+randomstring.generate({length:6,charset:'numeric'})+"."+ext;
                        sftp.fastPut(temp_path,new_location+new_file_name,function(err){
                            if (err) {
                                console.error(err);
                                res.status(200).json(status_codes.upload_err);
                            }
                            else{
                                var gallery_obj={
                                    user_id:user_id,
                                    name:upload_path.staging_path+new_file_name,
                                    gallery_type:'image',
                                    created_date:moment(new Date()).format('YYYY-MM-DD HH:mm:ss'),
                                    created_by:user_id,
                                    is_profile:'0'
                                };
                                connection.query("Insert into pheramor_user_gallery set?",gallery_obj,
                                    function(err,image){
                                        if (err) {
                                            console.error(err);
                                            res.status(200).json(status_codes.db_error);
                                        }
                                        else{
                                            res.status(200).json(status_codes.upload_success);
                                            conn.end();
                                        }
                                    });
                            }
                        });
                    }
                });
            }).connect(options);
        }
        else{
            res.status(200).json(status_codes.invalid_image);
        }
    });

}

module.exports.user_gallery=user_gallery;
module.exports.set_profile_picture=set_profile_picture;
module.exports.delete_picture=delete_picture;
module.exports.upload_picture=upload_picture;