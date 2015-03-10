/**
* @preserve Sticky Anything 1.2.4 | @senff | GPL2 Licensed
*/

(function($) {
	$(document).ready(function($) {

		$(sticky_anything_engage.element).stickThis({
			top:sticky_anything_engage.topspace,
			minscreenwidth:sticky_anything_engage.minscreenwidth,
			maxscreenwidth:sticky_anything_engage.maxscreenwidth,
			zindex:sticky_anything_engage.zindex,
			dynamicmode:sticky_anything_engage.dynamicmode,
			debugmode:sticky_anything_engage.debugmode
		});

	});
}(jQuery));

