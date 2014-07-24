var intervalID;
var l, w, h;
var type;
var item;
var cols;
var loaded = false;

// load new slideshow content
function loadGallery(){
	if(type == "slideshow"){
		$('.slideshow').load("/_php/gallery.php?type="+type+"&item="+item+"&cols="+cols, galleryLoaded);
	} else {
		if(loaded != true){
			$('.slideshow').load("/_php/gallery.php?type="+type+"&item="+item+"&cols="+cols, canvasLoaded);
		}
	}
}

function canvasLoaded(r, s, x){
	$('#slideshow').fadeIn(10);
	var canvas = document.getElementById("sketch");
	Processing.loadSketchFromSources(canvas, [sketchName]);
}

// callback when gallery html has been loaded
function galleryLoaded(r, s, x){
	$('#slideshow').waitForImages(function() {
		$('#slideshow').fadeIn(1000);
		setTimeout(switchSlideShow(r, s, x), 2000); 
	});
}

// set columns for text description
function setCols(){
	$('#contenttext').css({'-moz-column-count':cols.toString(), '-webkit-column-count':cols.toString(), 'column-count':cols.toString()});
}

function resize(){
	var wiw = window.innerWidth;
	
	if(wiw < 620){							// 1 col
		if(cols != 1){
			w = 300;
			cols = 1;
			loadGallery();
		}
	} else if(wiw >= 620 && wiw < 930){		// 2 col
		if(cols != 2){
			w = 610;
			cols = 2;
			loadGallery();
		}
	} else if(wiw >= 930 && wiw < 1240){	// 3 col
		if(cols != 3){
			w = 920;
			cols = 3;
			loadGallery();
		}
	} else if(wiw >= 1240 && wiw < 1550){	// 4 col
		if(cols != 4){
			w = 1230;
			cols = 4;
			loadGallery();
		}
	} else if(wiw >= 1550 && wiw < 1860){	// 5 col
		if(cols != 5){
			w = 1540;
			cols = 5;
			loadGallery();
		}
	} else if(wiw >= 1860 && wiw < 2170){	// 6 col
		if(cols != 6){
			w = 1850;
			cols = 6;
			loadGallery();
		}
	} else if(wiw >= 2170 && wiw < 2480){	// 7 col
		if(cols != 7){
			w = 2160;
			cols = 7;
			loadGallery();
		}
	} else {								// 8 col
		if(cols != 8){
			w = 2470;
			cols = 8;
			loadGallery();
		}
	}
	
	loaded = true;							// on first load, mark as loaded
	
	setCols();								// set columns
	
	l = (wiw - w) / 2;						// left position
	
	if(window.innerWidth > 640){
		h = (w-20) / 2.39;					// anamorphic
	} else {
		h = ((w-20) / 16) * 9;				// widescreen
	}
	
	// set left margin and width of header, gallery, and video divs
	$('#header').css({left: l+"px", width: w});
	$('#footer').css({left: l+"px", width: w});
	$('#contentcontainer').css({left: l+"px", width: w});
	$('#gallery').css({left: l+"px", width: w, height: h+20});
	$('#galleryimage').css({width: w-20, height: h});
	$('#videocontainer').css({left: l+"px", width: w, height: ((w-20) * videoAspectRatio)+20});
	$('#video').css({width: w-20, height: (w-20) * videoAspectRatio});
	$('#videoframe').css({width: w-20, height: (w-20) * videoAspectRatio});
}

// start a slideshow of images in the gallery
$.fn.slideShow = function(timeOut) {
	var $elem = this;
	this.children().eq(0).appendTo(this).show();
	intervalID = setInterval(function(){
		$elem.children(":first").hide().appendTo($elem).fadeIn(1000);
	}, timeOut || 4000);
};

// clear the current slideshow and restart it with new images
switchSlideShow = function(response, status, xhr){
	//console.log(response);
	clearInterval(intervalID);
	$('.slideshow').slideShow();
}

// init everything
window.onload = function(event){
	resize();
}

// check for changes on window resize
window.onresize = function(event){
	resize();
}