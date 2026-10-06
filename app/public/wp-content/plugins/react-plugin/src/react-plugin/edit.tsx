import { __ } from "@wordpress/i18n";
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { TextControl, PanelBody } from "@wordpress/components";
import { Placeholder } from "@wordpress/components";
import "./editor.scss";

interface WaitTimeMapAttributes {
	airport: string;
}

interface EditProps {
	attributes: WaitTimeMapAttributes;
	setAttributes: (attrs: WaitTimeMapAttributes) => void;
}

export default function Edit({ attributes, setAttributes }: EditProps) {
	const { airport } = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody title={__("Wait Times Settings", "react-plugin")}>
					<TextControl
						label="Airport Code"
						value={airport || ""}
						onChange={(value) =>
							setAttributes({
								airport: value.toUpperCase().slice(0, 3),
							})
						}
						maxLength={3}
						help="3-letter IATA code (e.g., LAX, JFK, ORD)"
						placeholder="ORD"
					/>
				</PanelBody>
			</InspectorControls>

			<div {...useBlockProps()}>
				<Placeholder
					icon="location-alt"
					label="Flight Wait Times Map"
					instructions={`Showing wait times for ${airport || 'ORD'}. Map preview available on frontend only.`}
				/>
			</div>
		</>
	);
}
