var nodemailer = require('nodemailer');
var transporter = nodemailer.createTransport({
    host: 'smtp.mailgun.org',
    port: 587,
    secure: false, // true for 465, false for other ports
    auth: {
        user:'postmaster@rnftechnologies.com',
        pass:'44f88f25e1443fd19a1bc2e3e9ed9cbd'
    }
});

module.exports={
        sendEmail:function (html,text,subject,receiver) {
            var mailOptions = {
                from:'"Pheramor" pheramorapp@gmail.com', // sender address
                to: [receiver], // list of receivers
                subject: subject, // Subject line
                text: text, // plaintext body
                html: html // html body
            };
            transporter.sendMail(mailOptions, function (err, info) {
                if (err) {
                    return console.log(err);
                }else{
                    return console.log("Email Sent Successfully<br> MessageId-"+info.messageId);
                }
            });
        }
    };