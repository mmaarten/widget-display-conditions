=== Widget Display Conditions ===
Contributors: MaartenM
Tags: widget, display, conditions, rules, sidebar, custom, admin, interface, visibility
Requires at least: 4.0.0
Tested up to: 5.1
Stable tag: 0.2.0
Requires PHP: 5.6.27
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Manages widget display by conditions.

== Description ==

With an easy to use interface you can control on which website page you want a particular widget to be displayed.
You can use built-in conditions or create some of your own.

=== Built-in conditions ===

* post type
* post status
* post template
* post category
* post format
* post tag
* post taxonomy
* post
* page type (front page, posts page, search page, 404 page, date page, author page, top level page, parent page, child page)
* page parent
* page template
* page
* attachment
* post type archive
* taxonomy archive
* author archive
* user role
* user logged in 
* user

== Installation ==

1. Upload `widget-display-conditions` plugin folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
 
== Screenshots ==
 
1. Widget form 
2. Widget Settings
2. Widget Settings with available options

== Changelog ==

= 0.2.1 =
Release date: May 1st, 2019

* Fix - Database update was available when no previous version was installed.
* Fix - UI: Field items not sorted correctly.
* Fix - UI: Submit button style.
* Enhancement - UI: Confirmation dialog opens when unsaved changes.
* Enhancement - UI: Preloading before modal opens.

= 0.2.0 =
Release date: Apr 26th, 2019

* Enhancement - Redesigned api. (custom functionality needs to be updated)
* Enhancement - More hookable.
* Enhancement - Plugin data is removed from database on uninstall.
* Enhancement - used namespace.
* Tested in WordPress 5.1

= 0.1.8 =
Release date: Dec 6th, 2017

* Fix - Taxonomy Archive Condition: call to an undefined function is_taxonomy_archive.
* Fix - Post Taxonomy Condition: checked only for custom taxonomies. UI supplies all public taxonomies.
* Fix - 'sidebars_widgets' hook: list of widgets can also be NULL (from WordPress 4.1.0 to 4.6.0)
* Tested in WordPress 4.0 to 4.8

= 0.1.7 =
Release date: Nov 30th, 2017

* Enhancement - renamed category id ‘media’ to ’attachment’
* Enhancement - provided the ability to set the default operators
* Enhancement - added operator ’is greater than’
* Enhancement - added operator ’is less than’
* Enhancement - added operator ‘is greater or equal than’
* Enhancement - added operator ’is smaller or equal than’

= 0.1.6 =
Release date: Nov 28th, 2017

* Fix - UI: 'Page Parent' condition was not listed (non-existing category)
* Fix - 'Page Type' condition: undefined variable 'queried_object'
* Enhancement - UI: loader displayed when condition data is loading.
* Enhancement - added screenshot.

= 0.1.5 =
Release date: Nov 28th, 2017

* Fix - UI: 'Post' option did not list all posts except those of type 'page' and 'attachment'. 
* Enhancement - more OOP.
* Enhancement - updated screenshot UI.

= 0.1.4 =
Release date: Nov 26th, 2017

* Enhancement - simplified API.
* Enhancement - UI: dropdown with search function is only available from a certain amount of options 

= 0.1.3 =
Release date: Nov 26th, 2017

* Enhancement - UI: added preloader.
* Enhancement - UI: cached loaded items.
* Enhancement - UI: added author archive.
* Test - tested successfully in php 5.6.27

= 0.1.2 =
Release date: Nov 25th, 2017

* Fix - Rule 'PostAuthor' could not be loaded.

= 0.1.1 =
Release date: Nov 25th, 2017

* Fix - Invalid WordPress readme file.
* Enhancement - Grouped rules by category.

= 0.1.0 =
Release date: Nov 22nd, 2017

* First release
