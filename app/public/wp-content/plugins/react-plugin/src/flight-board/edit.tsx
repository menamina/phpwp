import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { TextControl, PanelBody, RangeControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import './editor.scss';

interface FlightBoardAttributes {
	airport: string;
	limit: number;
}

interface EditProps {
	attributes: FlightBoardAttributes;
	setAttributes: ( attrs: Partial< FlightBoardAttributes > ) => void;
}

export default function Edit( { attributes, setAttributes }: EditProps ) {
	const { airport, limit } = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Flight Board Settings', 'react-plugin' ) }
				>
					<TextControl
						label="Airport Code"
						value={ airport || '' }
						onChange={ ( value ) =>
							setAttributes( {
								airport: value.toUpperCase().slice( 0, 3 ),
							} )
						}
						maxLength={ 3 }
						help="3-letter IATA code (e.g., LAX, JFK, ORD)"
						placeholder="ORD"
					/>
					<RangeControl
						label="Max flights to fetch"
						min={ 1 }
						max={ 100 }
						value={ limit }
						onChange={ ( value ) =>
							setAttributes( { limit: value } )
						}
						help="Number of flights to display"
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...useBlockProps() }>
				<ServerSideRender
					block="create-block/flight-board"
					attributes={ attributes }
				/>
			</div>
		</>
	);
}
