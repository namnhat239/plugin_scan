!function(){var e={184:function(e,t){var a;!function(){"use strict";var n={}.hasOwnProperty;function o(){for(var e=[],t=0;t<arguments.length;t++){var a=arguments[t];if(a){var s=typeof a;if("string"===s||"number"===s)e.push(a);else if(Array.isArray(a)){if(a.length){var l=o.apply(null,a);l&&e.push(l)}}else if("object"===s)if(a.toString===Object.prototype.toString)for(var r in a)n.call(a,r)&&a[r]&&e.push(r);else e.push(a.toString())}}return e.join(" ")}e.exports?(o.default=o,e.exports=o):void 0===(a=function(){return o}.apply(t,[]))||(e.exports=a)}()}},t={};function a(n){var o=t[n];if(void 0!==o)return o.exports;var s=t[n]={exports:{}};return e[n](s,s.exports,a),s.exports}a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,{a:t}),t},a.d=function(e,t){for(var n in t)a.o(t,n)&&!a.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},function(){"use strict";var e={};a.r(e),a.d(e,{metadata:function(){return l},name:function(){return v},settings:function(){return E}});var t=window.wp.element,n=(window.lodash,window.wp.blocks),o=(window.wp.richText,window.wp.i18n),s=(0,t.createElement)("svg",{viewBox:"0 0 24 24"},(0,t.createElement)("circle",{cx:"6.5",cy:"8",r:"1"}),(0,t.createElement)("path",{d:"M20,8H13.75a.25.25,0,0,1-.25-.25V5.5a1,1,0,0,0-1-1H4a1,1,0,0,0-1,1v13a1,1,0,0,0,1,1H20a1,1,0,0,0,1-1V9A1,1,0,0,0,20,8Zm0,10a.54.54,0,0,1-.53.54H4.53A.54.54,0,0,1,4,18V6a.54.54,0,0,1,.53-.54H12A.54.54,0,0,1,12.5,6V8.25a.54.54,0,0,0,.53.54h6.44a.54.54,0,0,1,.53.54Z"})),l=JSON.parse('{"apiVersion":2,"name":"snow-monkey-blocks/tabs","title":"Tabs","description":"This is tabs block.","category":"smb","attributes":{"tabs":{"type":"string","default":"[]"},"matchHeight":{"type":"string","source":"attribute","selector":".smb-tabs","attribute":"data-match-height","default":"false"},"tabsJustification":{"type":"string","source":"attribute","selector":".smb-tabs","attribute":"data-tabs-justification","default":"flex-start"},"tabsId":{"type":"string","source":"attribute","selector":".smb-tabs","attribute":"data-tabs-id","default":""},"orientation":{"type":"string","source":"attribute","selector":".smb-tabs","attribute":"data-orientation","default":"horizontal"}},"supports":{"html":false},"example":{"attributes":{"tabs":"[{\\"title\\":\\"Tab\\",\\"tabPanelId\\":\\"1\\"},{\\"title\\": \\"Tab\\",\\"tabPanelId\\":\\"2\\"}]"},"innerBlocks":[{"name":"snow-monkey-blocks/tab-panel","attributes":{"tabPanelId":"1","ariaHidden":"false"},"innerBlocks":[{"name":"core/paragraph","attributes":{"content":"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam"}}]},{"name":"snow-monkey-blocks/tab-panel","attributes":{"tabPanelId":"2","ariaHidden":"true"},"innerBlocks":[{"name":"core/paragraph","attributes":{"content":"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam"}}]}]},"style":"snow-monkey-blocks/tabs","editorStyle":"snow-monkey-blocks/tabs/editor","editorScript":"file:../../dist/block/tabs/editor.js"}');function r(){return r=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var a=arguments[t];for(var n in a)Object.prototype.hasOwnProperty.call(a,n)&&(e[n]=a[n])}return e},r.apply(this,arguments)}var i=a(184),c=a.n(i),b=window.wp.blockEditor,m=function(e){let{icon:a,size:n=24,...o}=e;return(0,t.cloneElement)(a,{width:n,height:n,...o})},d=window.wp.primitives,u=(0,t.createElement)(d.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,t.createElement)(d.Path,{d:"M14.6 7l-1.2-1L8 12l5.4 6 1.2-1-4.6-5z"})),p=(0,t.createElement)(d.SVG,{viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg"},(0,t.createElement)(d.Path,{d:"M6.5 12.4L12 8l5.5 4.4-.9 1.2L12 10l-4.5 3.6-1-1.2z"})),k=(0,t.createElement)(d.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,t.createElement)(d.Path,{d:"M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"})),h=(0,t.createElement)(d.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,t.createElement)(d.Path,{d:"M10.6 6L9.4 7l4.6 5-4.6 5 1.2 1 5.4-6z"})),w=(0,t.createElement)(d.SVG,{viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg"},(0,t.createElement)(d.Path,{d:"M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"})),f=(0,t.createElement)(d.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,t.createElement)(d.Path,{d:"M18 11.2h-5.2V6h-1.6v5.2H6v1.6h5.2V18h1.6v-5.2H18z"})),g=window.wp.components,y=window.wp.data;const _=["snow-monkey-blocks/tab-panel"],{name:v}=l,E={icon:{foreground:"#cd162c",src:s},edit:function(e){let{attributes:a,setAttributes:s,className:l,clientId:i}=e;const{tabs:d,matchHeight:v,tabsJustification:E,tabsId:P,orientation:S}=a,B=JSON.parse(d),{removeBlocks:I,insertBlocks:x,moveBlocksUp:N,moveBlocksDown:O,updateBlockAttributes:z}=(0,y.useDispatch)("core/block-editor"),{getBlockOrder:j,getBlock:C}=(0,y.useSelect)((e=>({getBlockOrder:e("core/block-editor").getBlockOrder,getBlock:e("core/block-editor").getBlock})),[]),[H,L]=(0,t.useState)(void 0);(0,t.useEffect)((()=>{0<B.length&&L(B[0].tabPanelId),P||s({tabsId:i})}),[]),(0,t.useEffect)((()=>{1>B.length||j(i).forEach((e=>{const t=C(e);z(e,{ariaHidden:t.attributes.tabPanelId===B[0].tabPanelId?"false":"true"})}))}),[B.length]),(0,t.useEffect)((()=>{2>document.querySelectorAll(`[data-tabs-id="${P}"]`).length||(j(i).forEach(((e,t)=>{const a=`block-${e}`;B[t].tabPanelId=a,z(e,{tabPanelId:a})})),s({tabsId:i,tabs:JSON.stringify(B)}),L(B[0].tabPanelId))}),[i]);const $="vertical"===S||"horizontal"===S&&"true"===v,M=c()("smb-tabs",l),T=(0,b.useBlockProps)({className:M}),V=(0,b.useInnerBlocksProps)({className:"smb-tabs__body"},{allowedBlocks:_,templateLock:!1,renderAppender:!1});return(0,t.createElement)(t.Fragment,null,(0,t.createElement)(b.InspectorControls,null,(0,t.createElement)(g.PanelBody,{title:(0,o.__)("Block settings","snow-monkey-blocks")},(0,t.createElement)(g.SelectControl,{label:(0,o.__)("Tabs orientation","snow-monkey-blocks"),value:S,onChange:e=>s({orientation:e}),options:[{value:"horizontal",label:(0,o.__)("Horizontal","snow-monkey-blocks")},{value:"vertical",label:(0,o.__)("Vertical","snow-monkey-blocks")}]}),"horizontal"===S&&(0,t.createElement)(t.Fragment,null,(0,t.createElement)(g.ToggleControl,{label:(0,o.__)("Align the height of each tab panels","snow-monkey-blocks"),checked:"true"===v,onChange:e=>s({matchHeight:e?"true":"false"})}),(0,t.createElement)(g.SelectControl,{label:(0,o.__)("Tabs justification","snow-monkey-blocks"),value:E,onChange:e=>s({tabsJustification:e}),options:[{label:(0,o.__)("Left","snow-monkey-blocks"),value:"flex-start"},{label:(0,o.__)("Center","snow-monkey-blocks"),value:"center"},{label:(0,o.__)("Right","snow-monkey-blocks"),value:"flex-end"},{label:(0,o.__)("Stretch","snow-monkey-blocks"),value:"stretch"}]})))),(0,t.createElement)("div",r({},T,{"data-tabs-id":P,"data-orientation":S,"data-match-height":$?"true":v,"data-tabs-justification":"horizontal"===S?E:void 0}),(0,t.createElement)("div",{className:"smb-tabs__tabs","data-has-tabs":1<B.length?"true":"false"},B.map(((e,a)=>{const n=j(i)[a];return(0,t.createElement)("div",{className:"smb-tabs__tab-wrapper",key:`${i}-${a}`,"aria-selected":H===e.tabPanelId?"true":"false"},0<a&&(0,t.createElement)("button",{className:"smb-tabs__up-tab",onClick:()=>{N(n?[n]:[],i);const e=B[a];B.splice(a,1),B.splice(a-1,0,e),s({tabs:JSON.stringify(B)}),L(B[a-1].tabPanelId)},"aria-label":"horizontal"===S?(0,o.__)("Move to left","snow-monkey-blocks"):(0,o.__)("Move to up","snow-monkey-blocks")},(0,t.createElement)(m,{icon:"horizontal"===S?u:p})),1<B.length&&(0,t.createElement)("button",{className:"smb-tabs__remove-tab",onClick:()=>{I(n?[n]:[],!1),B.splice(a,1),s({tabs:JSON.stringify(B)}),L(B[0].tabPanelId)},"aria-label":(0,o.__)("Remove this tab","snow-monkey-blocks")},(0,t.createElement)(m,{icon:k})),B.length-1>a&&(0,t.createElement)("button",{className:"smb-tabs__down-tab",onClick:()=>{O(n?[n]:[],i);const e=B[a];B.splice(a,1),B.splice(a+1,0,e),s({tabs:JSON.stringify(B)}),L(B[a+1].tabPanelId)},"aria-label":"horizontal"===S?(0,o.__)("Move to right","snow-monkey-blocks"):(0,o.__)("Move to down","snow-monkey-blocks")},(0,t.createElement)(m,{icon:"horizontal"===S?h:w})),(0,t.createElement)("button",{className:"smb-tabs__tab",role:"tab","aria-controls":e.tabPanelId,"aria-selected":H===e.tabPanelId?"true":"false",onClick:()=>{L(e.tabPanelId)}},(0,t.createElement)(b.RichText,{value:e.title,onChange:e=>{B[a].title=e,s({tabs:JSON.stringify(B)})},placeholder:(0,o.__)("Tab","snow-monkey-blocks")})))})),(0,t.createElement)("div",{className:"smb-tabs__tab-wrapper"},(0,t.createElement)("button",{className:"smb-tabs__tab smb-tabs__add-tab",onClick:()=>{const e=(0,n.createBlock)("snow-monkey-blocks/tab-panel"),t=`block-${e.clientId}`;e.attributes.tabPanelId=t,x(e,B.length,i,!1),B.push({tabPanelId:t}),s({tabs:JSON.stringify(B)}),L(t)}},(0,t.createElement)(m,{icon:f})))),(0,t.createElement)("div",V),!!H&&!$&&(0,t.createElement)("style",null,`[data-tabs-id="${P}"] > .smb-tabs__body > .smb-tab-panel:not(#${H}) {display: none !important}`),!!H&&$&&(0,t.createElement)("style",null,B.map(((e,t)=>`[data-tabs-id="${P}"] > .smb-tabs__body > .smb-tab-panel:nth-child(${t+1}) {left: ${-100*t}%}`)),`[data-tabs-id="${P}"] > .smb-tabs__body > .smb-tab-panel:not(#${H}) {visibility: hidden !important}`)))},save:function(e){let{attributes:a,className:n}=e;const{tabs:o,matchHeight:s,tabsJustification:l,tabsId:i,orientation:m}=a,d=JSON.parse(o),u="vertical"===m||"horizontal"===m&&"true"===s,p=c()("smb-tabs",n);return(0,t.createElement)("div",r({},b.useBlockProps.save({className:p}),{"data-tabs-id":i,"data-orientation":m,"data-match-height":u?"true":s,"data-tabs-justification":"horizontal"===m?l:void 0}),0<d.length&&(0,t.createElement)("div",{className:"smb-tabs__tabs"},d.map(((e,a)=>(0,t.createElement)("div",{className:"smb-tabs__tab-wrapper",key:a},(0,t.createElement)(b.RichText.Content,{tagName:"button",value:e.title,className:"smb-tabs__tab",role:"tab","aria-controls":e.tabPanelId,"aria-selected":0===a?"true":"false"}))))),(0,t.createElement)("div",b.useInnerBlocksProps.save({className:"smb-tabs__body"})),u&&(0,t.createElement)("style",null,d.map(((e,t)=>`[data-tabs-id="${i}"] > .smb-tabs__body > .smb-tab-panel:nth-child(${t+1}) {left: ${-100*t}%}`))))},styles:[{name:"default",label:(0,o.__)("Default","snow-monkey-blocks"),isDefault:!0},{name:"simple",label:(0,o.__)("Simple","snow-monkey-blocks")},{name:"line",label:(0,o.__)("Line","snow-monkey-blocks")}]};(e=>{if(!e)return;const{metadata:t,settings:a,name:s}=e;t&&(t.title&&(t.title=(0,o.__)(t.title,"snow-monkey-blocks"),a.title=t.title),t.description&&(t.description=(0,o.__)(t.description,"snow-monkey-blocks"),a.description=t.description),t.keywords&&(t.keywords=(0,o.__)(t.keywords,"snow-monkey-blocks"),a.keywords=t.keywords)),(0,n.registerBlockType)({name:s,...t},a)})(e)}()}();