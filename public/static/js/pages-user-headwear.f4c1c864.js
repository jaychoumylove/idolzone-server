(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-headwear"],{"103f":function(t,a,e){"use strict";var i=e("288e");Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n=i(e("f741")),r=i(e("961a")),o=i(e("bda9")),s={components:{badgeComponent:n.default,modalComponent:o.default,btnComponent:r.default},data:function(){return{list:[],curHeadwear:{}}},onLoad:function(){this.loadData()},onShow:function(){},onShareAppMessage:function(t){var a=t.target&&t.target.dataset.share;return this.$app.commonShareAppMessage(a)},methods:{preHead:function(t){this.curHeadwear=t},buy:function(t){var a=this;wx.showModal({title:"提示",content:"是否兑换？",success:function(e){e.confirm&&a.$app.request("headwear/buy",{hid:t.currentTarget.dataset.hid},function(t){a.$app.toast(t.msg),a.loadData()},"POST",!0)}})},use:function(t){var a=this;this.$app.request("headwear/use",{hid:t.currentTarget.dataset.hid},function(t){a.$app.toast(t.msg),a.loadData()},"POST",!0)},cancel:function(t){var a=this;this.$app.request("headwear/cancel",{},function(t){a.$app.toast(t.msg),a.loadData()},"POST",!0)},loadData:function(){var t=this;this.$app.request("headwear/select",{},function(a){t.list=a.data.list,t.curHeadwear=a.data.cur||null})}}};a.default=s},"14ce":function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".container[data-v-08f327cf]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;height:100%}.container .avatar-wrap[data-v-08f327cf]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center;padding:%?20?%;position:relative}.container .avatar-wrap .avatar-block[data-v-08f327cf]{margin:%?20?%;width:%?120?%;height:%?120?%;position:relative}.container .avatar-wrap .avatar[data-v-08f327cf]{width:100%;height:100%;border-radius:50%}.container .avatar-wrap .headwear[data-v-08f327cf]{width:%?180?%;height:%?180?%}.container .tips[data-v-08f327cf]{height:%?80?%;font-size:%?24?%;background-color:#fef8d4}.container .list-wrap[data-v-08f327cf]{-webkit-box-flex:1;-webkit-flex:1;flex:1;overflow-y:scroll;overflow-x:hidden;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-flex-wrap:wrap;flex-wrap:wrap;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between}.container .list-wrap .item[data-v-08f327cf]{font-size:%?24?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center;width:%?250?%;border-right:1px solid #eee;border-bottom:1px solid #eee;margin-right:-1px;margin-bottom:-1px}.container .list-wrap .item .top-wrap[data-v-08f327cf]{height:%?180?%;margin-top:%?10?%;margin-bottom:%?10?%;width:%?230?%;background-color:#fafafa;position:relative}.container .list-wrap .item .top-wrap.use[data-v-08f327cf]{background-color:#e6f8fc}.container .list-wrap .item .top-wrap .badge[data-v-08f327cf]{width:%?30?%;height:%?30?%;position:absolute;right:%?10?%;top:%?10?%}.container .list-wrap .item .top-wrap .icon[data-v-08f327cf]{width:%?120?%;height:%?120?%}.container .list-wrap .item .btn[data-v-08f327cf]{border-radius:%?50?%;background-color:#fd523e;color:#fff;width:%?150?%;height:%?50?%;margin:%?10?%}.container .list-wrap .item .btn.success[data-v-08f327cf]{background-color:#17b6f0}.container .list-wrap .item .btn.disable[data-v-08f327cf]{background-color:#999}",""])},9271:function(t,a,e){"use strict";e.r(a);var i=e("103f"),n=e.n(i);for(var r in i)"default"!==r&&function(t){e.d(a,t,function(){return i[t]})}(r);a["default"]=n.a},df0e:function(t,a,e){"use strict";var i=e("e835"),n=e.n(i);n.a},e490:function(t,a,e){"use strict";var i,n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"container"},[e("v-uni-view",{staticClass:"avatar-wrap flex-set"},[e("v-uni-view",{staticClass:"avatar-block"},[e("v-uni-image",{staticClass:"avatar",attrs:{src:t.$app.getData("userInfo").avatarurl||this.AVATAR}}),t.curHeadwear&&t.curHeadwear.img?e("v-uni-image",{staticClass:"headwear position-set",attrs:{src:t.curHeadwear.img}}):t._e()],1),e("v-uni-view",[t._v(t._s(t.$app.getData("userInfo").nickname))])],1),e("v-uni-view",{staticClass:"tips flex-set"},[t._v("点击可预览效果")]),e("v-uni-view",{staticClass:"list-wrap"},t._l(t.list,function(a,i){return e("v-uni-view",{key:i,staticClass:"item"},[e("v-uni-view",{staticClass:"top-wrap flex-set",class:{use:t.curHeadwear&&t.curHeadwear.id==a.id},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.preHead(a)}}},[e("v-uni-image",{staticClass:"icon",attrs:{src:a.img}})],1),e("v-uni-view",{staticClass:"fee"},[t._v(t._s(a.diamond)+"钻石")]),a.days>0?e("v-uni-view",[t._v("(有效期:"+t._s(a.days)+"天)")]):e("v-uni-view",[t._v("(长期)")]),-1==a.status?e("v-uni-view",{staticClass:"btn flex-set",attrs:{"data-hid":a.id},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.buy.apply(void 0,arguments)}}},[t._v("兑换")]):t._e(),0==a.status?e("v-uni-view",{staticClass:"btn flex-set success",attrs:{"data-hid":a.id},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.use.apply(void 0,arguments)}}},[t._v("佩戴")]):t._e(),1==a.status?e("v-uni-view",{staticClass:"btn flex-set disable",attrs:{"data-hid":a.id},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.cancel.apply(void 0,arguments)}}},[t._v("摘下")]):t._e()],1)}),1)],1)},r=[];e.d(a,"b",function(){return n}),e.d(a,"c",function(){return r}),e.d(a,"a",function(){return i})},e835:function(t,a,e){var i=e("14ce");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=e("4f06").default;n("06d70928",i,!0,{sourceMap:!1,shadowMode:!1})},f25a:function(t,a,e){"use strict";e.r(a);var i=e("e490"),n=e("9271");for(var r in n)"default"!==r&&function(t){e.d(a,t,function(){return n[t]})}(r);e("df0e");var o,s=e("f0c5"),c=Object(s["a"])(n["default"],i["b"],i["c"],!1,null,"08f327cf",null,!1,i["a"],o);a["default"]=c.exports}}]);