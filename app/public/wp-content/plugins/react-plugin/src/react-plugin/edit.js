/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';
/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { TextControl, PanelBody, RangeControl } from "@wordpress/components";
import ServerSideRender from '@wordpress/server-side-render';
/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit( {attributes, setAttributes} ) {
	const { airport, limit } = attributes;

	return (
		<>
		<InspectorControls>
			<PanelBody title={ __("Airport Settings", "react-plugin" )}>
				<TextControl
					label="Airport Code"
					value={airport || ''}
					onChange={(value) => setAttributes({airport: value.toUpperCase().slice(0, 3)})}
					maxLength={3}
					help="3-letter IATA code (e.g., LAX, JFK, ORD)"
					placeholder="LAX"
				/>
				<RangeControl
					label="Max flights to fetch from API"
					min={1}
					max={100}
					value={limit}
					onChange={(value) => setAttributes({limit: value})}
					help="Increase this to fetch more flights from the API (may find more airports)"
				/>
			</PanelBody>
		</InspectorControls>

		<div { ...useBlockProps() }>
			<ServerSideRender
				block="create-block/react-plugin"
				attributes={attributes}
			/>
		</div>
		</>
	);
}
