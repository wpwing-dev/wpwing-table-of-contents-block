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

	return (
		<div {...blockProps}>
			<InspectorControls>
				<Panel>
					<PanelBody>
						<PanelRow>
							<SelectControl
								label={__("Maximum Level", "wpwing-toc")}
								help={__("Maximum depth of the headings.", "wpwing-toc")}
								value={attributes.max_level}
								options={[
									{
										label:
											__("Including", "wpwing-toc") +
											" H6 (" +
											__("Show all", "wpwing-toc") +
											")",
										value: "6",
									},
									{
										label: __("Including", "wpwing-toc") + " H5",
										value: "5",
									},
									{
										label: __("Including", "wpwing-toc") + " H4",
										value: "4",
									},
									{
										label: __("Including", "wpwing-toc") + " H3",
										value: "3",
									},
									{
										label: __("Including", "wpwing-toc") + " H2",
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
								label={__("Remove heading", "wpwing-toc")}
								help={__(
									'Disable the "Table of contents" block heading and add your own heading block.',
									"wpwing-toc"
								)}
								checked={attributes.no_title}
								onChange={() =>
									setAttributes({ no_title: !attributes.no_title })
								}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label={__("Use an ordered list", "wpwing-toc")}
								help={__(
									"Replace the <ul> tag with an <ol> tag. This adds decimal numbers to each heading in the TOC.",
									"wpwing-toc"
								)}
								checked={attributes.use_ol}
								onChange={() => setAttributes({ use_ol: !attributes.use_ol })}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label={__("Remove list indent", "wpwing-toc")}
								help={__(
									"No bullet points or numbers at the first level.",
									"wpwing-toc"
								)}
								checked={attributes.remove_indent}
								onChange={() =>
									setAttributes({ remove_indent: !attributes.remove_indent })
								}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label={__("Use absolute urls", "wpwing-toc")}
								help={__(
									"Adds the permalink url to the fragment.",
									"wpwing-toc"
								)}
								checked={attributes.use_absolute_urls}
								onChange={() =>
									setAttributes({
										use_absolute_urls: !attributes.use_absolute_urls,
									})
								}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label={__("Smooth scrolling support", "wpwing-toc")}
								help={__(
									'Add the css class "smooth-scroll" to the links. This enables smooth scrolling in some themes like GeneratePress.',
									"wpwing-toc"
								)}
								checked={attributes.add_smooth}
								onChange={() =>
									setAttributes({
										add_smooth: !attributes.add_smooth,
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
						label={__("Update table of contents", "wpwing-toc")}
						onClick={() => setAttributes({ updated: Date.now() })}
						icon="update"
					/>
				</ToolbarGroup>
			</BlockControls>
			<ServerSideRender block="wpwing/toc" attributes={attributes} />
		</div>
	);
}
