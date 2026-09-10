// Captures WordPress.org listing screenshots and regenerates readme.txt's Screenshots section from the same list. Run via `make screenshots`.
'use strict';

const fs = require( 'fs' );
const path = require( 'path' );
const { chromium } = require( 'playwright' );

const BASE_URL = process.env.WPWING_TOC_BASE_URL || 'https://toc.local';
const REPO_ROOT = path.join( __dirname, '..' );
const OUT_DIR = path.join( REPO_ROOT, 'wporg-assets' );
const README_PATH = path.join( REPO_ROOT, 'src', 'readme.txt' );
const VIEWPORT = { width: 1280, height: 800 };

function shotPath( file ) {
	return path.join( OUT_DIR, file );
}

async function login( page ) {
	await page.goto( `${ BASE_URL }/wp-login.php` );
	if ( page.url().includes( 'wp-login.php' ) ) {
		await page.fill( '#user_login', 'admin' );
		await page.fill( '#user_pass', 'password' );
		await page.click( '#wp-submit' );
		await page.waitForURL( /wp-admin/ );
	}
}

async function getItem( page, type, slug ) {
	const res = await page.request.get(
		`${ BASE_URL }/?rest_route=/wp/v2/${ type }&slug=${ slug }`
	);
	const [ item ] = await res.json();
	if ( ! item ) {
		throw new Error(
			`No published ${ type } found with slug "${ slug }". Run "make dev" first.`
		);
	}
	return item;
}

// The block editor shows a one-time "Welcome to the editor" tour on a fresh profile.
async function dismissWelcomeGuide( page ) {
	const modal = page.locator( '.components-modal__screen-overlay' );
	try {
		await modal.waitFor( { state: 'visible', timeout: 4000 } );
		await page.keyboard.press( 'Escape' );
		await modal.waitFor( { state: 'hidden', timeout: 4000 } );
	} catch {
		// No welcome guide this run - already dismissed for this browser profile.
	}
}

// Other active plugins and core print their own admin notices, which otherwise land in a
// listing screenshot and advertise somebody else's plugin. Ours only appear after an import
// POST, which no shot performs, so hiding the lot is safe.
async function hideAdminNotices( page ) {
	await page.addStyleTag( {
		content:
			'#wpbody-content .notice, #wpbody-content .updated, #wpbody-content .error, .update-nag { display: none !important; }',
	} );
}

// Bounding boxes from a page.frameLocator() are already page-relative, so a block inside the
// editor's iframe canvas and its toolbar in the top-level document can share one clip rect.
function unionClip( ...boxes ) {
	const x1 = Math.max( 0, Math.min( ...boxes.map( ( b ) => b.x ) ) - 16 );
	const y1 = Math.max( 0, Math.min( ...boxes.map( ( b ) => b.y ) ) - 16 );
	const x2 = Math.max( ...boxes.map( ( b ) => b.x + b.width ) ) + 16;
	const y2 = Math.max( ...boxes.map( ( b ) => b.y + b.height ) ) + 16;
	return { x: x1, y: y1, width: x2 - x1, height: y2 - y1 };
}

