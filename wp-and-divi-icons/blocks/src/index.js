import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { ifCondition, compose } from '@wordpress/compose';
import { withSelect } from '@wordpress/data';
import { RichTextToolbarButton, InspectorControls } from '@wordpress/block-editor';
import { registerFormatType, insertObject, useAnchorRef } from '@wordpress/rich-text';
import { SVG, Path, Button, ButtonGroup, Icon, Modal, Flex, FlexItem, FlexBlock, ColorPicker, RangeControl, ToggleControl, Tip, Popover, PanelBody, SelectControl, __experimentalScrollable as Scrollable, __experimentalInputControl as InputControl } from '@wordpress/components';

var ourIcon = (<SVG xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><Path style={{fill:'#362985'}} d="M20.2695312,0H3.7304688C1.6733398,0,0,1.6733398,0,3.7304688v16.5390625 C0,22.3261719,1.6733398,24,3.7304688,24h16.5390625C22.3261719,24,24,22.3261719,24,20.2695312V3.7304688 C24,1.6733398,22.3261719,0,20.2695312,0z M22,20.2695312C22,21.2236328,21.2236328,22,20.2695312,22H3.7304688 C2.7763672,22,2,21.2236328,2,20.2695312V3.7304688C2,2.7763672,2.7763672,2,3.7304688,2h16.5390625 C21.2236328,2,22,2.7763672,22,3.7304688V20.2695312z M6.1955566,15.1322021 c-0.4520264,0.5498047-0.605835,1.2405396-0.4987793,1.8916626c0,0,0.5386963,2.0042725-0.3015747,2.8128052 c0,0,1.3220825,0.8447266,4.062439-1.3082886c0.0321045-0.0245361,0.1400757-0.1175537,0.1400757-0.1175537 c0.0926514-0.0820923,0.1844482-0.1652832,0.2654419-0.263855c0.8326416-1.0128784,0.6862183-2.508728-0.3265381-3.3411865 C8.5238037,13.9732666,7.0281372,14.1193237,6.1955566,15.1322021z M16.7513428,4.3816528l-5.0519409,6.1459961l1.6154785,1.3280029 l5.052002-6.1461182c0.3666382-0.4460449,0.3023071-1.1049805-0.1439209-1.4716187 C17.7769775,3.8710938,17.118042,3.9356079,16.7513428,4.3816528z M9.5584717,13.1323242 c0.3280029,0.1154785,0.6426392,0.2857056,0.9268799,0.5194092c0.2840576,0.2334595,0.5117798,0.5091553,0.6886597,0.8084717 l1.4021606-1.7058105l-1.6155396-1.3279419L9.5584717,13.1323242z"/></SVG>);

var icons = [], handleScrollTimeout, iconPickerRef = React.createRef();

var iconFilters = {
	'agsdix-fa': __('Font Awesome (All)', 'ds-icon-expansion'),
	'agsdix-fab': __('Font Awesome (Brands)', 'ds-icon-expansion'),
	'agsdix-fas': __('Font Awesome (Solid)', 'ds-icon-expansion'),
	'agsdix-far': __('Font Awesome (Line)', 'ds-icon-expansion'),
	'agsdix-smt': __('Material Design', 'ds-icon-expansion'),
	'agsdix-sao': __('Universal', 'ds-icon-expansion'),
	'agsdix-snp': __('Hand Drawn', 'ds-icon-expansion'),
	'agsdix-scs': __('Lineal', 'ds-icon-expansion'),
	'agsdix-sout': __('Outline', 'ds-icon-expansion'),
	'agsdix-sske': __('Sketch', 'ds-icon-expansion'),
	'agsdix-sele': __('Elegant', 'ds-icon-expansion'),
	'agsdix-sfil': __('Filled', 'ds-icon-expansion'),
	'agsdi-': __('Free Icons', 'ds-icon-expansion'),
	'agsdix-set-': __('Elegant Themes Line', 'ds-icon-expansion'),
	'agsdix-seth': __('Elegant Themes', 'ds-icon-expansion'),
};

