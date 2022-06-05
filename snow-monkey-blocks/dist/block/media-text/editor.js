!function(){var e={184:function(e,t){var i;!function(){"use strict";var a={}.hasOwnProperty;function l(){for(var e=[],t=0;t<arguments.length;t++){var i=arguments[t];if(i){var r=typeof i;if("string"===r||"number"===r)e.push(i);else if(Array.isArray(i)){if(i.length){var o=l.apply(null,i);o&&e.push(o)}}else if("object"===r)if(i.toString===Object.prototype.toString)for(var n in i)a.call(i,n)&&i[n]&&e.push(n);else e.push(i.toString())}}return e.join(" ")}e.exports?(l.default=l,e.exports=l):void 0===(i=function(){return l}.apply(t,[]))||(e.exports=i)}()}},t={};function i(a){var l=t[a];if(void 0!==l)return l.exports;var r=t[a]={exports:{}};return e[a](r,r.exports,i),r.exports}i.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return i.d(t,{a:t}),t},i.d=function(e,t){for(var a in t)i.o(t,a)&&!i.o(e,a)&&Object.defineProperty(e,a,{enumerable:!0,get:t[a]})},i.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},i.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},function(){"use strict";var e={};i.r(e),i.d(e,{metadata:function(){return g},name:function(){return W},settings:function(){return A}});var t=window.wp.element,a=window.lodash,l=window.wp.blocks,r=(window.wp.richText,window.wp.i18n);const o=e=>{let t="1-3",i="2-3";return 75===parseInt(e)?(t="1-4",i="3-4"):66===parseInt(e)?(t="1-3",i="2-3"):50===parseInt(e)?(t="1-2",i="1-2"):33===parseInt(e)?(t="2-3",i="1-3"):25===parseInt(e)&&(t="3-4",i="1-4"),{textColumnWidth:t,mediaColumnWidth:i,imageColumnWidth:i}},n=e=>e.media_type?"image"===e.media_type?"image":"video":e.type,s=(e,t)=>t?(0,a.reduce)(e,((e,i)=>{const l=(0,a.get)(t,["sizes",i.slug,"url"]),r=(0,a.get)(t,["media_details","sizes",i.slug,"source_url"]),o=(0,a.get)(t,["sizes",i.slug,"width"]),n=(0,a.get)(t,["media_details","sizes",i.slug,"width"]),s=(0,a.get)(t,["sizes",i.slug,"height"]),m=(0,a.get)(t,["media_details","sizes",i.slug,"height"]);return{...e,[i.slug]:{url:l||r,width:o||n,height:s||m}}}),{}):{},m=["avi","mpg","mpeg","mov","mp4","m4v","ogg","ogv","webm","wmv"];function c(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";const t=e.split(".");return t[t.length-1]}function d(){let e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";return!!e&&m.includes(c(e))}var g=JSON.parse('{"apiVersion":2,"name":"snow-monkey-blocks/media-text","title":"Media & text","description":"Set media and words side-by-side for a richer layout.","category":"smb","attributes":{"titleTagName":{"type":"string","default":"h2"},"title":{"type":"string","source":"html","selector":".smb-media-text__title","default":""},"mediaId":{"type":"number","default":0},"mediaUrl":{"type":"string","source":"attribute","selector":".smb-media-text__figure img, .smb-media-text__figure video","attribute":"src","default":""},"mediaLink":{"type":"string"},"mediaAlt":{"type":"string","source":"attribute","selector":".smb-media-text__figure img","attribute":"alt","default":""},"mediaWidth":{"type":"string","source":"attribute","selector":".smb-media-text__figure img, .smb-media-text__figure video","attribute":"width","default":""},"mediaHeight":{"type":"string","source":"attribute","selector":".smb-media-text__figure img, .smb-media-text__figure video","attribute":"height","default":""},"mediaSizeSlug":{"type":"string","default":"large"},"mediaType":{"type":"string"},"caption":{"type":"string","source":"html","selector":".smb-media-text__caption","default":""},"mediaPosition":{"type":"string","default":"right"},"verticalAlignment":{"type":"string","default":"center"},"mediaColumnSize":{"type":"string","default":66},"mobileOrder":{"type":"string"},"href":{"type":"string","default":""},"rel":{"type":"string","source":"attribute","selector":".smb-media-text__figure > a","attribute":"rel"},"linkClass":{"type":"string","source":"attribute","selector":".smb-media-text__figure > a","attribute":"class"},"linkDestination":{"type":"string"},"linkTarget":{"type":"string","source":"attribute","selector":".smb-media-text__figure > a","attribute":"target","default":"_self"}},"supports":{"anchor":true},"style":"snow-monkey-blocks/media-text","editorScript":"file:../../dist/block/media-text/editor.js"}'),u=(0,t.createElement)("svg",{viewBox:"0 0 24 24"},(0,t.createElement)("path",{d:"M0,7.11v9.78a.61.61,0,0,0,.61.61h9.78a.61.61,0,0,0,.61-.61V7.11a.61.61,0,0,0-.61-.61H.61A.61.61,0,0,0,0,7.11m9.78,9.47H1.22a.29.29,0,0,1-.3-.3V7.72a.29.29,0,0,1,.3-.3H9.78a.29.29,0,0,1,.3.3v8.56a.29.29,0,0,1-.3.3"}),(0,t.createElement)("path",{d:"M.92,13.7,3.33,12a.15.15,0,0,1,.17,0l1.84,1.18a.15.15,0,0,0,.19,0l2.31-2.22a.15.15,0,0,1,.21,0l2.43,2.37v.91L8.05,11.8a.14.14,0,0,0-.21,0L5.53,14a.17.17,0,0,1-.19,0L3.5,12.87a.15.15,0,0,0-.18,0L.92,14.62Z"}),(0,t.createElement)("rect",{y:"6.5",width:"11",height:"11",fill:"none"}),(0,t.createElement)("rect",{x:"13.5",y:"8.5",width:"10.5",height:"1"}),(0,t.createElement)("rect",{x:"13.5",y:"11.5",width:"10.5",height:"1"}),(0,t.createElement)("rect",{x:"13.5",y:"14.5",width:"10.5",height:"1"})),_=i(184),p=i.n(_),b=window.wp.data,h=window.wp.components,y=window.wp.blockEditor,f=window.wp.primitives,w=(0,t.createElement)(f.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,t.createElement)(f.Path,{d:"M4 18h6V6H4v12zm9-9.5V10h7V8.5h-7zm0 7h7V14h-7v1.5z"})),v=(0,t.createElement)(f.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,t.createElement)(f.Path,{d:"M14 6v12h6V6h-6zM4 10h7V8.5H4V10zm0 5.5h7V14H4v1.5z"}));function k(){return k=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var i=arguments[t];for(var a in i)Object.prototype.hasOwnProperty.call(i,a)&&(e[a]=i[a])}return e},k.apply(this,arguments)}const x=e=>{let{id:i,src:a,allowedTypes:l,accept:o,onSelect:n,onSelectURL:s,onRemove:m}=e;return(0,t.createElement)(y.BlockControls,{group:"inline"},(0,t.createElement)(y.MediaReplaceFlow,{mediaId:i,mediaURL:a,allowedTypes:l,accept:o,onSelect:n,onSelectURL:s}),!!a&&!!m&&(0,t.createElement)(h.ToolbarItem,{as:h.Button,onClick:m},(0,r.__)("Release","snow-monkey-blocks")))},E=e=>{let{src:i,alt:a,id:l,style:r}=e;return(0,t.createElement)("img",{src:i,alt:a,className:`wp-image-${l}`,style:r})},S=e=>{let{src:i,style:a}=e;return(0,t.createElement)("video",{controls:!0,src:i,style:a})},T=(0,t.memo)((e=>{let i,{id:l,src:r,alt:o,url:n,target:s,allowedTypes:m,accept:c,onSelect:d,onSelectURL:g,onRemove:u,mediaType:_,style:p,rel:b,linkClass:h}=e;if("image"===_){let e;i=(0,t.createElement)(E,{src:r,alt:o,id:l,style:p}),e=b?(0,a.isEmpty)(b)?void 0:b:"_self"!==s&&s?"noopener noreferrer":void 0,n&&(i=(0,t.createElement)("span",{href:n,target:"_self"===s?void 0:s,rel:e,className:h},i))}else"video"===_&&(i=(0,t.createElement)(S,{src:r,style:p}));return(0,t.createElement)(t.Fragment,null,(0,t.createElement)(x,{id:l,src:r,allowedTypes:m,accept:c,onSelect:d,onSelectURL:g,onRemove:u}),i)}),((e,t)=>{const i=Object.keys(e);for(const a of i)if(e[a]!==t[a])return!1;return!0}));function C(e){const{src:i,onSelect:a,onSelectURL:l,mediaType:o,allowedTypes:n=["image"]}=e,s=!o&&i?"image":o;let m=(0,r.__)("Media","snow-monkey-blocks");1===n.length&&("image"===n[0]?m=(0,r.__)("Image","snow-monkey-blocks"):"video"===n[0]&&(m=(0,r.__)("Video","snow-monkey-blocks")));const c=(0,t.useMemo)((()=>n.map((e=>`${e}/*`)).join(",")),[n]);return i?(0,t.createElement)(T,k({},e,{accept:c,allowedTypes:n,mediaType:s})):(0,t.createElement)(y.MediaPlaceholder,{icon:"format-image",labels:{title:m},onSelect:a,onSelectURL:l,accept:c,allowedTypes:n})}function N(e){const{label:i,id:a,slug:l,onChange:r}=e;if(!a)return null;const{options:o}=(0,b.useSelect)((e=>{const{getMedia:t}=e("core"),i=t(a);if(!i)return{options:[]};const{getSettings:l}=e("core/block-editor"),{imageSizes:r}=l(),o=s(r,i);return{options:r.map((e=>!!o[e.slug]&&{value:e.slug,label:e.name})).filter((e=>e))}}));return 1>o.length?null:(0,t.createElement)(h.SelectControl,{label:i,value:l,options:o,onChange:r})}const z=["image","video"],I="media",R="attachment",B=g.attributes;var U=[{attributes:{...B,url:{type:"string",default:""},imageMediaType:{type:"string"},imageSizeSlug:{type:"string",default:"large"},imagePosition:{type:"string",default:"right"},imageID:{type:"number",default:0},imageURL:{type:"string",source:"attribute",selector:".smb-media-text__figure img, .smb-media-text__figure video",attribute:"src",default:""},imageAlt:{type:"string",source:"attribute",selector:".smb-media-text__figure img",attribute:"alt",default:""},imageWidth:{type:"string",source:"attribute",selector:".smb-media-text__figure img, .smb-media-text__figure video",attribute:"width",default:""},imageHeight:{type:"string",source:"attribute",selector:".smb-media-text__figure img, .smb-media-text__figure video",attribute:"height",default:""},imageColumnSize:{type:"string",default:66},target:{type:"string",default:"_self"}},migrate:e=>({...e,href:e.url,mediaType:e.imageMediaType,mediaSizeSlug:e.imageSizeSlug,mediaPosition:e.imagePosition,mediaId:e.imageID,mediaUrl:e.imageURL,mediaAlt:e.imageAlt,mediaWidth:e.imageWidth,mediaHeight:e.imageHeight,mediaColumnSize:e.imageColumnSize,linkTarget:e.target}),save(e){let{attributes:i,className:a}=e;const{titleTagName:l,title:r,imageID:n,imageURL:s,imageAlt:m,imageWidth:c,imageHeight:d,imageMediaType:g,caption:u,imagePosition:_,verticalAlignment:b,imageColumnSize:h,mobileOrder:f,url:w,target:v}=i,{textColumnWidth:k,imageColumnWidth:x}=o(h),E=p()("smb-media-text",a,{[`smb-media-text--mobile-${f}`]:!!f}),S=p()("c-row","c-row--margin",{"c-row--reverse":"left"===_,"c-row--top":"top"===b,"c-row--middle":"center"===b,"c-row--bottom":"bottom"===b}),T=p()("c-row__col","c-row__col--1-1",[`c-row__col--lg-${k}`]),C=p()("c-row__col","c-row__col--1-1",[`c-row__col--lg-${x}`]),N=(0,t.createElement)("img",{src:s,alt:m,width:!!c&&c,height:!!d&&d,className:`wp-image-${n}`}),z=(0,t.createElement)("video",{controls:!0,src:s,width:!!c&&c,height:!!d&&d});let I;return s&&("image"===g||void 0===g?I=w?(0,t.createElement)("a",{href:w,target:"_self"===v?void 0:v,rel:"_self"===v?void 0:"noopener noreferrer"},N):N:"video"===g&&(I=z)),(0,t.createElement)("div",y.useBlockProps.save({className:E}),(0,t.createElement)("div",{className:S},(0,t.createElement)("div",{className:T},!y.RichText.isEmpty(r)&&"none"!==l&&(0,t.createElement)(y.RichText.Content,{className:"smb-media-text__title",tagName:l,value:r}),(0,t.createElement)("div",{className:"smb-media-text__body"},(0,t.createElement)(y.InnerBlocks.Content,null))),(0,t.createElement)("div",{className:C},(0,t.createElement)("div",{className:"smb-media-text__figure"},I),!y.RichText.isEmpty(u)&&(0,t.createElement)("div",{className:"smb-media-text__caption"},(0,t.createElement)(y.RichText.Content,{value:u})))))}},{attributes:{...B,url:{type:"string",default:""},imageMediaType:{type:"string"},imageSizeSlug:{type:"string",default:"large"},imagePosition:{type:"string",default:"right"},imageID:{type:"number",default:0},imageURL:{type:"string",source:"attribute",selector:".smb-media-text__figure img, .smb-media-text__figure video",attribute:"src",default:""},imageAlt:{type:"string",source:"attribute",selector:".smb-media-text__figure img",attribute:"alt",default:""},imageWidth:{type:"string",source:"attribute",selector:".smb-media-text__figure img, .smb-media-text__figure video",attribute:"width",default:""},imageHeight:{type:"string",source:"attribute",selector:".smb-media-text__figure img, .smb-media-text__figure video",attribute:"height",default:""},imageColumnSize:{type:"string",default:66},target:{type:"string",default:"_self"}},save(e){let{attributes:i}=e;const{title:a,imageID:l,imageURL:r,imagePosition:n,imageColumnSize:s}=i,{textColumnWidth:m,imageColumnWidth:c}=o(s);return(0,t.createElement)("div",{className:"smb-media-text"},(0,t.createElement)("div",{className:p()("c-row","c-row--margin","c-row--middle",{"c-row--reverse":"left"===n})},(0,t.createElement)("div",{className:`c-row__col c-row__col--1-1 c-row__col--lg-${m}`},!y.RichText.isEmpty(a)&&(0,t.createElement)("h2",{className:"smb-media-text__title"},(0,t.createElement)(y.RichText.Content,{value:a})),(0,t.createElement)("div",{className:"smb-media-text__body"},(0,t.createElement)(y.InnerBlocks.Content,null))),(0,t.createElement)("div",{className:`c-row__col c-row__col--1-1 c-row__col--lg-${c}`},(0,t.createElement)("div",{className:"smb-media-text__figure"},r&&(0,t.createElement)("img",{src:r,alt:"",className:`wp-image-${l}`})))))}},{attributes:{...B,url:{type:"string",default:""},imageMediaType:{type:"string"},imageSizeSlug:{type:"string",default:"large"},imagePosition:{type:"string",default:"right"},imageID:{type:"number",default:0},imageURL:{type:"string",source:"attribute",selector:".smb-media-text__figure img, .smb-media-text__figure video",attribute:"src",default:""},imageAlt:{type:"string",source:"attribute",selector:".smb-media-text__figure img",attribute:"alt",default:""},imageWidth:{type:"string",source:"attribute",selector:".smb-media-text__figure img, .smb-media-text__figure video",attribute:"width",default:""},imageHeight:{type:"string",source:"attribute",selector:".smb-media-text__figure img, .smb-media-text__figure video",attribute:"height",default:""},imageColumnSize:{type:"string",default:66},target:{type:"string",default:"_self"}},save(e){let{attributes:i}=e;const{title:a,imageURL:l,imagePosition:r,imageColumnSize:n}=i,{textColumnWidth:s,imageColumnWidth:m}=o(n);return(0,t.createElement)("div",{className:"smb-media-text"},(0,t.createElement)("div",{className:p()("c-row","c-row--margin","c-row--middle",{"c-row--reverse":"left"===r})},(0,t.createElement)("div",{className:`c-row__col c-row__col--1-1 c-row__col--lg-${s}`},!y.RichText.isEmpty(a)&&(0,t.createElement)("h2",{className:"smb-media-text__title"},(0,t.createElement)(y.RichText.Content,{value:a})),(0,t.createElement)("div",{className:"smb-media-text__body"},(0,t.createElement)(y.InnerBlocks.Content,null))),(0,t.createElement)("div",{className:`c-row__col c-row__col--1-1 c-row__col--lg-${m}`},(0,t.createElement)("div",{className:"smb-media-text__figure"},l&&(0,t.createElement)("img",{src:l,alt:""})))))}}],P={attributes:{mediaId:1,mediaUrl:`${smb.pluginUrl}/dist/img/photos/man-guy-photographer1579.jpg`},innerBlocks:[{name:"core/paragraph",attributes:{content:"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam"}}]};const{name:W}=g,A={icon:{foreground:"#cd162c",src:u},keywords:[(0,r.__)("Image","snow-monkey-blocks"),(0,r.__)("Video","snow-monkey-blocks"),(0,r.__)("Media & sentence","snow-monkey-blocks")],edit:function(e){let{attributes:i,setAttributes:l,isSelected:m,className:c,clientId:g}=e;const{titleTagName:u,title:_,mediaId:f,mediaUrl:k,mediaAlt:x,mediaWidth:E,mediaHeight:S,mediaSizeSlug:T,caption:B,mediaPosition:U,verticalAlignment:P,mediaColumnSize:W,mobileOrder:A,href:L,linkTarget:M,rel:$,linkClass:H,linkDestination:O,mediaType:V}=i,{resizedImages:j,image:D}=(0,b.useSelect)((e=>{if(!f)return{resizedImages:{}};const{getMedia:t}=e("core"),i=t(f);if(!i)return{resizedImages:{}};const{getSettings:a}=e("core/block-editor"),{imageSizes:l}=a();return{image:i,resizedImages:s(l,i)}}),[f]),F=(0,b.useSelect)((e=>{const{getBlock:t}=e("core/block-editor"),i=t(g);return!(!i||!i.innerBlocks.length)}),[g]),G=["h1","h2","h3","none"],{textColumnWidth:q,mediaColumnWidth:J}=o(W),Z=p()("smb-media-text",c,{[`smb-media-text--mobile-${A}`]:!!A}),K=p()("c-row","c-row--margin",{"c-row--reverse":"left"===U,"c-row--top":"top"===P,"c-row--middle":"center"===P,"c-row--bottom":"bottom"===P}),Q=p()("c-row__col","c-row__col--1-1",[`c-row__col--lg-${q}`]),X=p()("c-row__col","c-row__col--1-1",[`c-row__col--lg-${J}`]),Y=(0,y.useBlockProps)({className:Z}),ee=(0,y.useInnerBlocksProps)({className:"smb-media-text__body"},{renderAppender:F?y.InnerBlocks.DefaultBlockAppender:y.InnerBlocks.ButtonBlockAppender});return(0,t.createElement)(t.Fragment,null,(0,t.createElement)(y.InspectorControls,null,(0,t.createElement)(h.PanelBody,{title:(0,r.__)("Block settings","snow-monkey-blocks")},(0,t.createElement)(h.SelectControl,{label:(0,r.__)("Image column size","snow-monkey-blocks"),value:W,options:[{value:66,label:(0,r.__)("66%","snow-monkey-blocks")},{value:50,label:(0,r.__)("50%","snow-monkey-blocks")},{value:33,label:(0,r.__)("33%","snow-monkey-blocks")},{value:25,label:(0,r.__)("25%","snow-monkey-blocks")}],onChange:e=>l({mediaColumnSize:e})}),(0,t.createElement)(N,{label:(0,r.__)("Images size","snow-monkey-blocks"),id:f,slug:T,onChange:e=>{let t=k;j[e]&&j[e].url&&(t=j[e].url);let i=E;j[e]&&j[e].width&&(i=j[e].width);let a=S;j[e]&&j[e].height&&(a=j[e].height),l({mediaUrl:t,mediaWidth:i,mediaHeight:a,mediaSizeSlug:e})}}),(0,t.createElement)(h.SelectControl,{label:(0,r.__)("Sort by mobile","snow-monkey-blocks"),value:A,options:[{value:"",label:(0,r.__)("Default","snow-monkey-blocks")},{value:"text",label:(0,r.__)("Text > Image","snow-monkey-blocks")},{value:"image",label:(0,r.__)("Image > Text","snow-monkey-blocks")}],onChange:e=>l({mobileOrder:""===e?void 0:e})}),(0,t.createElement)(h.BaseControl,{label:(0,r.__)("Title tag","snow-monkey-blocks"),id:"snow-monkey-blocks/media-text/title-tag-name"},(0,t.createElement)("div",{className:"smb-list-icon-selector"},(0,a.times)(G.length,(e=>{const i=u===G[e];return(0,t.createElement)(h.Button,{isPrimary:i,isSecondary:!i,onClick:()=>l({titleTagName:G[e]}),key:e},G[e])})))))),(0,t.createElement)(y.BlockControls,{gruop:"block"},(0,t.createElement)(y.BlockVerticalAlignmentToolbar,{onChange:e=>l({verticalAlignment:e}),value:P}),(0,t.createElement)(h.ToolbarGroup,null,(0,t.createElement)(h.ToolbarButton,{icon:w,title:(0,r.__)("Show media on left","snow-monkey-blocks"),isActive:"left"===U,onClick:()=>l({mediaPosition:"left"})}),(0,t.createElement)(h.ToolbarButton,{icon:v,title:(0,r.__)("Show media on right","snow-monkey-blocks"),isActive:"right"===U,onClick:()=>l({mediaPosition:"right"})}),k&&("image"===V||void 0===V)&&(0,t.createElement)(y.__experimentalImageURLInputUI,{url:L||"",onChangeUrl:e=>{l(e)},linkDestination:O,mediaType:V,mediaUrl:k,mediaLink:D&&D.link,linkTarget:M,linkClass:H,rel:$}))),(0,t.createElement)("div",Y,(0,t.createElement)("div",{className:K},(0,t.createElement)("div",{className:Q},(!y.RichText.isEmpty(_)||m)&&"none"!==u&&(0,t.createElement)(y.RichText,{className:"smb-media-text__title",tagName:u,value:_,onChange:e=>l({title:e}),placeholder:(0,r.__)("Write title…","snow-monkey-blocks")}),(0,t.createElement)("div",ee)),(0,t.createElement)("div",{className:X},(0,t.createElement)("div",{className:"smb-media-text__figure"},(0,t.createElement)(C,{src:k,id:f,alt:x,url:L,target:M,onSelect:e=>{const t=e.sizes&&e.sizes[T]?e.sizes[T].url:e.url,i=e.sizes&&e.sizes[T]?e.sizes[T].width:e.width,a=e.sizes&&e.sizes[T]?e.sizes[T].height:e.height;let r=L;O===I&&(r=e.url),O===R&&(r=e.link),l({mediaType:n(e),mediaLink:e.link||void 0,mediaId:e.id,mediaUrl:t,mediaAlt:e.alt,mediaWidth:i,mediaHeight:a,href:r})},onSelectURL:e=>{if(e!==k){let t=L;O===I&&(t=e),O===R&&(t=""),l({mediaUrl:e,mediaId:0,mediaSizeSlug:"large",mediaType:n({media_type:d(e)?"video":"image"}),href:t})}},onRemove:()=>{l({mediaUrl:"",mediaAlt:"",mediaWidth:"",mediaHeight:"",mediaId:0,mediaType:void 0,href:"",linkDestination:""})},mediaType:V,allowedTypes:z,linkClass:H,rel:$})),(!y.RichText.isEmpty(B)||m)&&(0,t.createElement)(y.RichText,{className:"smb-media-text__caption",placeholder:(0,r.__)("Write caption…","snow-monkey-blocks"),value:B,onChange:e=>l({caption:e})})))))},save:function(e){let{attributes:i,className:l}=e;const{titleTagName:r,title:n,mediaId:s,mediaUrl:m,mediaAlt:c,mediaWidth:d,mediaHeight:g,mediaType:u,caption:_,mediaPosition:b,verticalAlignment:h,mediaColumnSize:f,mobileOrder:w,href:v,rel:k,linkClass:x,linkTarget:E}=i,S=(0,a.isEmpty)(k)?void 0:k,{textColumnWidth:T,mediaColumnWidth:C}=o(f),N=p()("smb-media-text",l,{[`smb-media-text--mobile-${w}`]:!!w}),z=p()("c-row","c-row--margin",{"c-row--reverse":"left"===b,"c-row--top":"top"===h,"c-row--middle":"center"===h,"c-row--bottom":"bottom"===h}),I=p()("c-row__col","c-row__col--1-1",[`c-row__col--lg-${T}`]),R=p()("c-row__col","c-row__col--1-1",[`c-row__col--lg-${C}`]),B=(0,t.createElement)("img",{src:m,alt:c,width:!!d&&d,height:!!g&&g,className:`wp-image-${s}`}),U=(0,t.createElement)("video",{controls:!0,src:m,width:!!d&&d,height:!!g&&g});let P;return m&&("image"===u||void 0===u?P=v?(0,t.createElement)("a",{href:v,target:E,className:x,rel:S},B):B:"video"===u&&(P=U)),(0,t.createElement)("div",y.useBlockProps.save({className:N}),(0,t.createElement)("div",{className:z},(0,t.createElement)("div",{className:I},!y.RichText.isEmpty(n)&&"none"!==r&&(0,t.createElement)(y.RichText.Content,{className:"smb-media-text__title",tagName:r,value:n}),(0,t.createElement)("div",y.useInnerBlocksProps.save({className:"smb-media-text__body"}))),(0,t.createElement)("div",{className:R},(0,t.createElement)("div",{className:"smb-media-text__figure"},P),!y.RichText.isEmpty(_)&&(0,t.createElement)(y.RichText.Content,{tagName:"div",className:"smb-media-text__caption",value:_}))))},deprecated:U,example:P};(e=>{if(!e)return;const{metadata:t,settings:i,name:a}=e;t&&(t.title&&(t.title=(0,r.__)(t.title,"snow-monkey-blocks"),i.title=t.title),t.description&&(t.description=(0,r.__)(t.description,"snow-monkey-blocks"),i.description=t.description),t.keywords&&(t.keywords=(0,r.__)(t.keywords,"snow-monkey-blocks"),i.keywords=t.keywords)),(0,l.registerBlockType)({name:a,...t},i)})(e)}()}();