const SHOTS = [
	{
		file: 'screenshot-1.png',
		caption: 'Editor with Table of Contents block',
		context: 'admin',
		capture: async ( page ) => {
			const item = await getItem( page, 'pages', 'wpwing-toc-demo' );
			await page.goto(
				`${ BASE_URL }/wp-admin/post.php?post=${ item.id }&action=edit`
			);
			await dismissWelcomeGuide( page );
			const frame = page.frameLocator( 'iframe[name="editor-canvas"]' );
			await frame.locator( '[data-type="wpwing/toc"]' ).first().waitFor();
			await page.screenshot( { path: shotPath( 'screenshot-1.png' ) } );
		},
	},
	{
		file: 'screenshot-2.png',
		caption: 'Block inserter section',
		context: 'admin',
		capture: async ( page ) => {
			const item = await getItem( page, 'pages', 'wpwing-toc-demo' );
			await page.goto(
				`${ BASE_URL }/wp-admin/post.php?post=${ item.id }&action=edit`
			);
			await dismissWelcomeGuide( page );
			await page.locator( 'button[aria-label="Block Inserter"]' ).click();
			const search = page.locator(
				'.block-editor-inserter__search input[type="search"]'
			);
			await search.waitFor();
			await search.fill( 'toc' );
			await page
				.locator(
					'.block-editor-block-types-list__item:has-text("Table Of Contents")'
				)
				.waitFor();
			await page.screenshot( { path: shotPath( 'screenshot-2.png' ) } );
		},
	},
	{
		file: 'screenshot-3.png',
		caption: 'Block editor section',
		context: 'admin',
		capture: async ( page ) => {
			const item = await getItem( page, 'pages', 'wpwing-toc-demo' );
			await page.goto(
				`${ BASE_URL }/wp-admin/post.php?post=${ item.id }&action=edit`
			);
			await dismissWelcomeGuide( page );
			const frame = page.frameLocator( 'iframe[name="editor-canvas"]' );
			const block = frame.locator( '[data-type="wpwing/toc"]' ).first();
			await block.click();
			const toolbar = page
				.locator(
					'.block-editor-block-contextual-toolbar, .block-editor-block-toolbar'
				)
				.first();
			await toolbar.waitFor();
			const clip = unionClip(
				await block.boundingBox(),
				await toolbar.boundingBox()
			);
			await page.screenshot( {
				path: shotPath( 'screenshot-3.png' ),
				clip,
			} );
		},
	},
	{
		file: 'screenshot-4.png',
		caption: 'Block settings section',
		context: 'admin',
		capture: async ( page ) => {
			const item = await getItem( page, 'pages', 'wpwing-toc-demo' );
			await page.goto(
				`${ BASE_URL }/wp-admin/post.php?post=${ item.id }&action=edit`
			);
			await dismissWelcomeGuide( page );
			const frame = page.frameLocator( 'iframe[name="editor-canvas"]' );
			await frame.locator( '[data-type="wpwing/toc"]' ).first().click();
			const sidebar = page.locator(
				'.interface-interface-skeleton__sidebar'
			);
			await sidebar.waitFor();
			await sidebar.screenshot( {
				path: shotPath( 'screenshot-4.png' ),
			} );
		},
	},
	{
		file: 'screenshot-5.png',
		caption: 'Output block with default settings',
		context: 'front',
		capture: async ( page ) => {
			const item = await getItem( page, 'pages', 'wpwing-toc-demo' );
			await page.goto( item.link );
			const toc = page.locator( '.wp-block-wpwing-toc' ).first();
			await toc.waitFor();
			await toc.screenshot( { path: shotPath( 'screenshot-5.png' ) } );
		},
	},
	{
		file: 'screenshot-6.png',
		caption: 'Output block using Group and background color',
		context: 'front',
		capture: async ( page ) => {
			const item = await getItem( page, 'pages', 'wpwing-toc-styled' );
			await page.goto( item.link );
			const group = page
				.locator( '.wp-block-group.has-background' )
				.first();
			await group.waitFor();
			await group.screenshot( { path: shotPath( 'screenshot-6.png' ) } );
		},
	},
	{
		file: 'screenshot-7.png',
		caption: 'TOC Block settings screen - global defaults and auto-insert',
		context: 'admin',
		capture: async ( page ) => {
			await page.goto(
				`${ BASE_URL }/wp-admin/admin.php?page=wpwing-toc`
			);
			await page.locator( 'h2:has-text("New block defaults")' ).waitFor();
			await hideAdminNotices( page );
			await page.screenshot( { path: shotPath( 'screenshot-7.png' ) } );
		},
	},
	{
		file: 'screenshot-8.png',
		caption: 'One-click settings import from LuckyWP Table of Contents',
		context: 'admin',
		// The Makefile seeds fake lwptoc_* options first so the import section actually renders.
		capture: async ( page ) => {
			await page.goto(
				`${ BASE_URL }/wp-admin/admin.php?page=wpwing-toc`
			);
			const heading = page.locator( 'h2:has-text("Import settings")' );
			const form = page.locator(
				'form:has(input[value="wpwing_toc_import_luckywp"])'
			);
			await form.waitFor();
			await hideAdminNotices( page );
			const clip = unionClip(
				await heading.boundingBox(),
				await form.boundingBox()
			);
			await page.screenshot( {
				path: shotPath( 'screenshot-8.png' ),
				clip,
			} );
		},
	},
];

function updateReadme() {
	const readme = fs.readFileSync( README_PATH, 'utf8' );
	const list = SHOTS.map(
		( shot, i ) => `${ i + 1 }. ${ shot.caption }`
	).join( '\n' );
	const pattern = /== Screenshots ==\n\n(?:\d+\..*\n)*\n/;
	if ( ! pattern.test( readme ) ) {
		throw new Error(
			'Could not find a "== Screenshots ==" section in readme.txt to update.'
		);
	}
	const updated = readme.replace(
		pattern,
		() => `== Screenshots ==\n\n${ list }\n\n`
	);
	fs.writeFileSync( README_PATH, updated );
}

async function main() {
	fs.mkdirSync( OUT_DIR, { recursive: true } );

	const browser = await chromium.launch();
	const adminPage = await (
		await browser.newContext( {
			viewport: VIEWPORT,
			ignoreHTTPSErrors: true,
		} )
	).newPage();
	const frontPage = await (
		await browser.newContext( {
			viewport: VIEWPORT,
			ignoreHTTPSErrors: true,
		} )
	).newPage();
	await login( adminPage );

	for ( const shot of SHOTS ) {
		const page = shot.context === 'front' ? frontPage : adminPage;
		process.stdout.write(
			`Capturing ${ shot.file } (${ shot.caption })... `
		);
		await shot.capture( page );
		console.log( 'done' );
	}

	await browser.close();
	updateReadme();
	console.log(
		`\nWrote ${ SHOTS.length } screenshots to wporg-assets/ and updated readme.txt.`
	);
}

main().catch( ( err ) => {
	console.error( '\nscreenshot capture failed:', err );
	process.exit( 1 );
} );
