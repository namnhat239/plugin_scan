!function(){var e={184:function(e,t){var n;!function(){"use strict";var o={}.hasOwnProperty;function s(){for(var e=[],t=0;t<arguments.length;t++){var n=arguments[t];if(n){var r=typeof n;if("string"===r||"number"===r)e.push(n);else if(Array.isArray(n)){if(n.length){var a=s.apply(null,n);a&&e.push(a)}}else if("object"===r)if(n.toString===Object.prototype.toString)for(var l in n)o.call(n,l)&&n[l]&&e.push(l);else e.push(n.toString())}}return e.join(" ")}e.exports?(s.default=s,e.exports=s):void 0===(n=function(){return s}.apply(t,[]))||(e.exports=n)}()},685:function(e){"use strict";var t=function(e){return parseInt(e,16)};e.exports=function(e,n){var o,s,r=function(e){return"#"===e.charAt(0)?e.slice(1):e}(e),a=function(e){var n=e.g,o=e.b,s=e.a;return{r:t(e.r),g:t(n),b:t(o),a:+(t(s)/255).toFixed(2)}}({r:(s=3===(o=r).length||4===o.length)?"".concat(o.slice(0,1)).concat(o.slice(0,1)):o.slice(0,2),g:s?"".concat(o.slice(1,2)).concat(o.slice(1,2)):o.slice(2,4),b:s?"".concat(o.slice(2,3)).concat(o.slice(2,3)):o.slice(4,6),a:(s?"".concat(o.slice(3,4)).concat(o.slice(3,4)):o.slice(6,8))||"ff"});return function(e,t){var n,o=e.r,s=e.g,r=e.b,a=e.a,l=(n=t,!isNaN(parseFloat(n))&&isFinite(n)?t:a);return"rgba(".concat(o,", ").concat(s,", ").concat(r,", ").concat(l,")")}(a,n)}},306:function(e){e.exports=function(e){if("string"!=typeof e)throw new Error("color has to be type of `string`");if("#"===e.substr(0,1))return{hex:e,alpha:1};var t=e.replace(/\s+/g,""),n=/(.*?)rgb(a)??\((\d{1,3}),(\d{1,3}),(\d{1,3})(,([01]|1.0*|0??\.([0-9]{0,})))??\)/.exec(t);if(!n)throw new Error("given color ("+e+") isn't a valid rgb or rgba color");var o=parseInt(n[3],10),s=parseInt(n[4],10),r=parseInt(n[5],10),a=n[6]?/([0-9\.]+)/.exec(n[6])[0]:"1",l=(r|s<<8|o<<16|1<<24).toString(16).slice(1);return"."===a.substr(0,1)&&(a=parseFloat("0"+a)),a=parseFloat(Math.round(100*a))/100,{hex:"#"+l.toString(16),alpha:a}}}},t={};function n(o){var s=t[o];if(void 0!==s)return s.exports;var r=t[o]={exports:{}};return e[o](r,r.exports,n),r.exports}n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,{a:t}),t},n.d=function(e,t){for(var o in t)n.o(t,o)&&!n.o(e,o)&&Object.defineProperty(e,o,{enumerable:!0,get:t[o]})},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},function(){"use strict";var e={};n.r(e),n.d(e,{settings:function(){return _}});var t={};n.r(t),n.d(t,{settings:function(){return I}});var o={};n.r(o),n.d(o,{settings:function(){return $}});var s={};n.r(s),n.d(s,{settings:function(){return G}});var r={};n.r(r),n.d(r,{settings:function(){return K}});var a={};n.r(a),n.d(a,{settings:function(){return oe}});var l={};n.r(l),n.d(l,{settings:function(){return de}});var i={};n.r(i),n.d(i,{settings:function(){return fe}});var c=window.wp.element,m=window.lodash,u=window.wp.blockEditor,g=window.wp.components,p=window.wp.richText,d=window.wp.i18n;const h=(0,c.createElement)("svg",{role:"img",focusable:"false",width:"20",height:"20",viewBox:"0 0 20 20",xmlns:"http://www.w3.org/2000/svg","aria-hidden":"true"},(0,c.createElement)("path",{d:"M13.982,16.711c-0.744,1.441 -2.248,2.428 -3.982,2.428c-1.735,0 -3.238,-0.986 -3.983,-2.428c0.909,-1.213 2.355,-2.002 3.983,-2.002c1.629,0 3.074,0.789 3.982,2.002Zm-0.748,-7.657c-0.314,2.56 1.248,2.919 1.248,5.603c0,0.467 -0.072,0.918 -0.205,1.344c-1.037,-1.203 -2.57,-1.967 -4.277,-1.967c-1.708,0 -3.24,0.764 -4.277,1.967c-0.133,-0.426 -0.205,-0.877 -0.205,-1.344c0,-2.684 1.563,-3.043 1.247,-5.603c-0.362,-2.928 -4.315,-2.465 -4.315,-5.334c0,-1.579 1.279,-2.858 2.858,-2.858c1.709,0 2.765,1.558 4.692,1.558c1.926,0 2.982,-1.558 4.691,-1.558c1.578,0 2.857,1.279 2.857,2.858c0.001,2.869 -3.952,2.406 -4.314,5.334Zm-4.677,-4.947l-0.708,0c0,0.498 -0.403,0.9 -0.901,0.9c-0.498,0 -0.901,-0.402 -0.901,-0.9l-0.708,0c0,0.889 0.72,1.609 1.609,1.609c0.889,0 1.609,-0.72 1.609,-1.609Zm0.979,7.141c0,-0.312 -0.253,-0.568 -0.566,-0.568c-0.313,0 -0.567,0.256 -0.567,0.568c0,0.312 0.254,0.566 0.567,0.566c0.313,0 0.566,-0.253 0.566,-0.566Zm2.062,0c0,-0.312 -0.254,-0.568 -0.568,-0.568c-0.312,0 -0.566,0.256 -0.566,0.568c0,0.312 0.254,0.566 0.566,0.566c0.314,0 0.568,-0.253 0.568,-0.566Zm3.062,-7.141l-0.707,0c0,0.498 -0.404,0.9 -0.9,0.9c-0.498,0 -0.902,-0.402 -0.902,-0.9l-0.707,0c0,0.889 0.721,1.609 1.609,1.609c0.886,0.001 1.607,-0.72 1.607,-1.609Z"})),v={position:"bottom left",isAlternate:!0};(0,p.registerFormatType)("snow-monkey-editor/dropdown",{title:"buttons",tagName:"sme-dropdown",className:null,edit:()=>(0,c.createElement)(u.BlockFormatControls,null,(0,c.createElement)("div",{className:"block-editor-format-toolbar"},(0,c.createElement)(g.ToolbarGroup,null,(0,c.createElement)(g.Slot,{name:"SnowMonkey.ToolbarControls"},(e=>0!==e.length&&(0,c.createElement)(g.ToolbarItem,null,(t=>(0,c.createElement)(g.DropdownMenu,{icon:h,label:(0,d.__)("Snow Monkey Editor Controls","snow-monkey-editor"),toggleProps:t,controls:(0,m.orderBy)(e.map((e=>{let[{props:t}]=e;return t})),"title"),popoverProps:v}))))),["sme-font-size","sme-letter-spacing","sme-line-height","sme-text-color","sme-bg-color","sme-highlighter","sme-badge"].map((e=>(0,c.createElement)(g.Slot,{name:`SnowMonkey.ToolbarControls.${e}`,key:e}))))))});const b=e=>{if(!e)return;const{name:t,settings:n}=e;(0,p.registerFormatType)(t,n)};var f=window.wp.data;function y(){return y=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var o in n)Object.prototype.hasOwnProperty.call(n,o)&&(e[o]=n[o])}return e},y.apply(this,arguments)}var C=window.wp.keycodes;function k(e){let t,{name:n,shortcutType:o,shortcutCharacter:s,...r}=e,a="SnowMonkey.ToolbarControls";return n&&(a+=`.${n}`),o&&s&&(t=C.displayShortcut[o](s)),(0,c.createElement)(g.Fill,{name:a},(0,c.createElement)(g.ToolbarButton,y({},r,{shortcut:t})))}const w=(0,d.__)("Remove formatting","snow-monkey-editor"),_={name:"snow-monkey-editor/remove-fomatting",title:w,tagName:"span",className:"sme-remove-fomatting",edit:e=>{let{value:t,onChange:n}=e;const o=(0,f.useSelect)((e=>e("core/rich-text").getFormatTypes()),[]),s=(0,c.useCallback)((()=>{if(0<o.length){let e=t;o.forEach((t=>{e=(0,p.removeFormat)(e,t.name)})),n({...e})}}),[t,o]);return(0,c.createElement)(k,{icon:"editor-removeformatting",title:w,onClick:s})}};var E=n(184),S=n.n(E);function F(e){let{ref:t,value:n,settings:o={}}=e;const{tagName:s,className:r,name:a}=o,l=a?(0,p.getActiveFormat)(n,a):void 0;return(0,c.useMemo)((()=>{if(!t.current)return;const{ownerDocument:{defaultView:e}}=t.current,n=e.getSelection();if(!n.rangeCount)return;const o=n.getRangeAt(0);if(!l)return o;let a=o.startContainer;for(a=a.nextElementSibling||a;a.nodeType!==a.ELEMENT_NODE;)a=a.parentNode;return a.closest(s+(r?"."+r:""))}),[])}const x=e=>{let{name:t,value:n,onChange:o,onClose:s}=e;const r=(0,f.useSelect)((e=>{const{getSettings:t}=e("core/block-editor");return(0,m.get)(t(),["fontSizes"],[])})),a=(0,c.useCallback)((e=>{if(e){let s;if((0,m.isString)(e)||r[0]&&(0,m.isString)(r[0].size))s=e;else{if(!(0,m.isNumber)(e))return;s=`${e}px`}const a=(0,m.find)(r,{size:e});o((0,p.applyFormat)(n,{type:t,attributes:a?{class:(0,u.getFontSizeClass)(a.slug)}:{style:`font-size: ${s}`}}))}else o((0,p.removeFormat)(n,t)),s()}),[r,o]),l=(0,c.useMemo)((()=>function(e,t,n){const o=(0,p.getActiveFormat)(t,e);if(!o)return;const s=o.attributes.style;if(s)return s.replace(new RegExp("^font-size:\\s*"),"");const r=o.attributes.class;if(r){const e=r.replace(/.*has-([^\s]*)-font-size.*/,"$1"),t=(0,m.find)(n,{slug:e});if(!t)return;return t.size}}(t,n,r)),[t,n]);return(0,c.createElement)(u.FontSizePicker,{value:l,onChange:a,fontSizes:r})};var R=(0,g.withSpokenMessages)((e=>{let{name:t,value:n,onChange:o,onClose:s,contentRef:r,settings:a}=e;const l=F({ref:r,value:n,settings:a});return(0,c.createElement)(u.URLPopover,{value:n,onClose:s,className:"sme-popover sme-popover--inline-font-size components-inline-color-popover",anchorRef:l},(0,c.createElement)("fieldset",null,(0,c.createElement)(x,{name:t,value:n,onChange:o,onClose:s})))}));const A="snow-monkey-editor/font-size",N=(0,d.__)("Font size","snow-monkey-editor"),M=[],I={name:A,title:N,tagName:"span",className:"sme-font-size",attributes:{style:"style",class:"class"},edit:e=>{const{value:t,onChange:n,isActive:o,activeAttributes:s,contentRef:r}=e,a=(0,u.useSetting)("typography.customFontSize",A),l=(0,u.useSetting)("typography.fontSizes")||M,[i,d]=(0,c.useState)(!1),h=(0,c.useCallback)((()=>d(!0)),[d]),v=(0,c.useCallback)((()=>d(!1)),[d]),b=!(0,m.isEmpty)(l)||!a;return b||o?(0,c.createElement)(c.Fragment,null,(0,c.createElement)(k,{key:o?"sme-font-size":"sme-font-size-not-active",name:o?"sme-font-size":void 0,title:N,className:S()("sme-toolbar-button",{"is-pressed":!!o}),onClick:b?h:()=>n((0,p.removeFormat)(t,A)),icon:(0,c.createElement)(g.Icon,{icon:"editor-textcolor"})}),i&&(0,c.createElement)(R,{name:A,onClose:v,activeAttributes:s,value:t,onChange:function(){n(...arguments)},contentRef:r,settings:I})):null}},O=e=>{let{name:t,title:n,value:o,onChange:s,onClose:r}=e;const a=(0,c.useCallback)((e=>{e?s((0,p.applyFormat)(o,{type:t,attributes:{style:`letter-spacing: ${e}rem`}})):(s((0,p.removeFormat)(o,t)),r())}),[s]),l=(0,c.useMemo)((()=>function(e,t){const n=(0,p.getActiveFormat)(t,e);if(!n)return;const o=n.attributes.style;return o?parseFloat(o.replace(new RegExp("^letter-spacing:\\s*"),"").replace("rem","")):void 0}(t,o)),[t,o]);return(0,c.createElement)(g.RangeControl,{label:n,value:l,onChange:a,min:"0",max:"2",step:"0.1",initialPosition:void 0,allowReset:!0})};var P=(0,g.withSpokenMessages)((e=>{let{name:t,title:n,value:o,onChange:s,onClose:r,contentRef:a,settings:l}=e;const i=F({ref:a,value:o,settings:l});return(0,c.createElement)(u.URLPopover,{value:o,onClose:r,className:"sme-popover sme-popover--inline-letter-spacing components-inline-color-popover",anchorRef:i},(0,c.createElement)("fieldset",null,(0,c.createElement)(O,{name:t,title:n,value:o,onChange:s,onClose:r})))}));const z="snow-monkey-editor/letter-spacing",T=(0,d.__)("Letter spacing","snow-monkey-editor"),$={name:z,title:T,tagName:"span",className:"sme-letter-spacing",attributes:{style:"style"},edit:e=>{const{value:t,onChange:n,isActive:o,activeAttributes:s,contentRef:r}=e,[a,l]=(0,c.useState)(!1),i=(0,c.useCallback)((()=>l(!0)),[l]),m=(0,c.useCallback)((()=>l(!1)),[l]);return(0,c.createElement)(c.Fragment,null,(0,c.createElement)(k,{key:o?"sme-letter-spacing":"sme-letter-spacing-not-active",name:o?"sme-letter-spacing":void 0,title:T,className:S()("sme-toolbar-button",{"is-pressed":!!o}),onClick:i,icon:(0,c.createElement)(g.Icon,{icon:"controls-pause"})}),a&&(0,c.createElement)(P,{name:z,title:T,onClose:m,activeAttributes:s,value:t,onChange:function(){n(...arguments)},contentRef:r,settings:$}))}},j=e=>{let{name:t,title:n,value:o,onChange:s,onClose:r}=e;const a=(0,c.useCallback)((e=>{e?s((0,p.applyFormat)(o,{type:t,attributes:{style:`line-height: ${e}`}})):(s((0,p.removeFormat)(o,t)),r())}),[s]),l=(0,c.useMemo)((()=>function(e,t){const n=(0,p.getActiveFormat)(t,e);if(!n)return;const o=n.attributes.style;return o?parseFloat(o.replace(new RegExp("^line-height:\\s*"),"")):void 0}(t,o)),[t,o]);return(0,c.createElement)(g.RangeControl,{label:n,value:l,onChange:a,min:"0",max:"5",step:"0.1",initialPosition:void 0,allowReset:!0})};var B=(0,g.withSpokenMessages)((e=>{let{name:t,title:n,value:o,onChange:s,onClose:r,contentRef:a,settings:l}=e;const i=F({ref:a,value:o,settings:l});return(0,c.createElement)(u.URLPopover,{value:o,onClose:r,className:"sme-popover sme-popover--inline-line-height components-inline-color-popover",anchorRef:i},(0,c.createElement)("fieldset",null,(0,c.createElement)(j,{name:t,title:n,value:o,onChange:s,onClose:r})))}));const L="snow-monkey-editor/line-height",V=(0,d.__)("Line height","snow-monkey-editor"),G={name:L,title:V,tagName:"span",className:"sme-line-height",attributes:{style:"style"},edit:e=>{const{value:t,onChange:n,isActive:o,activeAttributes:s,contentRef:r}=e,[a,l]=(0,c.useState)(!1),i=(0,c.useCallback)((()=>l(!0)),[l]),m=(0,c.useCallback)((()=>l(!1)),[l]);return(0,c.createElement)(c.Fragment,null,(0,c.createElement)(k,{key:o?"sme-line-height":"sme-line-height-not-active",name:o?"sme-line-height":void 0,title:V,className:S()("sme-toolbar-button",{"is-pressed":!!o}),onClick:i,icon:(0,c.createElement)(g.Icon,{icon:"editor-insertmore"})}),a&&(0,c.createElement)(B,{name:L,title:V,onClose:m,activeAttributes:s,value:t,onChange:function(){n(...arguments)},contentRef:r,settings:G}))}};function U(){const e={disableCustomColors:!(0,u.useSetting)("color.custom"),disableCustomGradients:!(0,u.useSetting)("color.customGradient")},t=(0,u.useSetting)("color.palette.custom"),n=(0,u.useSetting)("color.palette.theme"),o=(0,u.useSetting)("color.palette.default"),s=(0,u.useSetting)("color.defaultPalette");e.colors=(0,c.useMemo)((()=>{const e=[];return n&&n.length&&e.push({name:(0,d._x)("Theme","Indicates this palette comes from the theme.","snow-monkey-blocks"),colors:n}),s&&o&&o.length&&e.push({name:(0,d._x)("Default","Indicates this palette comes from WordPress.","snow-monkey-blocks"),colors:o}),t&&t.length&&e.push({name:(0,d._x)("Custom","Indicates this palette comes from the theme.","snow-monkey-blocks"),colors:t}),e}),[o,n,t]);const r=(0,u.useSetting)("color.gradients.custom"),a=(0,u.useSetting)("color.gradients.theme"),l=(0,u.useSetting)("color.gradients.default"),i=(0,u.useSetting)("color.defaultGradients");return e.gradients=(0,c.useMemo)((()=>{const e=[];return a&&a.length&&e.push({name:(0,d._x)("Theme","Indicates this palette comes from the theme.","snow-monkey-blocks"),gradients:a}),i&&l&&l.length&&e.push({name:(0,d._x)("Default","Indicates this palette comes from WordPress.","snow-monkey-blocks"),gradients:l}),r&&r.length&&e.push({name:(0,d._x)("Custom","Indicates this palette is created by the user.","snow-monkey-blocks"),gradients:r}),e}),[r,a,l]),e}function Z(e,t,n){const o=(0,p.getActiveFormat)(t,e);if(!o)return;const s=o.attributes.style;if(s)return s.replace(new RegExp("^color:\\s*"),"");const r=o.attributes.class;if(r){const e=r.replace(/.*has-([^\s]*)-color.*/,"$1");return(0,u.getColorObjectByAttributeValues)(n,e).color}}const D=e=>{let{name:t,value:n,onChange:o,onClose:s}=e;const r=(0,f.useSelect)((e=>{const{getSettings:t}=e("core/block-editor");return(0,m.get)(t(),["colors"],[])})),a=(0,c.useCallback)((e=>{if(e){const s=(0,u.getColorObjectByColorValue)(r,e);o((0,p.applyFormat)(n,{type:t,attributes:s?{class:(0,u.getColorClassName)("color",s.slug)}:{style:`color: ${e}`}}))}else o((0,p.removeFormat)(n,t)),s()}),[r,o]),l=(0,c.useMemo)((()=>Z(t,n,r)),[t,n,r]),i=U();return(0,c.createElement)(u.__experimentalColorGradientControl,y({label:(0,d.__)("Color","snow-monkey-editor"),colorValue:l,onColorChange:a},i,{__experimentalHasMultipleOrigins:!0,__experimentalIsRenderedInSidebar:!0}))};var H=(0,g.withSpokenMessages)((e=>{let{name:t,value:n,onChange:o,onClose:s,contentRef:r,settings:a}=e;const l=F({ref:r,value:n,settings:a});return(0,c.createElement)(u.URLPopover,{value:n,onClose:s,className:"sme-popover sme-popover--inline-color components-inline-color-popover",anchorRef:l},(0,c.createElement)(D,{name:t,value:n,onChange:o,onClose:s}))}));const q="snow-monkey-editor/text-color",W=(0,d.__)("Text color","snow-monkey-editor"),J=[],K={name:q,title:W,tagName:"span",className:"sme-text-color",attributes:{style:"style",class:"class"},edit:e=>{const{value:t,onChange:n,isActive:o,activeAttributes:s,contentRef:r}=e,a=(0,u.useSetting)("color.custom"),l=(0,u.useSetting)("color.palette")||J,[i,d]=(0,c.useState)(!1),h=(0,c.useCallback)((()=>d(!0)),[d]),v=(0,c.useCallback)((()=>d(!1)),[d]),b=(0,c.useMemo)((()=>Z(q,t,l)),[t,l]),f=!(0,m.isEmpty)(l)||!a;return f||o?(0,c.createElement)(c.Fragment,null,(0,c.createElement)(k,{key:o?"sme-text-color":"sme-text-color-not-active",name:o?"sme-text-color":void 0,title:W,style:{color:b},className:S()("sme-toolbar-button",{"is-pressed":!!o}),onClick:f?h:()=>n((0,p.removeFormat)(t,q)),icon:(0,c.createElement)(g.Icon,{icon:"edit"})}),i&&(0,c.createElement)(H,{name:q,onClose:v,activeAttributes:s,value:t,onChange:function(){n(...arguments)},contentRef:r,settings:K})):null}};function Q(e,t,n){const o=(0,p.getActiveFormat)(t,e);if(!o)return;const s=o.attributes.style;if(s)return s.replace(new RegExp("^background-color:\\s*"),"");const r=o.attributes.class;if(r){const e=r.replace(/.*has-([^\s]*)-background-color.*/,"$1");return(0,u.getColorObjectByAttributeValues)(n,e).color}}const X=e=>{let{name:t,value:n,onChange:o,onClose:s}=e;const r=(0,f.useSelect)((e=>{const{getSettings:t}=e("core/block-editor");return(0,m.get)(t(),["colors"],[])})),a=(0,c.useCallback)((e=>{if(e){const s=(0,u.getColorObjectByColorValue)(r,e);o((0,p.applyFormat)(n,{type:t,attributes:s?{class:(0,u.getColorClassName)("background-color",s.slug)}:{style:`background-color: ${e}`}}))}else o((0,p.removeFormat)(n,t)),s()}),[r,o]),l=(0,c.useMemo)((()=>Q(t,n,r)),[t,n,r]),i=U();return(0,c.createElement)(u.__experimentalColorGradientControl,y({label:(0,d.__)("Color","snow-monkey-editor"),colorValue:l,onColorChange:a},i,{__experimentalHasMultipleOrigins:!0,__experimentalIsRenderedInSidebar:!0}))};var Y=(0,g.withSpokenMessages)((e=>{let{name:t,value:n,onChange:o,onClose:s,contentRef:r,settings:a}=e;const l=F({ref:r,value:n,settings:a});return(0,c.createElement)(u.URLPopover,{value:n,onClose:s,className:"sme-popover sme-popover--inline-background-color components-inline-color-popover",anchorRef:l},(0,c.createElement)(X,{name:t,value:n,onChange:o,onClose:s}))}));const ee="snow-monkey-editor/bg-color",te=(0,d.__)("Background color","snow-monkey-editor"),ne=[],oe={name:ee,title:te,tagName:"span",className:"sme-bg-color",attributes:{style:"style",class:"class"},edit:e=>{const{value:t,onChange:n,isActive:o,activeAttributes:s,contentRef:r}=e,a=(0,u.useSetting)("color.custom"),l=(0,u.useSetting)("color.palette")||ne,[i,d]=(0,c.useState)(!1),h=(0,c.useCallback)((()=>d(!0)),[d]),v=(0,c.useCallback)((()=>d(!1)),[d]),b=(0,c.useMemo)((()=>Q(ee,t,l)),[t,l]),f=!(0,m.isEmpty)(l)||!a;return f||o?(0,c.createElement)(c.Fragment,null,(0,c.createElement)(k,{key:o?"sme-bg-color":"sme-bg-color-not-active",name:o?"sme-bg-color":void 0,title:te,style:{color:b},className:S()("sme-toolbar-button",{"is-pressed":!!o}),onClick:f?h:()=>n((0,p.removeFormat)(t,ee)),icon:(0,c.createElement)(g.Icon,{icon:"tag"})}),i&&(0,c.createElement)(Y,{name:ee,onClose:v,activeAttributes:s,value:t,onChange:function(){n(...arguments)},contentRef:r,settings:oe})):null}};var se=n(306),re=n.n(se),ae=n(685),le=n.n(ae);function ie(e,t){const n=(0,p.getActiveFormat)(t,e);if(!n)return;const o=n.attributes.style;if(!o)return;const s=o.match(/(#[0-9A-F]{3,6}) /i);if(s)return s;const r=o.match(/,\s*?(rgba?\([^)]+\)) /i);return r?function(e){if(!e||4===e.length)return e;const t=e.match(/^#([0-9A-F])\1([0-9A-F])\1([0-9A-F])\1$/i);return t?`#${t[1].slice(0,1)}${t[2].slice(0,1)}${t[3].slice(0,1)}`:e}(re()(r[1]).hex):void 0}const ce=e=>{let{name:t,value:n,onChange:o,onClose:s}=e;const r=(0,f.useSelect)((e=>{const{getSettings:t}=e("core/block-editor");return(0,m.get)(t(),["colors"],[])})),a=(0,c.useCallback)((e=>{e?o((0,p.applyFormat)(n,{type:t,attributes:{style:`background-image: linear-gradient(transparent 60%, ${le()(e,.5)} 60%)`}})):(o((0,p.removeFormat)(n,t)),s())}),[r,o]),l=(0,c.useMemo)((()=>ie(t,n)),[t,n,r]),i=U();return(0,c.createElement)(u.__experimentalColorGradientControl,y({label:(0,d.__)("Color","snow-monkey-editor"),colorValue:l,onColorChange:a},i,{__experimentalHasMultipleOrigins:!0,__experimentalIsRenderedInSidebar:!0}))};var me=(0,g.withSpokenMessages)((e=>{let{name:t,value:n,onChange:o,onClose:s,contentRef:r,settings:a}=e;const l=F({ref:r,value:n,settings:a});return(0,c.createElement)(u.URLPopover,{value:n,onClose:s,className:"sme-popover sme-popover--inline-color components-inline-color-popover",anchorRef:l},(0,c.createElement)(ce,{name:t,value:n,onChange:o,onClose:s}))}));const ue="snow-monkey-editor/highlighter",ge=(0,d.__)("Highlighter","snow-monkey-editor"),pe=[],de={name:ue,title:ge,tagName:"span",className:"sme-highlighter",attributes:{style:"style"},edit:e=>{const{value:t,onChange:n,isActive:o,activeAttributes:s,contentRef:r}=e,a=(0,u.useSetting)("color.custom"),l=(0,u.useSetting)("color.palette")||pe,[i,d]=(0,c.useState)(!1),h=(0,c.useCallback)((()=>d(!0)),[d]),v=(0,c.useCallback)((()=>d(!1)),[d]),b=(0,c.useMemo)((()=>ie(ue,t)),[t,l]),f=!(0,m.isEmpty)(l)||!a;return f||o?(0,c.createElement)(c.Fragment,null,(0,c.createElement)(k,{key:o?"sme-highlighter":"sme-highlighter-not-active",name:o?"sme-highlighter":void 0,title:ge,style:{color:b},className:S()("sme-toolbar-button",{"is-pressed":!!o}),onClick:f?h:()=>n((0,p.removeFormat)(t,ue)),icon:(0,c.createElement)(g.Icon,{icon:"tag"})}),i&&(0,c.createElement)(me,{name:ue,onClose:v,activeAttributes:s,value:t,onChange:function(){n(...arguments)},contentRef:r,settings:de})):null}},he="snow-monkey-editor/badge",ve=(0,d.__)("Badge","snow-monkey-editor"),be=[],fe={name:he,title:ve,tagName:"span",className:"sme-badge",attributes:{style:"style",class:"class"},edit:e=>{const{value:t,onChange:n,isActive:o,activeAttributes:s,contentRef:r}=e,a=(0,u.useSetting)("color.custom"),l=(0,u.useSetting)("color.palette")||be,[i,d]=(0,c.useState)(!1),h=(0,c.useCallback)((()=>d(!0)),[d]),v=(0,c.useCallback)((()=>d(!1)),[d]),b=(0,c.useMemo)((()=>Q(he,t,l)),[t,l]),f=!(0,m.isEmpty)(l)||!a;return f||o?(0,c.createElement)(c.Fragment,null,(0,c.createElement)(k,{key:o?"sme-badge":"sme-badge-not-active",name:o?"sme-badge":void 0,title:ve,style:{color:b},className:S()("sme-toolbar-button",{"is-pressed":!!o}),onClick:f?h:()=>n((0,p.removeFormat)(t,he)),icon:(0,c.createElement)(g.Icon,{icon:"tag"})}),i&&(0,c.createElement)(Y,{name:he,onClose:v,activeAttributes:s,value:t,onChange:function(){n(...arguments)},contentRef:r,settings:fe})):null}};b(e),b(t),b(o),b(s),b(r),b(a),b(l),b(i);var ye=window.wp.blocks,Ce=window.wp.hooks,ke=window.wp.compose,we={name:"sme-alert",label:(0,d.__)("Alert","snow-monkey-editor")},_e=["core/group","core/paragraph"].map((e=>({name:e,settings:we}))),Ee={name:"sme-alert-success",label:(0,d.__)("Alert (Success)","snow-monkey-editor")},Se=["core/group","core/paragraph"].map((e=>({name:e,settings:Ee}))),Fe={name:"sme-alert-warning",label:(0,d.__)("Alert (Warning)","snow-monkey-editor")},xe=["core/group","core/paragraph"].map((e=>({name:e,settings:Fe}))),Re={name:"sme-alert-remark",label:(0,d.__)("Alert (Remarks)","snow-monkey-editor")},Ae=["core/group","core/paragraph"].map((e=>({name:e,settings:Re}))),Ne={name:"sme-block-code-nowrap",label:(0,d.__)("No wrap","snow-monkey-editor")},Me=["core/code"].map((e=>({name:e,settings:Ne}))),Ie={name:"sme-block-code-wrap",label:(0,d.__)("Wrap","snow-monkey-editor")},Oe=["core/code"].map((e=>({name:e,settings:Ie}))),Pe={name:"sme-fluid-shape-1",label:(0,d.__)("Fluid Shape 1","snow-monkey-editor")},ze=["core/image","core/media-text"].map((e=>({name:e,settings:Pe}))),Te={name:"sme-fluid-shape-2",label:(0,d.__)("Fluid Shape 2","snow-monkey-editor")},$e=["core/image","core/media-text"].map((e=>({name:e,settings:Te}))),je={name:"sme-fluid-shape-3",label:(0,d.__)("Fluid Shape 3","snow-monkey-editor")},Be=["core/image","core/media-text"].map((e=>({name:e,settings:je}))),Le={name:"sme-list-arrow",label:(0,d.__)("Arrow","snow-monkey-editor")},Ve=["core/list"].map((e=>({name:e,settings:Le}))),Ge={name:"sme-list-check",label:(0,d.__)("Check","snow-monkey-editor")},Ue=["core/list"].map((e=>({name:e,settings:Ge}))),Ze={name:"sme-list-remark",label:(0,d.__)("Remarks","snow-monkey-editor")},De=["core/list"].map((e=>({name:e,settings:Ze}))),He={name:"sme-list-times",label:(0,d.__)("Times","snow-monkey-editor")},qe=["core/list"].map((e=>({name:e,settings:He}))),We={name:"sme-ordered-list-circle",label:(0,d.__)("Ordered list (Circle)","snow-monkey-editor")},Je=["core/list"].map((e=>({name:e,settings:We}))),Ke={name:"sme-ordered-list-square",label:(0,d.__)("Ordered list (Square)","snow-monkey-editor")},Qe=["core/list"].map((e=>({name:e,settings:Ke}))),Xe={name:"sme-post-it",label:(0,d.__)("Post-it","snow-monkey-editor")},Ye=["core/paragraph"].map((e=>({name:e,settings:Xe}))),et={name:"sme-post-it-narrow",label:(0,d.__)("Post-it (Narrow)","snow-monkey-editor")},tt=["core/paragraph"].map((e=>({name:e,settings:et}))),nt={name:"sme-shadowed",label:(0,d.__)("Shadowed","snow-monkey-editor")},ot=["core/image","core/button"].map((e=>({name:e,settings:nt}))),st={name:"sme-speech",label:(0,d.__)("Speech","snow-monkey-editor")};[_e,Se,xe,Ae,Me,Oe,ze,$e,Be,Ve,Ue,De,qe,Je,Qe,Ye,tt,ot,["core/paragraph"].map((e=>({name:e,settings:st})))].forEach((e=>{e.forEach((e=>(e=>{if(!e)return;const{name:t,settings:n}=e;(0,ye.registerBlockStyle)(t,n)})(e)))})),(0,Ce.addFilter)("editor.BlockEdit","snow-monkey-editor/ordered-list/block-edit",(0,ke.createHigherOrderComponent)((e=>t=>{const{attributes:n,name:o,clientId:s}=t,{start:r,reversed:a,ordered:l}=n;if("core/list"!==o)return(0,c.createElement)(e,t);const i=document.querySelector(`[data-block="${s}"].rich-text`);return i?i.classList.contains("is-style-sme-ordered-list-square")||i.classList.contains("is-style-sme-ordered-list-circle")?(i.style.counterReset=l?a?`sme-count ${r+1}`:"sme-count "+(r-1):"",(0,c.createElement)(e,t)):(i.style.counterReset="",(0,c.createElement)(e,t)):(0,c.createElement)(e,t)}),"withSnowMonkeyEditorOrderdListBlockEdit"))}()}();