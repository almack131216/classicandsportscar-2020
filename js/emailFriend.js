function URLEncode (clearString) {
	var output = '';
	var x = 0;
	clearString = clearString.toString();
	var regex = /(^[a-zA-Z0-9_.]*)/;
	while (x < clearString.length) {
		var match = regex.exec(clearString.substr(x));
		if (match != null && match.length > 1 && match[1] != '') {
			output += match[1];
			x += match[1].length;
		} else {
			if (clearString[x] == ' ')
				output += '+';
			else {
				var charCode = clearString.charCodeAt(x);
				var hexVal = charCode.toString(16);
				output += '%' + ( hexVal.length < 2 ? '0' : '' ) + hexVal.toUpperCase();
			}
			x++;
		}
	}
	return output;
}


function mailpage(SubjectString){
	tmpURL = URLEncode(window.location);

	mail_str = "mailto:?subject="+SubjectString;
	mail_str += "&body=Check out this link: ";
	mail_str += " " + tmpURL;
	location.href = mail_str;
}

//////////////////////////////////////////////////////////////////////////////////
function initiateMailFriend(){
	if(!document.getElementsByTagName) return false;
	if(!document.getElementsByTagName("a")) return false;
	if(!document.createTextNode) return false;
	//if(!window.sidebar && !document.all) return false;
	if(!document.title) return false;
	if(!location.href) return false;
	var linkText = document.createTextNode("Email a friend");
	var pageTitle = document.title;

	var links = document.getElementsByTagName("a");
	for (var i=0; i<links.length; i++){
		if(links[i].getAttribute("id") == "emailFriend"){
			var SubjectString = links[i].name;
			links[i].style.display = "block";
			links[i].onclick = function() {
									mailpage(SubjectString);return false;
								}
			links[i].appendChild(linkText);
			return false;
		}else{
			continue;
		}
	}
	return false;
}