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
	} = attributes;

	const levelError = Number( min_level ) > Number( max_level );

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
						value={min_level}
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
						value={max_level}
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
								"Minimum level is deeper than maximum level - the TOC will be empty.",
								"wpwing-table-of-contents-block",
							) }
						</Notice>
					) }
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
					<ToggleControl
						label={__( "Numbered list", "wpwing-table-of-contents-block" )}
						help={__(
							"Use a numbered list instead of bullet points.",
							"wpwing-table-of-contents-block",
						)}
						checked={use_ol}
						onChange={() => setAttributes( { use_ol: ! use_ol } )}
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

			<ServerSideRender block="wpwing/toc" attributes={attributes} />
		</div>
	);
}
