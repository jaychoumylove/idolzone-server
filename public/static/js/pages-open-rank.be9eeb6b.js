(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-open-rank"],{"0232":function(t,e,a){"use strict";a.r(e);var n=a("2f90"),i=a("9106");for(var o in i)"default"!==o&&function(t){a.d(e,t,(function(){return i[t]}))}(o);a("eaca");var r,s=a("f0c5"),c=Object(s["a"])(i["default"],n["b"],n["c"],!1,null,"2a549680",null,!1,n["a"],r);e["default"]=c.exports},"08b8":function(t,e,a){"use strict";var n=a("8e79"),i=a.n(n);i.a},"0ab3":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={data:function(){return{scale:""}},props:{type:{default:"none"}}};e.default=n},"25bb":function(t,e,a){"use strict";a.r(e);var n=a("65ad5"),i=a.n(n);for(var o in n)"default"!==o&&function(t){a.d(e,t,(function(){return n[t]}))}(o);e["default"]=i.a},"2f90":function(t,e,a){"use strict";var n,i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"button flex-set",class:[t.type,t.scale],on:{touchstart:function(e){arguments[0]=e=t.$handleEvent(e),t.scale="scale"},touchend:function(e){arguments[0]=e=t.$handleEvent(e),t.scale=""}}},[t._t("default")],2)},o=[];a.d(e,"b",(function(){return i})),a.d(e,"c",(function(){return o})),a.d(e,"a",(function(){return n}))},"402e":function(t,e,a){var n=a("24fb");e=n(!1),e.push([t.i,".button[data-v-2a549680]{color:#818286;-webkit-transition:.4s ease;transition:.4s ease;border-radius:%?40?%;box-shadow:0 %?2?% %?4?% hsla(0,0%,40%,.3)}.button.unset[data-v-2a549680]{color:unset;border-radius:unset;background:unset;box-shadow:unset}.button.scale[data-v-2a549680]{-webkit-transform:scale(.7);transform:scale(.7)}.button.default[data-v-2a549680]{color:#412b13;background:-webkit-linear-gradient(left top,#fed525,#fed525);background:linear-gradient(to right bottom,#fed525,#fed525)}.button.big[data-v-2a549680]{background:url(https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GnD8mrIKwSEItXUhLNibPUxibmPWGaicd1kWEsAHNRKt8jYQ9ILUZfXfVOib1mRFFbfRXiaMbXZj2nJsQ/0) 50%/100% 100% no-repeat}.button.success[data-v-2a549680]{color:#fff;background:-webkit-linear-gradient(left top,#962de0,#962de0);background:linear-gradient(to right bottom,#962de0,#962de0)}.button.disable[data-v-2a549680]{color:#fff;background:-webkit-linear-gradient(left top,#ccc,#ccc);background:linear-gradient(to right bottom,#ccc,#ccc)}.button.fangde[data-v-2a549680]{color:#412b13;box-shadow:none;border-radius:0;background:url(https://mmbiz.qpic.cn/mmbiz_jpg/w5pLFvdua9FsWnev2aG6T492FpDr5HAaXcd90tJhNX454ZhD1HhuMcEK0T5Suuva8yI7TvicibBTcw8CvEYibzl6w/0) 50%/100% 100% no-repeat}.button.css[data-v-2a549680]{color:#fff;background:-webkit-linear-gradient(left top,#f8648a,red);background:linear-gradient(to right bottom,#f8648a,red);box-shadow:none}.button.green[data-v-2a549680]{color:#fff;background:-webkit-linear-gradient(left top,#4ee059,#199b1a);background:linear-gradient(to right bottom,#4ee059,#199b1a);box-shadow:none}.button.yellow[data-v-2a549680]{color:#000;background:-webkit-linear-gradient(left top,#fdebb2,#fbcc3e);background:linear-gradient(to right bottom,#fdebb2,#fbcc3e);box-shadow:none}.button.custom1[data-v-2a549680]{background:#f74e37;color:#fff}.button.custom2[data-v-2a549680]{border:%?2?% solid #f74e37;color:#f74e37}.button.custom3[data-v-2a549680]{border:%?2?% solid #999;color:#999}.button.none[data-v-2a549680]{box-shadow:none}.button.color[data-v-2a549680]{background-color:#333;border-radius:%?60?%}.button.style2[data-v-2a549680]{background:-webkit-linear-gradient(top,#f7c566,#fe9a3c);background:linear-gradient(180deg,#f7c566,#fe9a3c);border-radius:%?10?%;color:#fff}",""]),t.exports=e},4295:function(t,e,a){"use strict";var n,i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"rank-container"},[t.$app.getData("userStar").captain?a("v-uni-view",{staticClass:"fixed-btn"},[a("btnComponent",{attrs:{type:"css"}},[a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"200upx",height:"65upx"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.$app.goPage("/pages/open/upload")}}},[t._v("我要上传")])],1)],1):t._e(),a("v-uni-view",{staticClass:"fixed-btn-desc"},[a("btnComponent",{staticClass:"right-btn",attrs:{type:"yellow"}},[a("v-uni-view",{staticClass:"desc",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.openDesc.apply(void 0,arguments)}}},[a("v-uni-text",{staticClass:"iconfont iconinfo"}),a("v-uni-view",[t._v(t._s(t.$app.getData("config").open.content[t.type].help.label))])],1)],1)],1),a("v-uni-view",{staticClass:"banner",style:{background:"url("+t.banner+") no-repeat center center","background-size":"cover"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.openDesc.apply(void 0,arguments)}}},[t.left_time.full>=0?a("v-uni-view",{staticClass:"small",staticStyle:{"font-size":"24upx"}},[t._v("距离结束还剩："),t.left_time.d?[a("v-uni-text",{staticClass:"text"},[t._v(t._s(t.left_time.d))]),t._v("天")]:t._e(),a("v-uni-text",{staticClass:"text"},[t._v(t._s(t.left_time.h))]),t._v("时"),a("v-uni-text",{staticClass:"text"},[t._v(t._s(t.left_time.i))]),t._v("分"),a("v-uni-text",{staticClass:"text"},[t._v(t._s(t.left_time.s))]),t._v("秒")],2):a("v-uni-view",{staticClass:"small",staticStyle:{"font-size":"24upx"}},[t._v("活动已截止")])],1),a("v-uni-view",{staticClass:"scroll-wrap"},[a("v-uni-view",{staticClass:"item",class:{active:"rank"==t.rankType},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.setRankType("rank")}}},[t._v("人气排名")]),a("v-uni-view",{staticClass:"item",class:{active:"star"==t.rankType},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.setRankType("star")}}},[t._v("只看"+t._s(t.$app.getData("userStar").name))]),t.$app.getData("userStar").captain?a("v-uni-view",{staticClass:"item",class:{active:"my"==t.rankType},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.setRankType("my")}}},[t._v("我的上传")]):t._e()],1),a("v-uni-view",{staticClass:"list-container"},[t._l(t.list,(function(e,n){return a("v-uni-view",{key:n,staticClass:"item"},[a("v-uni-image",{staticClass:"img",attrs:{"lazy-load":"true",src:e.img_url,mode:"aspectFill"},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.goDetail(e)}}}),a("v-uni-view",{staticClass:"content"},[a("v-uni-view",{staticClass:"content-container"},[a("v-uni-view",{staticClass:"star-name mb2",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.goDetail(e)}}},[t._v(t._s(e.star.name))]),a("v-uni-view",{staticClass:"open-hot mb2 text-overflow",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.goDetail(e)}}},[a("v-uni-view",[t._v("获得"),a("v-uni-text",{staticClass:"disinl pdlf10",staticStyle:{color:"red"}},[t._v(t._s(t.$app.formatNumber(e.hot)))])],1),a("v-uni-image",{staticClass:"img4",attrs:{"lazy-load":"true",src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERabwYgrRn5cjV3uoOa8BonlDPGMn7icL9icvz43XsbexzcqkCcrTcdZqw/0",mode:"widthFix"}})],1),a("v-uni-view",{staticClass:"label mb2",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.goDetail(e)}}},[a("v-uni-view",[t._v("最佳助攻")]),a("v-uni-view",{staticClass:"go-detail"},[a("v-uni-text",{staticClass:"iconfont iconjiantou"})],1)],1),a("v-uni-view",{staticClass:"assist-list",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.goDetail(e)}}},[t._l(e.open_rank,(function(e,n){return n<3?a("v-uni-view",{key:n,staticClass:"assist-item mb2"},[a("v-uni-view",{staticClass:"avatar-wrap"},[n<1?a("v-uni-image",{staticClass:"bg",attrs:{"lazy-load":"true",src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GCJoNu16zcgxj3gUbIRdCyJvaLiaX672IMaCuJuficOtzP8dB7Wr3lNv7ruRF0dSLPaovw9KMBSjzw/0",mode:""}}):t._e(),1==n?a("v-uni-image",{staticClass:"bg",attrs:{"lazy-load":"true",src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GCJoNu16zcgxj3gUbIRdCyYP1h97Ciaiayibsenjv84IOZTueq4kx0qqcu3BGibXIWPKJcoRiaqjFVmew/0",mode:""}}):t._e(),2==n?a("v-uni-image",{staticClass:"bg",attrs:{"lazy-load":"true",src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GCJoNu16zcgxj3gUbIRdCyiaSzL249agiaZudkwtFeg93PBjokvM0euYDiaCJ29ROibQOzMAJFMg0iczw/0",mode:""}}):t._e(),a("v-uni-image",{staticClass:"avatar position-set",attrs:{"lazy-load":"true",src:e.user_info.avatarurl,mode:"aspectFill"}})],1),a("v-uni-view",{staticClass:"assist-info"},[a("v-uni-view",{staticClass:"user-name"},[t._v(t._s(e.user_info.nickname))]),a("v-uni-view",{staticClass:"send-hot"},[a("v-uni-view",[t._v("贡献"),a("v-uni-text",{staticClass:"disinl pdlf10",staticStyle:{color:"red"}},[t._v(t._s(t.$app.formatNumber(e.count)))])],1),a("v-uni-image",{staticClass:"img4",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERabwYgrRn5cjV3uoOa8BonlDPGMn7icL9icvz43XsbexzcqkCcrTcdZqw/0",mode:"widthFix"}})],1)],1)],1):t._e()})),e.open_rank.length?t._e():a("v-uni-view",{staticClass:"mb2 flex-set",staticStyle:{"text-align":"center"}},[a("v-uni-text",{staticClass:"iconfont iconempty"}),t._v("暂无贡献")],1)],2),t.$app.getData("userStar").id==e.star_id?a("v-uni-view",{staticClass:"action"},[a("btnComponent",{attrs:{type:"css"}},[a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"50upx"},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.openSend(e)}}},[t._v("打榜助力")])],1),a("btnComponent",{staticClass:"right-btn",attrs:{type:"yellow"}},[a("v-uni-button",{staticClass:"btn",attrs:{"open-type":"share","data-share":"109","data-otherparam":e.id,"data-image":e.img_url}},[a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"50upx"}},[a("v-uni-text",{staticClass:"iconfont iconshare"}),t._v("分享拉票")],1)],1)],1)],1):t._e()],1)],1),a("v-uni-view",{staticClass:"rank-cover"},[a("v-uni-view",{staticClass:"rank-pos"},[a("v-uni-image",{staticClass:"img",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GCJoNu16zcgxj3gUbIRdCy9fyul1YdnQEAUBtOGI3icphl5GDgw4xic2IE1L0UicpBkOJSLMM0Sym8Q/0",mode:"aspectFill"},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.goDetail(e)}}}),e.rank>=99?[a("v-uni-view",{staticClass:"rank position-set"},[t._v("99")]),a("v-uni-view",{staticClass:"rank",staticStyle:{position:"absolute",right:"50upx",top:"30upx","font-size":"30upx","font-weight":"500"}},[t._v("+")])]:a("v-uni-view",{staticClass:"rank position-set"},[t._v(t._s(e.rank+1))])],2)],1),a("v-uni-view",{staticClass:"uploader-cover"},[a("v-uni-view",{staticClass:"uploader-pos"},[a("v-uni-image",{staticClass:"img",attrs:{src:e.uploader.avatarurl,mode:"aspectFill"}}),a("v-uni-view",{staticClass:"pdlf10 text-overflow",staticStyle:{"max-width":"140upx",color:"gold"}},[t._v(t._s(e.uploader.nickname))]),a("v-uni-view",[t._v("上传")])],1)],1),e.user_id==t.$app.getData("userInfo").id?a("v-uni-view",{staticClass:"remove-cover flex-set",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.remove(e)}}},[a("v-uni-view",{staticClass:"remove-pos"},[a("v-uni-image",{attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9HEap8qicrNnBS27libbHBCjX6uIiaKudB7L0oWwphXjZeu9xcIz6eYNCbIB1Xs8X9ibwNOhCr6Mg1QjQ/0",mode:"aspectFit"}})],1)],1):t._e()],1)})),t.list.length?t._e():a("v-uni-view",{staticClass:"flex-set",staticStyle:{margin:"30upx auto"}},[t.loading?a("v-uni-view",{staticClass:"flex-set"},[t._v("正在查询...")]):a("v-uni-view",{staticClass:"flex-set"},[t._v("暂无数据")])],1)],2),"send"==t.modal?a("modalComponent",{attrs:{type:"send",title:"pick"},on:{closeModal:function(e){arguments[0]=e=t.$handleEvent(e),t.modal=""}}},[a("v-uni-view",{staticClass:"send-modal-container"},[a("v-uni-view",{staticClass:"switch-wrap"},[a("v-uni-switch",{attrs:{checked:!t.danmakuClosed},on:{change:function(e){arguments[0]=e=t.$handleEvent(e),t.danmakuSwitch.apply(void 0,arguments)}}}),t._v("弹幕"),t.extHot.percent&&t.extHot.percent>0?[0==t.current?a("v-uni-view",{staticClass:"absolute-dog4",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.goExtraHotPage.apply(void 0,arguments)}}},[t._v("冲榜后额外赠送"),a("v-uni-text",{staticStyle:{color:"#fb8100"}},[t._v(t._s(t.$app.formatFloatNum(100*t.extHot.percent,2))+"%")]),a("v-uni-text",[t._v("金豆"),a("v-uni-text",{staticClass:"iconfont iconicon-test1"})],1)],1):t._e(),1==t.current?a("v-uni-view",{staticClass:"absolute-dog4",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.goExtraHotPage.apply(void 0,arguments)}}},[t._v("冲榜后额外赠送"),a("v-uni-text",{staticStyle:{color:"#FF0019"}},[t._v(t._s(t.$app.formatFloatNum(100*t.extHot.percent,2))+"%")]),a("v-uni-text",[t._v("鲜花"),a("v-uni-text",{staticClass:"iconfont iconicon-test1"})],1)],1):t._e(),a("v-uni-text",{staticClass:"absolute-go-dog"},[t._v("1"+t._s(0==t.current?"金豆":"鲜花")+" = 1人气")])]:a("v-uni-text",{staticClass:"absolute-go"},[t._v("1"+t._s(0==t.current?"金豆":"鲜花")+" = 1人气")])],2),t.$app.getData("config").version!=t.$app.getVal("VERSION")?a("v-uni-view",{staticClass:"swiper-change flex-set",class:{mt6:t.extHot.percent&&t.extHot.percent>0}},[a("v-uni-view",{staticClass:"item",class:{select:0==t.current},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.current=0,t.sendCount=""}}},[t._v("送金豆")]),a("v-uni-view",{staticClass:"item",class:{select:1==t.current},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.current=1,t.sendCount=""}}},[t._v("送鲜花")]),"1"==t.$app.getData("config").old_coin_open&&t.userCurrency.old_coin>0?a("v-uni-view",{staticClass:"item",class:{select:2==t.current},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.current=2,t.sendCount=""}}},[t._v("送旧豆")]):t._e()],1):t._e(),a("v-uni-view",{staticClass:"swiper-item"},[a("v-uni-view",{staticClass:"wrap"},[a("v-uni-view",{staticClass:"btn-wrapper"},[t._l(t.send_num_list,(function(e,n){return a("v-uni-view",{key:n,staticClass:"btn flex-set",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.sendHot(e)}}},[0==t.current?a("v-uni-image",{attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FctOFR9uh4qenFtU5NmMB5uWEQk2MTaRfxdveGhfFhS1G5dUIkwlT5fosfMaW0c9aQKy3mH3XAew/0",mode:"widthFix"}}):t._e(),1==t.current?a("v-uni-image",{attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERziauZWDgQPHRlOiac7NsMqj5Bbz1VfzicVr9BqhXgVmBmOA2AuE7ZnMbA/0",mode:"widthFix"}}):t._e(),2==t.current?a("v-uni-image",{attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/h9gCibVJa7JXlbbzMr1KRN6DJJyCKicqpD86VGeUeoibFPryXF3iaSF1bJba11sBtrCg6SNpEAxxPCyB2ictoq4Iia2Q/0",mode:"widthFix"}}):t._e(),t._v("+"+t._s(e))],1)})),a("v-uni-view",{staticClass:"btn flex-set self-input"},[a("v-uni-input",{attrs:{value:t.sendCount,type:"number",placeholder:"自定义数额"},on:{input:function(e){arguments[0]=e=t.$handleEvent(e),t.setSendCount.apply(void 0,arguments)}}})],1),a("v-uni-view",{staticClass:"btn flex-set pick",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.sendHot()}}},[t._v("冲榜")])],2),a("v-uni-view",{staticClass:"bottom-wrapper"},[0==t.current?a("v-uni-view",{staticClass:"text left flex-set"},[t._v("我的金豆："+t._s(t.userCurrency["coin"]))]):t._e(),1==t.current?a("v-uni-view",{staticClass:"text left flex-set"},[t._v("我的鲜花："+t._s(t.userCurrency["flower"]))]):t._e(),2==t.current?a("v-uni-view",{staticClass:"text left flex-set"},[t._v("我的旧豆："+t._s(t.userCurrency["old_coin"]))]):t._e(),t.$app.getData("config").version!=t.$app.getData("VERSION")||"MP-WEIXIN"!=t.$app.getData("platform")?[0==t.$app.chargeSwitch()?a("v-uni-view",{staticClass:"right",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.$app.goPage("/pages/charge/charge")}}},[t._v("充值"),a("v-uni-text",{staticClass:"iconfont iconjiantou"})],1):2==t.$app.chargeSwitch()&&0==t.current?a("v-uni-button",{attrs:{"open-type":"contact"}},[a("v-uni-view",{staticClass:"right reply"},[t._v('回复"1"获取更多金豆')])],1):2==t.$app.chargeSwitch()&&1==t.current?a("v-uni-button",{attrs:{"open-type":"contact"}},[a("v-uni-view",{staticClass:"right reply"},[t._v('回复"1"获取更多鲜花')])],1):t._e()]:t._e()],2)],1)],1)],1)],1):t._e()],1)},o=[];a.d(e,"b",(function(){return i})),a.d(e,"c",(function(){return o})),a.d(e,"a",(function(){return n}))},"65ad5":function(t,e,a){"use strict";var n=a("ee27");a("99af"),a("c740"),a("a9e3"),a("e25e"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=n(a("0232")),o=n(a("bbc3")),r={components:{btnComponent:i.default,modalComponent:o.default},data:function(){return{modal:"",list:[],danmakuClosed:!1,type:"",page:1,size:10,sendId:"",end:!1,current:0,banner:"",extHot:{},loading:!1,userCurrency:{},send_num_list:[99,520,1314,9999,66666,"全送"],sendCount:"",my:void 0,rankType:"rank",rankTypeMap:{rank:{},star:{star_id:this.$app}},left_time:{full:0,d:0,h:0,i:0,s:0},left_timer:void 0}},onShow:function(){this.setCurrentBanner(),this.danmakuClosed=this.$app.getData("danmakuClosed"),this.userCurrency=this.$app.getData("userCurrency"),this.getExtraSendHot(),this.refresh()},onShareAppMessage:function(t){var e=t.target&&t.target.dataset.share,a=t.target&&t.target.dataset.otherparam,n={image:t.target.dataset.image};return this.$app.commonShareAppMessage(e,"id="+a,n)},onReachBottom:function(){this.end||(this.page++,this.getList())},onUnload:function(){this.cleanTimer()},methods:{setCurrentBanner:function(){this.type=this.$app.getData("config").open.current;var t=this.$app.getData("config").open.content[this.type].banner,e=Math.round(Date.now()/1e3),a=t.findIndex((function(t){return t.vote_end>e}));if(a<0)return this.cleanTimer(),this.left_time={full:-1},void uni.showToast({mask:!0,title:"活动已截止"});this.setTimer(t[a].vote_end),this.banner=t[a].img_url},setTimer:function(t){var e=this;this.left_timer=setInterval((function(){var a=Math.round(Date.now()/1e3),n=t-a;if(n<=0)e.setCurrentBanner();else{var i=e.$app.timeGethms(n);e.left_time={full:t,d:i.day,h:i.hour,i:i.min,s:i.sec}}}),1e3)},cleanTimer:function(){clearInterval(this.left_timer),this.left_timer=void 0},openDesc:function(){var t=this.$app.getData("config").open.content[this.type].help.article;this.$app.goPage("/pages/notice/notice?id="+t)},goDetail:function(t){this.$app.goPage("/pages/open/detail?id="+t.id)},danmakuSwitch:function(t){this.danmakuClosed=!t.detail.value,this.$app.setData("danmakuClosed",!t.detail.value)},getExtraSendHot:function(){var t=this;this.$app.request(this.$app.API.STAR_EXTRA_SEND_HOT,{},(function(e){t.extHot=e.data}))},goExtraHotPage:function(){uni.navigateTo({url:"/pages/active/weal"})},setSendCount:function(t){this.sendCount=t.target.value},setRankType:function(t){this.rankType!=t&&(this.rankType=t,this.list=[],this.loading=!0,this.refresh())},refresh:function(){this.page=1,this.end=!1,this.getList()},goPage:function(t){this.$app.goPage(t)},openSend:function(t){if(this.$app.getData("userStar").id!=t.star_id)return this.$app.toast("不能给其他的爱豆送花哦");this.modal="send",this.sendId=t.id},openShare:function(t,e){if(this.$app.getData("userStar").id!=t.star_id)return this.$app.toast("不能给其他的爱豆分享哦");this.drawCanvas(t,e)},cleanSend:function(){this.modal="",this.sendId=0,this.sendCount=""},sendHot:function(t){var e=this;if("全送"==t){var a=["coin","flower","old_coin"];this.sendCount=this.userCurrency[a[this.current]]}else{if(t&&(this.sendCount=parseInt(t)),!this.sendCount)return this.$app.toast("数额不正确");var n=["coin","flower","old_coin"],i=this.userCurrency[n[this.current]];if(this.sendCount>i){var o=["金豆","鲜花","旧豆"];return this.$app.toast("".concat(o[this.current],"不足"))}}var r={id:this.sendId,hot:this.sendCount,type:this.type,current:this.current,danmaku:Number(!this.danmakuClosed)};uni.showLoading({title:"助力中...",mask:!0}),this.$app.request(this.$app.API.OPEN_SEND_HOT,r,(function(t){e.cleanSend(),e.$app.toast("助力成功","success"),e.$app.request(e.$app.API.USER_CURRENCY,{},(function(t){e.$app.setData("userCurrency",t.data),e.userCurrency=t.data})),e.refresh()}),"POST",!0)},getList:function(){var t=this,e=this.end,a=this.page,n=this.type,i=this.size,o=this.rankType;if(e)this.loading=!1;else{var r={page:a,size:i,type:n,rank:o};this.$app.request("open/select",r,(function(e){t.list=t.page>1?t.list.concat(e.data.list):e.data.list,e.data.list.length<i&&(t.end=!0),t.loading=!1}))}},getMyOpen:function(){var t=this;this.$app.request(this.$app.API.OPEN_INFO,{self:1},(function(e){e.data.hasOwnProperty("info")&&(t.my=e.data.info)}))},remove:function(t){var e=this;uni.showModal({title:"提示",content:"你确认删除这张图片么",success:function(a){a.cancel||a.confirm&&(uni.showLoading({title:"正在删除...",mask:!0}),e.$app.request(e.$app.API.OPEN_REMOVE,{open_id:t.id},(function(t){e.$app.toast("删除成功"),e.refresh()})))}})}}};e.default=r},"708c":function(t,e,a){"use strict";var n,i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"container",class:{show:t.show},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closeModal.apply(void 0,arguments)}}},[a("v-uni-view",{staticClass:"modal-container",class:[t.type],on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e)}}},[a("v-uni-view",{staticClass:"close-btn flex-set iconfont iconclose",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closeModal.apply(void 0,arguments)}}}),a("v-uni-view",{staticClass:"content"},[t._t("default")],2)],1)],1)},o=[];a.d(e,"b",(function(){return i})),a.d(e,"c",(function(){return o})),a.d(e,"a",(function(){return n}))},"766c":function(t,e,a){"use strict";var n=a("ee27");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=n(a("0232")),o={components:{btnComponent:i.default},data:function(){return{show:!1}},props:{title:{default:"提示"},headimg:{default:"/static/image/icon/db.png"},type:{default:"default"}},created:function(){this.show=!0},methods:{closeModal:function(){var t=this;this.show=!1,setTimeout((function(){t.$emit("closeModal")}),300)}}};e.default=o},"89d3":function(t,e,a){"use strict";var n=a("8fc0"),i=a.n(n);i.a},"8e79":function(t,e,a){var n=a("8fa6");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=a("4f06").default;i("13f9bea9",n,!0,{sourceMap:!1,shadowMode:!1})},"8fa6":function(t,e,a){var n=a("24fb");e=n(!1),e.push([t.i,".reply[data-v-4d8f97fa]{color:#fc3131;border-bottom:%?2?% solid #ffd4d4;font-size:%?23?%}.disinl[data-v-4d8f97fa]{display:inline-block}.mt6[data-v-4d8f97fa]{margin-top:%?60?%!important}.pdlf10[data-v-4d8f97fa]{padding:0 %?10?%}.img4[data-v-4d8f97fa]{width:%?40?%;height:%?40?%}.rank-container[data-v-4d8f97fa]{padding:%?10?% %?20?%}.rank-container .scroll-wrap[data-v-4d8f97fa]{margin:%?20?%;display:-webkit-box;display:-webkit-flex;display:flex;overflow-y:auto}.rank-container .scroll-wrap .item[data-v-4d8f97fa]{color:#9a9a9a;border:%?2?% solid #9a9a9a;border-radius:%?40?%;padding:%?5?% %?20?%;white-space:nowrap;margin-right:%?20?%}.rank-container .scroll-wrap .item.active[data-v-4d8f97fa]{border-color:#ff89ad;color:#ff89ad}.rank-container .banner[data-v-4d8f97fa]{width:%?690?%;height:%?250?%;margin:%?10?% auto;border-radius:%?20?%;position:relative}.rank-container .banner .small[data-v-4d8f97fa]{position:absolute;bottom:%?20?%;left:%?20?%;color:#fff;background-color:rgba(0,0,0,.8);border-radius:%?30?%;padding:%?5?% %?20?%}.rank-container .banner .small .text[data-v-4d8f97fa]{padding:0 %?10?%;color:#ff0}.rank-container .fixed-btn[data-v-4d8f97fa]{position:fixed;bottom:13%;left:35%;z-index:2;width:30%}.rank-container .fixed-btn-my[data-v-4d8f97fa]{position:fixed;bottom:%?40?%;left:%?40?%;z-index:2}.rank-container .fixed-btn-desc[data-v-4d8f97fa]{position:fixed;top:%?40?%;right:%?10?%;z-index:2;width:%?40?%;height:auto;line-height:%?28?%;text-align:center;font-size:%?24?%}.rank-container .fixed-btn-desc .desc[data-v-4d8f97fa]{height:%?150?%;padding:%?10?%;width:%?40?%}.rank-container .title-container[data-v-4d8f97fa]{font-size:%?32?%;font-weight:700}.rank-container .list-container[data-v-4d8f97fa]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;-webkit-flex-wrap:wrap;flex-wrap:wrap}.rank-container .list-container .item[data-v-4d8f97fa]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;position:relative;-webkit-box-align:center;-webkit-align-items:center;align-items:center;text-align:center;border-radius:5%;overflow:hidden;margin:%?20?%;background-color:#fff;box-shadow:0 %?2?% %?8?% rgba(0,0,0,.3);width:100%}.rank-container .list-container .item .img[data-v-4d8f97fa]{width:%?330?%;height:%?580?%}.rank-container .list-container .item .rank-cover[data-v-4d8f97fa]{position:absolute;left:%?-45?%;top:%?-30?%}.rank-container .list-container .item .rank-cover .rank-pos .img[data-v-4d8f97fa]{width:%?180?%;height:%?180?%}.rank-container .list-container .item .rank-cover .rank-pos .rank[data-v-4d8f97fa]{top:40%;font-size:%?45?%;font-weight:700;color:#fff}.rank-container .list-container .item .uploader-cover[data-v-4d8f97fa]{position:absolute;bottom:%?20?%;left:%?20?%}.rank-container .list-container .item .uploader-cover .uploader-pos[data-v-4d8f97fa]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;-webkit-box-pack:start;-webkit-justify-content:flex-start;justify-content:flex-start;line-height:%?40?%;height:%?40?%;color:#fff;background-color:rgba(226,91,69,.6);border-radius:%?20?%;padding-right:%?20?%}.rank-container .list-container .item .uploader-cover .uploader-pos uni-image[data-v-4d8f97fa]{width:%?40?%;height:%?40?%;border-radius:50%;margin-right:%?10?%}.rank-container .list-container .item .remove-cover[data-v-4d8f97fa]{position:absolute;box-sizing:border-box;top:%?20?%;right:%?20?%;width:%?40?%;height:%?40?%}.rank-container .list-container .item .remove-cover .remove-pos[data-v-4d8f97fa]{height:%?40?%}.rank-container .list-container .item .remove-cover .remove-pos uni-image[data-v-4d8f97fa]{width:%?40?%}.rank-container .list-container .item .content[data-v-4d8f97fa]{-webkit-box-flex:1;-webkit-flex:1;flex:1;height:%?580?%;font-size:%?24?%}.rank-container .list-container .item .content .content-container[data-v-4d8f97fa]{margin:%?30?% %?20?%}.rank-container .list-container .item .content .content-container .mb2[data-v-4d8f97fa]{margin-bottom:%?10?%}.rank-container .list-container .item .content .content-container .star-name[data-v-4d8f97fa]{font-size:%?36?%;font-weight:700}.rank-container .list-container .item .content .content-container .open-hot[data-v-4d8f97fa]{height:%?40?%;line-height:%?40?%;color:#333;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row}.rank-container .list-container .item .content .content-container .label[data-v-4d8f97fa]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:start;-webkit-justify-content:flex-start;justify-content:flex-start}.rank-container .list-container .item .content .content-container .label .go-detail[data-v-4d8f97fa]{margin-left:auto;color:#fc7025}.rank-container .list-container .item .content .content-container .assist-list[data-v-4d8f97fa]{height:%?300?%}.rank-container .list-container .item .content .content-container .assist-list .assist-item[data-v-4d8f97fa]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:start;-webkit-justify-content:flex-start;justify-content:flex-start;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row}.rank-container .list-container .item .content .content-container .assist-list .assist-item .avatar-wrap[data-v-4d8f97fa]{position:relative}.rank-container .list-container .item .content .content-container .assist-list .assist-item .avatar-wrap .bg[data-v-4d8f97fa]{width:%?90?%;height:%?90?%}.rank-container .list-container .item .content .content-container .assist-list .assist-item .avatar-wrap .avatar[data-v-4d8f97fa]{position:absolute;width:%?60?%;height:%?60?%;border-radius:50%}.rank-container .list-container .item .content .content-container .assist-list .assist-item .assist-info[data-v-4d8f97fa]{-webkit-box-flex:1;-webkit-flex:1;flex:1;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center}.rank-container .list-container .item .content .content-container .assist-list .assist-item .assist-info uni-view[data-v-4d8f97fa]{text-align:left}.rank-container .list-container .item .content .content-container .assist-list .assist-item .assist-info .send-hot[data-v-4d8f97fa]{font-size:%?20?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;-webkit-box-pack:start;-webkit-justify-content:flex-start;justify-content:flex-start;line-height:%?40?%}.rank-container .list-container .item .content .content-container .action[data-v-4d8f97fa]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:start;-webkit-justify-content:flex-start;justify-content:flex-start;margin-top:%?20?%;font-size:%?22?%}.rank-container .list-container .item .content .content-container .action .right-btn[data-v-4d8f97fa]{margin-left:auto}.rank-container .list-container .item .content .content-container .action .right-btn uni-button[data-v-4d8f97fa]{font-size:%?22?%}.rank-container .send-modal-container[data-v-4d8f97fa]{width:100%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;align-items:center;position:relative}.rank-container .send-modal-container .switch-wrap[data-v-4d8f97fa]{position:absolute;top:%?-60?%;left:%?20?%;font-size:%?34?%;-webkit-transform:scale(.7);transform:scale(.7)}.rank-container .send-modal-container .absolute-dog4[data-v-4d8f97fa]{position:absolute;left:100%;top:0;width:440%;font-size:%?40?%;font-weight:500;text-align:center}.rank-container .send-modal-container .absolute-go[data-v-4d8f97fa]{position:absolute;left:%?400?%;width:%?220?%}.rank-container .send-modal-container .absolute-go-dog[data-v-4d8f97fa]{position:absolute;left:%?0?%;top:%?80?%;width:%?220?%}.rank-container .send-modal-container .explain-wrapper[data-v-4d8f97fa]{font-size:%?24?%}.rank-container .send-modal-container .swiper-change[data-v-4d8f97fa]{margin:%?30?%;border-radius:%?30?%;overflow:hidden;box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3)}.rank-container .send-modal-container .swiper-change .item[data-v-4d8f97fa]{width:%?200?%;height:%?50?%;line-height:%?50?%;background-color:#fff;text-align:center}.rank-container .send-modal-container .swiper-change .item.select[data-v-4d8f97fa]{background-color:#ffc918;color:#2d1408}.rank-container .send-modal-container uni-swiper[data-v-4d8f97fa]{width:100%;height:100%}.rank-container .send-modal-container .swiper-item[data-v-4d8f97fa]{-webkit-box-flex:1;-webkit-flex:1;flex:1}.rank-container .send-modal-container .swiper-item .wrap[data-v-4d8f97fa]{position:relative;width:100%}.rank-container .send-modal-container .swiper-item .mt5[data-v-4d8f97fa]{margin-top:%?50?%}.rank-container .send-modal-container .btn-wrapper[data-v-4d8f97fa]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-flex-wrap:wrap;flex-wrap:wrap;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center}.rank-container .send-modal-container .btn-wrapper .btn[data-v-4d8f97fa]{border-radius:%?10?%;margin:%?8?% %?16?%;width:%?190?%;height:%?100?%;color:#333;border:%?2?% solid #333}.rank-container .send-modal-container .btn-wrapper .btn uni-image[data-v-4d8f97fa]{width:%?40?%;height:%?40?%}.rank-container .send-modal-container .btn-wrapper .btn.self-input[data-v-4d8f97fa]{width:%?412?%}.rank-container .send-modal-container .btn-wrapper .btn.self-input uni-input[data-v-4d8f97fa]{border-radius:%?60?%;width:100%;height:%?110?%;text-align:center;line-height:%?110?%}.rank-container .send-modal-container .btn-wrapper .btn.pick[data-v-4d8f97fa]{font-size:%?34?%;font-weight:700;background-color:#f8648a;color:#fff}.rank-container .send-modal-container .bottom-wrapper[data-v-4d8f97fa]{border-top:%?2?% solid #eee;margin:%?20?% %?60?%;padding:%?20?% 0;color:#333;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;align-items:center;width:80%}.rank-container .send-modal-container .bottom-wrapper .btn[data-v-4d8f97fa]{text-decoration:underline;margin:%?10?%}.rank-container .send-modal-container .bottom-wrapper .right[data-v-4d8f97fa]{font-weight:700}.rank-container .send-modal-container .bottom-wrapper .right .iconfont[data-v-4d8f97fa]{font-size:%?24?%}",""]),t.exports=e},"8fc0":function(t,e,a){var n=a("ad92");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=a("4f06").default;i("fa99cc4a",n,!0,{sourceMap:!1,shadowMode:!1})},9106:function(t,e,a){"use strict";a.r(e);var n=a("0ab3"),i=a.n(n);for(var o in n)"default"!==o&&function(t){a.d(e,t,(function(){return n[t]}))}(o);e["default"]=i.a},a407:function(t,e,a){"use strict";a.r(e);var n=a("766c"),i=a.n(n);for(var o in n)"default"!==o&&function(t){a.d(e,t,(function(){return n[t]}))}(o);e["default"]=i.a},ad92:function(t,e,a){var n=a("24fb");e=n(!1),e.push([t.i,".container[data-v-be63789e]{position:fixed;top:0;left:0;width:100%;height:100%;z-index:99;background-color:rgba(0,0,0,.6);-webkit-transition:.2s;transition:.2s;opacity:0}.container .modal-container[data-v-be63789e]{width:100%;height:auto;box-shadow:0 -2px 4px rgba(0,0,0,.5);border-top-left-radius:%?30?%;border-top-right-radius:%?30?%;background-color:#fff;overflow:hidden;position:absolute;bottom:var(--window-bottom);-webkit-transform:translateY(100%);transform:translateY(100%);-webkit-transition:all .2s ease;transition:all .2s ease}.container .modal-container .center-img[data-v-be63789e]{position:absolute;left:50%;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);width:%?120?%;height:%?120?%}.container .modal-container .close-btn[data-v-be63789e]{position:absolute;top:%?10?%;right:%?10?%;width:%?80?%;height:%?80?%;color:#999;font-size:%?45?%;z-index:9}.container .modal-container .content[data-v-be63789e]{width:100%;height:auto;position:relative;padding-top:%?80?%}.container .modal-container.center[data-v-be63789e]{width:%?600?%;left:50%;top:50%;bottom:auto;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);border-bottom-left-radius:%?30?%;border-bottom-right-radius:%?30?%}.container .modal-container.center-top[data-v-be63789e]{top:30%}.container .modal-container.centerNobg[data-v-be63789e]{width:%?600?%;left:50%;top:50%;bottom:auto;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);background-color:initial;box-shadow:none;border:none}.container.show[data-v-be63789e]{opacity:1}.container.show .modal-container[data-v-be63789e]{-webkit-transform:translateY(0);transform:translateY(0)}.container.show .modal-container.center[data-v-be63789e]{-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%)}.container.show .modal-container.centerNobg[data-v-be63789e]{-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%)}",""]),t.exports=e},bbc3:function(t,e,a){"use strict";a.r(e);var n=a("708c"),i=a("a407");for(var o in i)"default"!==o&&function(t){a.d(e,t,(function(){return i[t]}))}(o);a("89d3");var r,s=a("f0c5"),c=Object(s["a"])(i["default"],n["b"],n["c"],!1,null,"be63789e",null,!1,n["a"],r);e["default"]=c.exports},cc7a:function(t,e,a){"use strict";a.r(e);var n=a("4295"),i=a("25bb");for(var o in i)"default"!==o&&function(t){a.d(e,t,(function(){return i[t]}))}(o);a("08b8");var r,s=a("f0c5"),c=Object(s["a"])(i["default"],n["b"],n["c"],!1,null,"4d8f97fa",null,!1,n["a"],r);e["default"]=c.exports},eaca:function(t,e,a){"use strict";var n=a("f303"),i=a.n(n);i.a},f303:function(t,e,a){var n=a("402e");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=a("4f06").default;i("b4fb8d9e",n,!0,{sourceMap:!1,shadowMode:!1})}}]);