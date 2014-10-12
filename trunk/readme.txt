=== Sticky Menu (or Anything!) on Scroll ===
Contributors: senff
Tags: plugin, sticky, menu, scroll, element
Plugin URI: http://www.senff.com/plugins/sticky-anything-wp
Requires at least: 3.6
Tested up to: 4.0
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Sticky Menu (Or Anything!) On Scroll will let you choose any element on your page that will be "sticky" at the top once you scroll down.

== Description ==

= Summary =

The Sticky Menu (Or Anything) On Scroll plugin for WordPress allows you to make any element on your pages "sticky" as soon as it hits the top of the page when you scroll down. Although this is commonly used to keep menus at the top of your page, the plugin allows you to make ANY element sticky (such as a Call To Action box, a logo, etc.)

A little bit of basic HTML/CSS knowledge is required. You just need to know how to pick the right selector for the element you want to make sticky, and you need to be sure it's a unique selector. Sometimes a simple selector like "nav", "#main-menu", ".menu-main-menu-1" is enough. Other times you will have to be more detailed and use a more specific selector such as "header > ul:first-child" or "nav.top .menu-header ul.main".

= Features =

* **Any element can stick**: although common use is for navigation menus, the plugin will let you pick any unique element with a name, class or ID to stick at the top of the page once you scroll past it. Use it for a sidebar, Call-to-action box, banner ad, etc.
* **Positioning from top**: optionally, you can add any amount of space between the sticky element and the top of the page, so that the element is not always stuck at the "ceiling" of the page.
* **Z-index**: in case there are other elements on the page that obscure or peek through your sticky element, you can add a Z-index easily.
* **Debug Mode:** find out possible reasons why your element doesn't stick by switching on Debug Mode, and error messages will appear in your browser's console.


== Installation ==

1. Upload the "sticky-menu-or-anything" directory to your "wp-content/plugins" directory.
1. In your WordPress admin, go to PLUGINS and activate "Sticky Menu (or Anything!)"
1. Go to SETTINGS - STICKY MENU (OR ANYTHING!)
1. Pick the element you want to make sticky


== Frequently Asked Questions ==

= I selected a class/ID in the settings screen, but the element doesn't stick when I scroll downs. Why not? =
Make sure that if you select the element by its classname, it is preceded by a dot (e.g. ".main-menu"), and if you select it by its ID, that it's preceded by a pound/hash/number sign (e.g. "#main-menu").  Also, make sure there is only ONE element on the page with the selector you're using. If there is none, or more than one element that matches your selector, nothing will happen.

= Still doesn't work. What could be wrong? =
Check the "Debug Mode" checkbox in the plugin's settings. Reload the page and you may see errors in your browser's console window. If you've used a selector that returns zero elements on the page, OR more than one, it will be shown.

= Is it possible to have multiple sticky elements? =
The current version only allows one sticky element. Having more than one may clutter up your page and confuse the user (imagine having a menu stuck at the top, and a banner stuck on the left, and another thing on the right, etc.). Having said that, this functionality may be added to a future version.

= I'll need more help please! = 
The plugin's own page can be found [here](http://www.senff.com/plugins/sticky-anything-wp).

For any other issues, please use the [WordPress.org forum](https://wordpress.org/support/).


== Screenshots ==

1. Settings screen


== Changelog ==

= 1.0 =
* initial release (using v1.1 of the standalone jQuery plugin)


== Upgrade Notice ==

= 1.0 =
Initial release of the plugin