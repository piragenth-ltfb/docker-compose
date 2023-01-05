const { registerBlockType, createBlock } = wp.blocks,
	{ createElement:el, Component, RawHTML } = wp.element,
	{ string: shortcodeToString, next } = wp.shortcode,

	hustlePopupTriggerIconEl =
		el('svg', {
			class: 'dashicon', viewBox: '0 0 24 24',  width: 20, height: 20, xmlns: 'http://www.w3.org/2000/svg', preserveAspectRatio: 'xMidYMid meet', 'aria-hidden': 'true', role: 'img'
		},
			el (
				'path', {
					d: 'M1.5 0h21c.422 0 .777.145 1.066.434.29.289.434.644.434 1.066v21c0 .422-.145.777-.434 1.066A1.45 1.45 0 0 1 22.5 24h-21a1.45 1.45 0 0 1-1.066-.434A1.45 1.45 0 0 1 0 22.5v-21C0 1.078.145.723.434.434A1.45 1.45 0 0 1 1.5 0zM6 6a1.45 1.45 0 0 0-1.066.434A1.45 1.45 0 0 0 4.5 7.5v9c0 .422.145.777.434 1.066.289.29.644.434 1.066.434h12a1.45 1.45 0 0 0 1.066-.434c.29-.289.434-.644.434-1.066v-9a1.45 1.45 0 0 0-.434-1.066A1.45 1.45 0 0 0 18 6H6zm10.5 1.5c.422 0 .777.145 1.066.434.29.289.434.644.434 1.066 0 .422-.145.777-.434 1.066a1.45 1.45 0 0 1-1.066.434 1.45 1.45 0 0 1-1.066-.434A1.45 1.45 0 0 1 15 9c0-.422.145-.777.434-1.066A1.45 1.45 0 0 1 16.5 7.5z'
				}
			)
		);

/**
 * Block edit class
 */
