jQuery.fn.scrollTo = function(elem) {
	$(this).scrollTop($(this).scrollTop() - $(this).offset().top + $(elem).offset().top);
	return this;
};

$('audio').bind('contextmenu', function() {return false;});
$('video').bind('contextmenu', function() {return false;});