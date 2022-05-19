=== WPWing - Table of Contents Block ===
Contributors: wpwing, voboghure
Tags: AMP, Gutenberg, block, TOC, Table of Contents
Requires at least: 5.8
Donate link: https://wpwing.com/
Tested up to: 6.0
Stable tag: 5.0.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a custom Table of Contents Gutenberg block.

== Description ==

Add a Table of Contents block to your posts and pages. The TOC is a nested list of links to all headings found in the post or page. To use it, simply add a block and search for "SimpleTOC" or just "TOC".

The maximum depth of the "TOC" can be configured in the blocks' sidebar among many other options. There can hide the headline "Table of Contents" and add your own by using a normal heading block.

= Features =

* No JavaScript or CSS added.
* Minimal and valid HTML output.
* Designed for Gutenberg.
* Style SimpleTOC with Gutenberg's native group styling options.
* Convert the styled group to a reusable block for future posts.
* Inherits the style of your theme.
* Support for paginated posts.
* Support for column block layouts.
* Control the maximum depth of the headings.
* Choose between an ordered and unordered HTML list.
* SEO friendly: Disable the h2 heading of the TOC block and add your own.
* Comes with English, French, Spanish, German, and Brazilian Portuguese translations.
* Works with non-Latin texts. Tested with Japanese and Arabic.
* Finds headlines in groups and reusable blocks. And in groups within reusable blocks.
* Compatible with AMP plugins.
* Rank Math support.

== Changelog ==

= 1.0.0 =
* First release

== Installation ==

SimpleTOC can be found and installed via the Plugin menu within WordPress administration (Plugins -> Add New). Alternatively, it can be downloaded from WordPress.org and installed manually...

In Gutenberg, add a block and search for "SimpleTOC" or just "TOC". Please save your content before you use the block.

== Frequently Asked Questions ==

= Why did you do this? =

Because I needed a simple plugin to do this job and decided to do it on his own. I believe that a Table of Contents does not need Javascript and additional css. Furthermore, the plugin should work out-of-the-box without any configuration.

= How do I change the TOC heading ‘Table of contents’ to some other words? =

Hide the headline in the sidebar options of SimpleTOC and add your own heading.

= How do I add SimpleTOC to all articles automatically?  =

I don’t see an easy solution at the moment. SimpleTOC is only a block that can be placed in your post. If there would be a plugin that adds blocks to every post then this would be the solution. I think this should be another separate plug-in to keep the code of SimpleTOC clean and … well, simple. Maybe someone knows of a plug-in that adds blocks automatically to all posts with some parameters and settings? What about site editing in WordPress? I think the core team is working on something like that. I will keep this post open. If I have gained more knowledge on how to solve this I will add this feature.

= How do I add a background color to SimpleTOC using Gutenberg groups? =

Select the block and select "group" in the context menu. Apply "background color", "link color" and "text color" to this group. SimpleTOC will inherit these styles. You would like to use this styled SimpleTOC group next time you write a post? Convert it to a reusable block.

= How do I add smooth scrolling? =

You can optionally add the css class "smooth-scroll" to each link the TOC. Then you can install plugin that uses these classes.

= How do I hide a single heading? =

If you really want to hide a single heading from the table of contents then add the CSS class "simpletoc-hidden" to a heading block. But first of all, think about the reason you want to hide a specific heading. Maybe you want to remove all headins of a specific depth level. Then there is an option for that in the blocks options in Gutenberg. If you think this heading should not be part of the toc maybe it is not needed in the post itself?

== Screenshots ==
1. SimpleTOC block in Gutenberg editor.
2. SimpleTOC in the post.
3. Simple but powerful. Customize each TOC as you like.
4. Control the maximum depth of the headings.
5. Style SimpleTOC with Gutenbergs native group styling options.

== Credits ==

This plugin is forked from https://github.com/pdewouters/gutentoc by pdewouters and uses code from https://github.com/shazahm1/Easy-Table-of-Contents by shazahm1

Many thanks to Tom J Nowell https://tomjn.com and and Sally CJ who both helped me a lot with my questions over at wordpress.stackexchange.com

Thanks to Quintus Valerius Soranus for inventing the Table of Contents around 100 BC.

SimpleTOC is developed on GitHub: https://github.com/mtoensing/simpletoc