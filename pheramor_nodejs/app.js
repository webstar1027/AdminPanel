require('events').EventEmitter.prototype._maxListeners = 0;
var express = require('express');
var path = require('path');
var passport=require('passport');
require('./routes/passport')(passport);
var cookieParser = require('cookie-parser');
var bodyParser = require('body-parser');
var cron=require('./cron');


var app = express();

// view engine setup
//app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'ejs');


app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));
app.use(cookieParser());
app.use(passport.initialize());
app.use(passport.session());


//app.use(express.static(__dirname));
app.use(express.static(path.join(__dirname, 'public'),{index:false,extensions:['json']}));

app.use("/",require('./routes'));

app.set('port', process.env.PORT || 5000);
var server=app.listen(app.get('port'), function () {
    console.log('Express server listening on port ' + server.address().port);
});

