
var pattern_email = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w+)+$/; //email format
var pattern_password = /^(?=.*[A-Za-z])(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/;	//Password Format, minimum 8 characters, case-insensitive, no whitespace, one special character
var pattern_name = /^\S*\w+(\s\w+)*\S*$/;	//no leading or trailing whitespace, single whitespace allowed in the middle

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