window.jQuery.post(window.ajaxurl, {action: 'agsdi_get_icons'}, function(response) {
	if (response.success && response.data) {
		icons = response.data;
	}
}, 'json');

registerBlockType( 'aspengrove/icon-block', {
	title: __('Icon', 'ds-icon-expansion'),
	icon: () => {return ourIcon;},
	category: 'layout',
	attributes: {
		icon: {
			type: 'string',
			source: 'attribute',
			selector: '.agsdi-icon',
			attribute: 'data-icon'
		},
		color: {
			type: 'string',
			default: ''
		},
		size: {
			type: 'string',
			default: '48px'
		},
		align: {
			enum: ['center', 'left', 'right', 'inherit'],
			default: 'center'
		},
		title: {
			type: 'string',
			source: 'attribute',
			selector: '.agsdi-icon',
			attribute: 'title'
		}
	},
	example: {
		attributes: {
			icon: 'agsdix-self',
			size: '96px'
		}
	},
	edit: ( props ) => {
		const alignOptions = [
			{
				label: __('Center', 'ds-icon-expansion'),
				value: 'center'
			},
			{
				label: __('Left', 'ds-icon-expansion'),
				value: 'left'
			},
			{
				label: __('Right', 'ds-icon-expansion'),
				value: 'right'
			},
			{
				label: __('Same as surrounding content', 'ds-icon-expansion'),
				value: 'inherit'
			}
		];
		
		return <>
				<InspectorControls key="setting">
					<PanelBody title={__('Icon settings', 'ds-icon-expansion')}>
						<IconPicker icons={icons} selectedIcon={props.attributes.icon ? props.attributes.icon : getDefaultIcon()} onChange={ (value) => {
							var newAttributes = {icon: value};
							if ( !props.attributes.icon || props.attributes.title === getDefaultIconTitle(props.attributes.icon) ) {
								newAttributes['title'] = getDefaultIconTitle(value);
							}
							props.setAttributes(newAttributes);
						} } />
						<ToggleControl label={__('Set icon color', 'ds-icon-expansion')} checked={props.attributes.color}
										onChange={ (value) => {props.setAttributes({color: (value ? '#000000' : '')});} } />
						{ props.attributes.color
						&& <ColorPicker color={props.attributes.color ? props.attributes.color : '#000000'}
										onChange={ (value) => {props.setAttributes({color: value});} } /> }
						<InputControl label={__('Icon size', 'ds-icon-expansion')} labelPosition="side" value={props.attributes.size}
										onChange={ (value) => {props.setAttributes({size: value});} } />
						<RangeControl min="16" max="128" showTooltip={false} withInputField={false} value={props.attributes.size ? parseInt(props.attributes.size) : 0}
										onChange={ (value) => {props.setAttributes({size: value + 'px'});} } />
						<SelectControl label={__('Alignment', 'ds-icon-expansion')} value={props.attributes.align} options={alignOptions}
										onChange={ (value) => {console.log(value);  props.setAttributes({align: value});} } />
						<InputControl label={__('Icon title', 'ds-icon-expansion')} value={props.attributes.icon ? props.attributes.title : getDefaultIconTitle( getDefaultIcon() )}
										onChange={ (value) => {props.setAttributes({title: value});} } />
					</PanelBody>
				</InspectorControls>
				<IconBlock	icon={props.attributes.icon ? props.attributes.icon : getDefaultIcon()}
							color={props.attributes.color}
							size={props.attributes.size}
							align={props.attributes.align}
							title={props.attributes.icon ? props.attributes.title : getDefaultIconTitle( getDefaultIcon() )} />
			</>;
	},
	save: ( props ) => {
		return <IconBlock	icon={props.attributes.icon ? props.attributes.icon : getDefaultIcon()}
							color={props.attributes.color}
							size={props.attributes.size}
							align={props.attributes.align}
							title={props.attributes.icon ? props.attributes.title : getDefaultIconTitle( getDefaultIcon() )} />;
	},
} );

function getDefaultIcon() {
	for ( var i = 0; i < icons.length; ++i ) {
			if ( icons[i] !== 'agsdix-null' ) {
				return icons[i];
				break;
			}
		}
}



