(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-notice-notice"],{"0e95":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={data:function(){return{article:[],webview:""}},onLoad:function(t){t.url?this.$app.goPage(t.url):t.id&&this.getArticle(t.id)},methods:{getArticle:function(t){var e=this;this.$app.request(this.$app.API.ARTICLE,{id:t},function(t){try{e.article=JSON.parse(t.data.value)}catch(a){e.$app.goPage(t.data.value)}uni.setNavigationBarTitle({title:t.data.name})})}}};e.default=i},"959a":function(t,e,a){var i=a("c67d");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=a("4f06").default;n("232525ed",i,!0,{sourceMap:!1,shadowMode:!1})},"968f":function(t,e,a){"use strict";a.r(e);var i=a("d5de"),n=a("b853");for(var r in n)"default"!==r&&function(t){a.d(e,t,function(){return n[t]})}(r);a("a3d6");var c=a("2877"),u=Object(c["a"])(n["default"],i["a"],i["b"],!1,null,"7cd8850b",null);e["default"]=u.exports},a3d6:function(t,e,a){"use strict";var i=a("959a"),n=a.n(i);n.a},b853:function(t,e,a){"use strict";a.r(e);var i=a("0e95"),n=a.n(i);for(var r in i)"default"!==r&&function(t){a.d(e,t,function(){return i[t]})}(r);e["default"]=n.a},c67d:function(t,e,a){e=t.exports=a("2350")(!1),e.push([t.i,".container[data-v-7cd8850b]{padding:%?20?% %?40?%}",""])},d5de:function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"container"},[t.webview?a("v-uni-web-view",{attrs:{src:t.webview,progress:"0"}}):t._l(t.article,function(e,i){return[e.title?a("v-uni-view",{key:i+"_0",staticClass:"article-title"},[t._v(t._s(e.title))]):t._e(),t._l(e.content,function(n,r){return e.content.length>0?a("v-uni-text",{key:r+"_"+i+"_1",staticClass:"article-content",attrs:{decode:""}},[t._v(t._s(n))]):t._e()}),e.image?a("v-uni-image",{key:i+"_2",staticClass:"article-image",attrs:{src:e.image,mode:"widthFix"}}):t._e()]})],2)},n=[];a.d(e,"a",function(){return i}),a.d(e,"b",function(){return n})}}]);