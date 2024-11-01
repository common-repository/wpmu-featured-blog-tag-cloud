=== WPMU Featured Blog Tag Cloud ===
Contributors: DeannaS, kgraeme
Website: http://deannaschneider.wordpress.com
Tags: widget, shortcode, tags
Requires at least: 2.8
Tested up to: 3.0
Stable tag: trunk

A widget that allows for a custom tag cloud and creates a shortcode for using a tag cloud on a page.

== Description ==

*CETS Tag Cloud* is a widget that allows users to create a tag cloud based off of any blog in a WPMU install. It allows users to configure the font size, unit of measure, number of tags, sort, order, included tags, and excluded tags. It also allows users to generate a link to a page, on which a [wp\_tag\_cloud] short code can be used for a fuller version of the tag cloud.

== Installation ==

1. Upload the `cets_tag_cloud.php` file to your `/wp-content/plugins directory` or `wp-content/mu-plugins directory`
2. In your WordPress dashboard, head over to the *Plugins* section.
3. Activate *Customized Tag Cloud*.
4. Add *Customized Tag Cloud* widget to your sidebar & configure.
5. If desired, create a page, and add a [cets\_tag\_cloud] shortcode. The following attributes can be passed to the shortcode:

[cets\_tag\_cloud
      smallest=[number]
      largest=[number]
      unit=[pt|px|em|%]
      number=[number]
      format=[flat|list]
      orderby=[name|count]
      order=[ASC|DESC|RAND]
      exclude=[csv of tags to exclude]
      include=[csv of tags to include]
      ]





== Screenshots ==

1. Widget set-up view.
2. Widget output view with defaults set and link to more page.

== Changelog ==

1.2.1 - updating version number and structure for Wordpress.org auto install/activate