function getDefaultIconTitle(icon) {
	var lastSpacePos = icon.lastIndexOf(' ');
	var firstDashPos = icon.indexOf('-', lastSpacePos === -1 ? 0 : lastSpacePos);
	if (firstDashPos !== -1 && icon.substring(0, 6) !== 'agsdi-' && icon.substring(0, 9) !== 'agsdix-fa') {
		firstDashPos = icon.indexOf('-', firstDashPos + 1);
	}
	return (firstDashPos === -1 ? icon : icon.substr(firstDashPos + 1)).replace(/\-/g, ' ') + ' icon';
}


class IconPickerIcon extends React.Component {
	
	ref;
	
	constructor(props) {
		super(props);
		this.state = {
			inView: false
		};
		this.ref = React.createRef();
	}
	
	componentDidMount() {
		if ( !this.state.inView ) {
			this.checkIfInView();
		}
	}
	
	componentDidUpdate() {
		if ( !this.state.inView ) {
			this.checkIfInView();
		}
	}

	checkIfInView() {
		if ( this.ref.current
				&& this.props.icon !== 'agsdix-null'
				&& this.ref.current.offsetTop > this.ref.current.parentNode.scrollTop - 100
				&& this.ref.current.offsetTop < this.ref.current.parentNode.scrollTop + (this.ref.current.parentNode.clientHeight * 2) ) {
			this.setState({inView: true});
		}
	}

	render() {
		return this.state.inView
				? <span data-icon={this.props.icon} ref={this.ref} className={this.props.selected ? 'agsdi-selected' : ''} onClick={() => {this.props.onSelect && this.props.onSelect( this.props.icon );}}></span>
				: <span data-icon-pre={this.props.icon} ref={this.ref}></span>;
	}
}

class IconPicker extends React.Component {
	
	ref;
	scrollUpdateTimeout;
	filterUpdateTimeout;
	filteringOptions;
	
	constructor(props) {
		super(props);
		this.state = {
			selectedIcon: this.props.selectedIcon ? this.props.selectedIcon : null,
			filter: 'all',
			search: '',
			height: 0,
			scrollTop: 0,
			isLoading: false,
			filteredIcons: this.props.icons.filter((icon) => {
				return icon !== 'agsdix-null';
			})
		};

		this.ref = React.createRef();

		this.filteringOptions = [
			{
				label: __('All', 'ds-icon-expansion'),
				value: 'all'
			}
		];
		
		for (var filter in iconFilters) {
			for (var i = 0; i < this.props.icons.length; ++i) {
				if ( this.props.icons[i].substring(0, filter.length) === filter ) {
					this.filteringOptions.push(
						{
							label: iconFilters[filter],
							value: filter
						}
					);
					break;
				}
			}
		}
	}
	
	componentDidUpdate(oldProps, oldState) {
		if (this.props.selectedIcon !== oldProps.selectedIcon) {
			this.setState({selectedIcon: this.props.selectedIcon});
		}
		
		if (this.state.filter != oldState.filter || this.state.search != oldState.search) {
			
			if (this.filterUpdateTimeout) {
				clearTimeout(this.filterUpdateTimeout);
			}
			this.filterUpdateTimeout = setTimeout(() => {
				this.filterUpdateTimeout = null;
				this.setState({isLoading: true});
				this.updateFilteredIcons();
			}, 500);
		}
	}

