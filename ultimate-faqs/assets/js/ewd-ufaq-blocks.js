var el = wp.element.createElement,
	registerBlockType = wp.blocks.registerBlockType,
	ServerSideRender = wp.components.ServerSideRender,
	TextControl = wp.components.TextControl,
	SelectControl = wp.components.SelectControl,
	InspectorControls = wp.editor.InspectorControls,
	Localize = wp.i18n.__;

registerBlockType( 'ultimate-faqs/ewd-ufaq-display-faq-block', {
	title: Localize( 'Display FAQs', 'ultimate-faqs' ),
	icon: 'editor-help',
	category: 'ewd-ufaq-blocks',
	attributes: {
		post_count: { 
			type: 'string',
			default: -1
		},
		include_category: { type: 'string' },
		exclude_category: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( TextControl, {
					type: 'number',
					label: Localize( 'Number of FAQs', 'ultimate-faqs' ),
					value: props.attributes.post_count,
					onChange: ( value ) => { props.setAttributes( { post_count: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'Include Category', 'ultimate-faqs' ),
					value: props.attributes.include_category,
					onChange: ( value ) => { props.setAttributes( { include_category: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'Exclude Category', 'ultimate-faqs' ),
					value: props.attributes.exclude_category,
					onChange: ( value ) => { props.setAttributes( { exclude_category: value } ); },
				} )
			),
		);
		returnString.push( el( ServerSideRender, { 
			block: 'ultimate-faqs/ewd-ufaq-display-faq-block',
			attributes: props.attributes
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'ultimate-faqs/ewd-ufaq-search-block', {
	title: Localize( 'Search FAQs', 'ultimate-faqs' ),
	icon: 'editor-help',
	category: 'ewd-ufaq-blocks',
	attributes: {
		include_category: { type: 'string' },
		exclude_category: { type: 'string' },
		show_on_load: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( TextControl, {
					label: Localize( 'Include Category', 'ultimate-faqs' ),
					value: props.attributes.include_category,
					onChange: ( value ) => { props.setAttributes( { include_category: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'Exclude Category', 'ultimate-faqs' ),
					value: props.attributes.exclude_category,
					onChange: ( value ) => { props.setAttributes( { exclude_category: value } ); },
				} ),
				el( SelectControl, {
					label: Localize( 'Show all FAQs on Page Load?', 'ultimate-faqs' ),
					value: props.attributes.show_on_load,
					options: [ {value: '', label: 'No'}, {value: 'Yes', label: 'Yes'} ],
					onChange: ( value ) => { props.setAttributes( { show_on_load: value } ); },
				} )
			),
		);
		returnString.push( el( ServerSideRender, { 
			block: 'ultimate-faqs/ewd-ufaq-search-block',
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'ultimate-faqs/ewd-ufaq-submit-faq-block', {
	title: Localize( 'Submit FAQ', 'ultimate-faqs' ),
	icon: 'editor-help',
	category: 'ewd-ufaq-blocks',
	attributes: {
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push( el( ServerSideRender, { 
			block: 'ultimate-faqs/ewd-ufaq-submit-faq-block',
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'ultimate-faqs/ewd-ufaq-recent-faqs-block', {
	title: Localize( 'Recent FAQs', 'ultimate-faqs' ),
	icon: 'editor-help',
	category: 'ewd-ufaq-blocks',
	attributes: {
		post_count: { 
			type: 'string',
			default: -1
		},
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( TextControl, {
					type: 'number',
					label: Localize( 'Number of FAQs', 'ultimate-faqs' ),
					value: props.attributes.post_count,
					onChange: ( value ) => { props.setAttributes( { post_count: value } ); },
				} )
			),
		);
		returnString.push( el( ServerSideRender, { 
			block: 'ultimate-faqs/ewd-ufaq-recent-faqs-block',
			attributes: props.attributes
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'ultimate-faqs/ewd-ufaq-popular-faqs-block', {
	title: Localize( 'Popular FAQs', 'ultimate-faqs' ),
	icon: 'editor-help',
	category: 'ewd-ufaq-blocks',
	attributes: {
		post_count: { 
			type: 'string',
			default: -1
		},
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( TextControl, {
					type: 'number',
					label: Localize( 'Number of FAQs', 'ultimate-faqs' ),
					value: props.attributes.post_count,
					onChange: ( value ) => { props.setAttributes( { post_count: value } ); },
				} )
			),
		);
		returnString.push( el( ServerSideRender, { 
			block: 'ultimate-faqs/ewd-ufaq-popular-faqs-block',
			attributes: props.attributes
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );


