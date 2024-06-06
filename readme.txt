=== Truth ===
Contributors: UaMV
Donate link: https://typewheel.xyz/give/
Tags: bible, scripture, truth, verse, version, youversion, passage, christian
Requires PHP: 7.4
Requires at least: 3.1
Tested up to: 6.5.4
Stable tag: 2.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically links to Bible verses throughout your site.

== Description ==

The Truth plugin will scan the content of your posts, pages, comments, and widgets for references to Bible verses and can generate two types of links from these references.

= bibles.org =
Truth utilizes the [Global Bible Widget](https://bibles.org/widget "bibles.org Global Bible Widget") from [The Global Bible Project](https://global.bible "Global.Bible") to generate links that, when clicked, will display a modal containing the verse that has been referenced. The following Bible versions are currently supported:

* Amplified Bible (AMP)
* Contemporary English Version (CEV)
* Contemporary English Version (Anglicised) 2012 (CEVD)
* English Standard Version (ESV)
* Good News Translation (US Version) (GNTD)
* King James Version, American Edition (KJVA)
* King James Version with Apocrypha, American Edition (KJVA)
* New American Standard Bible (NASB)
* New International Version (NIV)
* New Revised Standard Version (NRSV)
* Good News Bible (Anglicised) 1994 (GNB)
* Good News Bible (Anglicised) Catholic Edition 1994 (GNB)

= YouVersion =
Truth can also generate links to [YouVersion](http://bible.com "YouVersion"). When using YouVersion, a shortcode allows override of the default Bible version. There is also an option to disable auto-generation of links and use only the shortcode. The following Bible versions are currently supported:

* Amplified Bible (AMP)
* American Standard Version (ASV)
* Berean Study Bible (BSB)
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
* New Revised Standard Version (NRSV)
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

1. Global.Bible Widget Settings
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

= 2.8 =
* Fixes YouVersion link structure
* Fixes various PHP undefined errors

= 2.7 =
* Adds BSB to available YouVersion translations

= 2.6 =
* Fix fatal error

= 2.5 =
* Fix for linking non-default Bible version in shortcode
* Added NRSV to available YouVersion translations

= 2.4 =
* Necessary migration form bibles.org Highlighter to Global.Bible Widget
* Removed unsupported version in Global.Bible

= 2.3 =
* URL fix for YouVersion links

= 2.2 =
* Added Bible Highlighter option to target specific DOM ids for instances when reference is not identified.
* Added delayed review prompt on Reading Settings screen.

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

= 2.4 =
* We've made a necessary migration form bibles.org Highlighter to Global.Bible Widget. If previously using bibles.org modals, please check and save your settings after this update

= 2.2 =
* Adds Bible Highlighter option to target specific DOM ids for instances when reference is not identified.
* Adds delayed review prompt on Reading Settings screen.

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