	updateFilteredIcons() {
		var filteredIcons = [], noFilter = this.state.filter === 'all';
		if (noFilter && !this.state.search) {
			filteredIcons = this.props.icons.filter((icon) => {
				return icon !== 'agsdix-null';
			});
		} else {
			for (var i = 0; i < this.props.icons.length; ++i) {
				var isVisible = true;
				if ( this.props.icons[i] === 'agsdix-null' ) {
					isVisible = false;
				} else if ( !noFilter && this.props.icons[i].substring(0, this.state.filter.length) !== this.state.filter ) {
					isVisible = false;
				} else if ( this.state.search ) {

					if (this.props.icons[i].substr(0, 6) === 'agsdi-') {
						var keywords = this.props.icons[i].substr(6);
					} else if (this.props.icons[i].substr(0, 9) === 'agsdix-fa') {
						var keywords = this.props.icons[i].substr(14);
					} else if (this.props.icons[i].substr(0, 7) === 'agsdix-') {
						var keywords = this.props.icons[i].substr(this.props.icons[i].indexOf('-', 7) + 1);
					} else {
						var keywords = '';
					}

					if (keywords) {
						keywords = keywords.split('-').join(' ');
					}

					if (window.agsdi_icon_aliases[this.props.icons[i]]) {
						keywords = (keywords ? keywords + ' ' : '') + window.agsdi_icon_aliases[ this.props.icons[i] ];
					}
					
					if (keywords.indexOf(this.state.search) === -1) {
						isVisible = false;
					}
				}
				if (isVisible) {
					filteredIcons.push(this.props.icons[i]);
				}
			}
				
		}
		
		this.setState({
			filteredIcons: filteredIcons,
			isLoading: false
		});
	}

	handleScroll() {
		if (this.scrollUpdateTimeout) {
			clearTimeout(this.scrollUpdateTimeout);
		}
		this.scrollUpdateTimeout = setTimeout(() => {
			this.scrollUpdateTimeout = null;
			this._handleScroll();
		}, 250);
	}
	
	_handleScroll() {
		if (this.ref.current) {
			this.setState({
				scrollTop: this.ref.current.scrollTop,
				height: this.ref.current.clientHeight,
			});
		}
	}

	handleIconSelection(value) {
		this.setState({selectedIcon: value});
		if (this.props.onChange) {
			this.props.onChange(value);
		}
	}

	componentDidMount() {
		this.handleScroll();
	}

	render() {
		var iconElements = [];

		for (var i = 0; i < this.state.filteredIcons.length; ++i) {
			iconElements.push(
				<IconPickerIcon	icon={this.state.filteredIcons[i]}
								selected={this.state.selectedIcon === this.state.filteredIcons[i]} key={i} onSelect={(value) => {this.handleIconSelection(value);}}
								parentScrollTop={this.state.scrollTop}
								parentHeight={this.state.height}
				/>
			);
		}
		

		return <div className="mce-agsdi-icon-picker gb-agsdi-icon-picker">
					<SelectControl className="gb-agsdi-filters-wrapper" options={this.filteringOptions} value={this.state.filter} onChange={(value) => {this.setState({filter: value});}} />
					<InputControl className="gb-agsdi-icon-search" type="search" hideLabelFromVision={true} placeholder={__( 'Search Icons...', 'ds-icon-expansion' )} value={this.state.search} onChange={(value) => {this.setState({search: value});}} />
					{this.state.isLoading
						?	<div className="agsdi-loading">{__('Loading...', 'ds-icon-expansion')}</div>
						:	<Scrollable className="agsdi-icons" ref={this.ref} onScroll={() => {this.handleScroll();}}>
								{iconElements}
							</Scrollable>
					}
				</div>;
	}
}


const IconPreview = ( props ) => {
	return <div className="agsdi-icon-preview" style={{color: props.color, fontSize: props.size ? props.size : '48px', minHeight: '1em'}}>
			{ props.icon && <span data-icon={props.icon}></span> }
		</div>;
}

const IconBlock = ( props ) => {
	var style = {};

	if (props.color) {
		style.color = props.color;
	}

	if (props.size) {
		style.fontSize = props.size;
	}

	if (props.align) {
		style.textAlign = props.align;
	}

	return <div style={style} className={props.className}>
			{ props.icon === 'agsdix-self' && <img width="100%" height="100%" src={window.ags_divi_icons_config.pluginDirUrl + '/blocks/images/block-free.svg'} /> }
			{ props.icon && props.icon !== 'agsdix-self' && <span className={"agsdi-icon"} data-icon={props.icon} title={props.title}></span> }
		</div>;
}

class IconsSelectionModal extends React.Component {
	