class Hustle_Popup_Trigger_BlockEdit extends Component {
	/**
	 * Class constructor
	 */
	constructor() {
		super( ...arguments );

		this.update_id = this.update_id.bind( this );
		this.update_css_class = this.update_css_class.bind( this );
		this.update_content = this.update_content.bind( this );

		this.state = {
			loading: false,     // Set to true while loading preview markup
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

	update_content( content ) {
		this.props.setAttributes( { content } );
	}

	update_css_class( css_class ) {
		this.props.setAttributes( { css_class } );
	}

	/**
	 * Set the module_id to this block by the shortcode_id provided.
	 * @param string id
	 */
	set_module_id_from_shortcode_id ( id ) {

		// Check if we already process ajax request
		if ( this.state.loading ) {
			// Ajax request in process, skip
			return;
		}

		// Set loading to true
		this.setState({ loading: true });

		let ajax_url = ajaxurl + '?action=hustle_get_module_id_by_shortcode&_wpnonce=' + hustle_popup_trigger_data.nonce + '&shortcode_id=' + id;
		window.fetch( ajax_url )
		.then( response => response.json() )
		.then( data => {

			if ( data.success && data.data.module_id ) {
				this.update_module_id( data.data.module_id );
			}

			// Set loading to false
			this.setState({ loading: false });

		});

	}

	/**
	 * React method called when block is initialized.
	 * Used to get the module_id when only the shortcode_id is provided.
	 */
	componentDidUpdate( prevProps ) {
		const { attributes } = this.props;
		let { id } = attributes;

		if( prevProps.attributes.id === id ) {
			return;
		}

		if ( ! id ) {
			// No shortcode_id provided to get the module_id.
			return;
		}

		// Set the module_id to this block.
		this.set_module_id_from_shortcode_id( id );
	}

	/**
	 * React method called when block is initialized.
	 * Used to get the module_id when only the shortcode_id is provided.
	 */
	componentDidMount() {
		const { attributes: { module_id, id } } = this.props;

		if ( ! id || module_id ) {
			// No shortcode_id provided to get the module_id.
			return;

		} else {
			// Set the module_id to this block.
			this.set_module_id_from_shortcode_id( id );
		}

	}

	open_settings( module_id ) {

		let url = hustle_popup_trigger_data.admin_url + '?page=' + hustle_popup_trigger_data.wizard_page + '&id=' + module_id;
		window.open( url );
	}

	/**
	 * Render
	 */
	render() {

		const
			{ loading } = this.state,
			{ attributes, isSelected } = this.props,
			{ module_id, id, content, css_class } = attributes,
			update_id = ( e ) => this.update_id( e.target.value ),
			open_settings = ( e ) => this.open_settings( module_id );

		let options = hustle_popup_trigger_data.modules;

		const controls = [ isSelected && el (
			wp.editor.InspectorControls,
			{ key: 'inspector' },

			el (
				wp.components.PanelBody,
				{
					title: hustle_popup_trigger_data.l10n.module,
					initialOpen: true
				},
				el (
					wp.components.PanelRow,
					null,
					el ( wp.components.SelectControl, {
						label: hustle_popup_trigger_data.l10n.name,
						value: id,
						options: options,
						onChange: this.update_id
					})
				)
			),
			el (
				wp.components.PanelBody,
				{
					title: hustle_popup_trigger_data.l10n.advanced,
					initialOpen: true
				},
				el (
					wp.components.PanelRow,
					null,
					el ( wp.components.TextControl, {
						label: hustle_popup_trigger_data.l10n.trigger_content,
						value: content,
						onChange: this.update_content
					}),
				),
				el (
					wp.components.PanelRow,
					null,
					el ( wp.components.TextControl, {
						label: hustle_popup_trigger_data.l10n.additional_css_classes,
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
					label: hustle_popup_trigger_data.l10n.customize_module,
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
					hustle_popup_trigger_data.l10n.rendering
				)
			)];
		}

		// If we have a module, allow editing its content.
		if( id ) {
			return [
				controls, el (
					wp.editor.RichText, {
						tagName: 'a',
						multiline: false,
						formattingControls: [],
						value: content,
						onChange: this.update_content,
						keepPlaceholderOnFocus: true,
						placeholder: hustle_popup_trigger_data.l10n.content_here
					}
				)
			];
		}

		// Fallback, display the select
		return [ controls, el (
			wp.components.Placeholder,
			{
				key: 'placeholder',
				className: 'wp-block-embed',
				instructions: hustle_popup_trigger_data.l10n.block_description,
				icon: hustlePopupTriggerIconEl,
				label: hustle_popup_trigger_data.l10n.block_name
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
			),
			el(
				'div',
				{ class: 'components-placeholder__instructions' },
				hustle_popup_trigger_data.l10n.block_more_description
			)
		)];
	}
}

registerBlockType( 'hustle/popup-trigger', {
	title: hustle_popup_trigger_data.l10n.block_name,
	description: hustle_popup_trigger_data.l10n.block_description,
    icon: hustlePopupTriggerIconEl,
	category: 'hustle',
	keywords: [ 'Hustle', hustle_popup_trigger_data.l10n.block_name ],
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
		content: {
			type: 'string',
			default: hustle_popup_trigger_data.l10n.click_here
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
				transform: ( { id, content, css_class } ) => {

					let options = {
						tag: hustle_popup_trigger_data.shortcode_tag,
						attrs: {
							id: ( id || '' ),
							css_class: ( css_class || '' ),
							type: 'popup'
						},
						content,
						type: 'closed'
					};

					let text = shortcodeToString( options );
					return createBlock( 'core/shortcode', {
						text,
					} );
				},
			},
		],

		from: [

			{
				type: 'block',
				blocks: [ 'core/shortcode' ],
				isMatch( { text } ) {
					let found_shortcode = next( 'wd_hustle', text );

					if ( 'undefined' === typeof found_shortcode ) {
						return false;
					}

					let { shortcode } = found_shortcode;

					if ( 'popup' !== shortcode.attrs.named.type ) {
						return false;
					}

					return true;
				},
				transform( { text } ) {

					let { shortcode } = next( 'wd_hustle', text ),
					{ content, attrs: { named: { id, type, css_class } } } = shortcode;

					return createBlock( 'hustle/popup-trigger', {
						id,
						type,
						css_class,
						content
					} );
				},
			},
		]
	},
	edit: Hustle_Popup_Trigger_BlockEdit,

	// This is rendered server-side.
	save() {

		return null;
	},

} );