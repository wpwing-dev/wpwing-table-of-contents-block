#!/bin/sh
set -eu

cd /var/www/html

if ! wp core is-installed --allow-root; then
	wp core install \
		--url=https://toc.local \
		--title="WPWing TOC Development" \
		--admin_user=admin \
		--admin_password=password \
		--admin_email=admin@example.com \
		--skip-email \
		--allow-root
fi

wp plugin activate wpwing-table-of-contents-block --allow-root

create_sample_content() {
	post_type="$1"
	slug="$2"
	title="$3"
	content="$4"

	if test -n "$(wp post list --post_type="$post_type" --name="$slug" --format=ids --allow-root)"; then
		return
	fi

	wp post create \
		--post_type="$post_type" \
		--post_name="$slug" \
		--post_title="$title" \
		--post_status=publish \
		--post_content="$content" \
		--porcelain \
		--allow-root >/dev/null
}

create_sample_content page wpwing-toc-demo "WPWing TOC Demo" '<!-- wp:wpwing/toc /-->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Getting started</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>This page demonstrates the Table of Contents block in the local development environment.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Add the block</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Choose a display style</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Test interactive options</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Native collapsible mode</h3>
<!-- /wp:heading -->'

create_sample_content post wpwing-toc-article "A Sample Article for Testing" '<!-- wp:paragraph -->
<p>This sample post contains enough structure to test anchors, nested headings, and responsive TOC styling.</p>
<!-- /wp:paragraph -->

<!-- wp:wpwing/toc {"collapsible":true,"collapse_mode":"native","start_collapsed":true} /-->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Planning the article</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Collect the requirements</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Outline the sections</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Reviewing the result</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Check keyboard navigation</h3>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Check mobile layout</h3>
<!-- /wp:heading -->'

create_sample_content post wpwing-toc-field-guide "Field Guide to Building a Better TOC" '<!-- wp:paragraph -->
<p>This longer sample gives the TOC enough vertical distance to test anchor scrolling in a realistic article layout. Use the links near the top, then compare the URL fragment with the heading in view.</p>
<!-- /wp:paragraph -->

<!-- wp:wpwing/toc {"use_ol":true,"min_headings":3,"show_back_to_top":true} /-->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Start with a clear structure</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A useful article starts with a structure that readers can scan quickly. Group related ideas under a small number of clear sections and use subheadings when a section grows beyond a few paragraphs.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Map the reader journey</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Before writing, decide what a reader needs to understand first, what can wait until later, and where a practical example will make the explanation easier to follow.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Name sections precisely</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Specific headings create useful landmarks. They also make the generated table of contents more meaningful than a series of vague labels such as Overview or Details.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Write content that is easy to scan</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Short paragraphs, descriptive links, and consistent spacing help readers move through a long page. The best structure supports both careful reading and quick return visits.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Use examples and lists</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Examples give abstract guidance a concrete shape. Lists are useful for steps, but they should support the explanation rather than replace it.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Keep related details together</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Readers should not need to jump between distant parts of the article to understand one idea. Keep definitions, decisions, and their consequences close together.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Review the finished page</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A final review should check the outline as well as the prose. Follow every TOC link, resize the browser, and make sure the heading that receives focus is not hidden under a fixed header.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Check anchors on narrow screens</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Mobile layouts often reveal wrapping and spacing problems that are easy to miss on a wide screen. Test the first, middle, and final entries in the TOC.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Check the return link</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>After jumping to a lower section, use the back-to-top link to confirm that the page returns to the expected position and that the browser history remains sensible.</p>
<!-- /wp:paragraph -->'

create_sample_content post wpwing-toc-performance "Performance Notes for Long Articles" '<!-- wp:paragraph -->
<p>This second long post is intended for comparing the default TOC output with different heading depths and spacing. Its sections are deliberately separated by content so anchor movement is easy to see.</p>
<!-- /wp:paragraph -->

<!-- wp:wpwing/toc {"collapsible":true,"collapse_mode":"js","start_collapsed":false,"remove_indent":true} /-->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Measure before changing the layout</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Performance work starts with a representative page. Record how the page behaves before changing styles or adding interactive features, then compare the same workflow afterward.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Observe the initial render</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Look for layout movement, delayed styling, and unexpected requests. A long article makes small changes easier to notice because more content participates in the layout.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Test the reading path</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Scroll from the beginning to the end and then use several TOC items in reverse order. This checks both ordinary scrolling and direct navigation to later sections.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Keep interaction lightweight</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>A table of contents should remain useful when scripts are delayed or disabled. Prefer semantic links and native browser behavior wherever they provide the needed experience.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Compare collapse choices</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Compare the JavaScript toggle on this post with the native collapsible mode on the sample article. Check the initial state, keyboard interaction, and visible focus treatment.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Preserve useful landmarks</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Even a collapsed list should expose a clear control and preserve stable heading IDs. This makes direct links and browser navigation reliable for returning visitors.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Validate the result on every device</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Finish by checking desktop and mobile widths, right-to-left presentation when available, and pages with long or short headings. The same content should remain readable in each case.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Check the deepest link</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Jump to the final subsection from the TOC and confirm that the target heading is visible at the top of the reading area rather than hidden behind navigation.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Compare page lengths</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Use the shorter demo page and these longer posts together. This gives the development environment both compact and scroll-heavy content for manual and automated checks.</p>
<!-- /wp:paragraph -->'