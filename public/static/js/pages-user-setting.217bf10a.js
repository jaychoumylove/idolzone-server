(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-setting"],{"0e22":function(t,e,i){"use strict";i.r(e);var n=i("d520"),a=i("111f");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);i("7f12");var r,s=i("f0c5"),c=Object(s["a"])(a["default"],n["b"],n["c"],!1,null,"1d3ce72d",null,!1,n["a"],r);e["default"]=c.exports},"0ef9":function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={props:{title:{type:String,default:"提示"},placeholder:{type:String,default:"请输入内容"},mainColor:{type:String,default:"#e74a39"},defaultValue:{type:String,default:""},inputStyle:{type:String,default:""},isMutipleLine:{type:Boolean,default:!1}},data:function(){return{value:""}},mounted:function(){this.value="true"===this.defaultValue?"":this.defaultValue},methods:{close:function(){this.$emit("closeModal")},confirm:function(){this.$emit("confirm",this.value),this.value=""}}};e.default=n},"111f":function(t,e,i){"use strict";i.r(e);var n=i("0ef9"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=a.a},"1efd":function(t,e,i){"use strict";var n=i("f33e2"),a=i.n(n);a.a},"455f":function(t,e,i){"use strict";i.r(e);var n=i("8b43"),a=i("8ca1");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);i("1efd");var r,s=i("f0c5"),c=Object(s["a"])(a["default"],n["b"],n["c"],!1,null,"e16e5436",null,!1,n["a"],r);e["default"]=c.exports},"7f12":function(t,e,i){"use strict";var n=i("cf91"),a=i.n(n);a.a},"7f53":function(t,e,i){"use strict";var n=i("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=n(i("92c7")),o=n(i("3be5")),r=n(i("7db4")),s=n(i("0e22")),c={components:{badgeComponent:a.default,modalComponent:r.default,btnComponent:o.default,prompt:s.default},data:function(){return{requestCount:0,userInfo:{},userCurrency:{},userStar:{},modal:"",never:!1,neverOnceVal:"",commitOnce:!1,neverAgainVal:"",rechargeList:[],confrimId:""}},onLoad:function(){},onShow:function(){var t=Math.round(Date.now()/1e3),e=this.$app.getData("userExt").exit_group_time;t<e&&(this.never=!0)},onShareAppMessage:function(t){var e=t.target&&t.target.dataset.share;return this.$app.commonShareAppMessage(e)},methods:{nerverOnce:function(){this.modal="neverQuitOnce"},cancelNever:function(){this.modal="",this.neverOnceVal="",this.neverAgainVal=""},setNeverOnce:function(t){this.neverOnceVal=t.target.value},setNeverAgain:function(t){this.neverAgainVal=t.target.value},setConfrimId:function(t){this.confrimId=t.target.value},nerverAgain:function(){this.neverOnceVal==1234*this.$app.getData("userInfo").id?this.commitOnce=!0:this.$app.toast("ID输入不正确")},nerverQuit:function(){var t=this;this.neverAgainVal==1234*this.$app.getData("userInfo").id?(uni.showLoading({mask:!0,title:"请稍后..."}),this.$app.request(this.$app.API.USER_NERVER_QUIT,{},(function(e){t.$app.toast("设置成功","success"),t.modal="",t.never=!0;var i=t.$app.getData("userExt");i.exit_group_time=2147483647,t.$app.setData("userExt",i)}))):this.$app.toast("ID输入不正确")},exitGroup:function(){var t=this;this.confrimId==1234*this.$app.getData("userInfo").id?(uni.showLoading({mask:!0,title:"请稍后..."}),this.$app.request(this.$app.API.USER_EXIT,{},(function(e){t.$app.toast("退出成功","success"),t.modal="",t.$app.setData("userStar",{},!0),t.userStar={}}))):this.$app.toast("ID输入不正确")}}};e.default=c},"8b43":function(t,e,i){"use strict";var n,a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"user-page-container"},[t.$app.getData("userStar").id&&!t.never?i("v-uni-view",{staticClass:"item-wrap",on:{longpress:function(e){arguments[0]=e=t.$handleEvent(e),t.nerverOnce.apply(void 0,arguments)},click:function(e){arguments[0]=e=t.$handleEvent(e),t.modal="exit"}}},[i("v-uni-view",{staticClass:"left-wrap"},[i("v-uni-image",{staticClass:"icon",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/h9gCibVJa7JWwlVcSNe42f7cdITecxbg4vgXqHL191U954COPpyUJZk3bVFibGKvBO6lw9qBP2iaJLsB1U01mLcug/0",mode:"aspectFill"}}),i("v-uni-view",{staticClass:"text"},[t._v("退圈")])],1),i("v-uni-view",{staticClass:"right-wrap iconfont iconjiantou"})],1):t._e(),1==t.$app.getData("userStar").captain?i("v-uni-view",{staticClass:"item-wrap",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.$app.goPage("/pages/user/starinfo")}}},[i("v-uni-view",{staticClass:"left-wrap"},[i("v-uni-image",{staticClass:"icon",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/h9gCibVJa7JWwlVcSNe42f7cdITecxbg47fX2JOF6HY6huxj9aDAzrIUfq5OLOzgCxbNiaurzUNctGlKLRxS3oHw/0",mode:"aspectFill"}}),i("v-uni-view",{staticClass:"text"},[t._v("修改爱豆信息")])],1),i("v-uni-view",{staticClass:"right-wrap iconfont iconjiantou"})],1):t._e(),"exit"==t.modal?i("modalComponent",{attrs:{type:"center",title:"提示"},on:{closeModal:function(e){arguments[0]=e=t.$handleEvent(e),t.modal=""}}},[i("v-uni-view",{staticClass:"confirm-modal-container flex-set"},[i("v-uni-view",{staticClass:"title flex-set"},[t._v("退圈")]),i("v-uni-view",{staticClass:"desc flex-set"},[t._v("退圈后等级、贡献、粉丝团、徽章(圈子相关数据)将清零。再次退圈需要90天之后才能操作")]),i("v-uni-view",{staticClass:"input"},[i("v-uni-input",{attrs:{type:"number",placeholder:"输入你的ID确认退圈"},on:{input:function(e){arguments[0]=e=t.$handleEvent(e),t.setConfrimId.apply(void 0,arguments)}}})],1),i("v-uni-view",{staticClass:"btn"},[i("btnComponent",{staticStyle:{"margin-right":"100upx"},attrs:{type:""}},[i("v-uni-view",{staticClass:"flex-set btn-unlock",staticStyle:{width:"140upx",height:"60upx"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.modal=""}}},[t._v("取消")])],1),i("btnComponent",{attrs:{type:"default"}},[i("v-uni-view",{staticClass:"flex-set btn-unlock",staticStyle:{width:"140upx",height:"60upx"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.exitGroup.apply(void 0,arguments)}}},[t._v("确认")])],1)],1)],1)],1):t._e(),"neverQuitOnce"==t.modal?i("modalComponent",{attrs:{type:"center",title:"提示"},on:{closeModal:function(e){arguments[0]=e=t.$handleEvent(e),t.cancelNever.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"confirm-modal-container flex-set"},[t.commitOnce?t._e():i("v-uni-view",{staticClass:"title flex-set"},[t._v("永不退圈")]),t.commitOnce?i("v-uni-view",{staticClass:"title flex-set"},[t._v("确认永不退圈")]):t._e(),t.commitOnce?t._e():i("v-uni-view",{staticClass:"desc flex-set"},[t._v("操作后,您再也无法退出当前圈子")]),t.commitOnce?i("v-uni-view",{staticClass:"desc flex-set"},[t._v("确认操作后,您再也无法退出当前圈子")]):t._e(),i("v-uni-view",{staticClass:"input"},[t.commitOnce?t._e():i("v-uni-input",{attrs:{type:"number",placeholder:"请输入你的ID"},on:{input:function(e){arguments[0]=e=t.$handleEvent(e),t.setNeverOnce.apply(void 0,arguments)}}}),t.commitOnce?i("v-uni-input",{attrs:{type:"number",placeholder:"再次请输入你的ID"},on:{input:function(e){arguments[0]=e=t.$handleEvent(e),t.setNeverAgain.apply(void 0,arguments)}}}):t._e()],1),i("v-uni-view",{staticClass:"btn"},[i("btnComponent",{staticStyle:{"margin-right":"100upx"},attrs:{type:""}},[i("v-uni-view",{staticClass:"flex-set btn-unlock",staticStyle:{width:"140upx",height:"60upx"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.cancelNever.apply(void 0,arguments)}}},[t._v("取消")])],1),t.commitOnce?t._e():i("btnComponent",{attrs:{type:"default"}},[i("v-uni-view",{staticClass:"flex-set btn-unlock",staticStyle:{width:"140upx",height:"60upx"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.nerverAgain.apply(void 0,arguments)}}},[t._v("确认")])],1),t.commitOnce?i("btnComponent",{attrs:{type:"default"}},[i("v-uni-view",{staticClass:"flex-set btn-unlock",staticStyle:{width:"140upx",height:"60upx"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.nerverQuit.apply(void 0,arguments)}}},[t._v("确认")])],1):t._e()],1)],1)],1):t._e()],1)},o=[];i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}))},"8ca1":function(t,e,i){"use strict";i.r(e);var n=i("7f53"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=a.a},"964a":function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,".user-page-container .item-wrap[data-v-e16e5436]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;align-items:center;margin:0 %?30?%;padding:%?25?% %?10?%;border-bottom:%?2?% solid #efefef}.user-page-container .item-wrap .left-wrap[data-v-e16e5436]{font-size:%?30?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.user-page-container .item-wrap .left-wrap .icon[data-v-e16e5436]{width:%?40?%;height:%?40?%;margin-right:%?10?%}.user-page-container .item-wrap .left-wrap .text[data-v-e16e5436]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.user-page-container .item-wrap .left-wrap .text .tips[data-v-e16e5436]{color:#999;margin:0 %?20?%;font-size:%?26?%}.user-page-container .item-wrap .left-wrap .text .tips .highlight[data-v-e16e5436]{color:#f5c815;font-weight:700}.user-page-container .item-wrap .right-wrap[data-v-e16e5436]{font-size:%?22?%;color:#999}.user-page-container .confirm-modal-container[data-v-e16e5436]{height:100%;padding:%?30?%;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;color:#333;margin-top:%?-40?%}.user-page-container .confirm-modal-container .title[data-v-e16e5436]{margin-bottom:%?40?%;font-size:%?36?%;font-weight:600}.user-page-container .confirm-modal-container .input[data-v-e16e5436]{margin:%?40?% 0}.user-page-container .confirm-modal-container .buttom[data-v-e16e5436]{margin:%?30?%;width:100%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:start;-webkit-justify-content:flex-start;justify-content:flex-start}.user-page-container .confirm-modal-container .buttom .right[data-v-e16e5436]{margin-left:auto;border-bottom:1px solid red}.user-page-container .confirm-modal-container .btn[data-v-e16e5436]{margin:0 auto;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-justify-content:space-around;justify-content:space-around;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row}.user-page-container .confirm-modal-container uni-input[data-v-e16e5436]{margin:%?10?% 0;background-color:#eee;border-radius:%?60?%;height:%?70?%;padding:0 %?20?%;color:#333;text-align:center}",""]),t.exports=e},cf91:function(t,e,i){var n=i("e231");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("f02da894",n,!0,{sourceMap:!1,shadowMode:!1})},d520:function(t,e,i){"use strict";var n,a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"prompt-box",on:{touchmove:function(e){arguments[0]=e=t.$handleEvent(e),(!0).apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"prompt"},[i("v-uni-view",{staticClass:"prompt-top"},[i("v-uni-text",{staticClass:"prompt-title"},[t._v(t._s(t.title))]),t.isMutipleLine?i("v-uni-textarea",{staticClass:"prompt-input",style:t.inputStyle,attrs:{type:"text",placeholder:t.placeholder},model:{value:t.value,callback:function(e){t.value=e},expression:"value"}}):i("v-uni-input",{staticClass:"prompt-input",style:t.inputStyle,attrs:{type:"text",placeholder:t.placeholder},model:{value:t.value,callback:function(e){t.value=e},expression:"value"}})],1),t._t("default"),i("v-uni-view",{staticClass:"prompt-buttons"},[i("v-uni-button",{staticClass:"prompt-cancle",style:"color:"+t.mainColor,on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.close.apply(void 0,arguments)}}},[t._v("取消")]),i("v-uni-button",{staticClass:"prompt-confirm",style:"background:"+t.mainColor,on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.confirm.apply(void 0,arguments)}}},[t._v("确定")])],1)],2)],1)},o=[];i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}))},e231:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,"uni-view[data-v-1d3ce72d],\n  uni-button[data-v-1d3ce72d],\n  uni-input[data-v-1d3ce72d]{box-sizing:border-box}.prompt-box[data-v-1d3ce72d]{position:fixed;left:0;top:0;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;align-items:center;width:100%;height:100vh;background:rgba(0,0,0,.2);-webkit-transition:opacity .2s linear;transition:opacity .2s linear}.prompt[data-v-1d3ce72d]{position:relative;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;align-items:center;width:%?600?%;min-height:%?300?%;background:#fff;border-radius:%?20?%;overflow:hidden}.prompt-top[data-v-1d3ce72d]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center;width:100%;margin-bottom:%?20?%}.prompt-title[data-v-1d3ce72d]{margin:%?20?%;color:#333}.prompt-input[data-v-1d3ce72d]{width:%?520?%;min-height:%?72?%;padding:%?8?% %?16?%;border:%?2?% solid #ddd;border-radius:%?8?%;font-size:%?28?%;text-align:left}.prompt-buttons[data-v-1d3ce72d]{display:-webkit-box;display:-webkit-flex;display:flex;width:100%;box-shadow:0 0 %?2?% %?2?% #eee}.prompt-buttons uni-button[data-v-1d3ce72d]:after{border-radius:0}uni-button[data-v-1d3ce72d]{width:50%;background:#fff;border-radius:0;line-height:%?80?%}.prompt-cancle[data-v-1d3ce72d]{background:#fff}.prompt-confirm[data-v-1d3ce72d]{color:#fff}",""]),t.exports=e},f33e2:function(t,e,i){var n=i("964a");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("63cb89d0",n,!0,{sourceMap:!1,shadowMode:!1})}}]);