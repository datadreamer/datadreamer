$(window).load(function(){

	/*
	$('div[class=item]').mouseover(function(){
		$(this).children('.colorthumb').children('.colorthumb').stop().animate({opacity:1},1);
	});

	$('div[class=item]').mouseleave(function(){
		$(this).children('.colorthumb').children('.colorthumb').stop().animate({opacity:0},300);
	});
	
	$('#container').masonry({
		itemSelector: '.item',
		isAnimated: true,
		isFitWidth: true
	});
	
	setInterval(resize, 33);
	
	resize();
	*/
	$('.slideshow').load("/_php/gallery.php?type=canvas&item=home&cols=3", canvasLoaded);
	
});

/*
function resize(){
	// get left position and width of masonry container
	var l = $('#container').css('marginLeft').replace('px', '');
	var w = $('#container').width() - 10;
	var h;
	if(window.innerWidth > 640){
		h = (w-20) / 2.39;				// anamorphic
	} else {
		h = ((w-20) / 16) * 9;			// widescreen
	}
	
	// set left margin and width of header and gallery divs
	$('#header').css({left: l+"px", width: w});
	$('#footer').css({left: l+"px", width: w});
	$('#gallery').css({left: l+"px", width: w, height: h+20});
	$('#galleryimage').css({width: w-20, height: h});

	// resize canvas to fit
	//resizeGalleryCanvas();
}
*/

function canvasLoaded(r, s, x){
	$('#slideshow').fadeIn(10);
	var canvas = document.getElementById("sketch");
	Processing.loadSketchFromSources(canvas, [sketchName]);
}
