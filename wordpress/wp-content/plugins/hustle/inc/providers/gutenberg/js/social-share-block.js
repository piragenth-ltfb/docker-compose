const { registerBlockType, createBlock } = wp.blocks,
	{ createElement: el, Component, RawHTML } = wp.element,
	{ string: shortcodeToString, next } = wp.shortcode,

	hustleSocialShareIconEl = el(
		'svg', { 
			class: 'dashicon', viewBox: '0 0 24 24',  width: 20, height: 20, xmlns: 'http://www.w3.org/2000/svg', preserveAspectRatio: 'xMidYMid meet', 'aria-hidden': 'true', role: 'img' 
		},
		el(
			'path', { 
				d: 'M18.628 8.578v-.023h.023c.918 0 1.705.336 2.363 1.008.657.671.986 1.484.986 2.437a3.33 3.33 0 0 1-.986 2.414c-.658.672-1.445 1.008-2.363 1.008-.214 0-.42-.02-.619-.059-.199-.039-.39-.09-.573-.152l-8.716 5.156V20.578a3.33 3.33 0 0 1-.986 2.414C7.099 23.664 6.312 24 5.394 24c-.932 0-1.727-.336-2.385-1.008a3.33 3.33 0 0 1-.986-2.414c0-.953.329-1.762.986-2.426a3.233 3.233 0 0 1 2.385-.996c.367 0 .72.055 1.056.164.336.11.634.274.894.492l8.096-4.757a4.427 4.427 0 0 1-.114-.504 3.277 3.277 0 0 1 .114-1.559L7.344 6.211a2.907 2.907 0 0 1-.917.492 3.39 3.39 0 0 1-1.055.164 3.233 3.233 0 0 1-2.386-.996C2.33 5.207 2 4.398 2 3.445c0-.953.329-1.765.986-2.437C3.644.336 4.44 0 5.372 0c.917 0 1.704.336 2.362 1.008a3.33 3.33 0 0 1 .986 2.414V3.656l8.693 5.156a3.205 3.205 0 0 1 1.216-.234z'
			}
		)
	);

/**
 * Block edit class
 */
class Hustle_Social_Share_BlockEdit extends Component {

	/**
	 * Class constructor
	 */
	constructor() {
		super( ...arguments );

		this.update_id = this.update_id.bind( this );
		this.update_css_class = this.update_css_class.bind( this );
		this.preview = this.preview.bind( this );

		this.state = {
			loading: false,     // Set to true while loading preview markup
			markup: ''          // Preview markup
		};
	}

	/**
	 * Update module id
	 */
	update_module_id( module_id ) {
		this.props.setAttributes( { module_id } );
	}

	/**
	 * Update shortcode id
	 */
	update_id( id ) {
		this.props.setAttributes( { id } );
	}

	update_css_class( css_class ) {
		this.props.setAttributes( { css_class } );
	}

	/**
	 * Preview module
	 */
	preview( attributes ) {
		const { module_id, id, type } = attributes;

		// Check if we already process ajax request
		if ( this.state.loading ) {
			// Ajax request in process, skip
			return;
		}

		// Set loading to true
		this.setState({ loading: true });

		let ajax_url = '';

		if ( id ) {
			let module_type_query = 'undefined' === typeof type ? '' : '&type=' + type;
			ajax_url = ajaxurl + '?action=hustle_render_module&_wpnonce=' + hustle_embed_data.nonce + '&shortcode_id=' + id + module_type_query;

		} else {
			return;

		}

		window.fetch( ajax_url )
			.then( response => response.json() )
			.then( data => {

				if ( data.success ) {

					const html = data.data.html,
						style = data.data.style,
						moduleData = data.data.data;

					this.setState({
						markup: html + style,
						loading: false
					});

					//const $module = $( '.hustle_module_id_' + moduleData.module_id );
					//HUI.sharingSimulation( $module );

					// Update the shortcode id
					if ( ! id ) {
						this.update_id( moduleData.shortcode_id );
					}

					// Update the module id
					this.update_module_id( moduleData.module_id );

				}

			})
			.catch( error => {
				console.log( error );
			})

		;

	}

	/**
	 * React method called when block is initialized
	 */
	componentDidMount() {
		const { attributes } = this.props;

		let { id } = attributes;
		// Check if module ID set
		if ( ! id ) {
			// Fallback
			return;

		} else {
			// Load preview
			this.preview( attributes );
		}
	}

	/**
	 * React method called when block is updated
	 */
	componentDidUpdate( prevProps ) {
		const { attributes } = this.props;
		let { id } = attributes;

		// Check if module is same => skip preview update
		if( prevProps.attributes.id === id ) {
			return;
		}

		// Check if we have module ID set
		if( ! id ) {

			// Clear the preview markup
			this.setState({ markup: '' });

			return;
		}

		// Load preview
		this.preview( attributes );
	}

	open_settings( module_id ) {

		let url = hustle_ss_data.admin_url + '?page=hustle_sshare&id=' + module_id;
		window.open( url );
	}

