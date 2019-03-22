(function($){
    
    var baseUrl = window.location.protocol + "//" + window.location.host + "/";
    if(window.location.host == 'localhost')
        baseUrl += 'pheramor/';
    
    $.fn.validationEngineLanguage = function(){
    };
    $.validationEngineLanguage = {
        newLang: function(){
            $.validationEngineLanguage.allRules = {
                "required": { // Add your regex rules here, you can take telephone as an example
                    "regex": "none",
                    "alertText": "* This field is required",
                    "alertTextCheckboxMultiple": "* Please select an option",
                    "alertTextCheckboxe": "* This checkbox is required",
                    "alertTextDateRange": "* Both date range fields are required"
                },
                "requiredInFunction": { 
                    "func": function(field, rules, i, options){
                        return (field.val() == "test") ? true : false;
                    },
                    "alertText": "* Field must equal test"
                },
                "dateRange": {
                    "regex": "none",
                    "alertText": "* Invalid ",
                    "alertText2": "Date Range"
                },
                "dateTimeRange": {
                    "regex": "none",
                    "alertText": "* Invalid ",
                    "alertText2": "Date Time Range"
                },
                "minSize": {
                    "regex": "none",
                    "alertText": "* Minimum ",
                    "alertText2": " characters required"
                },
                "maxSize": {
                    "regex": "none",
                    "alertText": "* Maximum ",
                    "alertText2": " characters allowed"
                },
		"groupRequired": {
                    "regex": "none",
                    "alertText": "* You must fill one of the following fields",
                    "alertTextCheckboxMultiple": "* Please select an option",
                    "alertTextCheckboxe": "* This checkbox is required"
                },
                "min": {
                    "regex": "none",
                    "alertText": "* Minimum value is "
                },
                "max": {
                    "regex": "none",
                    "alertText": "* Maximum value is "
                },
                "past": {
                    "regex": "none",
                    "alertText": "* Date prior to "
                },
                "future": {
                    "regex": "none",
                    "alertText": "* Date past "
                },	
                "maxCheckbox": {
                    "regex": "none",
                    "alertText": "* Maximum ",
                    "alertText2": " options allowed"
                },
                "minCheckbox": {
                    "regex": "none",
                    "alertText": "* Please select ",
                    "alertText2": " options"
                },
                "equals": {
                    "regex": "none",
                    "alertText": "* Fields do not match"
                },
                "creditCard": {
                    "regex": "none",
                    "alertText": "* Invalid credit card number"
                },
                "phone": {
                    // credit: jquery.h5validate.js / orefalo
                    "regex": /^([\+][0-9]{1,3}([ \.\-])?)?([\(][0-9]{1,6}[\)])?([0-9 \.\-]{1,32})(([A-Za-z \:]{1,11})?[0-9]{1,4}?)$/,
                    "alertText": "* Invalid phone number"
                },
                "email": {
                    // HTML5 compatible email regex ( http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#    e-mail-state-%28type=email%29 )
                    "regex": /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                    "alertText": "* Invalid email address"
                },
                "fullname": {
                    "regex":/^([a-zA-Z]+[\'\,\.\-]?[a-zA-Z ]*)+[ ]([a-zA-Z]+[\'\,\.\-]?[a-zA-Z ]+)+$/,
                    "alertText":"* Must be first and last name"
                },
                "zip": {
                    "regex":/^\d{5}$|^\d{5}-\d{4}$/,
                    "alertText":"* Invalid zip format"
                },
                "integer": {
                    "regex": /^[\-\+]?\d+$/,
                    "alertText": "* Not a valid integer"
                },
                "number": {
                    // Number, including positive, negative, and floating decimal. credit: orefalo
                    "regex": /^[\-\+]?((([0-9]{1,3})([,][0-9]{3})*)|([0-9]+))?([\.]([0-9]+))?$/,
                    "alertText": "* Invalid floating decimal number"
                },
                "date": {                    
                    //	Check if date is valid by leap year
                    "func": function (field) {
                                var pattern = new RegExp(/^(\d{4})[\/\-\.](0?[1-9]|1[012])[\/\-\.](0?[1-9]|[12][0-9]|3[01])$/);
                                var match = pattern.exec(field.val());
                                if (match == null)
                                   return false;

                                var year = match[1];
                                var month = match[2]*1;
                                var day = match[3]*1;					
                                var date = new Date(year, month - 1, day); // because months starts from 0.

                                return (date.getFullYear() == year && date.getMonth() == (month - 1) && date.getDate() == day);
                            },                		
                     "alertText": "* Invalid date, must be in YYYY-MM-DD format"
                },
                "ipv4": {
                    "regex": /^((([01]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))[.]){3}(([0-1]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))$/,
                    "alertText": "* Invalid IP address"
                },
                "url": {
                    "regex": /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i,
                    "alertText": "* Invalid URL"
                },
                "onlyNumberSp": {
                    "regex": /^[0-9\ ]+$/,
                    "alertText": "* Numbers only"
                },
                "onlyLetterSp": {
                    "regex": /^[a-zA-Z\ \u3300-\u9fff\uf900-\ufaff\u00C0-\u00ff\u0500-\u05FF']+$/,
                    "alertText": "* Letters only"
                },
		"onlyLetterAccentSp":{
                    "regex": /^[a-z\u00C0-\u017F\ ]+$/i,
                    "alertText": "* Letters only (accents allowed)"
                },
                "onlyLetterNumber": {
                    "regex": /^[0-9a-zA-Z]+$/,
                    "alertText": "* No special characters allowed"
                },
                 "onlyLetterNumberSp": {
                    "regex": /^[0-9a-zA-Z\ ]+$/,
                    "alertText": "* No special characters allowed"
                },
                // --- CUSTOM RULES -- Those are specific to the demos, they can be removed or changed to your likings
                "ajaxUserCall": {
                    "url": "ajaxValidateFieldUser",
                    // you may want to pass extra data on the ajax call
                    "extraData": "name=eric",
                    "alertText": "* This user is already taken",
                    "alertTextLoad": "* Validating, please wait"
                },
                
                 "validateMIME":  {
                     
                    "func": function(field, rules, i, options){
                    //add to input tag: data-validation-engine="validate[required, custom[validateMIME[image/jpeg|image/png]]]"
                      var file = $("#userfile")[0].files[0];
                        var MimeFilter = /^(image\/bmp|image\/gif|image\/jpeg|image\/png|image\/tiff)$/i;
                         if (file) {
                                return MimeFilter.test(file.type);
                            } else { return true;}
                            },
                        
                         "alertText": "* Wrong File Type.",
                        // "alertTextOk": "All good!",

                    },
                    "validateMIME1":  {
                     
                    "func": function(field, rules, i, options){
                    //add to input tag: data-validation-engine="validate[required, custom[validateMIME[image/jpeg|image/png]]]"
                      var file = $("#userfile1")[0].files[0];
                        var MimeFilter = /^(image\/bmp|image\/gif|image\/jpeg|image\/png|image\/tiff)$/i;
                         if (file) {
                                return MimeFilter.test(file.type);
                            } else { return true;}
                            },
                        
                         "alertText": "* Wrong File Type.",
                        // "alertTextOk": "All good!",

                    },
                     "validateMIME2":  {
                     
                    "func": function(field, rules, i, options){
                    //add to input tag: data-validation-engine="validate[required, custom[validateMIME[image/jpeg|image/png]]]"
                      var file = $("#userfile2")[0].files[0];
                        var MimeFilter = /^(image\/bmp|image\/gif|image\/jpeg|image\/png|image\/tiff)$/i;
                         if (file) {
                                return MimeFilter.test(file.type);
                            } else { return true;}
                            },
                        
                         "alertText": "* Wrong File Type.",
                        // "alertTextOk": "All good!",

                    },
                     "validateMIME3":  {
                     
                    "func": function(field, rules, i, options){
                    //add to input tag: data-validation-engine="validate[required, custom[validateMIME[image/jpeg|image/png]]]"
                      var file = $("#userfile3")[0].files[0];
                        var MimeFilter = /^(image\/bmp|image\/gif|image\/jpeg|image\/png|image\/tiff)$/i;
                         if (file) {
                                return MimeFilter.test(file.type);
                            } else { return true;}
                            },
                        
                         "alertText": "* Wrong File Type.",
                        // "alertTextOk": "All good!",

                    },
                // To check max due pay
                "maxDueAmount": {
                    "url": baseUrl + "PheramorAjax/maxDueAmount",
                    "extraDataDynamic": ["#mp_id", '#gen_membership_id','#membership_valid_from','#membership_valid_to',],
                    "alertText": "* Amount must be equal to due amount / start date invalid",
                    "alertTextLoad": "* Checking due amount, please wait"
                },
                 // To check max refund pay
                "maxRefundAmount": {
                    "url": baseUrl + "PheramorAjax/maxRefundAmount",
                    "extraDataDynamic": ["#mp_id",'#amount','#refundeed_amount',],
                    "alertText": "* Refund amount must be less than remaining amount and greater than 0",
                    "alertTextLoad": "* Checking refund amount, please wait",
                    "alertTextOk": "All good!"
                },
                // To check unique discount code
                "isDiscountCodeUnique1": {
                    "url": baseUrl + "PheramorAjax/discountCodeExist1",
                    "extraDataDynamic": ["#itsId"],
                    "alertText": "* This code is already in use",
                    "alertTextOk": "All good!",
                    "alertTextLoad": "* Validating, please wait"
                },
                // To check unique discount code
                "isDiscountCodeUnique": {
                    "url": baseUrl + "PheramorAjax/discountCodeExist",
                    "alertText": "* This code is already in use",
                    "alertTextOk": "All good!",
                    "alertTextLoad": "* Validating, please wait"
                },
                "isMasterTitleUnique": {
                    "url": baseUrl + "PheramorAjax/masterTitleUnique",
                    "extraDataDynamic": ["#edit_id",'#tbl_name'],
                    "alertText": "* This name is already taken",
                    "alertTextLoad": "* Checking name, please wait",
                    "alertTextOk": "All good!"
                },
                
                // To check unique discount code
                "isDiscountCodeValid": {
                    "url": baseUrl + "PheramorAjax/discountCodeValid",
                     "extraDataDynamic": ["#itsId"],
                    "alertText": "* This code is already in use",
                    "alertTextOk": "All good!",
                    "alertTextLoad": "* Validating, please wait"
                },
                // To check unique email
                "isEmailUnique": {
                    "url": baseUrl + "PheramorAjax/emailExist",
                    "alertText": "* This email is already associated with our system",
                    "alertTextOk": "All good!",
                    "alertTextLoad": "* Validating, please wait"
                },
                // To check unique email
                "isDobValid": {
                    "url": baseUrl + "PheramorAjax/DobValid",
                    "alertText": "* You are under 18 years old",
                    "alertTextOk": "All good!",
                    "alertTextLoad": "* Validating, please wait"
                },
                // To check unique email for referred user
                "isEmailUnique2": {
                    "url": baseUrl + "MemberRegistration/emailExist",
                    "alertText": "* This email is already associated with our system",
                    "alertTextOk": "All good!",
                    "alertTextLoad": "* Validating, please wait"
                },
                // To check unique username for referred user
                "isUserNameUnique2": {
                    "url": baseUrl + "MemberRegistration/usernameExist",
                    "alertText": "* This email is already associated with our system",
                    "alertTextOk": "All good!",
                    "alertTextLoad": "* Validating, please wait"
                },
                // To check unique email for existing user
                "isEmailUnique1": {
                    "url": baseUrl + "PheramorAjax/emailExist1",
                    "extraDataDynamic": ["#itsId"],
                    "alertText": "* This email is already associated with our system",
                    "alertTextOk": "All good!",
                    "alertTextLoad": "* Validating, please wait"
                },
                
                // To check unique username
                "isUserNameUnique": {
                    "url": baseUrl + "PheramorAjax/usernameExist",
                    "alertText": "* This username is already taken",
                    "alertTextOk": "All good!",
                    "alertTextLoad": "* Validating, please wait"
                },
                // To check unique username for existing user
                "isUserNameUnique1": {
                    "url": baseUrl + "PheramorAjax/usernameExist1",
                    "extraDataDynamic": ["#itsId"],
                    "alertText": "* This username is already taken",
                    "alertTextOk": "All good!",
                    "alertTextLoad": "* Validating, please wait"
                },
		"ajaxUserCallPhp": {
                    "url": "phpajax/ajaxValidateFieldUser.php",
                    // you may want to pass extra data on the ajax call
                    "extraData": "name=eric",
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertTextOk": "* This username is available",
                    "alertText": "* This user is already taken",
                    "alertTextLoad": "* Validating, please wait"
                },
                "ajaxNameCall": {
                    // remote json service location
                    "url": "ajaxValidateFieldName",
                    // error
                    "alertText": "* This name is already taken",
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertTextOk": "* This name is available",
                    // speaks by itself
                    "alertTextLoad": "* Validating, please wait"
                },
		"ajaxNameCallPhp": {
                    // remote json service location
                    "url": "phpajax/ajaxValidateFieldName.php",
                    // error
                    "alertText": "* This name is already taken",
                    // speaks by itself
                    "alertTextLoad": "* Validating, please wait"
                },
                "validate2fields": {
                    "alertText": "* Please input HELLO"
                },
	            //tls warning:homegrown not fielded 
                "dateFormat":{
                    "regex": /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$|^(?:(?:(?:0?[13578]|1[02])(\/|-)31)|(?:(?:0?[1,3-9]|1[0-2])(\/|-)(?:29|30)))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(?:(?:0?[1-9]|1[0-2])(\/|-)(?:0?[1-9]|1\d|2[0-8]))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(0?2(\/|-)29)(\/|-)(?:(?:0[48]00|[13579][26]00|[2468][048]00)|(?:\d\d)?(?:0[48]|[2468][048]|[13579][26]))$/,
                    "alertText": "* Invalid Date"
                },
                //tls warning:homegrown not fielded 
				"dateTimeFormat": {
	                "regex": /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])\s+(1[012]|0?[1-9]){1}:(0?[1-5]|[0-6][0-9]){1}:(0?[0-6]|[0-6][0-9]){1}\s+(am|pm|AM|PM){1}$|^(?:(?:(?:0?[13578]|1[02])(\/|-)31)|(?:(?:0?[1,3-9]|1[0-2])(\/|-)(?:29|30)))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^((1[012]|0?[1-9]){1}\/(0?[1-9]|[12][0-9]|3[01]){1}\/\d{2,4}\s+(1[012]|0?[1-9]){1}:(0?[1-5]|[0-6][0-9]){1}:(0?[0-6]|[0-6][0-9]){1}\s+(am|pm|AM|PM){1})$/,
                    "alertText": "* Invalid Date or Date Format",
                    "alertText2": "Expected Format: ",
                    "alertText3": "mm/dd/yyyy hh:mm:ss AM|PM or ", 
                    "alertText4": "yyyy-mm-dd hh:mm:ss AM|PM"
	            }
            };
            
        }
    };

    $.validationEngineLanguage.newLang();
    
})(jQuery);
