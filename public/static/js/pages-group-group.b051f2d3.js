(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-group-group"],{"258d":function(e,t,a){"use strict";a.r(t);var i=a("a116"),n=a.n(i);for(var r in i)"default"!==r&&function(e){a.d(t,e,function(){return i[e]})}(r);t["default"]=n.a},"35bb":function(e,t,a){"use strict";a.r(t);var i=a("a817"),n=a("258d");for(var r in n)"default"!==r&&function(e){a.d(t,e,function(){return n[e]})}(r);a("bcdb");var o=a("2877"),s=Object(o["a"])(n["default"],i["a"],i["b"],!1,null,"3dcaedeb",null);t["default"]=s.exports},"6e43":function(e,t,a){var i=a("a011");"string"===typeof i&&(i=[[e.i,i,""]]),i.locals&&(e.exports=i.locals);var n=a("4f06").default;n("404ca3ea",i,!0,{sourceMap:!1,shadowMode:!1})},a011:function(e,t,a){t=e.exports=a("2350")(!1),t.push([e.i,'.group-container[data-v-3dcaedeb]{position:fixed;width:100%;height:100%;height:100%}.group-container .blank-container[data-v-3dcaedeb]{height:100%}.group-container .blank-container .blank[data-v-3dcaedeb]{background:rgba(0,0,0,.9);font-size:%?34?%;color:#fff;height:100%;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column}.group-container .blank-container .blank uni-image[data-v-3dcaedeb]{width:%?150?%;margin-bottom:%?20?%}.group-container .blank-container .select-container[data-v-3dcaedeb]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;height:100%}.group-container .blank-container .select-container .search-wrapper[data-v-3dcaedeb]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center}.group-container .blank-container .select-container .search-wrapper uni-input[data-v-3dcaedeb]{height:%?70?%;width:%?400?%;background-color:#efccc8;border-radius:%?60?%;padding:%?10?% %?20?%;margin:%?20?%;position:relative;text-align:center;color:#fff}.group-container .blank-container .select-container .search-wrapper uni-input[data-v-3dcaedeb]:after{content:"\\E61F";position:absolute;right:%?20?%;top:50%;-webkit-transform:translateY(-50%);-ms-transform:translateY(-50%);transform:translateY(-50%);font-family:iconfont!important;font-size:%?40?%}.group-container .blank-container .select-container uni-scroll-view[data-v-3dcaedeb]{width:100%;border-bottom:1px solid #efccc8}.group-container .blank-container .select-container .starlist-wrapper[data-v-3dcaedeb]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-flex-wrap:nowrap;-ms-flex-wrap:nowrap;flex-wrap:nowrap;padding:%?10?% 0}.group-container .blank-container .select-container .starlist-wrapper .item[data-v-3dcaedeb]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;margin:%?10?%}.group-container .blank-container .select-container .starlist-wrapper .item uni-image[data-v-3dcaedeb]{width:%?120?%;height:%?120?%;border-radius:50%}.group-container .blank-container .select-container .starlist-wrapper .item .name[data-v-3dcaedeb]{text-align:center;width:%?100?%}.group-container .blank-container .select-container .blank-bottom[data-v-3dcaedeb]{-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;font-size:%?30?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;color:#b9796e}.group-container .blank-container .select-container .blank-bottom uni-image[data-v-3dcaedeb]{width:%?120?%;height:%?120?%;margin-bottom:%?20?%}',""])},a116:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var i=n(a("b7ce"));function n(e){return e&&e.__esModule?e:{default:e}}var r={components:{guildComponent:i.default},data:function(){return{starid:this.$app.getData("userStar",!0)["id"]||null,requestCount:1,blankHide:!0,rankList:[],keywords:"",page:1,share:{}}},onLoad:function(){},onShow:function(){var e=this;this.starid=this.$app.getData("userStar",!0)["id"]||null,this.starid?this.$nextTick(function(){this.$refs.guildComponent.load(this.starid)}):(setTimeout(function(){e.blankHide=!0},2e3),this.getRankList())},onHide:function(){this.starid&&(this.$refs.guildComponent.unLoad(this.starid),this.$refs.guildComponent.modal="")},onShareAppMessage:function(e){var t=e.target&&e.target.dataset.share;return this.$app.commonShareAppMessage(t)},methods:{goPage:function(e){this.$app.goPage("/pages/star/star?starid="+e)},searchInput:function(e){this.page=1,this.keywords=e.detail.value,this.getRankList()},getRankList:function(){var e=this;this.$app.request(this.$app.API.STAR_RANK,{page:this.page,size:100,keywords:this.keywords},function(t){var a=[];t.data.forEach(function(e){var t={};t.starid=e.star.id,t.name=e.star.name,t.avatar=e.star.head_img_s?e.star.head_img_s:e.star.head_img_l,a.push(t)}),e.rankList=a,e.$nextTick(function(){this.scrollLeft=0}),e.$app.closeLoading(e)})}}};t.default=r},a817:function(e,t,a){"use strict";var i=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("v-uni-view",{staticClass:"group-container"},[e.starid?a("guildComponent",{ref:"guildComponent"}):a("v-uni-view",{staticClass:"blank-container"},[e.blankHide?a("v-uni-view",{staticClass:"select-container"},[a("v-uni-view",{staticClass:"search-wrapper"},[a("v-uni-input",{attrs:{type:"text",value:"",placeholder:"搜索爱豆"},on:{input:function(t){t=e.$handleEvent(t),e.searchInput(t)}}})],1),a("v-uni-scroll-view",{attrs:{"scroll-x":""}},[a("v-uni-view",{staticClass:"starlist-wrapper"},e._l(e.rankList,function(t,i){return i<50?a("v-uni-view",{key:i,staticClass:"item",on:{click:function(a){a=e.$handleEvent(a),e.goPage(t.starid)}}},[a("v-uni-image",{attrs:{src:t.avatar,mode:"aspectFill"}}),a("v-uni-view",{staticClass:"name text-overflow"},[e._v(e._s(t.name))])],1):e._e()}),1),a("v-uni-view",{staticClass:"starlist-wrapper"},e._l(e.rankList,function(t,i){return i>=50?a("v-uni-view",{key:i,staticClass:"item",on:{click:function(a){a=e.$handleEvent(a),e.goPage(t.starid)}}},[a("v-uni-image",{attrs:{src:t.avatar,mode:"aspectFill"}}),a("v-uni-view",{staticClass:"name text-overflow"},[e._v(e._s(t.name))])],1):e._e()}),1)],1),a("v-uni-view",{staticClass:"blank-bottom flex-set"},[a("v-uni-image",{attrs:{src:"/static/image/user/blank.png",mode:"aspectFill"}}),a("v-uni-view",[e._v("亲！您还没有加入任何圈子！")]),a("v-uni-view",[e._v("搜索爱豆名字，点头像加入！")])],1)],1):a("v-uni-view",{staticClass:"blank flex-set"},[a("v-uni-image",{attrs:{src:"/static/image/user/blank.png",mode:"widthFix"}}),a("v-uni-view",[e._v("亲！您还没有加入任何圈子！")]),a("v-uni-view",[e._v("搜索爱豆名字，点头像加入！")])],1)],1)],1)},n=[];a.d(t,"a",function(){return i}),a.d(t,"b",function(){return n})},bcdb:function(e,t,a){"use strict";var i=a("6e43"),n=a.n(i);n.a}}]);