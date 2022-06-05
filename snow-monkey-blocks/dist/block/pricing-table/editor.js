!function(){var e={184:function(e,t){var n;!function(){"use strict";var r={}.hasOwnProperty;function l(){for(var e=[],t=0;t<arguments.length;t++){var n=arguments[t];if(n){var i=typeof n;if("string"===i||"number"===i)e.push(n);else if(Array.isArray(n)){if(n.length){var a=l.apply(null,n);a&&e.push(a)}}else if("object"===i)if(n.toString===Object.prototype.toString)for(var o in n)r.call(n,o)&&n[o]&&e.push(o);else e.push(n.toString())}}return e.join(" ")}e.exports?(l.default=l,e.exports=l):void 0===(n=function(){return l}.apply(t,[]))||(e.exports=n)}()}},t={};function n(r){var l=t[r];if(void 0!==l)return l.exports;var i=t[r]={exports:{}};return e[r](i,i.exports,n),i.exports}n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,{a:t}),t},n.d=function(e,t){for(var r in t)n.o(t,r)&&!n.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},function(){"use strict";var e={};n.r(e),n.d(e,{metadata:function(){return a},name:function(){return y},settings:function(){return v}});var t=window.wp.element,r=window.lodash,l=window.wp.blocks,i=(window.wp.richText,window.wp.i18n),a=JSON.parse('{"apiVersion":2,"name":"snow-monkey-blocks/pricing-table","title":"Pricing table","description":"Let\'s present the rate plan in an easy-to-understand manner.","category":"smb","attributes":{"columnSize":{"type":"string"},"childrenCount":{"type":"number","default":0}},"supports":{"html":false},"style":"snow-monkey-blocks/pricing-table","editorScript":"file:../../dist/block/pricing-table/editor.js"}'),o=(0,t.createElement)("svg",{viewBox:"0 0 24 24"},(0,t.createElement)("path",{d:"M12,3a9,9,0,1,0,9,9A9,9,0,0,0,12,3Zm0,17a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z"}),(0,t.createElement)("path",{d:"M12.33,11.34c-.76-.29-1.42-.54-1.42-1s.45-.85,1.17-.85a2.31,2.31,0,0,1,1.63.63l.06.06.61-.69-.06,0a2.82,2.82,0,0,0-1.79-.89V7h-.86V8.52A1.89,1.89,0,0,0,9.8,10.33c0,1.14,1.12,1.58,2.1,2,.79.31,1.54.61,1.54,1.2s-.49.9-1.29.9a3.45,3.45,0,0,1-2.08-.76L10,13.57l-.54.81.06,0a4.17,4.17,0,0,0,2.16.9V17h.86V15.32c1.23-.16,2-.9,2-1.9C14.55,12.18,13.37,11.73,12.33,11.34Z"}));function c(){return c=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e},c.apply(this,arguments)}var s=n(184),m=n.n(s),b=window.wp.components,u=window.wp.blockEditor,g=window.wp.data;const p=["snow-monkey-blocks/pricing-table-item"],_=[["snow-monkey-blocks/pricing-table-item"],["snow-monkey-blocks/pricing-table-item"]];var d=[{attributes:{...a.attributes},save(e){let{attributes:n,className:r}=e;const{columnSize:l}=n,i=m()("smb-pricing-table",{[`smb-pricing-table--col-size-${l}`]:!!l,[r]:!!r});return(0,t.createElement)("div",{className:i},(0,t.createElement)("div",{className:"c-row c-row--md-nowrap"},(0,t.createElement)(u.InnerBlocks.Content,null)))}},{save:()=>(0,t.createElement)("div",{className:"smb-pricing-table"},(0,t.createElement)("div",{className:"c-row c-row--md-nowrap"},(0,t.createElement)(u.InnerBlocks.Content,null)))},{attributes:{content:{type:"array",source:"query",selector:".smb-pricing-table__item",default:[],query:{title:{source:"html",selector:".smb-pricing-table__item__title"},price:{source:"html",selector:".smb-pricing-table__item__price"},lede:{source:"html",selector:".smb-pricing-table__item__lede"},list:{source:"html",selector:"ul"},btnLabel:{source:"html",selector:".smb-pricing-table__item__btn > .smb-btn__label"},btnURL:{type:"string",source:"attribute",selector:".smb-pricing-table__item__btn",attribute:"href",default:""},btnTarget:{type:"string",source:"attribute",selector:".smb-pricing-table__item__btn",attribute:"target",default:"_self"},btnBackgroundColor:{type:"string",source:"attribute",selector:".smb-pricing-table__item__btn",attribute:"data-background-color"},btnTextColor:{type:"string",source:"attribute",selector:".smb-pricing-table__item__btn",attribute:"data-color"},imageID:{type:"number",source:"attribute",selector:".smb-pricing-table__item__figure > img",attribute:"data-image-id",default:0},imageURL:{type:"string",source:"attribute",selector:".smb-pricing-table__item__figure > img",attribute:"src",default:""}}},columns:{type:"number",default:1}},migrate:e=>[{},(()=>{const t=void 0===e.content?0:e.content.length;return(0,r.times)(t,(t=>{const n=(0,r.get)(e.content,[t,"title"],""),i=(0,r.get)(e.content,[t,"price"],""),a=(0,r.get)(e.content,[t,"lede"],""),o=(0,r.get)(e.content,[t,"list"],""),c=(0,r.get)(e.content,[t,"btnLabel"],""),s=(0,r.get)(e.content,[t,"btnURL"],""),m=(0,r.get)(e.content,[t,"btnTarget"],"_self"),b=(0,r.get)(e.content,[t,"btnBackgroundColor"],""),u=(0,r.get)(e.content,[t,"btnTextColor"],""),g=(0,r.get)(e.content,[t,"imageID"],0),p=(0,r.get)(e.content,[t,"imageURL"],"");return(0,l.createBlock)("snow-monkey-blocks/pricing-table-item",{title:n,price:i,lede:a,list:o,btnLabel:c,btnURL:s,btnTarget:m,btnBackgroundColor:b,btnTextColor:u,imageID:Number(g),imageURL:p})}))})()],save(e){let{attributes:n}=e;const{content:l}=n,i=void 0===n.content?0:n.content.length;return(0,t.createElement)("div",{className:"smb-pricing-table"},(0,t.createElement)("div",{className:"smb-pricing-table__row"},(0,r.times)(i,(e=>{const n=(0,r.get)(l,[e,"title"],""),i=(0,r.get)(l,[e,"price"],""),a=(0,r.get)(l,[e,"lede"],""),o=(0,r.get)(l,[e,"list"],""),c=(0,r.get)(l,[e,"btnLabel"],""),s=(0,r.get)(l,[e,"btnURL"],""),m=(0,r.get)(l,[e,"btnTarget"],"_self"),b=(0,r.get)(l,[e,"btnBackgroundColor"],""),g=(0,r.get)(l,[e,"btnTextColor"],""),p=(0,r.get)(l,[e,"imageID"],0),_=(0,r.get)(l,[e,"imageURL"],"");return(0,t.createElement)("div",{className:"smb-pricing-table__col"},(0,t.createElement)("div",{className:"smb-pricing-table__item"},!!p&&(0,t.createElement)("div",{className:"smb-pricing-table__item__figure"},(0,t.createElement)("img",{src:_,alt:"",className:`wp-image-${p}`,"data-image-id":p})),(0,t.createElement)("div",{className:"smb-pricing-table__item__title"},(0,t.createElement)(u.RichText.Content,{value:n})),!u.RichText.isEmpty(i)&&(0,t.createElement)("div",{className:"smb-pricing-table__item__price"},(0,t.createElement)(u.RichText.Content,{value:i})),!u.RichText.isEmpty(a)&&(0,t.createElement)("div",{className:"smb-pricing-table__item__lede"},(0,t.createElement)(u.RichText.Content,{value:a})),(0,t.createElement)("ul",null,(0,t.createElement)(u.RichText.Content,{value:o})),(!u.RichText.isEmpty(c)||!!s)&&(0,t.createElement)("div",{className:"smb-pricing-table__item__action"},(0,t.createElement)("a",{className:"smb-pricing-table__item__btn smb-btn",href:s,target:m,style:{backgroundColor:b},"data-background-color":b,"data-color":g},(0,t.createElement)("span",{className:"smb-btn__label",style:{color:g}},(0,t.createElement)(u.RichText.Content,{value:c}))))))}))))}},{attributes:{content:{type:"array",source:"query",selector:".smb-pricing-table__item",default:[],query:{title:{source:"html",selector:".smb-pricing-table__item__title"},price:{source:"html",selector:".smb-pricing-table__item__price"},lede:{source:"html",selector:".smb-pricing-table__item__lede"},list:{source:"html",selector:"ul"},btnLabel:{source:"html",selector:".smb-pricing-table__item__btn > .smb-btn__label"},btnURL:{type:"string",source:"attribute",selector:".smb-pricing-table__item__btn",attribute:"href",default:""},btnTarget:{type:"string",source:"attribute",selector:".smb-pricing-table__item__btn",attribute:"target",default:"_self"},btnBackgroundColor:{type:"string",source:"attribute",selector:".smb-pricing-table__item__btn",attribute:"data-background-color"},btnTextColor:{type:"string",source:"attribute",selector:".smb-pricing-table__item__btn",attribute:"data-color"},imageID:{type:"number",source:"attribute",selector:".smb-pricing-table__item__figure > img",attribute:"data-image-id",default:0},imageURL:{type:"string",source:"attribute",selector:".smb-pricing-table__item__figure > img",attribute:"src",default:""}}},columns:{type:"number",default:1}},save(e){let{attributes:n}=e;const{content:l,columns:i}=n;return(0,t.createElement)("div",{className:`smb-pricing-table smb-pricing-table--${i}`},(0,t.createElement)("div",{className:"smb-pricing-table__row"},(0,r.times)(i,(e=>{const n=(0,r.get)(l,[e,"title"],""),i=(0,r.get)(l,[e,"price"],""),a=(0,r.get)(l,[e,"lede"],""),o=(0,r.get)(l,[e,"list"],""),c=(0,r.get)(l,[e,"btnLabel"],""),s=(0,r.get)(l,[e,"btnURL"],""),m=(0,r.get)(l,[e,"btnTarget"],"_self"),b=(0,r.get)(l,[e,"btnBackgroundColor"],""),g=(0,r.get)(l,[e,"btnTextColor"],""),p=(0,r.get)(l,[e,"imageID"],0),_=(0,r.get)(l,[e,"imageURL"],"");return(0,t.createElement)("div",{className:"smb-pricing-table__col"},(0,t.createElement)("div",{className:"smb-pricing-table__item"},!!p&&(0,t.createElement)("div",{className:"smb-pricing-table__item__figure"},(0,t.createElement)("img",{src:_,alt:"","data-image-id":p})),(0,t.createElement)("div",{className:"smb-pricing-table__item__title"},(0,t.createElement)(u.RichText.Content,{value:n})),!u.RichText.isEmpty(i)&&(0,t.createElement)("div",{className:"smb-pricing-table__item__price"},(0,t.createElement)(u.RichText.Content,{value:i})),!u.RichText.isEmpty(a)&&(0,t.createElement)("div",{className:"smb-pricing-table__item__lede"},(0,t.createElement)(u.RichText.Content,{value:a})),(0,t.createElement)("ul",null,(0,t.createElement)(u.RichText.Content,{value:o})),(!u.RichText.isEmpty(c)||!!s)&&(0,t.createElement)("div",{className:"smb-pricing-table__item__action"},(0,t.createElement)("a",{className:"smb-pricing-table__item__btn smb-btn",href:s,target:m,style:{backgroundColor:b},"data-background-color":b,"data-color":g},(0,t.createElement)("span",{className:"smb-btn__label",style:{color:g}},(0,t.createElement)(u.RichText.Content,{value:c}))))))}))))}}],k={attributes:{columnSize:"1-2"},innerBlocks:[{name:"snow-monkey-blocks/pricing-table-item",attributes:{title:"Lorem",price:"$100",lede:"/month",list:"<li>Lorem ipsum dolor</li><li>sit amet</li>",btnLabel:"more",btnURL:"https://2inc.org",imageURL:`${smb.pluginUrl}/dist/img/photos/beach-sand-coast2756.jpg`,imageID:1}},{name:"snow-monkey-blocks/pricing-table-item",attributes:{title:"ipsum",price:"$100",lede:"/month",list:"<li>consectetur adipiscing</li><li>elit, sed</li>",btnLabel:"more",btnURL:"https://2inc.org",imageURL:`${smb.pluginUrl}/dist/img/photos/building-architecture-sky2096.jpg`,imageID:1}}]};const{name:y}=a,v={icon:{foreground:"#cd162c",src:o},edit:function(e){let{attributes:n,setAttributes:r,className:a,clientId:o}=e;((e,n)=>{const{replaceBlock:r}=(0,g.useDispatch)("core/block-editor"),{getBlockOrder:i,getBlock:a}=(0,g.useSelect)((e=>({getBlockOrder:e("core/block-editor").getBlockOrder,getBlock:e("core/block-editor").getBlock})),[]),o=e=>`wp-block-${e.replace("/","-")}`;(0,t.useEffect)((()=>{i(e).forEach((e=>{const t=a(e);n.forEach((e=>{if("core/missing"===t.name||e.oldBlockName===t.name){const n=(0,l.parse)(t.originalContent.replace(e.oldBlockName,e.newBlockName).replace(o(e.oldBlockName),o(e.oldBlockName)+" "+o(e.newBlockName)))[0];r(t.clientId,n)}}))}))}),[])})(o,[{oldBlockName:"snow-monkey-blocks/pricing-table--item",newBlockName:"snow-monkey-blocks/pricing-table-item"}]);const{columnSize:s,childrenCount:d}=n,k=(0,g.useSelect)((e=>{var t,n;return!(null===(t=e("core/block-editor").getBlock(o))||void 0===t||null===(n=t.innerBlocks)||void 0===n||!n.length)}),[o]),y=(0,g.useSelect)((e=>{var t,n;return null===(t=e("core/block-editor").getBlock(o))||void 0===t||null===(n=t.innerBlocks)||void 0===n?void 0:n.length}),[o]);(0,t.useEffect)((()=>{y&&r({childrenCount:y})}),[y]);const v=m()("smb-pricing-table",{[`smb-pricing-table--col-size-${s}`]:!!s,[a]:!!a}),f=m()("c-row","c-row--md-nowrap"),h=(0,u.useBlockProps)({className:v}),w=(0,u.useInnerBlocksProps)({className:f},{allowedBlocks:p,template:_,templateLock:!1,orientation:"horizontal",renderAppender:k?u.InnerBlocks.DefaultBlockAppender:u.InnerBlocks.ButtonBlockAppender});return(0,t.createElement)(t.Fragment,null,(0,t.createElement)(u.InspectorControls,null,(0,t.createElement)(b.PanelBody,{title:(0,i.__)("Block settings","snow-monkey-blocks")},(0,t.createElement)(b.BaseControl,{label:(0,i.__)("Column size","snow-monkey-blocks"),help:(0,i.__)('If the text of each item is long, it is recommended to select other than "Auto".',"snow-monkey-blocks"),id:"snow-monkey-blocks/pricing-table/column-size"},(0,t.createElement)(b.SelectControl,{value:s,options:[{value:"",label:(0,i.__)("Auto","snow-monkey-blocks")},{value:"1-4",label:(0,i.__)("25%","snow-monkey-blocks")},{value:"1-3",label:(0,i.__)("33%","snow-monkey-blocks")},{value:"1-2",label:(0,i.__)("50%","snow-monkey-blocks")},{value:"1-1",label:(0,i.__)("100%","snow-monkey-blocks")}],onChange:e=>r({columnSize:e})})))),(0,t.createElement)("div",c({},h,{"data-has-items":0<d?d:void 0}),(0,t.createElement)("div",w)))},save:function(e){let{attributes:n,className:r}=e;const{columnSize:l,childrenCount:i}=n,a=m()("smb-pricing-table",{[`smb-pricing-table--col-size-${l}`]:!!l,[r]:!!r}),o=m()("c-row","c-row--md-nowrap");return(0,t.createElement)("div",c({},u.useBlockProps.save({className:a}),{"data-has-items":0<i?i:void 0}),(0,t.createElement)("div",u.useInnerBlocksProps.save({className:o})))},deprecated:d,example:k};(e=>{if(!e)return;const{metadata:t,settings:n,name:r}=e;t&&(t.title&&(t.title=(0,i.__)(t.title,"snow-monkey-blocks"),n.title=t.title),t.description&&(t.description=(0,i.__)(t.description,"snow-monkey-blocks"),n.description=t.description),t.keywords&&(t.keywords=(0,i.__)(t.keywords,"snow-monkey-blocks"),n.keywords=t.keywords)),(0,l.registerBlockType)({name:r,...t},n)})(e)}()}();