	constructor(props) {
		super(props);
		this.state = this.deriveStateFromIconAttributes();
	}

	deriveStateFromIconAttributes() {
		var parsedStyle = {};
		if (this.props.iconAttributes.style) {
			var styleRules = this.props.iconAttributes.style.split(';');
			for (var i = 0; i < styleRules.length; ++i) {
				var colonPos = styleRules[i].indexOf(':');
				if (colonPos !== -1) {
					parsedStyle[styleRules[i].substring(0, colonPos)] = styleRules[i].substring(colonPos + 1);
				}
			}
		}

		var iconClasses = this.props.iconAttributes.className
							? this.props.iconAttributes.className
								.split(' ')
								.filter((value) => {
									return value && value !== 'agsdi-icon' && value.substring(0, 7) !== 'i-agsdi';
								})
								.join(' ')
							: '';

		return {
			selectedIcon: this.props.iconAttributes.icon ? this.props.iconAttributes.icon : null,
			iconColor: parsedStyle['color'] ? parsedStyle['color'] : '',
			iconSize: parsedStyle['font-size'] ? parsedStyle['font-size'] : '48px',
			iconTitle: this.props.iconAttributes.title ? this.props.iconAttributes.title : '',
			iconClasses: iconClasses
		};
	}

	componentDidMount() {
		if (!this.state.selectedIcon) {
			var defaultIcon = getDefaultIcon();
			this.setState({selectedIcon: defaultIcon, iconTitle: getDefaultIconTitle(defaultIcon)});
		}
	}

	componentDidUpdate(oldProps, oldState) {
		if (
			this.props.iconAttributes.icon !== oldProps.iconAttributes.icon
			|| this.props.iconAttributes.className !== oldProps.iconAttributes.className
			|| this.props.iconAttributes.style !== oldProps.iconAttributes.style
			|| this.props.iconAttributes.title !== oldProps.iconAttributes.title
		) {
			this.setState( this.deriveStateFromIconAttributes() );
		}
		
		if ( this.state.selectedIcon && oldState.selectedIcon
						&& this.state.selectedIcon !== oldState.selectedIcon
						&& oldState.iconTitle === getDefaultIconTitle(oldState.selectedIcon) ) {
			this.setState({iconTitle: getDefaultIconTitle(this.state.selectedIcon)});
		}
	}
	
	closeModal() {
		if (this.props.onClose) {
			this.props.onClose();
		}
	}

	render() {
		return this.props.open ? (
			<Modal
				title={__( 'Insert Icon', 'ds-icon-expansion' )}
				onRequestClose={ () => { this.closeModal(); } }
				className="agsdi-gutenberg-insert-modal"
			>
				
				<Flex>
					<FlexItem style={{width:'30%'}}>
						<IconPicker icons={icons} selectedIcon={this.state.selectedIcon} onChange={ (value) => {this.setState({selectedIcon: value});} } />
					</FlexItem>
					<FlexItem style={{width:'70%'}}>
						<Flex>
							<FlexItem style={{width:'60%'}}>
								<ToggleControl label={__('Set icon color', 'ds-icon-expansion')} checked={this.state.iconColor}
										onChange={ (value) => {this.setState({iconColor: (value ? '#000000' : '')});} } />
								{this.state.iconColor && <ColorPicker color={this.state.iconColor ? this.state.iconColor : '#000000'}
												onChange={ (value) => {this.setState({iconColor: value});} } />}
								<InputControl label={__('Icon size', 'ds-icon-expansion')} labelPosition="side" value={this.state.iconSize}
												onChange={ (value) => {this.setState({iconSize: value});} } />
								<RangeControl min="16" max="128" showTooltip={false} withInputField={false} value={parseInt(this.state.iconSize)}
												onChange={ (value) => {this.setState({iconSize: value + 'px'});} } />
								<InputControl label={__('Icon title', 'ds-icon-expansion')} labelPosition="side" value={this.state.iconTitle}
												onChange={ (value) => {this.setState({iconTitle: value});} } />
								<InputControl label={__('Icon class(es)', 'ds-icon-expansion')} labelPosition="side" value={this.state.iconClasses}
												onChange={ (value) => {this.setState({iconClasses: value});} } />
							</FlexItem>
							<FlexItem style={{width:'40%'}} className="mce-agsdi-icon-preview">
								<IconPreview icon={this.state.selectedIcon} color={this.state.iconColor} size={this.state.iconSize} />
							</FlexItem>
						</Flex>
						<Tip>If you leave the color and/or size settings blank, the icon will derive its color and size from the surrounding text's color and size (based on the styling of the icon's parent element). This is not reflected in the icon preview.</Tip>
					</FlexItem>
					
				</Flex>
				
				<Button variant="primary" onClick={() => {this.props.onApply && this.props.onApply(this.state.selectedIcon, this.state.iconColor, this.state.iconSize, this.state.iconTitle, this.state.iconClasses); this.closeModal();}}>{__( 'OK', 'ds-icon-expansion' )}</Button>
				<Button variant="secondary" onClick={() => {this.closeModal();}}>{__( 'Cancel', 'ds-icon-expansion' )}</Button>
			</Modal>
		) : null;
	}
}