	/**
	 * Render
	 */
	render() {

		const
			{ loading, markup } = this.state,
			{ attributes, isSelected } = this.props,
			{ module_id, id, css_class } = attributes,
			update_id = ( e ) => this.update_id( e.target.value ),
			open_settings = ( e ) => this.open_settings( module_id );

		let options = hustle_ss_data.modules;

		const controls = [ isSelected && el (
			wp.editor.InspectorControls,
			{ key: 'inspector' },

			el (
				wp.components.PanelBody,
				{
					title: hustle_ss_data.l10n.module,
					initialOpen: true
				},
				el (
					wp.components.PanelRow,
					null,
					el ( wp.components.SelectControl, {
						label: hustle_ss_data.l10n.name,
						value: id,
						options: options,
						onChange: this.update_id
					})
				)
			),
			el (
				wp.components.PanelBody,
				{
					title: hustle_ss_data.l10n.advanced,
					initialOpen: true
				},
				el (
					wp.components.PanelRow,
					null,
					el ( wp.components.TextControl, {
						label: hustle_ss_data.l10n.additional_css_classes,
						value: css_class,
						onChange: this.update_css_class
					})
				)
			)
		), el ( wp.editor.BlockControls,
			null,
			!! id && el ( wp.components.Toolbar,
				null,
				el ( wp.components.IconButton, {
					className: 'components-toolbar__control',
					label: hustle_ss_data.l10n.customize_module,
					icon: 'edit',
					onClick: open_settings
				})
			)
		)];

		// If preview is being loaded, show spinner
		if( loading ) {
			return [ controls, el ( 'div',
				{ key: 'loading', className: 'wp-block-embed is-loading' },
				el ( wp.components.Spinner, null ),
				el ( 'span',
					null,
					hustle_ss_data.l10n.rendering
				)
			)];
		}

		// If we have preview markup display it
		if( markup ) {
			return [
				controls, el ( RawHTML,
					null,
					markup
				)];
		}

		// Fallback, display the select
		return [ controls, el (
			wp.components.Placeholder,
			{
				key: 'placeholder',
				className: 'wp-block-embed',
				instructions: hustle_ss_data.l10n.block_description,
				icon: hustleSocialShareIconEl,
				label: hustle_ss_data.l10n.block_name
			},
			el(
				'form',
				null,
				el(
					'select',
					{ value: id, onChange: update_id },
					options.map(row => el(
						'option',
						{ key: row.value, value: row.value },
						row.label
					))
				)
			)
		)];
	}
}

registerBlockType( 'hustle/social-share', {
	title: hustle_ss_data.l10n.block_name,
	description: hustle_ss_data.l10n.block_description,
    icon: hustleSocialShareIconEl,
	category: 'hustle',
	keywords: [ 'Hustle', hustle_ss_data.l10n.block_name ],
	attributes: {
		module_id: {
			type: 'string'
		},

		// 'shortcode_id' as identifier.
		id: {
			type: 'string'
		},
		type: {
			type: 'string'
		},
		css_class: {
			type: 'string'
		}
	},
	supports:    {
		customClassName: false,
		className:       false,
		html:            false,
	},
	transforms: {
		to: [
			{
				type: 'block',
				blocks: [ 'core/shortcode' ],
				transform: ( { id, css_class } ) => {
					let options = {
						tag: hustle_popup_trigger_data.shortcode_tag,
						attrs: {
							id: ( id || '' ),
							css_class: ( css_class || '' ),
							type: 'social_sharing'
						},
						type: 'single'
					},
					text = shortcodeToString( options );

					return createBlock( 'core/shortcode', {
						text,
					} );
				},
			},
		],

		from: [
			//{
			//	type: 'shortcode',
			//	// Use 'isMatch' here to differentiate social shares from embeds by the shortcode's attribute.
			//	// Not possible atm as it's an open issue. https://github.com/WordPress/gutenberg/issues/10674
			//	tag: [ hustle_ss_data.shortcode_tag ],
			//	attributes: {
			//		id: {
			//			type: 'string',
			//			shortcode: ( { named: { id } } ) => {
			//				return id;
			//			} 
			//		},
			//		type: {
			//			type: 'string',
			//			shortcode: ( { named: { type } } ) => {
			//				return type;
			//			} 
			//		},
			//	}
			//},
			
			// Legacy shortcode.
			{
				type: 'shortcode',
				tag: [ 'wd_hustle_ss' ],
				attributes: {
					id: {
						type: 'string',
						shortcode: ( { named: { id } } ) => {
							return id;
						} 
					},
					type: {
						type: 'string',
						shortcode: ( { named: { type } } ) => {
							return type;
						} 
					},
				}
			},

			{
				type: 'block',
				blocks: [ 'core/shortcode' ],
				isMatch( { text } ) {

					let shortcode_tag = next( 'wd_hustle', text );

					// If it's not a Hustle shortcode, abort.
					if ( 'undefined' === typeof shortcode_tag ) {

						shortcode_tag = next( 'wd_hustle_ss', text );
						if ( 'undefined' === typeof shortcode_tag ) {
							return false;
						}
					}

					let { shortcode } = shortcode_tag;
					if ( 'social_sharing' !== shortcode.attrs.named.type ) {
						// The old shortcode didn't have the same attributes. Prevent a false positive.
						if ( 'wd_hustle_ss' !== shortcode.attrs.tag ) {
							return false;
						}
					}

					return true;
				},
				transform( { text } ) {

					let { shortcode } = next( 'wd_hustle', text );
					if ( 'undefined' === shortcode ) {
						shortcode = next( 'wd_hustle_ss', text );
					}
					let { attrs: { named: { id, type, css_class } } } = shortcode;

					return createBlock( 'hustle/social-share', {
						id,
						css_class,
						type
					} );
				},
			},
		]
	},
	edit: Hustle_Social_Share_BlockEdit,

	save() {

		return null;
	},

} );