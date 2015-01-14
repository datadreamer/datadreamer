var visible = false;
function toggleMenu(){
	if(visible){
		hideMenu();
	} else {
		showMenu();
	}
}

function showMenu(){
	$("#menu").fadeIn(300);
	visible = true;
}
function hideMenu(){
	$("#menu").fadeOut(400);
	visible = false;
}