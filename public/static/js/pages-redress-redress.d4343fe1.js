(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-redress-redress"],{"0090":function(t,e,a){"use strict";var n,o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"container",class:{show:t.show},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closeModal.apply(void 0,arguments)}}},[a("v-uni-view",{staticClass:"modal-container",class:[t.type],on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e)}}},[a("v-uni-view",{staticClass:"close-btn flex-set iconfont iconclose",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closeModal.apply(void 0,arguments)}}}),a("v-uni-view",{staticClass:"content"},[t._t("default")],2)],1)],1)},i=[];a.d(e,"b",function(){return o}),a.d(e,"c",function(){return i}),a.d(e,"a",function(){return n})},"00dc":function(t,e,a){e=t.exports=a("2350")(!1),e.push([t.i,".container[data-v-409a6318]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;align-items:center;padding:%?15?% 0;background-color:#fff;width:100%}.container .left-container .rank-num[data-v-409a6318]{text-align:center;width:%?90?%}.container .left-container .avatar[data-v-409a6318]{width:%?80?%;height:%?80?%;border-radius:50%;margin-right:%?40?%}",""])},"02f49":function(t,e,a){var n=a("ff79");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var o=a("4f06").default;o("7dc96ea2",n,!0,{sourceMap:!1,shadowMode:!1})},"04cc":function(t,e,a){"use strict";a.r(e);var n=a("fd6c"),o=a.n(n);for(var i in n)"default"!==i&&function(t){a.d(e,t,function(){return n[t]})}(i);e["default"]=o.a},"0a94":function(t,e,a){var n=a("db7b");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var o=a("4f06").default;o("4623445e",n,!0,{sourceMap:!1,shadowMode:!1})},"16b9":function(t,e,a){"use strict";var n,o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"container"},[a("v-uni-view",{staticClass:"left-container flex-set"},[a("v-uni-view",{staticClass:"rank-num"},[t._v(t._s(t.rank))]),t.avatar?a("v-uni-image",{staticClass:"avatar",attrs:{src:t.avatar,mode:"aspectFill"}}):t._e(),t._t("left-container")],2),a("v-uni-view",{staticClass:"right-container"},[t._t("right-container")],2)],1)},i=[];a.d(e,"b",function(){return o}),a.d(e,"c",function(){return i}),a.d(e,"a",function(){return n})},"1d5d":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={data:function(){return{}},props:{rank:{default:""},avatar:{default:""}}};e.default=n},"36a9":function(t,e,a){"use strict";a.r(e);var n=a("16b9"),o=a("6672");for(var i in o)"default"!==i&&function(t){a.d(e,t,function(){return o[t]})}(i);a("d86f");var r,c=a("f0c5"),s=Object(c["a"])(o["default"],n["b"],n["c"],!1,null,"409a6318",null,!1,n["a"],r);e["default"]=s.exports},6672:function(t,e,a){"use strict";a.r(e);var n=a("1d5d"),o=a.n(n);for(var i in n)"default"!==i&&function(t){a.d(e,t,function(){return n[t]})}(i);e["default"]=o.a},8638:function(t,e,a){"use strict";a.r(e);var n=a("ebc2"),o=a.n(n);for(var i in n)"default"!==i&&function(t){a.d(e,t,function(){return n[t]})}(i);e["default"]=o.a},9502:function(t,e,a){e=t.exports=a("2350")(!1),e.push([t.i,".container[data-v-668d0e5c]{position:fixed;top:0;left:0;width:100%;height:100%;z-index:99;background-color:rgba(0,0,0,.6);-webkit-transition:.2s;transition:.2s;opacity:0}.container .modal-container[data-v-668d0e5c]{width:100%;height:auto;box-shadow:0 -2px 4px rgba(0,0,0,.5);border-top-left-radius:%?30?%;border-top-right-radius:%?30?%;background-color:#fff;overflow:hidden;position:absolute;bottom:var(--window-bottom);-webkit-transform:translateY(100%);transform:translateY(100%);-webkit-transition:.2s;transition:.2s}.container .modal-container .center-img[data-v-668d0e5c]{position:absolute;left:50%;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);width:%?120?%;height:%?120?%}.container .modal-container .close-btn[data-v-668d0e5c]{position:absolute;top:%?10?%;right:%?10?%;width:%?80?%;height:%?80?%;color:#999;font-size:%?45?%;z-index:9}.container .modal-container .content[data-v-668d0e5c]{width:100%;height:auto;position:relative;padding-top:%?80?%}.container .modal-container.center[data-v-668d0e5c]{width:%?600?%;left:50%;top:50%;bottom:auto;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);border-bottom-left-radius:%?30?%;border-bottom-right-radius:%?30?%}.container .modal-container.centerNobg[data-v-668d0e5c]{width:%?600?%;left:50%;top:50%;bottom:auto;-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);background-color:initial;box-shadow:none;border:none}.container.show[data-v-668d0e5c]{opacity:1}.container.show .modal-container[data-v-668d0e5c]{-webkit-transform:translateY(0);transform:translateY(0)}.container.show .modal-container.center[data-v-668d0e5c]{-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%)}.container.show .modal-container.centerNobg[data-v-668d0e5c]{-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%)}",""])},"961a":function(t,e,a){"use strict";a.r(e);var n=a("ac6d"),o=a("04cc");for(var i in o)"default"!==i&&function(t){a.d(e,t,function(){return o[t]})}(i);a("9a1c");var r,c=a("f0c5"),s=Object(c["a"])(o["default"],n["b"],n["c"],!1,null,"f6a650e6",null,!1,n["a"],r);e["default"]=s.exports},9933:function(t,e,a){"use strict";a.r(e);var n=a("b307"),o=a("a1e5");for(var i in o)"default"!==i&&function(t){a.d(e,t,function(){return o[t]})}(i);a("f55b");var r,c=a("f0c5"),s=Object(c["a"])(o["default"],n["b"],n["c"],!1,null,"5a3a552b",null,!1,n["a"],r);e["default"]=s.exports},"9a1c":function(t,e,a){"use strict";var n=a("0a94"),o=a.n(n);o.a},a1e5:function(t,e,a){"use strict";a.r(e);var n=a("b111"),o=a.n(n);for(var i in n)"default"!==i&&function(t){a.d(e,t,function(){return n[t]})}(i);e["default"]=o.a},ac6d:function(t,e,a){"use strict";var n,o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"button flex-set",class:[t.type,t.scale],on:{touchstart:function(e){arguments[0]=e=t.$handleEvent(e),t.scale="scale"},touchend:function(e){arguments[0]=e=t.$handleEvent(e),t.scale=""}}},[t._t("default")],2)},i=[];a.d(e,"b",function(){return o}),a.d(e,"c",function(){return i}),a.d(e,"a",function(){return n})},b111:function(t,e,a){"use strict";var n=a("288e");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=n(a("bda9")),i=n(a("961a")),r=n(a("36a9")),c={components:{modalComponent:o.default,btnComponent:i.default,listItemComponent:r.default},data:function(){return{info:{}}},onShareAppMessage:function(t){var e=t.target&&t.target.dataset.share;return this.$app.commonShareAppMessage(e)},onLoad:function(){this.loadData()},onShow:function(){},methods:{loadData:function(){var t=this;this.$app.request("ext/redress",{},function(e){t.info=e.data})}}};e.default=c},b307:function(t,e,a){"use strict";var n,o=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"help-container"},[0==t.info.status?a("v-uni-image",{staticClass:"img",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GndUW3a1UarD8yKON3YUcuXgUhrr4l1IqYsJicRZyfjgyviaO321GRWFVYkhMuyIeIzfqEx1rHicsRg/0",mode:""}}):a("v-uni-image",{staticClass:"img",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GndUW3a1UarD8yKON3YUcuH1Atwz2WxscBZs2FcNRt94RfqKUyY437dZictUNNLPp71aOVxWHCzaQ/0",mode:""}}),a("v-uni-view",{staticClass:"show-status"},[t._v(t._s(t.info.msg))]),a("v-uni-navigator",{staticClass:"continue-btn",attrs:{"open-type":"switchTab",url:"/pages/index/index"}},[t._v("继续冲榜 >")])],1)},i=[];a.d(e,"b",function(){return o}),a.d(e,"c",function(){return i}),a.d(e,"a",function(){return n})},bda9:function(t,e,a){"use strict";a.r(e);var n=a("0090"),o=a("8638");for(var i in o)"default"!==i&&function(t){a.d(e,t,function(){return o[t]})}(i);a("c282");var r,c=a("f0c5"),s=Object(c["a"])(o["default"],n["b"],n["c"],!1,null,"668d0e5c",null,!1,n["a"],r);e["default"]=s.exports},c282:function(t,e,a){"use strict";var n=a("c77e"),o=a.n(n);o.a},c77e:function(t,e,a){var n=a("9502");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var o=a("4f06").default;o("a8db2362",n,!0,{sourceMap:!1,shadowMode:!1})},cfd8:function(t,e,a){var n=a("00dc");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var o=a("4f06").default;o("395e5f2e",n,!0,{sourceMap:!1,shadowMode:!1})},d86f:function(t,e,a){"use strict";var n=a("cfd8"),o=a.n(n);o.a},db7b:function(t,e,a){e=t.exports=a("2350")(!1),e.push([t.i,".button[data-v-f6a650e6]{color:#818286;-webkit-transition:.4s ease;transition:.4s ease;border-radius:%?40?%;box-shadow:0 %?2?% %?4?% hsla(0,0%,40%,.3)}.button.scale[data-v-f6a650e6]{-webkit-transform:scale(.7);transform:scale(.7)}.button.default[data-v-f6a650e6]{color:#412b13;background:-webkit-linear-gradient(left top,#fed525,#fed525);background:linear-gradient(to right bottom,#fed525,#fed525)}.button.big[data-v-f6a650e6]{background:url(https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GnD8mrIKwSEItXUhLNibPUxibmPWGaicd1kWEsAHNRKt8jYQ9ILUZfXfVOib1mRFFbfRXiaMbXZj2nJsQ/0) 50%/100% 100% no-repeat}.button.success[data-v-f6a650e6]{color:#fff;background:-webkit-linear-gradient(left top,#962de0,#962de0);background:linear-gradient(to right bottom,#962de0,#962de0)}.button.disable[data-v-f6a650e6]{color:#fff;background:-webkit-linear-gradient(left top,#ccc,#ccc);background:linear-gradient(to right bottom,#ccc,#ccc)}.button.fangde[data-v-f6a650e6]{color:#412b13;box-shadow:none;border-radius:0;background:url(https://mmbiz.qpic.cn/mmbiz_jpg/w5pLFvdua9FsWnev2aG6T492FpDr5HAaXcd90tJhNX454ZhD1HhuMcEK0T5Suuva8yI7TvicibBTcw8CvEYibzl6w/0) 50%/100% 100% no-repeat}.button.css[data-v-f6a650e6]{color:#fff;background:-webkit-linear-gradient(left top,#f8648a,red);background:linear-gradient(to right bottom,#f8648a,red);box-shadow:none}.button.green[data-v-f6a650e6]{color:#fff;background:-webkit-linear-gradient(left top,#4ee059,#199b1a);background:linear-gradient(to right bottom,#4ee059,#199b1a);box-shadow:none}.button.yellow[data-v-f6a650e6]{color:#000;background:-webkit-linear-gradient(left top,#fdebb2,#fbcc3e);background:linear-gradient(to right bottom,#fdebb2,#fbcc3e);box-shadow:none}.button.none[data-v-f6a650e6]{box-shadow:none}.button.color[data-v-f6a650e6]{background-color:#333;border-radius:%?60?%}.button.style2[data-v-f6a650e6]{background:-webkit-linear-gradient(top,#f7c566,#fe9a3c);background:linear-gradient(180deg,#f7c566,#fe9a3c);border-radius:%?10?%;color:#fff}",""])},ebc2:function(t,e,a){"use strict";var n=a("288e");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=n(a("961a")),i={components:{btnComponent:o.default},data:function(){return{show:!1}},props:{title:{default:"提示"},headimg:{default:"/static/image/icon/db.png"},type:{default:"default"}},created:function(){this.show=!0},methods:{closeModal:function(){var t=this;this.show=!1,setTimeout(function(){t.$emit("closeModal")},300)}}};e.default=i},f55b:function(t,e,a){"use strict";var n=a("02f49"),o=a.n(n);o.a},fd6c:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={data:function(){return{scale:""}},props:{type:{default:"none"}}};e.default=n},ff79:function(t,e,a){e=t.exports=a("2350")(!1),e.push([t.i,".help-container[data-v-5a3a552b]{width:100%;padding-top:%?175?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.help-container .img[data-v-5a3a552b]{width:%?190?%;height:%?210?%;display:block}.show-status[data-v-5a3a552b]{width:%?400?%;text-align:center;line-height:2;font-size:%?40?%;font-weight:600;color:#666}.msg[data-v-5a3a552b]{width:100%;height:%?110?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;color:#ff9600;line-height:%?38?%;font-size:%?28?%;text-align:center}.continue[data-v-5a3a552b]{width:100%;line-height:%?50?%;font-size:%?30?%;color:#9f9b9f;text-align:center}.continue-btn[data-v-5a3a552b]{width:%?450?%;height:%?90?%;margin:%?10?% auto;background-color:#ffba00;font-size:%?37?%;line-height:%?90?%;border-radius:%?45?%;text-align:center;color:#6c3800;font-weight:700}.bottom-btn[data-v-5a3a552b]{width:%?250?%;position:fixed;bottom:%?80?%;left:%?250?%;text-align:center;text-decoration:underline;color:#fff;font-size:%?28?%}",""])}}]);