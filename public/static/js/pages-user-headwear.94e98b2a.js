(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-headwear"],{3067:function(t,e,a){"use strict";a.r(e);var i=a("9364"),n=a.n(i);for(var r in i)"default"!==r&&function(t){a.d(e,t,function(){return i[t]})}(r);e["default"]=n.a},"6b77":function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"container"},[a("v-uni-view",{staticClass:"avatar-wrap flex-set"},[a("v-uni-view",{staticClass:"avatar-block"},[a("v-uni-image",{staticClass:"avatar",attrs:{src:t.$app.getData("userInfo").avatarurl||this.AVATAR}}),t.curHeadwear&&t.curHeadwear.img?a("v-uni-image",{staticClass:"headwear position-set",attrs:{src:t.curHeadwear.img}}):t._e()],1),a("v-uni-view",[t._v(t._s(t.$app.getData("userInfo").nickname))])],1),a("v-uni-view",{staticClass:"tips flex-set"},[t._v("点击可预览效果")]),a("v-uni-view",{staticClass:"list-wrap"},t._l(t.list,function(e,i){return a("v-uni-view",{key:i,staticClass:"item"},[a("v-uni-view",{staticClass:"top-wrap flex-set",class:{use:t.curHeadwear.id==e.id},attrs:{"data-cur":e},on:{click:function(e){e=t.$handleEvent(e),t.preHead(e)}}},[a("v-uni-image",{staticClass:"icon",attrs:{src:e.img}})],1),a("v-uni-view",{staticClass:"fee"},[t._v(t._s(e.diamond)+"钻石")]),-1==e.status?a("v-uni-view",{staticClass:"btn flex-set",attrs:{"data-hid":e.id},on:{click:function(e){e=t.$handleEvent(e),t.buy(e)}}},[t._v("兑换")]):t._e(),0==e.status?a("v-uni-view",{staticClass:"btn flex-set success",attrs:{"data-hid":e.id},on:{click:function(e){e=t.$handleEvent(e),t.use(e)}}},[t._v("佩戴")]):t._e(),1==e.status?a("v-uni-view",{staticClass:"btn flex-set disable",attrs:{"data-hid":e.id},on:{click:function(e){e=t.$handleEvent(e),t.cancel(e)}}},[t._v("摘下")]):t._e()],1)}),1)],1)},n=[];a.d(e,"a",function(){return i}),a.d(e,"b",function(){return n})},9364:function(t,e,a){"use strict";var i=a("288e");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=i(a("65dc")),r=i(a("b111")),s=i(a("5390")),o={components:{badgeComponent:n.default,modalComponent:s.default,btnComponent:r.default},data:function(){return{list:[],curHeadwear:{}}},onLoad:function(){this.loadData()},onShow:function(){},onShareAppMessage:function(t){var e=t.target&&t.target.dataset.share;return this.$app.commonShareAppMessage(e)},methods:{preHead:function(t){this.curHeadwear=t.currentTarget.dataset.cur},buy:function(t){var e=this;wx.showModal({title:"提示",content:"是否兑换？",success:function(a){a.confirm&&e.$app.request("headwear/buy",{hid:t.currentTarget.dataset.hid},function(t){e.$app.toast(t.msg),e.loadData()},"POST",!0)}})},use:function(t){var e=this;this.$app.request("headwear/use",{hid:t.currentTarget.dataset.hid},function(t){e.$app.toast(t.msg),e.loadData()},"POST",!0)},cancel:function(t){var e=this;this.$app.request("headwear/cancel",{},function(t){e.$app.toast(t.msg),e.loadData()},"POST",!0)},loadData:function(){var t=this;this.$app.request("headwear/select",{},function(e){t.list=e.data.list,t.curHeadwear=e.data.cur||null})}}};e.default=o},b311:function(t,e,a){var i=a("c97b");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=a("4f06").default;n("272f5344",i,!0,{sourceMap:!1,shadowMode:!1})},c97b:function(t,e,a){e=t.exports=a("2350")(!1),e.push([t.i,".container[data-v-7f30e7c6]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;height:100%}.container .avatar-wrap[data-v-7f30e7c6]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;padding:%?20?%;position:relative}.container .avatar-wrap .avatar-block[data-v-7f30e7c6]{margin:%?20?%;width:%?120?%;height:%?120?%;position:relative}.container .avatar-wrap .avatar[data-v-7f30e7c6]{width:100%;height:100%;border-radius:50%}.container .avatar-wrap .headwear[data-v-7f30e7c6]{width:%?180?%;height:%?180?%}.container .tips[data-v-7f30e7c6]{height:%?80?%;font-size:%?24?%;background-color:#fef8d4}.container .list-wrap[data-v-7f30e7c6]{-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;overflow-y:scroll;overflow-x:hidden;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-flex-wrap:wrap;-ms-flex-wrap:wrap;flex-wrap:wrap;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between}.container .list-wrap .item[data-v-7f30e7c6]{font-size:%?24?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;width:%?250?%;border-right:1px solid #eee;border-bottom:1px solid #eee;margin-right:-1px;margin-bottom:-1px}.container .list-wrap .item .top-wrap[data-v-7f30e7c6]{height:%?180?%;margin-top:%?10?%;margin-bottom:%?10?%;width:%?230?%;background-color:#fafafa;position:relative}.container .list-wrap .item .top-wrap.use[data-v-7f30e7c6]{background-color:#e6f8fc}.container .list-wrap .item .top-wrap .badge[data-v-7f30e7c6]{width:%?30?%;height:%?30?%;position:absolute;right:%?10?%;top:%?10?%}.container .list-wrap .item .top-wrap .icon[data-v-7f30e7c6]{width:%?120?%;height:%?120?%}.container .list-wrap .item .btn[data-v-7f30e7c6]{border-radius:%?50?%;background-color:#fd523e;color:#fff;width:%?150?%;height:%?50?%;margin:%?10?%}.container .list-wrap .item .btn.success[data-v-7f30e7c6]{background-color:#17b6f0}.container .list-wrap .item .btn.disable[data-v-7f30e7c6]{background-color:#999}",""])},ca78:function(t,e,a){"use strict";a.r(e);var i=a("6b77"),n=a("3067");for(var r in n)"default"!==r&&function(t){a.d(e,t,function(){return n[t]})}(r);a("e679");var s=a("2877"),o=Object(s["a"])(n["default"],i["a"],i["b"],!1,null,"7f30e7c6",null);e["default"]=o.exports},e679:function(t,e,a){"use strict";var i=a("b311"),n=a.n(i);n.a}}]);