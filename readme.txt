=== Truth ===
Contributors: UaMV
Donate link: http://vandercar.net/wp
Tags: bible, scripture, truth, verse, version, youversion, passage, christian
Requires at least: 3.1
Tested up to: 4.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically links to Bible verses throughout your site.

== Description ==

The Truth plugin will scan the content of your posts, pages, comments, and widgets for references to Bible verses and can generate two types of links from these references.

= bibles.org =
Truth utilizes the [highlighter tool](http://tools.bibles.org/highlighter.html "bibles.org Highlighter Tool") from [bibles.org](http://bibles.org "BibleSearch") to generates links that, when clicked, will display a modal containing the verse that has been referenced. The following Bible versions are currently supported:

* Amplified Bible (AMP)
* The Holy Bible: American Standard Version (ASV)
* Contemporary English Version (US Version) (CEV)
* Contemporary English Version (US Version) (CEVD)
* Contemporary English Version (CEVUS06)
* English Standard Version (ESV)
* Good News Translation (US Version) (GNTD)
* King James Version with Apocrypha, American Edition (KJVA)
* The Message (MSG)
* New American Bible, Revised Edition (NABRE)
* New American Standard Bible (NASB)
* New International Version (NIV)
* New Living Translation (NLT)
* New Revised Standard Version (NRSV)
* Revised Standard Version (RSV)
* Revised Version 1885 (RV)
* World English Bible (WEB)
* World Messianic Bible (WMB)
* World Messianic Bible British Edition (WMBBE)

= YouVersion =
Truth can also generate links to [YouVersion](http://bible.com "YouVersion"). When using YouVersion, a shortcode allows override of the default Bible version. There is also an option to disable auto-generation of links and use only the shortcode. The following Bible versions are currently supported:

* Amplified Bible (AMP)
* American Standard Version (ASV)
* Common English Bible (CEB)
* Contemporary English Version (CEVUS06)
* Catholic Public Domain Version (CPDV)
* Darby Translation 1890 (Darby)
* Douay Rheims (DRA)
* English Standard Version (ESV)
* Good News Bible (GNB)
* GOD'S WORD Translation (GWT)
* Good News Translation (GNT)
* Holman Christian Standard Bible (HCSB)
* King James Version (KJV)
* The Message (MSG)
* New American Standard Bible (NASB)
* New Century Version (NCV)
* New English Translation (NET)
* New International Reader's Version (NIRV)
* New International Version (NIV)
* New King James Version (NKJV)
* New Living Translation (NLT)
* Orthodox Jewish Bible (OJB)
* Tree of Life Bible (TLV)
* World English Bible (WEB)
* La Palabra (BLPH)
* La Bilia de las Americas (LBLA)
* Nueva Versión Internacional (NVI)
* Biblia Reina Valera 1960 (RVR60)

View an implementation of the plugin [here](http://wmpl.org/articles "WMPL Articles").

== Installation ==

1. Upload the `truth` directory to `/wp-content/plugins/`
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure settings on the 'Settings' > 'Reading' admin page.

== Screenshots ==

1. Bibles.org Highlighter Settings
1. YouVersion Settings

== Frequently Asked Questions ==

= Can I display a pop-up bubble showing the text of the passage? =

As of Truth v2.0, you can display a modal when utilizing Bibles.org Highlighter as your Bible service. Due to the Terms of Use for YouVersion and lack of an API, the plugin is not able to publicly display works based on YouVersion.

= How do I define my shortcode? =

`[truth version="nkjv"]2 Corinthians 1:20[/truth]`

= Can the Truth plugin be used on a multisite install? =

Yes. Upload `truth.php` to `/wp-content/mu-plugins/` (auto-enables use on every site) or upload the `truth` directory to `/wp-content/plugins/` and manage through your plugin menu.

= How do I authorize use on all sites of a multisite install? =

In the plugin file (line 22), set `define( TRUTH_AUTH_ALL, TRUE );`

== Changelog ==

= 2.1 =
* Fix for fatal error. Files were not uploaded to repository. Breaking sites. Sorry!

= 2.0 =
* Added support for modal display of verses via bibles.org highlighter
* Rebuilt codebase.

= 1.7 =
* Remove filters when using WordPress Front-End Editor

= 1.6 =
* Readme edit

= 1.5 =
* Fix for Good News Bible version
* Marked compatibility with WP 3.8
* Added information notices
* Readme edits

= 1.4 =
* Author, plugin, and giving URI edits
* Enables validator for outbound link generation

= 1.3 =
* Restricts unwanted regex matches.
* Adds option for link target.

= 1.2 =
* Added options for appending version to shortcode.
* Added support for spanish versions.

= 1.1 =
* Fixed regex match bug.
* Added capability to match single chapter reference.
* Added option to disable auto-generation of links.

= 1.0 =
* Initial Release

== Upgrade Notice ==

= 2.1 =
* Fix for fatal error. Files were not uploaded to repository. Breaking sites. Sorry!

= 2.0 =
* Please check settings after this update which adds support for modal display of verses via bibles.org highlighter.
* Codebase has been rebuilt.

= 1.7 =
* Remove filters when using WordPress Front-End Editor

= 1.6 =
* Readme edit

= 1.5 =
* Fix for Good News Bible version
* Marked compatibility with WP 3.8
* Readme edits

= 1.4 =
* Author, plugin, and giving URI edits
* Enables validator for outbound link generation

= 1.3 =
* Restricts unwanted regex matches.
* Adds option for link target.

= 1.2 =
* Added options for appending version to shortcode.
* Added support for spanish versions.

= 1.1 =
* Fixed regex match bug.
* Added capability to match single chapter reference.
* Added option to disable auto-generation of links.

= 1.0 =
* Initial Release
