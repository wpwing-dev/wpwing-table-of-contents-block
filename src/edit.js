import { __ } from "@wordpress/i18n";
import { InspectorControls, BlockControls } from "@wordpress/block-editor";
import ServerSideRender from "@wordpress/server-side-render";
import {
	SelectControl,
	ToolbarGroup,
	ToolbarButton,
	ToggleControl,
	Panel,
	PanelBody,
	PanelRow,
} from "@wordpress/components";
import { useBlockProps } from "@wordpress/block-editor";

export default function Edit({ attributes, setAttributes }) {
	const blockProps = useBlockProps();

	const {
		no_title,
		use_ol,
		remove_indent,
		add_smooth,
		use_absolute_urls,
		max_level,
	} = attributes;

	return (
		<div {...blockProps}>
			<InspectorControls>
				<Panel>
					<PanelBody>
						<PanelRow>
							<SelectControl
								label={__("Maximum Level", "wpwing-table-of-contents-block")}
								help={__(
									"Maximum depth of the headings.",
									"wpwing-table-of-contents-block",
								)}
								value={max_level}
								options={[
									{
										label:
											__("Including", "wpwing-table-of-contents-block") +
											" H6 (" +
											__("Show all", "wpwing-table-of-contents-block") +
											")",
										value: "6",
									},
									{
										label:
											__("Including", "wpwing-table-of-contents-block") + " H5",
										value: "5",
									},
									{
										label:
											__("Including", "wpwing-table-of-contents-block") + " H4",
										value: "4",
									},
									{
										label:
											__("Including", "wpwing-table-of-contents-block") + " H3",
										value: "3",
									},
									{
										label:
											__("Including", "wpwing-table-of-contents-block") + " H2",
										value: "2",
									},
								]}
								onChange={(level) =>
									setAttributes({ max_level: Number(level) })
								}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label={__("Remove heading", "wpwing-table-of-contents-block")}
								help={__(
									'Disable the "Table of contents" block heading and add your own heading block.',
									"wpwing-table-of-contents-block",
								)}
								checked={no_title}
								onChange={() => setAttributes({ no_title: !no_title })}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label={__(
									"Use an ordered list",
									"wpwing-table-of-contents-block",
								)}
								help={__(
									"Replace the <ul> tag with an <ol> tag. This adds decimal numbers to each heading in the TOC.",
									"wpwing-table-of-contents-block",
								)}
								checked={use_ol}
								onChange={() => setAttributes({ use_ol: !use_ol })}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label={__(
									"Remove list indent",
									"wpwing-table-of-contents-block",
								)}
								help={__(
									"No bullet points or numbers at the first level.",
									"wpwing-table-of-contents-block",
								)}
								checked={remove_indent}
								onChange={() =>
									setAttributes({ remove_indent: !remove_indent })
								}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label={__(
									"Use absolute urls",
									"wpwing-table-of-contents-block",
								)}
								help={__(
									"Adds the permalink url to the fragment.",
									"wpwing-table-of-contents-block",
								)}
								checked={use_absolute_urls}
								onChange={() =>
									setAttributes({
										use_absolute_urls: !use_absolute_urls,
									})
								}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label={__(
									"Smooth scrolling support",
									"wpwing-table-of-contents-block",
								)}
								help={__(
									'Add the css class "smooth-scroll" to the links. This enables smooth scrolling in some themes like GeneratePress.',
									"wpwing-table-of-contents-block",
								)}
								checked={add_smooth}
								onChange={() =>
									setAttributes({
										add_smooth: !add_smooth,
									})
								}
							/>
						</PanelRow>
					</PanelBody>
				</Panel>
			</InspectorControls>

			<BlockControls>
				<ToolbarGroup>
					<ToolbarButton
						className="components-icon-button components-toolbar__control"
						label={__(
							"Update table of contents",
							"wpwing-table-of-contents-block",
						)}
						onClick={() => setAttributes({ updated: Date.now() })}
						icon="update"
					/>
				</ToolbarGroup>
			</BlockControls>

			<ServerSideRender block="wpwing/toc" attributes={attributes} />
		</div>
	);
}
