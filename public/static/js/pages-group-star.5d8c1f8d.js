(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-group-star"],{"0a32":function(t,a,n){"use strict";var e=n("6201"),i=n.n(e);i.a},"4a2b":function(t,a,n){"use strict";n.r(a);var e=n("4a42"),i=n("cd38");for(var o in i)"default"!==o&&function(t){n.d(a,t,function(){return i[t]})}(o);n("0a32");var s=n("2877"),r=Object(s["a"])(i["default"],e["a"],e["b"],!1,null,"047c8709",null);a["default"]=r.exports},"4a42":function(t,a,n){"use strict";var e=function(){var t=this,a=t.$createElement,n=t._self._c||a;return n("v-uni-view",{staticClass:"star-container"},[n("guildComponent",{ref:"guildComponent"}),n("v-uni-button",{attrs:{"open-type":"getUserInfo"},on:{getuserinfo:function(a){arguments[0]=a=t.$handleEvent(a),t.getUserInfo.apply(void 0,arguments)}}},[t.tips?n("v-uni-view",{staticClass:"tips-container"},[n("v-uni-image",{attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERenSrzKEkAp9n21IPHed1tVsOl379qcR2nARISo0xjicBPNmQgohh7aw/0",mode:"widthFix"}})],1):t._e()],1)],1)},i=[];n.d(a,"a",function(){return e}),n.d(a,"b",function(){return i})},6201:function(t,a,n){var e=n("cd65");"string"===typeof e&&(e=[[t.i,e,""]]),e.locals&&(t.exports=e.locals);var i=n("4f06").default;i("266bf86e",e,!0,{sourceMap:!1,shadowMode:!1})},ccec:function(t,a,n){"use strict";var e=n("288e");Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var i=e(n("6994")),o={components:{guildComponent:i.default},data:function(){return{tips:!1}},onShareAppMessage:function(){return this.$app.commonShareAppMessage()},onLoad:function(t){this.starid=t.starid,this.starid==this.$app.getData("userStar").id&&this.$app.goPage("/pages/group/group")},onReady:function(){},onShow:function(){this.$app.getData("userStar").id||(this.tips=!0),this.$nextTick(function(){this.$refs.guildComponent.load&&this.$refs.guildComponent.load(this.starid)})},onUnload:function(){this.$refs.guildComponent.unLoad&&this.$refs.guildComponent.unLoad(this.starid),this.$refs.guildComponent.modal=""},methods:{getUserInfo:function(t){var a=this,n=t.detail.userInfo;this.tips=!1,n&&!this.$app.getData("userInfo").nickname&&~this.$app.getData("platform").indexOf("MP")&&this.$app.request(this.$app.API.USER_SAVEINFO,{iv:t.detail.iv,encryptedData:t.detail.encryptedData},function(t){t.data.userInfo.id!=a.$app.getData("userInfo").id&&(a.$app.token=null,a.$app.request("page/app",{},function(t){a.$app.setData("userCurrency",t.data.userCurrency),a.$app.setData("userStar",t.data.userStar),a.$app.setData("userExt",t.data.userExt),uni.showModal({title:"提示",content:"已同步其他平台账号数据",showCancel:!1,success:function(n){n.confirm&&(t.data.userStar.id?a.$app.goPage("/pages/group/group"):a.$refs.guildComponent.load(a.starid))}})})),a.$app.setData("userInfo",t.data.userInfo)},"POST",!0)}}};a.default=o},cd38:function(t,a,n){"use strict";n.r(a);var e=n("ccec"),i=n.n(e);for(var o in e)"default"!==o&&function(t){n.d(a,t,function(){return e[t]})}(o);a["default"]=i.a},cd65:function(t,a,n){a=t.exports=n("2350")(!1),a.push([t.i,".star-container[data-v-047c8709]{position:relative;height:100%}.star-container .tips-container[data-v-047c8709]{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.8);z-index:6;margin-top:%?-130?%}.star-container .tips-container uni-image[data-v-047c8709]{width:100%;margin-top:%?100?%}",""])}}]);