import { registerBlockType } from '@wordpress/blocks';
import './style.scss';
import Edit from './edit';
import metadata from './block.json';

interface BlockMetadata {
	name: string;
	[ key: string ]: any;
}

registerBlockType( ( metadata as BlockMetadata ).name, {
	edit: Edit,
} );
