(window.webpackJsonp=window.webpackJsonp||[]).push([[36],{269:function(e,t,r){var a=r(421);"string"==typeof a&&(a=[[e.i,a,""]]);var n={hmr:!0,transform:void 0,insertInto:void 0};r(8)(a,n);a.locals&&(e.exports=a.locals)},420:function(e,t,r){"use strict";var a=r(269);r.n(a).a},421:function(e,t,r){(e.exports=r(7)(!1)).push([e.i,"\n.v-messages__message[data-v-1ea0a61a] {\n  color: white !important;\n  font-weight: bold;\n}\n#myVideo[data-v-1ea0a61a] {\n  position: fixed;\n  min-height: 100%;\n  left: -20%;\n}\n.flexible-container[data-v-1ea0a61a] {\n  position: relative;\n  background-color: black;\n  min-height: 100%;\n  width: 100%;\n  overflow: hidden;\n}\n.flexible-container video[data-v-1ea0a61a] {\n  position: absolute;\n  top: 50%;\n  left: 50%;\n  min-width: 100%;\n  min-height: 100%;\n  width: auto;\n  height: auto;\n  z-index: 0;\n  transform: translateX(-50%) translateY(-50%);\n}\n.login-card[data-v-1ea0a61a] {\n  position: absolute;\n  max-width: 400px;\n  right: 0;\n  background-color: rgba(0, 0, 0, 0.4) !important;\n  top: 50%;\n  margin-top: -285px;\n}\n@media only screen and (max-width: 768px) {\n  /* For mobile phones: */\n.login-card[data-v-1ea0a61a]{\n    top: 0;\n    margin-top: 0px;\n    height: 100%;\n    background-color: transparent !important;\n}\n}\n@media only screen and (max-width: 1366px) {\n.flexible-container video[data-v-1ea0a61a] {\n    max-width: 290%;\n    left: 37%;\n}\n}\n",""])},480:function(e,t,r){"use strict";r.r(t);var a=r(15),n=r(14),i=r(10),o=r(17),s=r(20),l=r.n(s);function c(e){return function(e){if(Array.isArray(e))return d(e)}(e)||function(e){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e))return Array.from(e)}(e)||function(e,t){if(!e)return;if("string"==typeof e)return d(e,t);var r=Object.prototype.toString.call(e).slice(8,-1);"Object"===r&&e.constructor&&(r=e.constructor.name);if("Map"===r||"Set"===r)return Array.from(e);if("Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r))return d(e,t)}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function d(e,t){(null==t||t>e.length)&&(t=e.length);for(var r=0,a=new Array(t);r<t;r++)a[r]=e[r];return a}function m(e){return(m="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function u(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,a)}return r}function p(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?u(Object(r),!0).forEach((function(t){f(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):u(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function f(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}var v={data:function(){return{dialog:!1,valid:!0,valid2:!0,password:"",password2:"",passwordRules:o.b,email:"",email2:"",emailRules:o.a,remember:!1,lazy:!1,loading:!1,loading2:!1,items:[],item:null}},computed:p(p(p({},Object(a.c)({host:"util/host"})),Object(i.b)("user",["error"])),{},{newPassword:function(){if(("undefined"==typeof data?"undefined":m(data))==m("string")){var e=JSON.parse(data);return"undefined"!=m(e.email)&&e.email}return!1}}),watch:{item:function(e){var t=this;this.loading=!0,this.$store.dispatch("user/authenticate",{email:e.email,password:"admin"==e.role.slug?"admin":"123",remember:!0,newPassword:!1}).then((function(){t.loading=!1})).catch((function(e){t.error=e,console.log(error)}))}},methods:p(p({},Object(a.b)({resetPassword:"user/resetPassword"})),{},{sendResetPasswordEmail:function(){var e=this;this.$refs.form2.validate()&&(this.loading2=!0,this.resetPassword({email:this.email2}).then((function(t){e.loading2=!1,200==t.status&&(e.dialog=!1,n.a.$emit("alert",{text:t.data,color:"success"}))})).catch((function(e){n.a.$emit("alert",{text:e.response.data,color:"error"})})).finally((function(){return e.loading2=!1})))},reset:function(){this.$refs.form.reset()},resetValidation:function(){this.$refs.form.resetValidation()},authenticate:function(){var e=this;if(this.$refs.form.validate()){if(this.newPassword&&this.password!=this.password2)return void(this.error={data:{error:"Las contraseñas deben ser iguales."}});this.loading=!0,this.$store.dispatch("user/authenticate",{email:this.email,password:this.password,remember:this.remember,newPassword:!!this.newPassword}).then((function(){e.loading=!1})).catch((function(t){e.error=t,console.log(error)}))}}}),created:function(){var e=this;this.email=this.newPassword||"",l.a.get("/users-select-test").then((function(t){e.items=[{header:"Role Admin"}].concat(c(t.data.data),[{divider:!0}]),console.log(e.items)}))}},h=(r(420),r(6)),b=r(11),w=r.n(b),g=r(399),y=r(106),x=r(75),_=r(262),P=r(469),O=r(93),k=r(397),j=r(384),V=r(84),C=r(110),S=r(23),A=r(398),I=r(39),R=r(26),$=Object(h.a)(v,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("v-row",{staticClass:"h-100"},[r("v-col",{staticClass:"d-md-flex pa-0",attrs:{cols:"12"}},[r("div",{staticClass:"flexible-container"},[r("video",{attrs:{autoplay:"",muted:"",loop:"",id:"myVidedo"},domProps:{muted:!0}},[r("source",{attrs:{src:"/videos/company-video.mp4",type:"video/mp4"}}),e._v(" "),r("source",{attrs:{src:"/videos/company-video.mp4",type:"video/ogg"}})]),e._v(" "),r("v-card",{staticClass:"w-100 mr-0 mr-md-10 login-card",attrs:{elevation:"0"}},[r("div",{staticClass:"pa-5"},[e.error?r("v-alert",{staticClass:"my-3",attrs:{type:"error"}},[e._v(e._s(e.error.data.error||e.error.data.message))]):e._e(),e._v(" "),r("div",{staticClass:"text-h5 mt-10 white--text"},[e._v("Iniciar sesión")]),e._v(" "),r("v-form",{ref:"form",staticClass:"mb-10 mt-5",attrs:{"lazy-validation":e.lazy},model:{value:e.valid,callback:function(t){e.valid=t},expression:"valid"}},[r("v-select",{attrs:{items:e.items,label:"Select a test user",outlined:"",solo:""},scopedSlots:e._u([{key:"selection",fn:function(t){return[r("v-chip",e._b({},"v-chip",t.attrs,!1),[r("v-avatar",{attrs:{left:""}},[t.item.image?r("img",{attrs:{src:t.item.image.url,alt:""}}):r("v-icon",{attrs:{color:"grey",dark:"",size:"40"}},[e._v("mdi-account")])],1),e._v("\n                  "+e._s(t.item.name)+"\n                ")],1)]}},{key:"item",fn:function(t){return["object"==typeof t.item?[r("v-list-item-avatar",[t.item.image?r("img",{attrs:{src:t.item.image.url,alt:""}}):r("v-icon",{attrs:{color:"grey",dark:"",size:"40"}},[e._v("mdi-account")])],1),e._v(" "),r("v-list-item-content",[r("v-list-item-title",{domProps:{innerHTML:e._s(t.item.name)}}),e._v(" "),r("v-list-item-subtitle",{domProps:{innerHTML:e._s(t.item.group)}})],1)]:[r("v-list-item-content",{domProps:{textContent:e._s(t.item)}})]]}}]),model:{value:e.item,callback:function(t){e.item=t},expression:"item"}}),e._v(" "),r("v-text-field",{attrs:{disabled:""!=e.newPassword,rules:e.emailRules,label:"Email","prepend-inner-icon":"mdi-email",required:"",solo:"",outlined:""},model:{value:e.email,callback:function(t){e.email=t},expression:"email"}}),e._v(" "),r("v-text-field",{attrs:{rules:e.passwordRules,label:e.newPassword?"Crear contraseña":"Contraseña","prepend-inner-icon":"mdi-lock",type:"password",required:"",solo:"",outlined:""},model:{value:e.password,callback:function(t){e.password=t},expression:"password"}}),e._v(" "),e.newPassword?r("v-text-field",{attrs:{rules:e.passwordRules,label:"Repetir contraseña","prepend-inner-icon":"mdi-lock",type:"password",required:"",solo:"",outlined:""},model:{value:e.password2,callback:function(t){e.password2=t},expression:"password2"}}):e._e(),e._v(" "),e.newPassword?e._e():r("v-checkbox",{attrs:{dark:"",label:"Recordar contraseña?",required:""},model:{value:e.remember,callback:function(t){e.remember=t},expression:"remember"}}),e._v(" "),r("v-btn",{attrs:{"min-width":"100%",rounded:"",color:"primary",loading:e.loading},on:{click:e.authenticate}},[e._v(e._s("Iniciar sesión"))]),e._v(" "),e.newPassword?e._e():r("div",{staticClass:"mt-5 font-weight-600 white--text"},[r("a",{staticClass:"white--text",on:{click:function(t){e.dialog=!0}}},[e._v("Olvide mi contraseña")])])],1)],1)])],1)])],1)}),[],!1,null,"1ea0a61a",null);t.default=$.exports;w()($,{VAlert:g.a,VAvatar:y.a,VBtn:x.a,VCard:_.a,VCheckbox:P.a,VChip:O.a,VCol:k.a,VForm:j.a,VIcon:V.a,VListItemAvatar:C.a,VListItemContent:S.a,VListItemSubtitle:S.b,VListItemTitle:S.c,VRow:A.a,VSelect:I.a,VTextField:R.a})}}]);