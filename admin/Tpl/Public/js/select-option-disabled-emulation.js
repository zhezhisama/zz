/****************************************************************
* Author:	Alistair Lattimore
* Website:	http://www.lattimore.id.au/
* Contact:	http://www.lattimore.id.au/contact/
*			Errors, suggestions or comments
* Date:		30 June 2005
* Version:	1.0
* Purpose:	Emulate the disabled attributte for the <option> 
*			element in Internet Explorer.
* Use:		You are free to use this script in non-commercial
*			applications. You are however required to leave
*			this comment at the top of this file.
*
*			I'd love an email if you find a use for it on your 
*			site, though not required.
****************************************************************/

//window.onload = function() {
if (navigator.appVersion.indexOf("MSIE 5.5") >= 0 || navigator.appVersion.indexOf("MSIE 6.0") >= 0 || navigator.appVersion.indexOf("MSIE 7.0") >= 0){
	window.onload = ReloadSelectElement;
}
function ReloadSelectElement(){
	if (document.getElementsByTagName) {
		var s = document.getElementsByTagName("select");

		if (s.length > 0) {
			window.select_current = new Array();

			for (var i=0, select; select = s[i]; i++) {
				select.onfocus = function(){ window.select_current[this.id] = this.selectedIndex; }
				select.onchange = function(){ restore(this); }
				emulate(select);
			}
		}
	}
}

function restore(e) {
	if (e.options[e.selectedIndex].disabled) {
		e.selectedIndex = window.select_current[e.id];
	}
}

function emulate(e) {
	for (var i=0, option; option = e.options[i]; i++) {
		if (option.disabled) {
			option.style.color = "graytext";
		}
		else {
			option.style.color = "menutext";
		}
	}
}