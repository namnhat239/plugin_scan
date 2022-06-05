!function(){var e={4184:function(e,t){var n;!function(){"use strict";var s={}.hasOwnProperty;function r(){for(var e=[],t=0;t<arguments.length;t++){var n=arguments[t];if(n){var i=typeof n;if("string"===i||"number"===i)e.push(n);else if(Array.isArray(n)){if(n.length){var a=r.apply(null,n);a&&e.push(a)}}else if("object"===i)if(n.toString===Object.prototype.toString)for(var o in n)s.call(n,o)&&n[o]&&e.push(o);else e.push(n.toString())}}return e.join(" ")}e.exports?(r.default=r,e.exports=r):void 0===(n=function(){return r}.apply(t,[]))||(e.exports=n)}()},7145:function(e,t){"use strict";function n(e){return"object"!=typeof e||"toString"in e?e:Object.prototype.toString.call(e).slice(8,-1)}Object.defineProperty(t,"__esModule",{value:!0});var s="object"==typeof process&&!0;function r(e,t){if(!e){if(s)throw new Error("Invariant failed");throw new Error(t())}}t.invariant=r;var i=Object.prototype.hasOwnProperty,a=Array.prototype.splice,o=Object.prototype.toString;function l(e){return o.call(e).slice(8,-1)}var c=Object.assign||function(e,t){return p(t).forEach((function(n){i.call(t,n)&&(e[n]=t[n])})),e},p="function"==typeof Object.getOwnPropertySymbols?function(e){return Object.keys(e).concat(Object.getOwnPropertySymbols(e))}:function(e){return Object.keys(e)};function u(e){return Array.isArray(e)?c(e.constructor(e.length),e):"Map"===l(e)?new Map(e):"Set"===l(e)?new Set(e):e&&"object"==typeof e?c(Object.create(Object.getPrototypeOf(e)),e):e}var d=function(){function e(){this.commands=c({},h),this.update=this.update.bind(this),this.update.extend=this.extend=this.extend.bind(this),this.update.isEquals=function(e,t){return e===t},this.update.newContext=function(){return(new e).update}}return Object.defineProperty(e.prototype,"isEquals",{get:function(){return this.update.isEquals},set:function(e){this.update.isEquals=e},enumerable:!0,configurable:!0}),e.prototype.extend=function(e,t){this.commands[e]=t},e.prototype.update=function(e,t){var n=this,s="function"==typeof t?{$apply:t}:t;Array.isArray(e)&&Array.isArray(s)||r(!Array.isArray(s),(function(){return"update(): You provided an invalid spec to update(). The spec may not contain an array except as the value of $set, $push, $unshift, $splice or any custom command allowing an array value."})),r("object"==typeof s&&null!==s,(function(){return"update(): You provided an invalid spec to update(). The spec and every included key path must be plain objects containing one of the following commands: "+Object.keys(n.commands).join(", ")+"."}));var a=e;return p(s).forEach((function(t){if(i.call(n.commands,t)){var r=e===a;a=n.commands[t](s[t],a,s,e),r&&n.isEquals(a,e)&&(a=e)}else{var o="Map"===l(e)?n.update(e.get(t),s[t]):n.update(e[t],s[t]),c="Map"===l(a)?a.get(t):a[t];n.isEquals(o,c)&&(void 0!==o||i.call(e,t))||(a===e&&(a=u(e)),"Map"===l(a)?a.set(t,o):a[t]=o)}})),a},e}();t.Context=d;var h={$push:function(e,t,n){return f(t,n,"$push"),e.length?t.concat(e):t},$unshift:function(e,t,n){return f(t,n,"$unshift"),e.length?e.concat(t):t},$splice:function(e,t,s,i){return function(e,t){r(Array.isArray(e),(function(){return"Expected $splice target to be an array; got "+n(e)})),y(t.$splice)}(t,s),e.forEach((function(e){y(e),t===i&&e.length&&(t=u(i)),a.apply(t,e)})),t},$set:function(e,t,n){return function(e){r(1===Object.keys(e).length,(function(){return"Cannot have more than one key in an object with $set"}))}(n),e},$toggle:function(e,t){g(e,"$toggle");var n=e.length?u(t):t;return e.forEach((function(e){n[e]=!t[e]})),n},$unset:function(e,t,n,s){return g(e,"$unset"),e.forEach((function(e){Object.hasOwnProperty.call(t,e)&&(t===s&&(t=u(s)),delete t[e])})),t},$add:function(e,t,n,s){return v(t,"$add"),g(e,"$add"),"Map"===l(t)?e.forEach((function(e){var n=e[0],r=e[1];t===s&&t.get(n)!==r&&(t=u(s)),t.set(n,r)})):e.forEach((function(e){t!==s||t.has(e)||(t=u(s)),t.add(e)})),t},$remove:function(e,t,n,s){return v(t,"$remove"),g(e,"$remove"),e.forEach((function(e){t===s&&t.has(e)&&(t=u(s)),t.delete(e)})),t},$merge:function(e,t,s,i){var a,o;return a=t,r((o=e)&&"object"==typeof o,(function(){return"update(): $merge expects a spec of type 'object'; got "+n(o)})),r(a&&"object"==typeof a,(function(){return"update(): $merge expects a target of type 'object'; got "+n(a)})),p(e).forEach((function(n){e[n]!==t[n]&&(t===i&&(t=u(i)),t[n]=e[n])})),t},$apply:function(e,t){var s;return r("function"==typeof(s=e),(function(){return"update(): expected spec of $apply to be a function; got "+n(s)+"."})),e(t)}},m=new d;function f(e,t,s){r(Array.isArray(e),(function(){return"update(): expected target of "+n(s)+" to be an array; got "+n(e)+"."})),g(t[s],s)}function g(e,t){r(Array.isArray(e),(function(){return"update(): expected spec of "+n(t)+" to be an array; got "+n(e)+". Did you forget to wrap your parameter in an array?"}))}function y(e){r(Array.isArray(e),(function(){return"update(): expected spec of $splice to be an array of arrays; got "+n(e)+". Did you forget to wrap your parameters in an array?"}))}function v(e,t){var s=l(e);r("Map"===s||"Set"===s,(function(){return"update(): "+n(t)+" expects a target of type Set or Map; got "+n(s)}))}t.isEquals=m.update.isEquals,t.extend=m.extend,t.default=m.update,t.default.default=e.exports=c(t.default,t)}},t={};function n(s){var r=t[s];if(void 0!==r)return r.exports;var i=t[s]={exports:{}};return e[s](i,i.exports,n),i.exports}n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,{a:t}),t},n.d=function(e,t){for(var s in t)n.o(t,s)&&!n.o(e,s)&&Object.defineProperty(e,s,{enumerable:!0,get:t[s]})},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){"use strict";var e=window.wp.element,t=window.React,s=n.n(t),r=window.ReactDOM,i=window.wp.domReady,a=n.n(i);class o extends s().Component{constructor(e){super(e),this.state={hasError:!1}}static getDerivedStateFromError(){return{hasError:!0}}componentDidCatch(e,t){console.error(e),console.error(t)}render(){return this.state.hasError?(0,e.createElement)("div",{className:"sui-notice sui-notice-error"},(0,e.createElement)("div",{className:"sui-notice-content"},(0,e.createElement)("div",{className:"sui-notice-message"},(0,e.createElement)("span",{className:"sui-notice-icon sui-icon-warning-alert sui-md","aria-hidden":"true"}),(0,e.createElement)("p",null,(0,e.createElement)("strong",null,"Something went wrong. Please contact ",(0,e.createElement)("a",{target:"_blank",href:"https://wpmudev.com/get-support/"},"support"),"."))))):this.props.children}}var l=o;function c(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}var p=n(7145),u=n.n(p),d=window.wp.i18n;class h extends s().Component{constructor(e){super(e),this.state={}}handleRemove(e){const{index:t,onRemove:n}=this.props;e.preventDefault(),n(t)}render(){const{index:t,recipient:n,fieldName:r}=this.props;return(0,e.createElement)("div",{className:"wds-recipient sui-recipient"},(0,e.createElement)("span",{className:"sui-recipient-name"},n.name),(0,e.createElement)("span",{className:"sui-recipient-email"},n.email),(0,e.createElement)("span",null,r&&(0,e.createElement)("a",{className:"sui-button-icon",href:"#","aria-label":(0,d.__)("Delete email recipient","wds"),onClick:e=>this.handleRemove(e)},(0,e.createElement)("span",{className:"sui-icon-trash","aria-hidden":"true"}))),r&&(0,e.createElement)(s().Fragment,null,(0,e.createElement)("input",{type:"hidden",name:r+"["+t+"][name]",value:n.name}),(0,e.createElement)("input",{type:"hidden",name:r+"["+t+"][email]",value:n.email})))}}function m(){return(m=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var s in n)Object.prototype.hasOwnProperty.call(n,s)&&(e[s]=n[s])}return e}).apply(this,arguments)}c(h,"defaultProps",{index:"",recipient:"",fieldName:"",onRemove:()=>!1});var f=n(4184),g=n.n(f);class y extends s().Component{handleClick(e){e.preventDefault(),this.props.onClick()}render(){let t,n;return this.props.href?(t="a",n={href:this.props.href}):(t="button",n={disabled:this.props.disabled,onClick:e=>this.handleClick(e)}),(0,e.createElement)(s().Fragment,null,(0,e.createElement)(t,m({},n,{className:g()(this.props.className,"sui-button-"+this.props.color,{"sui-button-onload":this.props.loading,"sui-button-ghost":this.props.ghost,"sui-button-icon":!this.props.text.trim(),"sui-button-dashed":this.props.dashed,"sui-button":this.props.text.trim()}),id:this.props.id}),this.text(),this.loadingIcon()))}text(){const t=this.props.icon?(0,e.createElement)("span",{className:this.props.icon,"aria-hidden":"true"}):"";return(0,e.createElement)("span",{className:g()({"sui-loading-text":this.props.loading})},t," ",this.props.text)}loadingIcon(){return this.props.loading?(0,e.createElement)("span",{className:"sui-icon-loader sui-loading","aria-hidden":"true"}):""}}c(y,"defaultProps",{id:"",text:"",color:"",dashed:!1,icon:!1,loading:!1,ghost:!1,disabled:!1,className:"",onClick:()=>!1});var v=SUI,E=n.n(v),b=jQuery,w=n.n(b);class N extends s().Component{constructor(e){super(e),this.props=e}componentDidMount(){E().openModal(this.props.id,this.props.focusAfterClose,this.props.focusAfterOpen?this.props.focusAfterOpen:this.getTitleId(),!1,!1)}componentWillUnmount(){E().closeModal()}handleKeyDown(e){w()(e.target).is(".sui-modal.sui-active input")&&13===e.keyCode&&(e.preventDefault(),e.stopPropagation(),!this.props.enterDisabled&&this.props.onEnter&&this.props.onEnter(e))}render(){const t=this.getHeaderActions(),n=Object.assign({},{"sui-modal-sm":this.props.small,"sui-modal-lg":!this.props.small},this.props.dialogClasses);return(0,e.createElement)("div",{className:g()("sui-modal",n),onKeyDown:e=>this.handleKeyDown(e)},(0,e.createElement)("div",{role:"dialog",id:this.props.id,className:g()("sui-modal-content",this.props.id+"-modal"),"aria-modal":"true","aria-labelledby":this.props.id+"-modal-title","aria-describedby":this.props.id+"-modal-description"},(0,e.createElement)("div",{className:"sui-box",role:"document"},(0,e.createElement)("div",{className:g()("sui-box-header",{"sui-flatten sui-content-center sui-spacing-top--40":this.props.small})},(0,e.createElement)("h3",{id:this.getTitleId(),className:g()("sui-box-title",{"sui-lg":this.props.small})},this.props.title),t),(0,e.createElement)("div",{className:g()("sui-box-body",{"sui-content-center":this.props.small})},this.props.description&&(0,e.createElement)("p",{className:"sui-description",id:this.props.id+"-modal-description"},this.props.description),this.props.children),this.props.footer&&(0,e.createElement)("div",{className:"sui-box-footer"},this.props.footer))))}getTitleId(){return this.props.id+"-modal-title"}getHeaderActions(){const t=this.getCloseButton();return this.props.small?t:this.props.headerActions?this.props.headerActions:(0,e.createElement)("div",{className:"sui-actions-right"},t)}getCloseButton(){return(0,e.createElement)("button",{id:this.props.id+"-close-button",type:"button",onClick:()=>this.props.onClose(),disabled:this.props.disableCloseButton,className:g()("sui-button-icon",{"sui-button-float--right":this.props.small})},(0,e.createElement)("span",{className:"sui-icon-close sui-md","aria-hidden":"true"}),(0,e.createElement)("span",{className:"sui-screen-reader-text"},(0,d.__)("Close this dialog window","wds")))}}c(N,"defaultProps",{id:"",title:"",description:"",small:!1,headerActions:!1,focusAfterOpen:"",focusAfterClose:"container",dialogClasses:[],disableCloseButton:!1,enterDisabled:!1,onEnter:!1,onClose:()=>!1});class C extends s().Component{render(){const{label:t,isRequired:n,errorMessage:s,description:r,isValid:i}=this.props,a=this.props.formControl;return(0,e.createElement)("div",{className:g()("sui-form-field",{"sui-form-field-error":!i})},(0,e.createElement)("label",{className:"sui-label"},t," ",n&&(0,e.createElement)("span",{className:"wds-required-asterisk"},"*")),(0,e.createElement)(a,this.props),!i&&!!s&&(0,e.createElement)("span",{className:"sui-error-message",role:"alert"},s),!!r&&(0,e.createElement)("p",{className:"sui-description"},(0,e.createElement)("small",null,r)))}}c(C,"defaultProps",{label:"",description:"",errorMessage:"",isValid:!0,isRequired:!1,formControl:!1});class x extends s().Component{render(){const{id:t,value:n,placeholder:s,disabled:r,onChange:i}=this.props;return(0,e.createElement)("input",{id:t,type:"text",className:"sui-form-control",onChange:e=>i(e.target.value),value:n,disabled:r,placeholder:s})}}c(x,"defaultProps",{id:"",value:"",placeholder:"",disabled:!1,onChange:()=>!1});class A extends s().Component{render(){return(0,e.createElement)(C,m({},this.props,{formControl:x}))}}const _=e=>e&&e.trim(),O=e=>{var t;const n=(new DOMParser).parseFromString(e,"text/html");return(null==n||null===(t=n.body)||void 0===t?void 0:t.textContent)===e};var j=function(t,n){var r,i;return i=r=class extends s().Component{constructor(e){super(e);const{value:t,validateOnInit:n}=this.props;n?this.handleChange(t):(this.isValid=!0,this.errorMessage="")}validateValue(e){Array.isArray(n)?n.some((t=>{if(this.isValid=this.runValidator(t,e),this.errorMessage=this.isValid?"":this.getErrorMessage(t),!this.isValid)return!0})):(this.isValid=this.runValidator(n,e),this.errorMessage=this.isValid?"":this.getErrorMessage(n))}getErrorMessage(e){let t="";return e.getError instanceof Function&&(t=e.getError()),t}runValidator(e,t){let n;return e.isValid instanceof Function?n=e.isValid(t):e instanceof Function&&(n=e(t)),n}handleChange(e){this.validateValue(e),this.props.onChange(e,this.isValid)}render(){return(0,e.createElement)(t,m({},this.props,{isValid:this.isValid,errorMessage:this.errorMessage,onChange:e=>this.handleChange(e)}))}},c(r,"defaultProps",{value:"",validateOnInit:!1,onChange:()=>!1}),i};const $=j(A,[_,O]),M=j(A,[_,O,new class{constructor(e,t){this.func=e,this.errorMessage=t}isValid(e){return this.func(e)}getError(){return this.errorMessage}}((e=>!!e.toLowerCase().match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)),(0,d.__)("Email is invalid.","wds"))]);class S extends s().Component{constructor(e){super(e),this.state={name:"",email:"",isNameValid:!0,isEmailValid:!0}}render(){const{id:t,onClose:n}=this.props,{name:r,email:i,isNameValid:a,isEmailValid:o}=this.state,l=!a||!o;return(0,e.createElement)(N,{id:t,title:(0,d.__)("Add Recipient","wds"),description:(0,d.__)("Add as many recipients as you like, they will receive email reports as per the schedule you set.","wds"),small:!0,onEnter:()=>this.handleSubmit(),enterDisabled:l,onClose:n,footer:(0,e.createElement)(s().Fragment,null,(0,e.createElement)(y,{className:"wds-cancel-button",ghost:!0,text:(0,d.__)("Cancel","wds"),onClick:n}),(0,e.createElement)("div",{className:"sui-actions-right"},(0,e.createElement)(y,{id:"wds-add-email-recipient",text:(0,d.__)("Add","wds"),onClick:()=>this.handleSubmit(),disabled:l})))},(0,e.createElement)($,{id:"wds-recipient-name",label:(0,d.__)("First name","wds"),placeholder:(0,d.__)("E.g. John","wds"),value:r,onChange:(e,t)=>this.handleChangeName(e,t),isValid:a}),(0,e.createElement)(M,{id:"wds-recipient-email",label:(0,d.__)("Email address","wds"),placeholder:(0,d.__)("E.g. john@doe.com","wds"),value:i,onChange:(e,t)=>this.handleChangeEmail(e,t),isValid:o}))}handleSubmit(){const{name:e,email:t}=this.state;this.props.onSubmit(e,t)}handleChangeName(e,t){this.setState({name:e,isNameValid:t})}handleChangeEmail(e,t){this.setState({email:e,isEmailValid:t})}}c(S,"defaultProps",{id:"",onSubmit:()=>!1,onClose:()=>!1});class P extends s().Component{render(){const t=this.getIcon(this.props.type);return(0,e.createElement)("div",{className:g()("sui-notice","sui-notice-"+this.props.type,this.props.className)},(0,e.createElement)("div",{className:"sui-notice-content"},(0,e.createElement)("div",{className:"sui-notice-message"},t&&(0,e.createElement)("span",{className:g()("sui-notice-icon sui-md",t),"aria-hidden":"true"}),(0,e.createElement)("p",null,this.props.message))))}getIcon(e){return{warning:"sui-icon-warning-alert",error:"sui-icon-warning-alert",info:"sui-icon-info",success:"sui-icon-check-tick",purple:"sui-icon-info","":"sui-icon-info"}[e]}}c(P,"defaultProps",{type:"warning",message:""});class V extends s().Component{render(){return(0,e.createElement)("div",{className:"sui-floating-notices"},(0,e.createElement)("div",{role:"alert",id:this.props.id,className:"sui-notice","aria-live":"assertive"}))}}c(V,"defaultProps",{id:""});class k extends s().Component{constructor(e){super(e),this.state={recipients:this.props.recipients,openDialog:!1}}render(){const{id:t,fieldName:n}=this.props,{recipients:r,openDialog:i}=this.state;return(0,e.createElement)(s().Fragment,null,(0,e.createElement)(V,{id:"wds-email-recipient-notice"}),!r.length&&(0,e.createElement)(P,{type:"warning",message:(0,d.__)("You've removed all recipients. If you save without a recipient, we'll automatically turn off reports.","wds")}),(0,e.createElement)("div",null,r.map(((t,s)=>(0,e.createElement)(h,{key:s,index:s,recipient:t,fieldName:n,onRemove:e=>this.handleRemove(e)})))),(0,e.createElement)(y,{ghost:!0,icon:"sui-icon-plus",onClick:()=>this.toggleModal(),text:(0,d.__)("Add Recipient","wds")}),i&&(0,e.createElement)(S,{id:t,onSubmit:(e,t)=>this.handleAdd(e,t),onClose:()=>this.toggleModal()}))}handleAdd(e,t){this.setState({recipients:u()([{name:e,email:t}],{$push:this.state.recipients}),openDialog:!1},(()=>{(class{static showSuccessNotice(e,t,n=!0){return this.showNotice(e,t,"success",n)}static showErrorNotice(e,t,n=!0){return this.showNotice(e,t,"error",n)}static showInfoNotice(e,t,n=!0){return this.showNotice(e,t,"info",n)}static showWarningNotice(e,t,n=!0){return this.showNotice(e,t,"warning",n)}static showNotice(e,t,n="success",s=!0){SUI.closeNotice(e),SUI.openNotice(e,"<p>"+t+"</p>",{type:n,icon:{error:"warning-alert",info:"info",warning:"warning-alert",success:"check-tick"}[n],dismiss:{show:s}})}}).showInfoNotice("wds-email-recipient-notice",(0,d.sprintf)((0,d.__)("%s has been added as a recipient. Please save your changes to set this live.","wds"),e),!1)}))}handleRemove(e){this.setState({recipients:u()(this.state.recipients,{$splice:[[e,1]]})})}toggleModal(){this.setState({openDialog:!this.state.openDialog})}}c(k,"defaultProps",{id:"",recipients:"",fieldName:""});var D=class{static get(e,t="general"){Array.isArray(e)||(e=[e]);let n=window["_wds_"+t]||{};return e.forEach((e=>{n=n&&n.hasOwnProperty(e)?n[e]:""})),n}static get_bool(e,t="general"){return!!this.get(e,t)}};a()((()=>{const t=document.getElementById("wds-email-recipients");if(t){const n=D.get("id","email_recipients"),s=D.get("recipients","email_recipients"),i=D.get("field_name","email_recipients");(0,r.render)((0,e.createElement)(l,null,(0,e.createElement)(k,{id:n,recipients:s,fieldName:i})),t)}}))}()}();