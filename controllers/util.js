
var pattern_email = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w+)+$/; //email format
var pattern_password = /^(?=.*[A-Za-z])(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/;	//Password Format, minimum 8 characters, case-insensitive, no whitespace, one special character
var pattern_username = /^[a-zA-Z0-9]+([a-zA-Z0-9]*(_|-)*[a-zA-Z0-9]*)*[a-zA-Z0-9]+$/;	//no leading or trailing whitespace

var BUGREPORT_MAX_CHARACTERS = 140;

////////////////////////////////////////////////////////////
///////////////DEV MODE////////////////////////////////////
var development_mode = true;  //Comment for production mode
//////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////

function validate(value, pattern){
	if (pattern.test(value)) return true;
	else return false;
	return true;
}

function log_event(message, value) {
  if (development_mode) {
    console.log(message);
    console.log(value);
  }
}