class DiviIconAction extends React.Component {

	constructor(props) {
		super(props);
		this.state = {
			isOpen: false
		};
	}

	onApply(icon, color, size, title, classes) {
		
		var styleRules = [];
		
		if (color) {
			styleRules.push('color:' + color);
		}

		if (size) {
			styleRules.push('font-size:' + size);
		}

		var iconAttributes = {
			'data-icon': icon,
			className: 'agsdi-icon' + (classes ? ' ' + classes : '')
		};

		if (styleRules.length) {
			iconAttributes.style = styleRules.join(';');
		}

		if (title) {
			iconAttributes.title = title;
		}

		this.props.onChange(
			insertObject(
				this.props.value,
				{
					type: 'aspengrove/icon',
					attributes: iconAttributes
				}
			)
		);

		this.props.onFocus();
	}

	render() {
		return (
			<>
	<RichTextToolbarButton icon={ourIcon} title={__('Icon', 'ds-icon-expansion')} onClick={ () => {this.setState({isOpen: true});} } />
				{ this.state.isOpen && <IconsSelectionModal
					open={this.state.isOpen}
					onClose={ () => {this.setState({isOpen: false}); this.props.onFocus();} }
					onApply={ (icon, color, size, title, classes) => {this.onApply(icon, color, size, title, classes);} }
					iconAttributes={this.props.activeObjectAttributes}
				/> }
				{ this.props.isObjectActive && <EditIconPopover iconRef={this.props.contentRef} selectionValue={this.props.value} onEditButtonClick={ () => {this.setState({isOpen: true});} } /> }
			</>
		)
	}

}


const EditIconPopover = ( props ) => {
	return <Popover anchorRef={useAnchorRef({ ref: props.iconRef, value: props.selectionValue, settings: AgsIconFormat })} noArrow={false} position="bottom center">
				<ButtonGroup style={{whiteSpace: 'nowrap'}}>
					<Button icon="edit" onClick={() => {props.onEditButtonClick();}}>{__('Edit Icon', 'ds-icon-expansion')}</Button>
					<Button icon="trash" onClick={() => {props.onRemoveButtonClick();}}>{__('Remove Icon', 'ds-icon-expansion')}</Button>
				</ButtonGroup>
			</Popover>
};

const AgsIconFormat = {
	name: 'aspengrove/icon',
	title: __('Icon', 'ds-icon-expansion'),
	tagName: 'span',
	className: 'agsdi-icon',
	object: true,
	attributes: {
		icon: 'data-icon',
		style: 'style',
		className: 'class',
		title: 'title'
	},
	edit: compose(
				withSelect( function( select ) {
					return {
						selectedBlock: select( 'core/block-editor' ).getSelectedBlock()
					}
				} ),
				ifCondition( function( props ) {
					return (
						props.selectedBlock &&
						props.selectedBlock.name === 'core/paragraph'
					);
				} )
			)( DiviIconAction ),
};

registerFormatType('aspengrove/icon', AgsIconFormat);