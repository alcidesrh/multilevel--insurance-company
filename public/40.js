(window.webpackJsonp=window.webpackJsonp||[]).push([[40],{485:function(e,t,i){"use strict";i.r(t);var r=i(14),a=i(10),l=i(88),s=i(71),n=i(15),o=i(89),c=i(90);function m(e,t){var i=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),i.push.apply(i,r)}return i}function u(e){for(var t=1;t<arguments.length;t++){var i=null!=arguments[t]?arguments[t]:{};t%2?m(Object(i),!0).forEach((function(t){f(e,t,i[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(i)):m(Object(i)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(i,t))}))}return e}function f(e,t,i){return t in e?Object.defineProperty(e,t,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[t]=i,e}var d={mixins:[o.a,c.a],components:{ListUserCard:l.a,Alert:s.a},computed:u({},Object(a.b)("elite",["page","items","loadingList","perPage","pages","search","filterActive","filters"])),methods:u(u({},Object(r.b)({removeUser:"user/remove",getItemsAction:"elite/getItems"})),{},{getItems:function(){this.getItemsAction({endpoint:"users",query:{role:"elite"}})},remove:function(e){var t=this;window.confirm("Seguro desea eliminar este usuario.")&&this.removeUser("users/"+e).then((function(e){"deleted"==e.data&&(n.a.$emit("alert",{text:"Se ha eliminado el usuario",color:"success"}),t.getItems())}))}})},p=i(8),v=i(11),b=i.n(v),g=i(74),x=i(398),_=i(82),w=i(473),y=i(399),h=i(37),O=i(26),j=Object(p.a)(d,(function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",[i("v-row",[i("v-col",{staticClass:"d-flex justify-end"},[i("v-btn",{attrs:{icon:"",color:e.filterActive?"teal":"primary"},on:{click:function(t){e.filterActive=!e.filterActive}}},[i("v-icon",{attrs:{size:"30"}},[e._v("mdi-filter-menu")])],1),e._v(" "),i("v-btn",{staticClass:"ml-2",attrs:{rounded:"",color:"primary"},on:{click:function(t){e.$router.push({name:"edit-user",params:{user:{id:"new",role:"elite"}}},(function(){}))}}},[i("v-icon",{staticClass:"mr-1",attrs:{size:"20"}},[e._v("mdi-plus")]),e._v("Crear Usuario\n      ")],1)],1)],1),e._v(" "),e.filterActive?i("v-row",[i("v-col",{attrs:{cols:"12",md:"6",lg:"4"}},[i("v-text-field",{attrs:{label:"Nombre","prepend-inner-icon":"mdi-magnify",clearable:"",loading:e.loadingList},model:{value:e.filters.name,callback:function(t){e.$set(e.filters,"name",t)},expression:"filters.name"}})],1),e._v(" "),i("v-col",{attrs:{cols:"12",md:"6",lg:"4"}},[i("v-text-field",{attrs:{label:"Apellido","prepend-inner-icon":"mdi-magnify",clearable:"",loading:e.loadingList},model:{value:e.filters.last_name,callback:function(t){e.$set(e.filters,"last_name",t)},expression:"filters.last_name"}})],1),e._v(" "),i("v-col",{attrs:{cols:"12",md:"6",lg:"4"}},[i("v-text-field",{attrs:{label:"Email","prepend-inner-icon":"mdi-magnify",clearable:"",loading:e.loadingList},model:{value:e.filters.email,callback:function(t){e.$set(e.filters,"email",t)},expression:"filters.email"}})],1),e._v(" "),i("v-col",{attrs:{cols:"12",md:"6",lg:"4"}},[i("v-text-field",{attrs:{label:"Teléfono","prepend-inner-icon":"mdi-magnify",clearable:"",loading:e.loadingList},model:{value:e.filters.phone,callback:function(t){e.$set(e.filters,"phone",t)},expression:"filters.phone"}})],1),e._v(" "),i("v-col",{attrs:{cols:"12",md:"4",lg:"4"}},[i("v-text-field",{attrs:{label:"Código personal","prepend-inner-icon":"mdi-magnify",clearable:"",loading:e.loadingList},model:{value:e.filters.number_account,callback:function(t){e.$set(e.filters,"number_account",t)},expression:"filters.number_account"}})],1),e._v(" "),i("v-col",{staticClass:"d-flex",attrs:{cols:"12",md:"6",lg:"4"}},[i("v-select",{attrs:{clearable:"",items:[{text:"Subscripción activa",value:"active"},{text:"Subscripción expirada",value:"inactive"}],label:"Subscripción"},model:{value:e.filters.subscription,callback:function(t){e.$set(e.filters,"subscription",t)},expression:"filters.subscription"}})],1),e._v(" "),i("v-col",{attrs:{cols:"12",md:"12",lg:"4"}},[i("v-select",{attrs:{clearable:"",items:e.licenses,label:"Licencia","item-value":"id","item-text":"title"},model:{value:e.filters.license,callback:function(t){e.$set(e.filters,"license",t)},expression:"filters.license"}})],1),e._v(" "),i("v-col",{attrs:{cols:"12",md:"6",lg:"4"}},[i("DatePicker",{attrs:{label:"Desde","date-param":e.filters.from},on:{"update:dateParam":function(t){return e.$set(e.filters,"from",t)},"update:date-param":function(t){return e.$set(e.filters,"from",t)}}})],1),e._v(" "),i("v-col",{attrs:{cols:"12",md:"6",lg:"4"}},[i("DatePicker",{attrs:{min:e.filters.from,label:"Hasta","date-param":e.filters.to},on:{"update:dateParam":function(t){return e.$set(e.filters,"to",t)},"update:date-param":function(t){return e.$set(e.filters,"to",t)}}})],1)],1):e._e(),e._v(" "),e.items.length?i("v-row",e._l(e.items,(function(t,r){return i("v-col",{key:r,staticClass:"pa-1",attrs:{cols:"12",md:"6",lg:"4"}},[i("ListUserCard",{attrs:{item:t,role:"elite"},on:{remove:e.remove}})],1)})),1):i("v-row",[i("v-col",{attrs:{cols:"12"}},[i("Alert",{attrs:{text:"No se encontraron representantes"}})],1)],1),e._v(" "),i("v-pagination",{directives:[{name:"show",rawName:"v-show",value:!e.loadingList&&e.pages,expression:"!loadingList && pages"}],staticClass:"mt-5",attrs:{length:e.pages,"total-visible":e.pages-e.perPage>5?5:e.pages},model:{value:e.page,callback:function(t){e.page=t},expression:"page"}})],1)}),[],!1,null,null,null);t.default=j.exports;b()(j,{VBtn:g.a,VCol:x.a,VIcon:_.a,VPagination:w.a,VRow:y.a,VSelect:h.a,VTextField:O.a})}}]);