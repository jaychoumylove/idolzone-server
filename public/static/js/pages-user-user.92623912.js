(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-user"],{"094b":function(t,a,e){"use strict";var i=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"user-page-container"},[e("v-uni-view",{staticClass:"top-bg"}),e("v-uni-view",{staticClass:"top-container"},[e("v-uni-view",{staticClass:"left-wrap flex-set"},[e("v-uni-button",{attrs:{"open-type":"getUserInfo"},on:{getuserinfo:function(a){arguments[0]=a=t.$handleEvent(a),t.getUserInfo.apply(void 0,arguments)}}},[e("v-uni-view",{staticClass:"avatar-wrap"},[e("v-uni-image",{staticClass:"avatar",attrs:{src:t.userInfo.avatarurl,mode:"aspectFill"}})],1)],1),e("v-uni-view",{staticClass:"text-wrap"},[e("v-uni-view",{staticClass:"row"},[t._v(t._s(t.userInfo.nickname||"")),e("v-uni-text",{staticClass:"iconfont iconeditor",staticStyle:{color:"#666"},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.$app.goPage("/pages/user/edit_user")}}})],1),e("v-uni-view",{staticClass:"row",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.$app.copy(1234*t.userInfo.id)}}},[t._v("ID "+t._s(1234*t.userInfo.id||""))])],1)],1)],1),t.$app.getData("config").version!=t.$app.getVal("VERSION")?e("v-uni-view",{staticClass:"top-float-container"},[e("v-uni-view",{staticClass:"row row-1"},[e("v-uni-view",{staticClass:"item-wrap"},[e("v-uni-view",{staticClass:"count"},[e("v-uni-image",{staticClass:"icon",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FctOFR9uh4qenFtU5NmMB5uWEQk2MTaRfxdveGhfFhS1G5dUIkwlT5fosfMaW0c9aQKy3mH3XAew/0",mode:"aspectFill"}}),e("v-uni-text",{staticClass:"num"},[t._v(t._s(t.userCurrency.coin||0))])],1),e("v-uni-view",{staticClass:"text"},[t._v("金豆")])],1),e("v-uni-view",{staticClass:"item-wrap"},[e("v-uni-view",{staticClass:"count"},[e("v-uni-image",{staticClass:"icon",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERziauZWDgQPHRlOiac7NsMqj5Bbz1VfzicVr9BqhXgVmBmOA2AuE7ZnMbA/0",mode:"aspectFill"}}),e("v-uni-text",{staticClass:"num"},[t._v(t._s(t.userCurrency.flower||0))])],1),e("v-uni-view",{staticClass:"text"},[t._v("鲜花")])],1),e("v-uni-view",{staticClass:"item-wrap"},[e("v-uni-view",{staticClass:"count"},[e("v-uni-image",{staticClass:"icon",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERibO7VvqicUHiaSaSa5xyRcvuiaOibBLgTdh8Mh4csFEWRCbz3VIQw1VKMCQ/0",mode:"aspectFill"}}),e("v-uni-text",{staticClass:"num"},[t._v(t._s(t.userCurrency.stone||0))])],1),e("v-uni-view",{staticClass:"text"},[t._v("钻石")])],1)],1),e("v-uni-view",{staticClass:"row row-2"},[e("v-uni-view",{staticClass:"item-wrap",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.$app.goPage("/pages/task/task")}}},[e("v-uni-image",{staticClass:"icon",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9EqVxh70XuVn1VhJLyPnEbxg8poPGbPbBhXGBtzqccQicFtFiaMzq8O2yB0fVKsIziaJNSFR5c56g8lw/0",mode:"aspectFill"}}),e("v-uni-view",{staticClass:"text"},[t._v("任务")])],1),e("v-uni-view",{staticClass:"item-wrap",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.$app.goPage("/pages/charge/charge")}}},[e("v-uni-image",{staticClass:"icon",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9EqVxh70XuVn1VhJLyPnEbxWT97VwdicBRcWiaic6aw5wqkz9EUKVsyJ21ib3SJB2vhd9oEibcEuV5vUeA/0",mode:"aspectFill"}}),e("v-uni-view",{staticClass:"text"},[t._v("充值")])],1),e("v-uni-view",{staticClass:"item-wrap",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.$app.goPage("/pages/user/badge")}}},[e("v-uni-image",{staticClass:"icon",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9G95njnZp6t7hkcfsoraFhyFkjhRwv6OG00pSKo7DLXZAUibrL8SldBmf7kdCFB1icsWHxc0n34AGrA/0",mode:"aspectFill"}}),e("v-uni-view",{staticClass:"text"},[t._v("荣誉徽章")])],1),e("v-uni-view",{staticClass:"item-wrap",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.$app.goPage("/pages/user/exchange")}}},[e("v-uni-image",{staticClass:"icon",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9EqVxh70XuVn1VhJLyPnEbxaonPdq5wuw0mcjvxg7fiaH9U9f5HX3D4VTVJibsHHf8MB4C2nAIELfog/0",mode:"aspectFill"}}),e("v-uni-view",{staticClass:"text"},[t._v("积分兑换")])],1)],1)],1):t._e(),t.$app.getData("config").version!=t.$app.getVal("VERSION")&&t.$app.getData("config").user_ad?e("v-uni-view",{staticClass:"ad-container flex-set",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.goPage(t.default_user_ad_url)}}},[e("v-uni-image",{attrs:{src:t.$app.getData("config").user_ad.img,mode:"widthFix"}})],1):t._e(),e("v-uni-view",{staticClass:"func-container"},[e("v-uni-view",{staticClass:"item-wrap",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.$app.preImg("https://mmbiz.qpic.cn/mmbiz_jpg/h9gCibVJa7JWh9kicrjHxwae75myNP7juRd5dQdAxjex8dKWSXiakDTyCyCKMpBfAtICOh4sJzXJUCteU1AiaJpOYw/0")}}},[e("v-uni-view",{staticClass:"left-wrap"},[e("v-uni-image",{staticClass:"icon",attrs:{src:"/static/image/user_level/lv"+t.userLevel.level+".png",mode:"aspectFill"}}),e("v-uni-view",{staticClass:"text"},[t._v("粉丝等级"),e("v-uni-view",{staticClass:"tips flex-set"},[t._v("再贡献"),e("v-uni-view",{staticClass:"highlight"},[t._v(t._s((t.userLevel.gap/1e4).toFixed(1)+"万"))]),t._v("人气可升至下一级")],1)],1)],1),e("v-uni-view",{staticClass:"right-wrap iconfont iconjiantou"})],1),e("v-uni-view",{staticClass:"item-wrap",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.$app.goPage("/pages/user/log")}}},[e("v-uni-view",{staticClass:"left-wrap"},[e("v-uni-image",{staticClass:"icon",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9E7MFExyreICyFJqp5RoRBLs3HXkP5LxtpUq4WeLeyViaHXPLfwlkP82KpsV3SPxpFT8wRALtw89Wg/0",mode:"aspectFill"}}),e("v-uni-view",{staticClass:"text"},[t._v("记录明细")])],1),e("v-uni-view",{staticClass:"right-wrap iconfont iconjiantou"})],1),e("v-uni-button",{staticClass:"item-wrap",attrs:{"open-type":"contact","session-from":t.$app.getData("userInfo")}},[e("v-uni-view",{staticClass:"left-wrap"},[e("v-uni-image",{staticClass:"icon",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9E7MFExyreICyFJqp5RoRBLGBtJCqmhlXzuZaoribFll5kYOEewiaxiakgKM8RHibko8U2zWxIMVsdLPA/0",mode:"aspectFill"}}),e("v-uni-view",{staticClass:"text"},[t._v("在线客服")])],1),e("v-uni-view",{staticClass:"right-wrap iconfont iconjiantou"})],1),e("v-uni-view",{staticClass:"item-wrap",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.$app.goPage("/pages/user/setting")}}},[e("v-uni-view",{staticClass:"left-wrap"},[e("v-uni-image",{staticClass:"icon",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9E7MFExyreICyFJqp5RoRBLAfUuB7zP0TVUIdw8AjXVEibArIEoZLSmHfzyqIY3pjT5xOVK97dianRQ/0",mode:"aspectFill"}}),e("v-uni-view",{staticClass:"text"},[t._v("设置")])],1),e("v-uni-view",{staticClass:"right-wrap iconfont iconjiantou"})],1)],1),e("v-uni-view",{staticClass:"bottom-block"})],1)},n=[];e.d(a,"a",function(){return i}),e.d(a,"b",function(){return n})},"2fb1":function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".user-page-container[data-v-69b3b476]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;height:100%}.user-page-container .top-bg[data-v-69b3b476]{background-color:#fbcc3e;height:%?350?%;width:100%;top:0;border-bottom-left-radius:%?10?%;border-bottom-right-radius:%?10?%;position:absolute;z-index:0}.user-page-container .top-container[data-v-69b3b476]{position:relative;z-index:1;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;align-items:center;padding-top:%?40?%}.user-page-container .top-container .left-wrap[data-v-69b3b476]{padding-left:%?40?%}.user-page-container .top-container .left-wrap .avatar-wrap[data-v-69b3b476]{position:relative;overflow:hidden;z-index:1;border-radius:50%;width:%?120?%;height:%?120?%;border:%?2?% solid #fff}.user-page-container .top-container .left-wrap .avatar-wrap .tips[data-v-69b3b476]{position:absolute;width:100%;background-color:rgba(0,0,0,.3);bottom:0;height:%?34?%;color:#fff;font-size:%?18?%;text-align:center;line-height:%?30?%}.user-page-container .top-container .left-wrap .text-wrap[data-v-69b3b476]{padding-left:%?20?%;line-height:1.7}.user-page-container .top-container .right-wrap .btn-wrap[data-v-69b3b476]{font-size:%?24?%;background-color:#b25a1d;border-top-left-radius:%?30?%;border-bottom-left-radius:%?30?%;padding:%?8?% %?16?%;color:#fff}.user-page-container .top-container .right-wrap .btn-wrap .iconfont[data-v-69b3b476]{font-size:%?18?%}.user-page-container .top-float-container[data-v-69b3b476]{position:relative;z-index:1;margin:%?30?% %?20?%;background-color:#fff;box-shadow:0 %?4?% %?32?% hsla(0,0%,40%,.3);border-radius:%?30?%}.user-page-container .top-float-container .row[data-v-69b3b476]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between}.user-page-container .top-float-container .row.row-1[data-v-69b3b476]{border-bottom:%?2?% dashed #dadade}.user-page-container .top-float-container .row.row-1 .item-wrap[data-v-69b3b476]{-webkit-box-flex:1;-webkit-flex:1;flex:1;padding:%?20?% %?40?%}.user-page-container .top-float-container .row.row-1 .item-wrap .count[data-v-69b3b476]{font-size:%?26?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.user-page-container .top-float-container .row.row-1 .item-wrap .count .icon[data-v-69b3b476]{width:%?32?%;height:%?32?%}.user-page-container .top-float-container .row.row-1 .item-wrap .count .num[data-v-69b3b476]{padding-left:%?10?%}.user-page-container .top-float-container .row.row-1 .item-wrap .text[data-v-69b3b476]{font-size:%?24?%;color:#afafaf}.user-page-container .top-float-container .row.row-2[data-v-69b3b476]{padding:%?20?% 0}.user-page-container .top-float-container .row.row-2 .item-wrap[data-v-69b3b476]{-webkit-box-flex:1;-webkit-flex:1;flex:1;text-align:center}.user-page-container .top-float-container .row.row-2 .item-wrap .icon[data-v-69b3b476]{width:%?60?%;height:%?60?%}.user-page-container .ad-container[data-v-69b3b476]{border-top:%?12?% solid #f6f5fa;border-bottom:%?12?% solid #f6f5fa}.user-page-container .func-container[data-v-69b3b476]{position:relative;z-index:1}.user-page-container .func-container .item-wrap[data-v-69b3b476]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;align-items:center;margin:0 %?30?%;padding:%?25?% %?10?%;border-bottom:%?2?% solid #efefef}.user-page-container .func-container .item-wrap .left-wrap[data-v-69b3b476]{font-size:%?30?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.user-page-container .func-container .item-wrap .left-wrap .icon[data-v-69b3b476]{width:%?40?%;height:%?40?%;margin-right:%?10?%}.user-page-container .func-container .item-wrap .left-wrap .text[data-v-69b3b476]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.user-page-container .func-container .item-wrap .left-wrap .text .tips[data-v-69b3b476]{color:#999;margin:0 %?20?%;font-size:%?26?%}.user-page-container .func-container .item-wrap .left-wrap .text .tips .highlight[data-v-69b3b476]{color:#f5c815;font-weight:700}.user-page-container .func-container .item-wrap .right-wrap[data-v-69b3b476]{font-size:%?22?%;color:#999}.user-page-container .bottom-block[data-v-69b3b476]{-webkit-box-flex:1;-webkit-flex:1;flex:1;background-color:#f6f5fa}",""])},"361f":function(t,a,e){"use strict";e.r(a);var i=e("7342"),n=e.n(i);for(var s in i)"default"!==s&&function(t){e.d(a,t,function(){return i[t]})}(s);a["default"]=n.a},4771:function(t,a,e){var i=e("2fb1");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=e("4f06").default;n("69f927e8",i,!0,{sourceMap:!1,shadowMode:!1})},7342:function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var i={data:function(){return{default_user_ad_url:this.$app.getData("config").user_ad.url,requestCount:0,userInfo:{},userCurrency:{},userStar:{},modal:"",rechargeList:[],userLevel:{}}},onLoad:function(){},onShow:function(){var t=this;this.userInfo={avatarurl:this.$app.getData("userInfo")["avatarurl"]||this.$app.AVATAR,nickname:this.$app.getData("userInfo")["nickname"]||this.$app.NICKNAME,id:this.$app.getData("userInfo")["id"]||null},this.userCurrency=this.$app.getData("userCurrency")||{coin:0,stone:0,trumpet:0},this.userStar=this.$app.getData("userStar")||{},this.$app.request(this.$app.API.USER_CURRENCY,{},function(a){t.$app.setData("userCurrency",a.data),t.userCurrency=t.$app.getData("userCurrency")}),this.getUserLevel()},onShareAppMessage:function(t){var a=t.target&&t.target.dataset.share;return this.$app.commonShareAppMessage(a)},methods:{goPage:function(t){var a=this;this.$app.getData("userStar").id?this.$app.goPage(t):uni.showModal({content:"请先加入一个圈子",confirmText:"去加圈子",showCancel:!1,success:function(t){t.confirm&&a.$app.goPage("/pages/group/group")}})},copy:function(){var t=this;uni.setClipboardData({data:"ouridol",success:function(){t.$app.toast("微信号已复制\n请到微信中添加客服为好友")}})},exitGroup:function(){var t=this;this.$app.modal("只有一次机会\n并且会清除你的师徒关系\n是否退出".concat(this.$app.getData("userStar").name,"圈子？"),function(){t.$app.request(t.$app.API.USER_EXIT,{},function(a){t.$app.toast("退出成功"),t.$app.setData("userStar",{},!0),t.userStar={}})})},getUserLevel:function(){var t=this;this.$app.request("user/level",{user_id:this.userInfo.id},function(a){t.userLevel=a.data})},getUserInfo:function(t){var a=this,e=t.detail.userInfo;e&&~this.$app.getData("platform").indexOf("MP")?this.$app.modal("是否同步微信头像和昵称？",function(){a.$app.request(a.$app.API.USER_SAVEINFO,{iv:t.detail.iv,encryptedData:t.detail.encryptedData},function(t){t.data.userInfo.id!=a.$app.getData("userInfo").id&&(a.$app.token=null,a.$app.request("page/app",{},function(t){a.$app.setData("userCurrency",t.data.userCurrency),a.userCurrency=t.data.userCurrency,a.$app.setData("userStar",t.data.userStar),a.userStar=t.data.userStar,a.$app.setData("userExt",t.data.userExt)})),a.$app.setData("userInfo",t.data.userInfo),a.userInfo=t.data.userInfo,a.$app.toast("更新成功")},"POST",!0)}):this.$app.toast("未更新")}}};a.default=i},ac61:function(t,a,e){"use strict";var i=e("4771"),n=e.n(i);n.a},fd1f:function(t,a,e){"use strict";e.r(a);var i=e("094b"),n=e("361f");for(var s in n)"default"!==s&&function(t){e.d(a,t,function(){return n[t]})}(s);e("ac61");var r=e("2877"),o=Object(r["a"])(n["default"],i["a"],i["b"],!1,null,"69b3b476",null);a["default"]=o.exports}}]);