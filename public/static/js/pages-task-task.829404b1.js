(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-task-task"],{"0090":function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"container",class:{show:t.show},on:{click:function(e){e=t.$handleEvent(e),t.closeModal(e)}}},[a("v-uni-view",{staticClass:"modal-container",class:[t.type],on:{click:function(e){e.stopPropagation(),e=t.$handleEvent(e)}}},[a("v-uni-view",{staticClass:"close-btn flex-set iconfont iconclose",on:{click:function(e){e=t.$handleEvent(e),t.closeModal(e)}}}),a("v-uni-view",{staticClass:"content"},[t._t("default")],2)],1)],1)},n=[];a.d(e,"a",function(){return i}),a.d(e,"b",function(){return n})},"04cc":function(t,e,a){"use strict";a.r(e);var i=a("eae3"),n=a.n(i);for(var o in i)"default"!==o&&function(t){a.d(e,t,function(){return i[t]})}(o);e["default"]=n.a},"21ce":function(t,e,a){"use strict";a.r(e);var i=a("fa13"),n=a.n(i);for(var o in i)"default"!==o&&function(t){a.d(e,t,function(){return i[t]})}(o);e["default"]=n.a},"371d":function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"container"},[t._l(t.taskList,function(e,i){return 7!=e.id||7==e.id&&t.$app.getData("config").version!=t.$app.getVal("VERSION")?a("v-uni-view",{key:i,staticClass:"item"},[2!=t.current?a("v-uni-view",{staticClass:"left-content"},[a("v-uni-image",{staticClass:"img",attrs:{src:e.icon,mode:""}}),a("v-uni-view",{staticClass:"content "},[a("v-uni-view",{staticClass:"top text-overflow"},[t._v(t._s(e.name))]),e.desc?a("v-uni-view",{staticClass:"bottom"},[t._v(t._s(e.desc))]):e.times?a("v-uni-view",{staticClass:"bottom"},[t._v("("+t._s(e.doneTimes)+"/"+t._s(e.times)+")")]):t._e()],1)],1):a("v-uni-view",{staticClass:"left-content badge-type"},[a("v-uni-image",{staticClass:"img",attrs:{src:e.icon,mode:""}}),a("v-uni-view",{staticClass:"content"},[a("v-uni-view",{staticClass:"top text-overflow"},[t._v(t._s(e.name))]),e.desc?a("v-uni-view",{staticClass:"bottom"},[t._v(t._s(e.desc)+"("+t._s(e.doneTimes)+"/"+t._s(e.count)+")")]):t._e()],1)],1),a("v-uni-view",{staticClass:"right-content"},[a("v-uni-view",{staticClass:"earn"},[e.coin?a("v-uni-view",{staticClass:"right-item"},[a("v-uni-image",{attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FctOFR9uh4qenFtU5NmMB5uWEQk2MTaRfxdveGhfFhS1G5dUIkwlT5fosfMaW0c9aQKy3mH3XAew/0",mode:"widthFix"}}),a("v-uni-view",{staticClass:"add-count"},[t._v("+"+t._s(e.coin))])],1):t._e(),e.stone?a("v-uni-view",{staticClass:"right-item"},[a("v-uni-image",{attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERibO7VvqicUHiaSaSa5xyRcvuiaOibBLgTdh8Mh4csFEWRCbz3VIQw1VKMCQ/0",mode:"widthFix"}}),a("v-uni-view",{staticClass:"add-count"},[t._v("+"+t._s(e.stone))])],1):t._e(),e.flower?a("v-uni-view",{staticClass:"right-item"},[a("v-uni-image",{attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERziauZWDgQPHRlOiac7NsMqj5Bbz1VfzicVr9BqhXgVmBmOA2AuE7ZnMbA/0",mode:"widthFix"}}),a("v-uni-view",{staticClass:"add-count"},[t._v("+"+t._s(e.flower))])],1):t._e(),e.trumpet?a("v-uni-view",{staticClass:"right-item"},[a("v-uni-image",{attrs:{src:"/static/image/user/trumpet-icon.png",mode:"widthFix"}}),a("v-uni-view",{staticClass:"add-count"},[t._v("+"+t._s(e.trumpet))])],1):t._e()],1),2!=t.current?a("v-uni-view",{staticClass:"btn",on:{click:function(a){a=t.$handleEvent(a),t.doTask(e,i)}}},[0==e.status?a("btnComponent",{attrs:{type:"default"}},[7==e.id&&~t.$app.getData("sysInfo").system.indexOf("iOS")&&2==t.$app.getData("config").ios_switch?a("v-uni-button",{staticClass:"btn",attrs:{"open-type":"contact"},on:{click:function(e){e.stopPropagation(),e=t.$handleEvent(e)}}},[a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v(t._s(e.btn_text||"去完成"))])],1):a("v-uni-button",{staticClass:"btn",attrs:{"open-type":e.open_type},on:{click:function(e){e=t.$handleEvent(e),t.$app.buttonHandler()}}},[a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v(t._s(e.btn_text||"去完成"))])],1)],1):t._e(),1==e.status?a("btnComponent",{attrs:{type:"success"}},[a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v("可领取")])],1):t._e(),2==e.status?a("btnComponent",{attrs:{type:"disable"}},[a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v("已完成")])],1):t._e()],1):a("v-uni-view",{staticClass:"btn",on:{click:function(a){a=t.$handleEvent(a),t.useBadge(e,i)}}},[0==e.status?a("btnComponent",{attrs:{type:"default"}},[1==e.type?a("v-uni-button",{staticClass:"btn",attrs:{"open-type":"share"}},[a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v(t._s(e.btn_text||"去完成"))])],1):a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v(t._s(e.btn_text||"去完成"))])],1):t._e(),1==e.status?a("btnComponent",{attrs:{type:"success"}},[a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v("佩戴")])],1):t._e(),2==e.status?a("btnComponent",{attrs:{type:"disable"}},[a("v-uni-view",{staticClass:"flex-set",staticStyle:{width:"130upx",height:"65upx"}},[t._v("卸下")])],1):t._e()],1)],1)],1):t._e()}),"weibo"==t.modal?a("modalComponent",{attrs:{type:"center",title:"提示"},on:{closeModal:function(e){e=t.$handleEvent(e),t.modal=""}}},[a("v-uni-view",{staticClass:"weibo-modal-container flex-set"},[a("v-uni-view",{staticClass:"row-line"},[a("v-uni-view",{staticClass:"left"},[t._v("第一步")]),a("v-uni-view",{staticClass:"right"},[a("v-uni-view",{staticClass:"btn",staticStyle:{"text-decoration":"underline"},on:{click:function(e){e=t.$handleEvent(e),t.$app.copy(this.shareText)}}},[t._v("点击复制微博格式")])],1)],1),a("v-uni-view",{staticClass:"row-line"},[a("v-uni-view",{staticClass:"left"},[t._v("第二步")]),a("v-uni-view",{staticClass:"right"},[a("v-uni-view",{},[t._v("在支持的爱豆微博超话中发布复制的微博格式的帖子，每日需要发布新的帖子哦")]),a("v-uni-image",{attrs:{src:t.$app.getData("config").weibo_demo_img,mode:"widthFix"}})],1)],1),a("v-uni-view",{staticClass:"row-line"},[a("v-uni-view",{staticClass:"left"},[t._v("第三步")]),a("v-uni-view",{staticClass:"right"},[a("v-uni-view",{},[t._v("发布的帖子可以直接复制微博链接，在下方输入框提交，系统判定后即可领取奖励")]),a("v-uni-input",{attrs:{type:"text",placeholder:"帖子链接"},on:{input:function(e){e=t.$handleEvent(e),t.weiboUrl=e.detail.value}}})],1)],1),a("btnComponent",{attrs:{type:"default"}},[a("v-uni-view",{staticClass:"flex-set btn",staticStyle:{width:"160upx",height:"80upx"},on:{click:function(e){e=t.$handleEvent(e),t.weiboCommit(0)}}},[t._v("提交")])],1)],1)],1):t._e(),"weibo_zhuanfa"==t.modal?a("modalComponent",{attrs:{type:"center",title:"提示"},on:{closeModal:function(e){e=t.$handleEvent(e),t.modal=""}}},[a("v-uni-view",{staticClass:"weibo-modal-container flex-set"},[a("v-uni-view",{staticClass:"row-line"},[a("v-uni-view",{staticClass:"left"},[t._v("第一步")]),a("v-uni-view",{staticClass:"right"},[a("v-uni-view",{},[t._v("进入"+t._s(t.weibo_zhuanfa.host)+"主页查看"+t._s(t.weibo_zhuanfa.text))]),a("v-uni-image",{staticClass:"trans",attrs:{src:t.weibo_zhuanfa.img,mode:"widthFix"}})],1)],1),a("v-uni-view",{staticClass:"row-line"},[a("v-uni-view",{staticClass:"left"},[t._v("第二步")]),a("v-uni-view",{staticClass:"right"},[a("v-uni-view",{},[t._v("发布的帖子可以直接复制微博链接，在下方输入框提交，系统判定后即可领取奖励")]),a("v-uni-input",{attrs:{type:"text",placeholder:"帖子链接"},on:{input:function(e){e=t.$handleEvent(e),t.weiboUrl=e.detail.value}}})],1)],1),a("btnComponent",{attrs:{type:"default"}},[a("v-uni-view",{staticClass:"flex-set btn",staticStyle:{width:"160upx",height:"80upx"},on:{click:function(e){e=t.$handleEvent(e),t.weiboCommit(1)}}},[t._v("提交")])],1)],1)],1):t._e()],2)},n=[];a.d(e,"a",function(){return i}),a.d(e,"b",function(){return n})},"3d2c":function(t,e,a){var i=a("3e90");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=a("4f06").default;n("1aca3dfe",i,!0,{sourceMap:!1,shadowMode:!1})},"3d8d":function(t,e,a){var i=a("6c04");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=a("4f06").default;n("6355686f",i,!0,{sourceMap:!1,shadowMode:!1})},"3e90":function(t,e,a){e=t.exports=a("2350")(!1),e.push([t.i,".container .swiper-change[data-v-26afef51]{margin:%?30?%;border-radius:%?30?%;overflow:hidden;-webkit-box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3);box-shadow:0 %?2?% %?4?% rgba(0,0,0,.3)}.container .swiper-change .swiper-item[data-v-26afef51]{-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;height:%?70?%;line-height:%?70?%;background-color:#f5f5f5;color:#ff648d;text-align:center}.container .swiper-change .swiper-item.select[data-v-26afef51]{background-color:#ff648d;color:#f5f5f5}.container .item[data-v-26afef51]{margin:%?20?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;padding:%?20?% %?40?%;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;border-radius:%?60?%;border:%?2?% solid #efefef}.container .item .left-content[data-v-26afef51]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.container .item .left-content .img[data-v-26afef51]{width:%?80?%;height:%?80?%;border-radius:50%}.container .item .left-content .content[data-v-26afef51]{margin-left:%?20?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-justify-content:space-around;-ms-flex-pack:distribute;justify-content:space-around}.container .item .left-content .content .top[data-v-26afef51]{max-width:%?250?%}.container .item .left-content .content .bottom[data-v-26afef51]{font-size:%?24?%;color:#888}.container .item .left-content.badge-type .img[data-v-26afef51]{width:%?169?%;height:%?51?%;border-radius:0}.container .item .right-content[data-v-26afef51]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex}.container .item .right-content .earn[data-v-26afef51]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-justify-content:space-around;-ms-flex-pack:distribute;justify-content:space-around;-webkit-box-align:start;-webkit-align-items:flex-start;-ms-flex-align:start;align-items:flex-start;margin-right:%?30?%;min-width:%?140?%}.container .item .right-content .earn .right-item[data-v-26afef51]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.container .item .right-content .earn .right-item uni-image[data-v-26afef51]{width:%?40?%}.container .item .right-content .btn[data-v-26afef51]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.container .weibo-modal-container[data-v-26afef51]{height:100%;padding:%?30?%;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;color:#333;margin-top:%?-40?%}.container .weibo-modal-container .row-line[data-v-26afef51]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;width:100%;margin:%?10?%}.container .weibo-modal-container .row-line .left[data-v-26afef51]{width:20%}.container .weibo-modal-container .row-line .right[data-v-26afef51]{-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;font-size:%?26?%}.container .weibo-modal-container .row-line .right .btn[data-v-26afef51]{display:inline-block}.container .weibo-modal-container .row-line .right uni-image[data-v-26afef51]{width:60%;margin:%?10?% auto;-webkit-box-shadow:0 %?4?% %?8?% rgba(0,0,0,.3);box-shadow:0 %?4?% %?8?% rgba(0,0,0,.3)}.container .weibo-modal-container uni-input[data-v-26afef51]{margin:%?10?% 0;background-color:#eee;border-radius:%?60?%;height:%?70?%;padding:0 %?20?%;color:#333}",""])},6325:function(t,e,a){var i=a("8f83");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=a("4f06").default;n("1b28dbb4",i,!0,{sourceMap:!1,shadowMode:!1})},"6c04":function(t,e,a){e=t.exports=a("2350")(!1),e.push([t.i,".container[data-v-668d0e5c]{position:fixed;top:0;left:0;width:100%;height:100%;z-index:99;background-color:rgba(0,0,0,.6);-webkit-transition:.2s;-o-transition:.2s;transition:.2s;opacity:0}.container .modal-container[data-v-668d0e5c]{width:100%;height:auto;-webkit-box-shadow:0 -2px 4px rgba(0,0,0,.5);box-shadow:0 -2px 4px rgba(0,0,0,.5);border-top-left-radius:%?30?%;border-top-right-radius:%?30?%;background-color:#fff;overflow:hidden;position:absolute;bottom:var(--window-bottom);-webkit-transform:translateY(100%);-ms-transform:translateY(100%);transform:translateY(100%);-webkit-transition:.2s;-o-transition:.2s;transition:.2s}.container .modal-container .center-img[data-v-668d0e5c]{position:absolute;left:50%;-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%);width:%?120?%;height:%?120?%}.container .modal-container .close-btn[data-v-668d0e5c]{position:absolute;top:%?10?%;right:%?10?%;width:%?80?%;height:%?80?%;color:#999;font-size:%?45?%;z-index:9}.container .modal-container .content[data-v-668d0e5c]{width:100%;height:auto;position:relative;padding-top:%?80?%}.container .modal-container.center[data-v-668d0e5c]{width:%?600?%;left:50%;top:50%;bottom:auto;-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%);border-bottom-left-radius:%?30?%;border-bottom-right-radius:%?30?%}.container .modal-container.centerNobg[data-v-668d0e5c]{width:%?600?%;left:50%;top:50%;bottom:auto;-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%);background-color:rgba(0,0,0,0);-webkit-box-shadow:none;box-shadow:none;border:none}.container.show[data-v-668d0e5c]{opacity:1}.container.show .modal-container[data-v-668d0e5c]{-webkit-transform:translateY(0);-ms-transform:translateY(0);transform:translateY(0)}.container.show .modal-container.center[data-v-668d0e5c]{-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%)}.container.show .modal-container.centerNobg[data-v-668d0e5c]{-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%)}",""])},"78ce":function(t,e,a){"use strict";var i=a("3d2c"),n=a.n(i);n.a},8638:function(t,e,a){"use strict";a.r(e);var i=a("8923"),n=a.n(i);for(var o in i)"default"!==o&&function(t){a.d(e,t,function(){return i[t]})}(o);e["default"]=n.a},8923:function(t,e,a){"use strict";var i=a("288e");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=i(a("961a")),o={components:{btnComponent:n.default},data:function(){return{show:!1}},props:{title:{default:"提示"},headimg:{default:"/static/image/icon/db.png"},type:{default:"default"}},created:function(){this.show=!0},methods:{closeModal:function(){var t=this;this.show=!1,setTimeout(function(){t.$emit("closeModal")},300)}}};e.default=o},"8f83":function(t,e,a){e=t.exports=a("2350")(!1),e.push([t.i,".button[data-v-0274f9ce]{color:#818286;-webkit-transition:.4s ease;-o-transition:.4s ease;transition:.4s ease;border-radius:%?40?%;-webkit-box-shadow:0 %?2?% %?4?% hsla(0,0%,40%,.3);box-shadow:0 %?2?% %?4?% hsla(0,0%,40%,.3)}.button.scale[data-v-0274f9ce]{-webkit-transform:scale(.7);-ms-transform:scale(.7);transform:scale(.7)}.button.default[data-v-0274f9ce]{color:#412b13;background:-webkit-gradient(linear,left top,right bottom,from(#fed525),to(#fed525));background:-o-linear-gradient(left top,#fed525,#fed525);background:linear-gradient(to right bottom,#fed525,#fed525)}.button.big[data-v-0274f9ce]{background:url(https://tva1.sinaimg.cn/large/0060lm7Tly1g2dxu4igebg303v02cgld.gif) 50%/100% 100% no-repeat}.button.success[data-v-0274f9ce]{color:#fff;background:-webkit-gradient(linear,left top,right bottom,from(#962de0),to(#962de0));background:-o-linear-gradient(left top,#962de0,#962de0);background:linear-gradient(to right bottom,#962de0,#962de0)}.button.disable[data-v-0274f9ce]{color:#fff;background:-webkit-gradient(linear,left top,right bottom,from(#ccc),to(#ccc));background:-o-linear-gradient(left top,#ccc,#ccc);background:linear-gradient(to right bottom,#ccc,#ccc)}.button.fangde[data-v-0274f9ce]{color:#412b13;-webkit-box-shadow:none;box-shadow:none;border-radius:0;background:url(https://mmbiz.qpic.cn/mmbiz_jpg/w5pLFvdua9FsWnev2aG6T492FpDr5HAaXcd90tJhNX454ZhD1HhuMcEK0T5Suuva8yI7TvicibBTcw8CvEYibzl6w/0) 50%/100% 100% no-repeat}.button.css[data-v-0274f9ce]{color:#fff;background:-webkit-gradient(linear,left top,right bottom,from(#f8648a),to(red));background:-o-linear-gradient(left top,#f8648a,red);background:linear-gradient(to right bottom,#f8648a,red);-webkit-box-shadow:none;box-shadow:none}.button.green[data-v-0274f9ce]{color:#fff;background:-webkit-gradient(linear,left top,right bottom,from(#4ee059),to(#199b1a));background:-o-linear-gradient(left top,#4ee059,#199b1a);background:linear-gradient(to right bottom,#4ee059,#199b1a);-webkit-box-shadow:none;box-shadow:none}.button.yellow[data-v-0274f9ce]{color:#000;background:-webkit-gradient(linear,left top,right bottom,from(#fdebb2),to(#fbcc3e));background:-o-linear-gradient(left top,#fdebb2,#fbcc3e);background:linear-gradient(to right bottom,#fdebb2,#fbcc3e);-webkit-box-shadow:none;box-shadow:none}.button.none[data-v-0274f9ce]{-webkit-box-shadow:none;box-shadow:none}.button.color[data-v-0274f9ce]{background-color:#333;border-radius:%?60?%}",""])},"961a":function(t,e,a){"use strict";a.r(e);var i=a("aa5b"),n=a("04cc");for(var o in n)"default"!==o&&function(t){a.d(e,t,function(){return n[t]})}(o);a("f67b");var s=a("2877"),r=Object(s["a"])(n["default"],i["a"],i["b"],!1,null,"0274f9ce",null);e["default"]=r.exports},aa5b:function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"button flex-set",class:[t.type,t.scale],on:{touchstart:function(e){e=t.$handleEvent(e),t.scale="scale"},touchend:function(e){e=t.$handleEvent(e),t.scale=""}}},[t._t("default")],2)},n=[];a.d(e,"a",function(){return i}),a.d(e,"b",function(){return n})},bda9:function(t,e,a){"use strict";a.r(e);var i=a("0090"),n=a("8638");for(var o in n)"default"!==o&&function(t){a.d(e,t,function(){return n[t]})}(o);a("c282");var s=a("2877"),r=Object(s["a"])(n["default"],i["a"],i["b"],!1,null,"668d0e5c",null);e["default"]=r.exports},c13f:function(t,e,a){"use strict";a.r(e);var i=a("371d"),n=a("21ce");for(var o in n)"default"!==o&&function(t){a.d(e,t,function(){return n[t]})}(o);a("78ce");var s=a("2877"),r=Object(s["a"])(n["default"],i["a"],i["b"],!1,null,"26afef51",null);e["default"]=r.exports},c282:function(t,e,a){"use strict";var i=a("3d8d"),n=a.n(i);n.a},eae3:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={data:function(){return{scale:""}},props:{type:{default:"none"}}};e.default=i},f67b:function(t,e,a){"use strict";var i=a("6325"),n=a.n(i);n.a},fa13:function(t,e,a){"use strict";var i=a("288e");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=i(a("961a")),o=i(a("bda9")),s={components:{btnComponent:n.default,modalComponent:o.default},data:function(){return{$app:this.$app,requestCount:1,taskList:this.$app.getData("taskList")||[],modal:"",shareText:"",weiboUrl:"",weibo_zhuanfa:{},current:1}},onShow:function(){this.getTaskList()},onLoad:function(){this.getShareText()},onShareAppMessage:function(t){var e=t.target&&t.target.dataset.share;return this.$app.commonShareAppMessage(e)},methods:{openAdver:function(){var t=this;this.$app.openVideoAd(function(){t.taskSettle(19)})},clipboard:function(){var t=this;uni.setClipboardData({data:this.shareText,success:function(){t.$app.toast("复制成功","success")}})},weiboCommit:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:0;this.weiboUrl&&this.$app.request(this.$app.API.TASK_WEIBO,{weiboUrl:this.weiboUrl,type:e},function(e){t.$app.toast("提交成功","success"),t.modal="",t.weiboUrl="",t.getTaskList()})},useBadge:function(t){var e=this;if(0==t.status){if(1==t.type)return}else this.$app.request("badge/use",{badge_id:t.id},function(t){e.getTaskList()},"POST",!0)},doTask:function(t,e){0==t.status?8==t.id?this.modal="weibo":9==t.id?this.modal="weibo_zhuanfa":19==t.id?this.openAdver():t.gopage&&this.$app.goPage(t.gopage):1==t.status&&(this.taskList[e].status=2,this.taskSettle(t.id))},taskSettle:function(t){var e=this;this.$app.request(this.$app.API.TASK_SETTLE,{task_id:t},function(t){var a="领取成功";t.data.coin&&(a+="，金豆+"+t.data.coin),t.data.flower&&(a+="，鲜花+"+t.data.flower),t.data.stone&&(a+="，钻石+"+t.data.stone),t.data.trumpet&&(a+="，喇叭+"+t.data.trumpet),e.$app.toast(a),e.getTaskList(),e.$app.request(e.$app.API.USER_CURRENCY,{},function(t){e.$app.setData("userCurrency",t.data)})},"POST",!0)},getShareText:function(){var t=this;this.$app.request(this.$app.API.EXT_SHARETEXT,{},function(e){t.shareText=e.data.share_text,t.weibo_zhuanfa=e.data.weibo_zhuanfa})},getTaskList:function(){var t=this;this.$app.request(this.$app.API.TASK,{type:this.current},function(e){t.taskList=e.data,t.$app.setData("taskList",e.data)})}}};e.default=s}}]);