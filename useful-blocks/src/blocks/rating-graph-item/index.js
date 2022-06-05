/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
// import { useSelect } from '@wordpress/data';
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, useBlockProps, RichText } from '@wordpress/block-editor';
import { useMemo } from '@wordpress/element';

/**
 * Internal dependencies
 */
import icon from './_icon';
import MySidebar from './_sidebar';
import { textDomain, iconColor } from '@blocks/config';

/**
 * External dependencies
 */
import classnames from 'classnames';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { findIconDefinition } from '@fortawesome/fontawesome-svg-core';

/**
 * metadata
 */
import metadata from './block.json';
const { name, apiVersion, parent, supports, category } = metadata;

/**
 * 普通のアイコンと fontawesome を分けるための関数
 */
export const splitIconClass = (iconClass) => {
	let iconData;

	// FAだったら配列が返される
	if (null !== iconClass.match(/fas |fab |far /)) {
		iconData = iconClass.split(' ');
		iconData[1] = iconData[1].replace('fa-', '');
		return iconData;
	}

	// FA以外は普通に文字列のまま
	return iconClass;
};

const GraphIcon = ({ iconClass }) => {
	if (!iconClass) return null;

	const iconData = splitIconClass(iconClass);
	if (typeof iconData === 'string') {
		return null;
		// return <i className={classnames(iconClass, icon)}></i>;
	}

	const iconDefinition = findIconDefinition({ prefix: iconData[0], iconName: iconData[1] });

	if (!iconDefinition) return null;

	return <FontAwesomeIcon icon={iconData} />;
};

/**
 * Block
 */
const blockName = 'pb-rating-graph';

registerBlockType(name, {
	apiVersion,
	title: __('Graph', textDomain),
	icon: {
		foreground: iconColor,
		src: icon,
	},
	// keywords: [],
	category,
	supports,
	parent,
	attributes: metadata.attributes,
	edit: ({ attributes, setAttributes }) => {
		const {
			graphLabel,
			leftLabel,
			rightLabel,
			label01,
			label02,
			label03,
			label04,
			label05,
			activePoint,
			maxStep,
			markType,
			iconClass,
			mediaUrl,
			// mediaId,
		} = attributes;

		const activePoints = activePoint.split(',');

		const blockProps = useBlockProps({
			className: `${blockName}__item`,
			'data-max-step': maxStep,
		});

		const scales = useMemo(() => {
			const mapNums = maxStep === 5 ? [1, 2, 3, 4, 5] : [1, 2, 3];

			return mapNums.map((num) => {
				/* eslint no-eval: 0 */
				let theLabrel;
				eval(`theLabrel = label0${num};`);
				const labelTextComp = (
					<RichText
						tagName='span'
						className={classnames(`__label`, {
							'is-null': RichText.isEmpty(theLabrel),
						})}
						placeholder='…'
						value={theLabrel}
						onChange={(val) => setAttributes({ [`label0${num}`]: val })}
					/>
				);
				if (activePoints.includes(String(num))) {
					return (
						<div
							className={`${blockName}__scale`}
							data-step={num}
							data-check='1'
							key={`scale_key_${num}`}
						>
							<div className={`__shape -${markType}`}>
								{'icon' === markType && <GraphIcon iconClass={iconClass} />}
								{'image' === markType && mediaUrl && <img src={mediaUrl} alt='' />}
							</div>
							{labelTextComp}
						</div>
					);
				}
				return (
					<div className={`${blockName}__scale`} data-step={num} key={`scale_key_${num}`}>
						<div className={`__shape -dot`}></div>
						{labelTextComp}
					</div>
				);
			});
		}, [
			maxStep,
			activePoints,
			markType,
			iconClass,
			mediaUrl,
			label01,
			label02,
			label03,
			label04,
			label05,
		]);

		return (
			<>
				<InspectorControls>
					<MySidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div {...blockProps}>
					<RichText
						tagName='span'
						className={classnames(`${blockName}__label`, {
							'is-null': RichText.isEmpty(graphLabel),
						})}
						placeholder={__('Graph Label', textDomain)}
						value={graphLabel}
						onChange={(val) => setAttributes({ graphLabel: val })}
					/>
					<div className={`${blockName}__wrap`}>
						<RichText
							tagName='div'
							className={classnames(`${blockName}__basis -left`, {
								'is-null': RichText.isEmpty(leftLabel),
							})}
							placeholder={__('Label…', textDomain)}
							value={leftLabel}
							onChange={(val) => setAttributes({ leftLabel: val })}
						/>
						<div className={`${blockName}__axis`}>{scales}</div>
						<RichText
							tagName='div'
							className={classnames(`${blockName}__basis -right`, {
								'is-null': RichText.isEmpty(rightLabel),
							})}
							placeholder={__('Label…', textDomain)}
							value={rightLabel}
							onChange={(val) => setAttributes({ rightLabel: val })}
						/>
					</div>
				</div>
			</>
		);
	},

	save: ({ attributes }) => {
		const {
			graphLabel,
			leftLabel,
			rightLabel,
			activePoint,
			maxStep,
			markType,
			iconClass,
			mediaUrl,
			mediaId,
		} = attributes;

		const activePoints = activePoint.split(',');

		const blockProps = useBlockProps.save({
			className: `${blockName}__item`,
			'data-max-step': maxStep,
		});

		const mapNums = maxStep === 5 ? [1, 2, 3, 4, 5] : [1, 2, 3];
		const scales = mapNums.map((num) => {
			/* eslint no-eval: 0 */
			let theLabrel;
			eval(`theLabrel = attributes.label0${num};`);
			let labelTextComp = null;
			if (!RichText.isEmpty(theLabrel)) {
				labelTextComp = (
					<RichText.Content tagName='span' className='__label' value={theLabrel} />
				);
			}

			if (activePoints.includes(String(num))) {
				return (
					<div
						className={`${blockName}__scale`}
						data-step={num}
						data-check='1'
						key={`scale_key_${num}`}
					>
						<div className={`__shape -${markType}`}>
							{'icon' === markType && (
								<FontAwesomeIcon icon={splitIconClass(iconClass)}></FontAwesomeIcon>
							)}
							{'image' === markType && mediaUrl && (
								<img
									src={mediaUrl}
									alt=''
									className={`wp-image-${mediaId} -no-lb`}
								/>
							)}
						</div>
						{labelTextComp}
					</div>
				);
			}

			return (
				<div className={`${blockName}__scale`} key={`scale_key_${num}`} data-step={num}>
					<div className={`__shape -dot`}></div>
					{labelTextComp}
				</div>
			);
		});
		return (
			<div {...blockProps}>
				{!RichText.isEmpty(graphLabel) && (
					<RichText.Content
						tagName='span'
						className={`${blockName}__label`}
						value={graphLabel}
					/>
				)}

				<div className={`${blockName}__wrap`}>
					<div className={`${blockName}__basis -left`}>{leftLabel}</div>
					<div className={`${blockName}__axis`}>{scales}</div>
					<div className={`${blockName}__basis -right`}>{rightLabel}</div>
				</div>
			</div>
		);
	},
});
