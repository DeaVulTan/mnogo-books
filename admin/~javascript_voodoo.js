
var flag, add_form, del_form;

script = getScriptName();

switch(script) {
	case "book_form.php" 		: { add_form = 'add_book'; focused_select_id="book_list"; del_form = 'del_book'; break; }
	case "author_form.php"	:	{ add_form = 'add_author'; focused_select_id=""; del_form = 'del_author'; break; }
	case "genre_form.php"		: { add_form = 'add_genre';  focused_select_id=""; del_form = 'del_genre'; break; }
}

function getScriptName() {

	url = new String(document.URL);
	lastslash = url.lastIndexOf('/');
	dotphp = url.lastIndexOf('.php');
	if (lastslash) {
		shortname = url.substring(lastslash + 1, dotphp+4);
		return shortname;
	} 
	else return "";
}

function canModify() {

		url = new String(document.URL);
		is_checked = url.lastIndexOf('id=');
		if (is_checked>0) return true;
			else return false;
}


function go(there) { // подтверждение выхода
	
	if (confirm("Уверен?")) 
		location.href=there;
}

function check_form(form_name) {

	if (confirm("Точно удалить?"))
		document.forms[form_name].submit();
}


function getData(t) {

	if (flag == 'change')  { // change_stuff
		url = getScriptName();
		location.href=url+"?id="+t.value+"&checked=change";
	}
}

function kick_form(form_name, state) { 	// 	вкл/выкл форму
	
	element = document.forms[form_name].elements;
  for (i=0; i < element.length; i++) 
  	element[i].disabled = !state;
}

function clear_form(form_name) {
	element = document.forms[form_name].elements;
	for (i=0; i < element.length; i++) 
	// это для book_form.php
	if (element[i].name=='uri') element[i].value="http://book.stream.uz/Books/";
		else if ((element[i].type!=='submit') && (element[i].type!=='hidden'))
			element[i].value = '';	
}

function show_form(form_name) {
	element = document.forms[form_name].elements;
  for (i=0; i < element.length; i++) {
		alert("Тип: " + element[i].type + "\nЗначение: " + element[i].value);
	}
}

function add_stuff() {

	flag = 'add';
// document.body.style.color = 'navy';
	kick_form(del_form, false);
	kick_form(add_form, true);
	
	del_button = document.getElementById("del_button");
	act = document.getElementById("hidden_add");
	act.value = "add";
	
	clear_form(add_form);
}

function change_stuff() {

	flag = 'change';
// document.body.style.color = 'yellow';
	kick_form(del_form, true);
	del_button = document.getElementById("del_button");
	del_button.disabled = true;
	
	document.forms[add_form].reset();
	if (canModify()) {
		kick_form(add_form, true);
	}
	else kick_form(add_form, false);
	if  (focused_select_id=="book_list") {
	  element = document.getElementById("book_list");
	  element.focus();
	}

	act = document.getElementById("hidden_add");
	act.value = "change";
}

function del_stuff() {

	flag = 'del';
	kick_form(add_form, false);
	kick_form(del_form, true);	
	if  (focused_select_id=="book_list") {
	  element = document.getElementById("book_list");
	  element.focus();
	}
	clear_form(add_form);
}







