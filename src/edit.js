import { __ } from "@wordpress/i18n";
import { InspectorControls, BlockControls, useBlockProps } from "@wordpress/block-editor";
import ServerSideRender from "@wordpress/server-side-render";
import {
	SelectControl,
	ToolbarGroup,
	ToolbarButton,
	ToggleControl,
	PanelBody,
	TextControl,
	Notice,
	RangeControl,
} from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
	const blockProps = useBlockProps();

	const {
		no_title,
		use_ol,
		remove_indent,
		add_smooth,
		use_absolute_urls,
		min_level,
		max_level,
		title_text,
		collapsible,
		style_preset,
		show_back_to_top,
		min_headings,
		add_back_to_top,
		show_heading_count,
		exclude_keywords,
		hierarchical_numbers,
		copy_anchor,
	} = attributes;

	const levelError = Number( min_level ) > Number( max_level );

	const listStyle = hierarchical_numbers
		? "hierarchical"
		: use_ol
			? "ordered"
			: "bullet";

	return (
		<div {...blockProps}>
			<InspectorControls>
				<PanelBody
					title={__( "Content", "wpwing-table-of-contents-block" )}
					initialOpen={true}
				>
					<TextControl
						label={__( "Custom Title", "wpwing-table-of-contents-block" )}
						help={__(
							'Leave empty to use the default "Table of Contents" title.',
							"wpwing-table-of-contents-block",
						)}
						value={title_text}
						onChange={( value ) => setAttributes( { title_text: value } )}
					/>
					<ToggleControl
						label={__( "Hide TOC Title", "wpwing-table-of-contents-block" )}
						help={__(
							"Hide the title and add your own heading block above the TOC.",
							"wpwing-table-of-contents-block",
						)}
						checked={no_title}
						onChange={() => setAttributes( { no_title: ! no_title } )}
					/>
					<SelectControl
						label={__( "Minimum Level", "wpwing-table-of-contents-block" )}
						help={__(
							"Start the TOC from this heading level.",
							"wpwing-table-of-contents-block",
						)}
						value={String(min_level)}
						options={[
							{
								label: __( "H2 (show all)", "wpwing-table-of-contents-block" ),
								value: "2",
							},
							{ label: "H3", value: "3" },
							{ label: "H4", value: "4" },
							{ label: "H5", value: "5" },
							{ label: "H6", value: "6" },
						]}
						onChange={( level ) =>
							setAttributes( { min_level: Number( level ) } )
						}
					/>
					<SelectControl
						label={__( "Maximum Level", "wpwing-table-of-contents-block" )}
						help={__(
							"Stop including headings deeper than this level.",
							"wpwing-table-of-contents-block",
						)}
						value={String(max_level)}
						options={[
							{
								label: __( "H6 (show all)", "wpwing-table-of-contents-block" ),
								value: "6",
							},
							{ label: "H5", value: "5" },
							{ label: "H4", value: "4" },
							{ label: "H3", value: "3" },
							{ label: "H2", value: "2" },
						]}
						onChange={( level ) =>
							setAttributes( { max_level: Number( level ) } )
						}
					/>
					{ levelError && (
						<Notice status="warning" isDismissible={false}>
							{ __(
								"Minimum level is deeper than maximum - the TOC will be empty.",
								"wpwing-table-of-contents-block",
							) }
						</Notice>
					) }
					<RangeControl
						label={__( "Minimum headings to show TOC", "wpwing-table-of-contents-block" )}
						help={__(
							"Hide the TOC if the post has fewer qualifying headings than this number.",
							"wpwing-table-of-contents-block",
						)}
						value={min_headings}
						onChange={( value ) => setAttributes( { min_headings: value } )}
						min={1}
						max={10}
					/>
					<ToggleControl
						label={__( "Show heading count", "wpwing-table-of-contents-block" )}
						help={__(
							"Display the number of visible headings next to the TOC title. Has no effect when the title is hidden.",
							"wpwing-table-of-contents-block",
						)}
						checked={show_heading_count}
						onChange={() => setAttributes( { show_heading_count: ! show_heading_count } )}
					/>
					{ show_heading_count && no_title && (
						<Notice status="info" isDismissible={false}>
							{ __(
								"Heading count is hidden because the TOC title is disabled.",
								"wpwing-table-of-contents-block",
							) }
						</Notice>
					) }
					<TextControl
						label={__( "Exclude headings", "wpwing-table-of-contents-block" )}
						help={__(
							"Comma-separated keywords. Headings containing these words are hidden from the TOC.",
							"wpwing-table-of-contents-block",
						)}
						value={exclude_keywords}
						onChange={( value ) => setAttributes( { exclude_keywords: value } )}
					/>
				</PanelBody>

				<PanelBody
					title={__( "Display", "wpwing-table-of-contents-block" )}
					initialOpen={false}
				>
					<SelectControl
						label={__( "Style", "wpwing-table-of-contents-block" )}
						help={__(
							"Choose a visual style for the TOC.",
							"wpwing-table-of-contents-block",
						)}
						value={style_preset}
						options={[
							{
								label: __( "Default", "wpwing-table-of-contents-block" ),
								value: "default",
							},
							{
								label: __( "Boxed", "wpwing-table-of-contents-block" ),
								value: "boxed",
							},
						]}
						onChange={( value ) => setAttributes( { style_preset: value } )}
					/>
					<ToggleControl
						label={__( "Collapsible", "wpwing-table-of-contents-block" )}
						help={__(
							"Add a toggle button so readers can show or hide the TOC.",
							"wpwing-table-of-contents-block",
						)}
						checked={collapsible}
						onChange={() => setAttributes( { collapsible: ! collapsible } )}
					/>
					{ no_title && collapsible && (
						<Notice status="info" isDismissible={false}>
							{ __(
								'Without a TOC title, the toggle button will appear as an icon only. Consider adding a Custom Title in the Content panel.',
								"wpwing-table-of-contents-block",
							) }
						</Notice>
					) }
					<SelectControl
						label={__( "List style", "wpwing-table-of-contents-block" )}
						help={__(
							"Choose how TOC items are marked.",
							"wpwing-table-of-contents-block",
						)}
						value={listStyle}
						options={[
							{
								label: __( "Bulleted", "wpwing-table-of-contents-block" ),
								value: "bullet",
							},
							{
								label: __( "Numbered", "wpwing-table-of-contents-block" ),
								value: "ordered",
							},
							{
								label: __(
									"Hierarchical (1.1, 1.1.1)",
									"wpwing-table-of-contents-block",
								),
								value: "hierarchical",
							},
						]}
						onChange={( value ) =>
							setAttributes( {
								use_ol: value === "ordered",
								hierarchical_numbers: value === "hierarchical",
							} )
						}
					/>
					<ToggleControl
						label={__( "Flat list (no indent)", "wpwing-table-of-contents-block" )}
						help={__(
							"Remove bullet points and indentation from the first level.",
							"wpwing-table-of-contents-block",
						)}
						checked={remove_indent}
						onChange={() =>
							setAttributes( { remove_indent: ! remove_indent } )
						}
					/>
				</PanelBody>

				<PanelBody
					title={__( "Links & Behavior", "wpwing-table-of-contents-block" )}
					initialOpen={false}
				>
					<ToggleControl
						label={__(
							'Show "Back to top" link',
							"wpwing-table-of-contents-block",
						)}
						help={__(
							"Add a link below the TOC that scrolls back to the top of the page.",
							"wpwing-table-of-contents-block",
						)}
						checked={show_back_to_top}
						onChange={() =>
							setAttributes( { show_back_to_top: ! show_back_to_top } )
						}
					/>
					<ToggleControl
						label={__(
							"Add per-section back to top links",
							"wpwing-table-of-contents-block",
						)}
						help={__(
							'Inserts a "↑ Back to top" link after each heading in the post content.',
							"wpwing-table-of-contents-block",
						)}
						checked={add_back_to_top}
						onChange={() =>
							setAttributes( { add_back_to_top: ! add_back_to_top } )
						}
					/>
					<ToggleControl
						label={__(
							"Copy link button on hover",
							"wpwing-table-of-contents-block",
						)}
						help={__(
							"Show a button beside each TOC item that copies a direct link to that section.",
							"wpwing-table-of-contents-block",
						)}
						checked={copy_anchor}
						onChange={() =>
							setAttributes( { copy_anchor: ! copy_anchor } )
						}
					/>
					<ToggleControl
						label={__(
							"Enable smooth scrolling",
							"wpwing-table-of-contents-block",
						)}
						help={__(
							'Adds css class "smooth-scroll" to links and enables scroll-behavior: smooth on the page.',
							"wpwing-table-of-contents-block",
						)}
						checked={add_smooth}
						onChange={() =>
							setAttributes( {
								add_smooth: ! add_smooth,
							} )
						}
					/>
					<ToggleControl
						label={__(
							"Use absolute URLs",
							"wpwing-table-of-contents-block",
						)}
						help={__(
							"Include the full page URL in each anchor link. Useful when sharing direct links to sections.",
							"wpwing-table-of-contents-block",
						)}
						checked={use_absolute_urls}
						onChange={() =>
							setAttributes( {
								use_absolute_urls: ! use_absolute_urls,
							} )
						}
					/>
				</PanelBody>
			</InspectorControls>

			<BlockControls>
				<ToolbarGroup>
					<ToolbarButton
						label={__(
							"Update table of contents",
							"wpwing-table-of-contents-block",
						)}
						onClick={() => setAttributes( { updated: Date.now() } )}
						icon="update"
					/>
				</ToolbarGroup>
			</BlockControls>

			{ ! levelError && (
				<ServerSideRender block="wpwing/toc" attributes={attributes} />
			) }
		</div>
	);
}
