import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';

/**
 * Internal dependencies
 */
import Edit from './edit';
import save from './save';

registerBlockType(metadata, {
  /**
   * @see ./edit.js
   */
  edit: Edit,

  /**
   * @see ./save.js
   */
  save,
});
