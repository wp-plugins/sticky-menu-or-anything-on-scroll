/**
* @preserve Sticky Anything 1.2.2 | @senff | GPL2 Licensed
*/

(function ($) {

  $.fn.stickThis = function(options) {

    var settings = $.extend({
      // Default
      top: 0,
      minscreenwidth: 0, 
      maxscreenwidth: 99999, 
      zindex: 1, 
      dynamicmode: false,
      debugmode: false
      }, options );

    var numElements = $(this).length;

    if (numElements < 1) {
      // There are no elements on the page with the called selector.
      if(settings.debugmode == true) {
        console.error('STICKY ANYTHING DEBUG: There are no elements with the selector/class/ID you selected.');
      }
    } else if (numElements > 1) {
      // This is not going to work either. You can't make more than one element sticky. They will only get in eachother's way.
      // Make sure that you use an selector that applies to only ONE SINGLE element on the page.
      // Want to find out quickly where all the elements are that you targeted? Uncomment the next line to debug.
      // $(this).css('border','solid 3px #00ff00');
      if(settings.debugmode == true) {
        console.error('STICKY ANYTHING DEBUG: There is more than one element with the selector/class/ID you selected. You can only make ONE element sticky.');
      }      
    } else {
      $(this).addClass('original');
      if(settings.dynamicmode != true) {
        // Create a clone of the menu, right next to original (in the DOM) on initial page load
        createClone(settings.top,settings.zindex);
      }
      checkElement = setInterval(function(){stickIt(settings.top,settings.minscreenwidth,settings.maxscreenwidth,settings.zindex,settings.dynamicmode)},10);
    }

    return this;
  };


  function stickIt(stickyTop,minwidth,maxwidth,stickyZindex,dynamic) {

    var orgElementPos = $('.original').offset();
    orgElementTop = orgElementPos.top;               

    // Calculating actual viewport width
    var e = window, a = 'inner';
    if (!('innerWidth' in window )) {
      a = 'client';
      e = document.documentElement || document.body;
    }
    viewport = e[ a+'Width' ];

    if (($(window).scrollTop() >= (orgElementTop - stickyTop)) && (viewport >= minwidth) && (viewport <= maxwidth)) {

      // scrolled past the original position; now only show the cloned, sticky element.

      // Cloned element should always have same left position and width as original element.     
      orgElement = $('.original');
      coordsOrgElement = orgElement.offset();
      leftOrgElement = coordsOrgElement.left;  
      widthOrgElement = orgElement.css('width');
      // If padding is percentages, convert to pixels
      paddingOrgElement = [orgElement.css('padding-top'), orgElement.css('padding-right'), orgElement.css('padding-bottom'), orgElement.css('padding-left')];
      paddingCloned = paddingOrgElement[0] + ' ' + paddingOrgElement[1] + ' ' + paddingOrgElement[2] + ' ' + paddingOrgElement[3];

      if( (dynamic == true) && ($('.cloned').length < 1)     ) {
        // DYNAMIC MODE: if there is no clone present, create it right now
        createClone(stickyTop,stickyZindex);
      }

      $('.cloned').css('left',leftOrgElement+'px').css('top',stickyTop+'px').css('width',widthOrgElement).css('padding',paddingCloned).show();
      $('.original').css('visibility','hidden');
    } else {
      // not scrolled past the menu; only show the original menu.
      if(dynamic == true) {
        $('.cloned').remove();
      } else {
        $('.cloned').hide();
      }
      $('.original').css('visibility','visible');
    }
  }

  function createClone(cloneTop,cloneZindex) {
    $('.original').clone().insertAfter($('.original')).addClass('cloned').css('position','fixed').css('top',cloneTop+'px').css('margin-top','0').css('margin-left','0').css('z-index',cloneZindex).removeClass('original').hide();
  }

}(jQuery));