(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-index-index"],{"0abd":function(t,a,e){"use strict";var n=e("288e");Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var i=n(e("b111")),o={components:{btnComponent:i.default},data:function(){return{show:!1}},props:{title:{default:"提示"},headimg:{default:"/static/image/icon/db.png"},type:{default:"default"}},created:function(){this.show=!0},methods:{closeModal:function(){var t=this;this.show=!1,setTimeout(function(){t.$emit("closeModal")},300)}}};a.default=o},"12ea":function(t,a,e){"use strict";var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"container",class:{show:t.show},on:{click:function(a){a=t.$handleEvent(a),t.closeModal(a)}}},[e("v-uni-view",{staticClass:"modal-container",class:[t.type],on:{click:function(a){a.stopPropagation(),a=t.$handleEvent(a)}}},[e("v-uni-view",{staticClass:"close-btn flex-set iconfont iconclose",on:{click:function(a){a=t.$handleEvent(a),t.closeModal(a)}}}),e("v-uni-view",{staticClass:"content"},[t._t("default")],2)],1)],1)},i=[];e.d(a,"a",function(){return n}),e.d(a,"b",function(){return i})},1550:function(t,a,e){var n=e("4ca4");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("638eea86",n,!0,{sourceMap:!1,shadowMode:!1})},1576:function(t,a,e){var n=e("3ef3");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("3173d7de",n,!0,{sourceMap:!1,shadowMode:!1})},"18ad":function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,'.index-page-container .top-container[data-v-58dcf506]{padding:%?20?% %?30?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.index-page-container .top-container .left-wrap[data-v-58dcf506]{-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;position:relative;color:#c6c9cc}.index-page-container .top-container .left-wrap .iconfont[data-v-58dcf506]{position:absolute;left:%?20?%;top:50%;-webkit-transform:translateY(-50%);-ms-transform:translateY(-50%);transform:translateY(-50%)}.index-page-container .top-container .left-wrap .input[data-v-58dcf506]{background-color:#f1f2f6;border-radius:%?30?%;padding:0 %?60?%;height:%?50?%;color:#333}.index-page-container .top-container .right-wrap[data-v-58dcf506]{margin-left:%?20?%}.index-page-container .top-container .right-wrap .iconfont[data-v-58dcf506]{margin:0 %?10?%}.index-page-container .swiper-container[data-v-58dcf506]{margin:%?5?% %?30?%;height:%?250?%;border-radius:%?30?%;overflow:hidden;position:relative;z-index:1}.index-page-container .swiper-container .banner[data-v-58dcf506]{width:%?690?%;height:%?250?%;background:url(https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERVpWtSSpwicFERRz0Wa4Nw9AG4iaH5mBbnjW6zmm26oETkLm86mfk8srw/0) no-repeat 0 0;background-size:cover}.index-page-container .swiper-container .bottom-hold[data-v-58dcf506]{position:absolute;z-index:3;bottom:0;left:0;height:%?100?%;width:100%}.index-page-container .swiper-container .bottom-hold .bg[data-v-58dcf506]{position:absolute}.index-page-container .swiper-container .bottom-hold .content[data-v-58dcf506]{top:73%;width:100%;color:#fff;font-size:%?26?%}.index-page-container .swiper-container .bottom-hold .content .avatar[data-v-58dcf506]{width:%?40?%;height:%?40?%;border-radius:50%}.index-page-container .swiper-container .bottom-hold .content uni-text[data-v-58dcf506]{color:#fbb225;margin:0 %?4?%}.index-page-container .tab-container[data-v-58dcf506]{font-size:%?28?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;padding:%?20?% %?20?%;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between}.index-page-container .tab-container .left-wrap[data-v-58dcf506]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.index-page-container .tab-container .left-wrap .tab-item[data-v-58dcf506]{position:relative;margin:0 %?20?%}.index-page-container .tab-container .left-wrap .tab-item.active[data-v-58dcf506]{font-size:%?34?%;font-weight:700}.index-page-container .tab-container .left-wrap .tab-item.active[data-v-58dcf506]:before{position:absolute;content:"";height:%?8?%;width:%?50?%;width:60%;border-radius:%?14?%;bottom:%?-10?%;left:50%;-webkit-transform:translateX(-50%);-ms-transform:translateX(-50%);transform:translateX(-50%);background-color:#fbcc3e}.index-page-container .topthree-container[data-v-58dcf506]{padding:0 %?30?%;margin:%?30?% 0 %?-25?%;position:relative}.index-page-container .topthree-container .remaintime[data-v-58dcf506]{position:absolute;right:%?40?%;top:%?-50?%;font-size:%?18?%;color:#bbb}.index-page-container .topthree-container .row-info[data-v-58dcf506]{padding:0 %?30?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between}.index-page-container .topthree-container .row-info .content[data-v-58dcf506]{-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;height:100%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.index-page-container .topthree-container .row-info .content .avatar-wrap[data-v-58dcf506]{position:relative}.index-page-container .topthree-container .row-info .content .avatar-wrap .bg[data-v-58dcf506]{width:%?148?%;height:%?148?%}.index-page-container .topthree-container .row-info .content .avatar-wrap .avatar[data-v-58dcf506]{position:absolute;border-radius:50%;width:%?100?%;height:%?100?%}.index-page-container .topthree-container .row-info .content .starname[data-v-58dcf506]{margin-top:%?-20?%;font-weight:700}.index-page-container .topthree-container .row-info .content .hot[data-v-58dcf506]{text-align:center;color:#818286;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.index-page-container .topthree-container .row-info .content .hot uni-image[data-v-58dcf506]{width:%?24?%;height:%?24?%;margin-right:%?10?%}.index-page-container .topthree-container .row-info .content.mid[data-v-58dcf506]{margin-top:%?-40?%}.index-page-container .topthree-container .three-taijie[data-v-58dcf506]{width:100%;height:%?210?%;margin-top:%?-125?%;position:relative;z-index:-1}.index-page-container .rank-list-container .rank-list-item[data-v-58dcf506]{margin:%?20?% %?30?%;-webkit-box-shadow:0 %?2?% %?16?% hsla(0,0%,60%,.3);box-shadow:0 %?2?% %?16?% hsla(0,0%,60%,.3);border-radius:%?30?%;overflow:hidden}.index-page-container .rank-list-container .rank-list-item .left-container[data-v-58dcf506]{line-height:%?44?%}.index-page-container .rank-list-container .rank-list-item .left-container .bottom-text[data-v-58dcf506]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.index-page-container .rank-list-container .rank-list-item .left-container .bottom-text .hot-count[data-v-58dcf506]{color:#333;margin-right:%?4?%}.index-page-container .rank-list-container .rank-list-item .left-container .bottom-text .icon-heart[data-v-58dcf506]{width:%?24?%;height:%?24?%}.index-page-container .rank-list-container .rank-list-item .right-container[data-v-58dcf506]{margin-right:%?40?%}.index-page-container .rank-list-container .rank-list-item .right-container .btn[data-v-58dcf506]{border:%?2?% solid #ff8aaa;border-radius:%?30?%;padding:%?5?% %?30?%;color:#ff8aaa}.index-page-container .rank-list-container .rank-list-item-s[data-v-58dcf506]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;background-color:#fff;margin:%?20?% %?30?%;-webkit-box-shadow:0 %?2?% %?16?% hsla(0,0%,60%,.3);box-shadow:0 %?2?% %?16?% hsla(0,0%,60%,.3);border-radius:%?30?%;overflow:hidden}.index-page-container .rank-list-container .rank-list-item-s .num[data-v-58dcf506]{width:%?80?%;padding:%?10?% %?20?%}.index-page-container .rank-list-container .rank-list-item-s .avatar[data-v-58dcf506]{width:%?80?%;height:%?80?%;border-radius:50%}.index-page-container .rank-list-container .rank-list-item-s .content[data-v-58dcf506]{padding:%?10?% %?20?%;line-height:1.6;max-width:%?470?%}.index-page-container .rank-list-container .rank-list-item-s .content .top[data-v-58dcf506]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.index-page-container .rank-list-container .rank-list-item-s .content .top .name[data-v-58dcf506]{font-size:%?30?%;color:#000;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1}.index-page-container .rank-list-container .rank-list-item-s .content .top .star[data-v-58dcf506]{border-radius:%?20?%;background-color:#82c7d4;color:#fff;padding:0 %?10?%;margin:0 %?10?%;font-size:%?22?%}.index-page-container .rank-list-container .rank-list-item-s .son-wrap[data-v-58dcf506]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex}.index-page-container .rank-list-container .rank-list-item-s .son-wrap .avatar[data-v-58dcf506]{width:%?40?%;height:%?40?%;border-radius:50%;margin:%?5?%}.index-page-container .open-ad-container[data-v-58dcf506]{position:fixed;top:0;left:0;right:0;bottom:0;z-index:9;background-color:rgba(0,0,0,.6);-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column}.index-page-container .open-ad-container .main[data-v-58dcf506]{width:%?500?%;height:%?800?%;border-radius:%?20?%}.index-page-container .open-ad-container .close-btn[data-v-58dcf506]{width:%?80?%;height:%?80?%;margin-top:%?10?%;z-index:10;border-radius:50%;background-color:rgba(0,0,0,.5);color:#fff;font-size:%?45?%}',""])},2290:function(t,a,e){var n=e("639d");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("ebb0b9b2",n,!0,{sourceMap:!1,shadowMode:!1})},"2bde":function(t,a,e){"use strict";e.r(a);var n=e("d3fe"),i=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(a,t,function(){return n[t]})}(o);a["default"]=i.a},3485:function(t,a,e){"use strict";var n=e("1550"),i=e.n(n);i.a},"3ef3":function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".swiper-container[data-v-1bc51290]{position:relative}.swiper-container .banner-wrapper[data-v-1bc51290]{border-radius:%?10?%;overflow:hidden}.swiper-container .banner-wrapper .banner-item-img[data-v-1bc51290]{border-radius:%?10?%;width:100%;height:100%}.swiper-container .banner-wrapper.muti[data-v-1bc51290]{margin:0 %?-20?%;padding:%?18?% 0}.swiper-container .banner-wrapper.muti .banner-item-img[data-v-1bc51290]{-webkit-transform:scale(.95);-ms-transform:scale(.95);transform:scale(.95)}.swiper-container .small[data-v-1bc51290]{width:90%;position:absolute;bottom:%?25?%;left:%?75?%;height:%?40?%}.swiper-container .small .swiper-item[data-v-1bc51290]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.swiper-container .small .swiper-item .item[data-v-1bc51290]{font-size:%?24?%;background-color:rgba(0,0,0,.6);border-radius:%?30?%;color:#fff;padding:0 %?10?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.swiper-container .small .swiper-item .item .icon[data-v-1bc51290]{width:%?24?%;height:%?24?%;margin:0 %?6?%}.swiper-container .small .swiper-item .item .text[data-v-1bc51290]{-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1}.swiper-container .small.muti[data-v-1bc51290]{bottom:%?5?%;left:%?29?%}",""])},"4ca4":function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".container[data-v-9548756c]{position:fixed;top:0;left:0;width:100%;height:100%;z-index:99;background-color:rgba(0,0,0,.6);-webkit-transition:.2s;-o-transition:.2s;transition:.2s;opacity:0}.container .modal-container[data-v-9548756c]{width:100%;height:auto;-webkit-box-shadow:0 -2px 4px rgba(0,0,0,.5);box-shadow:0 -2px 4px rgba(0,0,0,.5);border-top-left-radius:%?30?%;border-top-right-radius:%?30?%;background-color:#fff;overflow:hidden;position:absolute;bottom:var(--window-bottom);-webkit-transform:translateY(100%);-ms-transform:translateY(100%);transform:translateY(100%);-webkit-transition:.2s;-o-transition:.2s;transition:.2s}.container .modal-container .center-img[data-v-9548756c]{position:absolute;left:50%;-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%);width:%?120?%;height:%?120?%}.container .modal-container .close-btn[data-v-9548756c]{position:absolute;top:%?10?%;right:%?10?%;width:%?80?%;height:%?80?%;color:#999;font-size:%?45?%;z-index:9}.container .modal-container .content[data-v-9548756c]{width:100%;height:auto;position:relative;padding-top:%?80?%}.container .modal-container.center[data-v-9548756c]{width:%?600?%;left:50%;top:50%;bottom:auto;-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%);border-bottom-left-radius:%?30?%;border-bottom-right-radius:%?30?%}.container .modal-container.centerNobg[data-v-9548756c]{width:%?600?%;left:50%;top:50%;bottom:auto;-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%);background-color:rgba(0,0,0,0);-webkit-box-shadow:none;box-shadow:none;border:none}.container.show[data-v-9548756c]{opacity:1}.container.show .modal-container[data-v-9548756c]{-webkit-transform:translateY(0);-ms-transform:translateY(0);transform:translateY(0)}.container.show .modal-container.center[data-v-9548756c]{-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%)}.container.show .modal-container.centerNobg[data-v-9548756c]{-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%)}",""])},"50c5":function(t,a,e){"use strict";var n=e("1576"),i=e.n(n);i.a},5390:function(t,a,e){"use strict";e.r(a);var n=e("12ea"),i=e("b3b6");for(var o in i)"default"!==o&&function(t){e.d(a,t,function(){return i[t]})}(o);e("3485");var r=e("2877"),s=Object(r["a"])(i["default"],n["a"],n["b"],!1,null,"9548756c",null);a["default"]=s.exports},5903:function(t,a,e){"use strict";e.r(a);var n=e("af4c"),i=e("a1d3");for(var o in i)"default"!==o&&function(t){e.d(a,t,function(){return i[t]})}(o);e("50c5");var r=e("2877"),s=Object(r["a"])(i["default"],n["a"],n["b"],!1,null,"1bc51290",null);a["default"]=s.exports},6032:function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".container[data-v-07eb943e]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;padding:%?15?% 0;background-color:#fff;width:100%}.container .left-container .rank-num[data-v-07eb943e]{text-align:center;width:%?90?%}.container .left-container .avatar[data-v-07eb943e]{width:%?80?%;height:%?80?%;border-radius:50%;margin-right:%?40?%}",""])},"639d":function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".button[data-v-13fd8e87]{color:#818286;-webkit-transition:.4s ease;-o-transition:.4s ease;transition:.4s ease;border-radius:%?40?%;-webkit-box-shadow:0 %?2?% %?4?% hsla(0,0%,40%,.3);box-shadow:0 %?2?% %?4?% hsla(0,0%,40%,.3)}.button.scale[data-v-13fd8e87]{-webkit-transform:scale(.7);-ms-transform:scale(.7);transform:scale(.7)}.button.default[data-v-13fd8e87]{color:#412b13;background:-webkit-gradient(linear,left top,right bottom,from(#fed525),to(#fed525));background:-o-linear-gradient(left top,#fed525,#fed525);background:linear-gradient(to right bottom,#fed525,#fed525)}.button.big[data-v-13fd8e87]{background:url(https://tva1.sinaimg.cn/large/0060lm7Tly1g2dxu4igebg303v02cgld.gif) 50%/100% 100% no-repeat}.button.success[data-v-13fd8e87]{color:#fff;background:-webkit-gradient(linear,left top,right bottom,from(#962de0),to(#962de0));background:-o-linear-gradient(left top,#962de0,#962de0);background:linear-gradient(to right bottom,#962de0,#962de0)}.button.disable[data-v-13fd8e87]{color:#fff;background:-webkit-gradient(linear,left top,right bottom,from(#ccc),to(#ccc));background:-o-linear-gradient(left top,#ccc,#ccc);background:linear-gradient(to right bottom,#ccc,#ccc)}.button.fangde[data-v-13fd8e87]{color:#412b13;-webkit-box-shadow:none;box-shadow:none;border-radius:0;background:url(https://mmbiz.qpic.cn/mmbiz_jpg/w5pLFvdua9FsWnev2aG6T492FpDr5HAaXcd90tJhNX454ZhD1HhuMcEK0T5Suuva8yI7TvicibBTcw8CvEYibzl6w/0) 50%/100% 100% no-repeat}.button.css[data-v-13fd8e87]{color:#fff;background:-webkit-gradient(linear,left top,right bottom,from(#f8648a),to(red));background:-o-linear-gradient(left top,#f8648a,red);background:linear-gradient(to right bottom,#f8648a,red);-webkit-box-shadow:none;box-shadow:none}.button.green[data-v-13fd8e87]{color:#fff;background:-webkit-gradient(linear,left top,right bottom,from(#4ee059),to(#199b1a));background:-o-linear-gradient(left top,#4ee059,#199b1a);background:linear-gradient(to right bottom,#4ee059,#199b1a);-webkit-box-shadow:none;box-shadow:none}.button.yellow[data-v-13fd8e87]{color:#000;background:-webkit-gradient(linear,left top,right bottom,from(#fdebb2),to(#fbcc3e));background:-o-linear-gradient(left top,#fdebb2,#fbcc3e);background:linear-gradient(to right bottom,#fdebb2,#fbcc3e);-webkit-box-shadow:none;box-shadow:none}.button.none[data-v-13fd8e87]{-webkit-box-shadow:none;box-shadow:none}.button.color[data-v-13fd8e87]{background-color:#333;border-radius:%?60?%}",""])},6519:function(t,a,e){var n=e("6032");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("68ea6dd8",n,!0,{sourceMap:!1,shadowMode:!1})},6583:function(t,a,e){"use strict";var n=e("6519"),i=e.n(n);i.a},"7d5e":function(t,a,e){"use strict";e.r(a);var n=e("fb83"),i=e("c450");for(var o in i)"default"!==o&&function(t){e.d(a,t,function(){return i[t]})}(o);e("e700");var r=e("2877"),s=Object(r["a"])(i["default"],n["a"],n["b"],!1,null,"58dcf506",null);a["default"]=s.exports},"7e45":function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n={data:function(){return{}},props:{bannerHeight:{default:"300"},bannerType:{default:"0"},bannerList:{default:[]},sList:{default:[]},muti:{default:!1}},computed:{bannerHeightComputed:function(){return uni.upx2px(this.bannerHeight)+"px"}},methods:{goPage:function(t){"/pages/subPages/fanclub_list/fanclub_list"!=t||this.$app.getData("userStar").id?this.$app.goPage(t):this.$app.toast("请先加入一个圈子")}}};a.default=n},"8d4d":function(t,a,e){"use strict";e.r(a);var n=e("d9fb"),i=e("cb75");for(var o in i)"default"!==o&&function(t){e.d(a,t,function(){return i[t]})}(o);e("6583");var r=e("2877"),s=Object(r["a"])(i["default"],n["a"],n["b"],!1,null,"07eb943e",null);a["default"]=s.exports},"8e1c":function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n={data:function(){return{}},props:{rank:{default:""},avatar:{default:""}}};a.default=n},"9b09":function(t,a,e){"use strict";var n=e("288e");Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var i=n(e("5390")),o=n(e("5903")),r=n(e("b111")),s=n(e("8d4d")),c={components:{bannerComponent:o.default,btnComponent:r.default,listItemComponent:s.default,modalComponent:i.default},data:function(){return{$app:this.$app,theme:this.$app.getData("theme")||0,modal:"indexBanner",showBottomLoading:!0,page:1,keywords:"",rankField:"week_hot",sign:0,rankList:this.$app.getData("index_rankList")||[],topImg:{},cutOffDate:""}},onLoad:function(t){return this.getSunday(),t.path?this.$app.goPage(t.path):t.starid?(this.starid=t.starid,this.goGroup(this.starid)):void 0},onShow:function(){this.page=1,this.keywords="",this.getRankList(),this.getBannerList()},onShareAppMessage:function(){return this.$app.commonShareAppMessage()},onHide:function(){uni.pageScrollTo({scrollTop:0,duration:0})},onReachBottom:function(){~this.rankField.indexOf("last")||(this.page++,this.getRankList())},methods:{goGroup:function(t){this.modal="qrcode",this.$app.getData("userStar")["id"]==t?this.$app.goPage("/pages/group/group"):this.$app.goPage("/pages/group/star?starid="+t)},getLast:function(){var t=new Date,a=t.getMonth(),e=++a,n=new Date(t.getFullYear(),e,1),i=864e5,o=new Date(n-i);this.cutOffDate=o.getMonth()+1+"月"+o.getDate()+"日"},getSunday:function(){var t=new Date,a=t.getDay()||7;t.setDate(t.getDate()-a+7),this.cutOffDate=t.getMonth()+1+"月"+t.getDate()+"日"},changeField:function(t){this.page=1,this.rankField=t,this.keywords="",this.getRankList()},searchInput:function(t){this.keywords&&t.detail.value||(this.rankList=[]),this.page=1,this.sign=0,this.rankField="week_hot",this.keywords=t.detail.value,this.getRankList()},getBannerList:function(){var t=this;this.$app.request("banner/top",{},function(a){t.topImg=a.data})},getRankList:function(){var t=this;this.$app.request(this.$app.API.STAR_RANK,{page:this.page,rankField:this.rankField,keywords:this.keywords,sign:this.sign},function(a){"fengyun"!=t.rankField?(a.data.length<10&&(t.showBottomLoading=!1),1==t.page?(t.rankList=a.data,t.$app.setData("index_rankList",a.data)):t.rankList=t.rankList.concat(a.data)):1==t.page?t.rankList=a.data:t.rankList=t.rankList.concat(a.data)})}}};a.default=c},a1d3:function(t,a,e){"use strict";e.r(a);var n=e("7e45"),i=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(a,t,function(){return n[t]})}(o);a["default"]=i.a},af4c:function(t,a,e){"use strict";var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"swiper-container"},[e("v-uni-swiper",{staticClass:"banner-wrapper",class:{muti:t.muti},style:{height:t.bannerHeightComputed},attrs:{"previous-margin":t.muti?"30px":"","next-margin":t.muti?"30px":"",circular:"true",autoplay:"true"}},t._l(t.bannerList,function(a,n){return e("v-uni-swiper-item",{key:n,staticClass:"banner-item",on:{click:function(e){e=t.$handleEvent(e),t.goPage(a.url)}}},[e("v-uni-image",{staticClass:"banner-item-img",attrs:{src:a.img,mode:"aspectFill"}})],1)}),1),e("v-uni-swiper",{staticClass:"small",class:{muti:!t.muti},attrs:{autoplay:"",interval:"3000",vertical:"",circular:""}},t._l(t.sList,function(a,n){return e("v-uni-swiper-item",{key:n,staticClass:"swiper-item",on:{click:function(e){e=t.$handleEvent(e),t.goPage(a.page)}}},[e("v-uni-view",{staticClass:"item"},[e("v-uni-image",{staticClass:"icon",attrs:{src:"/static/image/index/laba.png",mode:""}}),e("v-uni-view",{staticClass:"text text-overflow"},[t._v(t._s(a.name))])],1)],1)}),1)],1)},i=[];e.d(a,"a",function(){return n}),e.d(a,"b",function(){return i})},b111:function(t,a,e){"use strict";e.r(a);var n=e("f3a0"),i=e("2bde");for(var o in i)"default"!==o&&function(t){e.d(a,t,function(){return i[t]})}(o);e("bfda");var r=e("2877"),s=Object(r["a"])(i["default"],n["a"],n["b"],!1,null,"13fd8e87",null);a["default"]=s.exports},b3b6:function(t,a,e){"use strict";e.r(a);var n=e("0abd"),i=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(a,t,function(){return n[t]})}(o);a["default"]=i.a},bfda:function(t,a,e){"use strict";var n=e("2290"),i=e.n(n);i.a},c450:function(t,a,e){"use strict";e.r(a);var n=e("9b09"),i=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(a,t,function(){return n[t]})}(o);a["default"]=i.a},cb75:function(t,a,e){"use strict";e.r(a);var n=e("8e1c"),i=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(a,t,function(){return n[t]})}(o);a["default"]=i.a},d3fe:function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n={data:function(){return{scale:""}},props:{type:{default:"none"}}};a.default=n},d9fb:function(t,a,e){"use strict";var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"container"},[e("v-uni-view",{staticClass:"left-container flex-set"},[e("v-uni-view",{staticClass:"rank-num"},[t._v(t._s(t.rank))]),t.avatar?e("v-uni-image",{staticClass:"avatar",attrs:{src:t.avatar,mode:"aspectFill"}}):t._e(),t._t("left-container")],2),e("v-uni-view",{staticClass:"right-container"},[t._t("right-container")],2)],1)},i=[];e.d(a,"a",function(){return n}),e.d(a,"b",function(){return i})},e1f1:function(t,a,e){var n=e("18ad");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=e("4f06").default;i("3423a039",n,!0,{sourceMap:!1,shadowMode:!1})},e700:function(t,a,e){"use strict";var n=e("e1f1"),i=e.n(n);i.a},f3a0:function(t,a,e){"use strict";var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"button flex-set",class:[t.type,t.scale],on:{touchstart:function(a){a=t.$handleEvent(a),t.scale="scale"},touchend:function(a){a=t.$handleEvent(a),t.scale=""}}},[t._t("default")],2)},i=[];e.d(a,"a",function(){return n}),e.d(a,"b",function(){return i})},fb83:function(t,a,e){"use strict";var n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"index-page-container"},[e("v-uni-view",{staticClass:"top-container"},[e("v-uni-view",{staticClass:"left-wrap"},[e("v-uni-text",{staticClass:"iconfont iconfangdajing flex-set"}),e("v-uni-input",{staticClass:"input",attrs:{type:"text",value:t.keywords,placeholder:"搜索爱豆名字"},on:{input:function(a){a=t.$handleEvent(a),t.searchInput(a)}}})],1),t.$app.getData("config").version!=t.$app.VERSION?e("v-uni-view",{staticClass:"right-wrap",on:{click:function(a){a=t.$handleEvent(a),t.$app.goPage("/pages/notice/notice?id=1")}}},[t._v("榜单福利"),e("v-uni-text",{staticClass:"iconfont iconinfo"})],1):t._e()],1),t.topImg.star?e("v-uni-view",{staticClass:"swiper-container",on:{click:function(a){a=t.$handleEvent(a),t.$app.goPage("/pages/index/fengyun")}}},[e("v-uni-image",{staticClass:"img",attrs:{src:t.topImg.star.head_img_l,mode:"aspectFill"}}),e("v-uni-view",{staticClass:"bottom-hold"},[e("v-uni-image",{staticClass:"bg",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9FctOFR9uh4qenFtU5NmMB5TZUvVibQBxK00NyCdWmK7QNRDKdkAn4xFuXtEYgY4ib2gL4dEh0RIyIg/0",mode:""}}),e("v-uni-view",{staticClass:"content position-set flex-set"},[e("v-uni-image",{staticClass:"avatar",attrs:{src:t.topImg.user.avatarurl,mode:""}}),e("v-uni-text",{staticClass:"text-overflow",staticStyle:{"max-width":"250upx"}},[t._v(t._s(t.topImg.user.nickname))]),t._v("为"),e("v-uni-text",[t._v(t._s(t.topImg.star.name))]),t._v("占领了封面")],1)],1)],1):e("v-uni-view",{staticClass:"swiper-container",on:{click:function(a){a=t.$handleEvent(a),t.$app.goPage(t.$app.getData("config").index_banner.url)}}},[e("v-uni-image",{staticClass:"img",attrs:{src:t.$app.getData("config").index_banner.img,mode:"aspectFill"}})],1),e("v-uni-view",{staticClass:"tab-container"},[e("v-uni-view",{staticClass:"left-wrap"},[e("v-uni-view",{staticClass:"tab-item",class:{active:"week_hot"==t.rankField},on:{click:function(a){a=t.$handleEvent(a),t.changeField("week_hot"),t.getSunday()}}},[t._v("周榜")]),t.$app.getData("config").version!=t.$app.VERSION?e("v-uni-view",{staticClass:"tab-item",class:{active:"month_hot_flower"==t.rankField},on:{click:function(a){a=t.$handleEvent(a),t.changeField("month_hot_flower"),t.getLast()}}},[t._v("鲜花月榜")]):t._e(),e("v-uni-view",{staticClass:"tab-item",class:{active:"month_hot_coin"==t.rankField},on:{click:function(a){a=t.$handleEvent(a),t.changeField("month_hot_coin"),t.getLast()}}},[t._v("金豆月榜")]),t.$app.getData("config").version!=t.$app.VERSION&&1==t.$app.getData("config").dashen_rank_switch?e("v-uni-view",{staticClass:"tab-item",on:{click:function(a){a=t.$handleEvent(a),t.$app.goPage("/pages/user/dashen_rank")}}},[t._v("大神榜")]):t._e()],1),e("v-uni-view",{staticClass:"right-wrap",on:{click:function(a){a=t.$handleEvent(a),t.$app.goPage("/pages/index/rank")}}},[t._v("往期榜单"),e("v-uni-text",{staticClass:"iconfont iconicon_workmore"})],1)],1),"fengyun"!=t.rankField?[t.keywords?t._e():e("v-uni-view",{staticClass:"topthree-container"},[e("v-uni-view",{staticClass:"remaintime"},[t._v("本期截止："+t._s(t.cutOffDate))]),e("v-uni-view",{staticClass:"row-info"},[e("v-uni-view",{staticClass:"content",on:{click:function(a){a=t.$handleEvent(a),t.goGroup(t.rankList[1]&&t.rankList[1].star.id)}}},[e("v-uni-view",{staticClass:"avatar-wrap"},[e("v-uni-image",{staticClass:"bg",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Equ3ngUPQiaWPxrVxZhgzk9eJFfnnRCICicicZI1QKbEwDTLpCAqbUlCFhYFp0okTicNRpoZHaxXJXNQ/0",mode:""}}),e("v-uni-image",{staticClass:"avatar position-set",attrs:{src:t.rankList[1]&&t.rankList[1].star.head_img_s,mode:"aspectFill"}})],1),e("v-uni-view",{staticClass:"starname"},[t._v(t._s(t.rankList[1]&&t.rankList[1].star.name))]),e("v-uni-view",{staticClass:"hot"},[e("v-uni-image",{attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERabwYgrRn5cjV3uoOa8BonlDPGMn7icL9icvz43XsbexzcqkCcrTcdZqw/0",mode:""}}),t._v(t._s(t.$app.formatNumber(t.rankList[1]&&t.rankList[1].hot||0)))],1)],1),e("v-uni-view",{staticClass:"content mid",on:{click:function(a){a=t.$handleEvent(a),t.goGroup(t.rankList[0]&&t.rankList[0].star.id)}}},[e("v-uni-view",{staticClass:"avatar-wrap"},[e("v-uni-image",{staticClass:"bg",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Equ3ngUPQiaWPxrVxZhgzk9D0G7NbQic3qWC5phiaopNWhKb9a2IY29hBLbOtqRblDq7kA98uz4GYiaA/0",mode:""}}),e("v-uni-image",{staticClass:"avatar position-set",attrs:{src:t.rankList[0]&&t.rankList[0].star.head_img_s,mode:"aspectFill"}})],1),e("v-uni-view",{staticClass:"starname"},[t._v(t._s(t.rankList[0]&&t.rankList[0].star.name))]),e("v-uni-view",{staticClass:"hot"},[e("v-uni-image",{attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERabwYgrRn5cjV3uoOa8BonlDPGMn7icL9icvz43XsbexzcqkCcrTcdZqw/0",mode:""}}),t._v(t._s(t.$app.formatNumber(t.rankList[0]&&t.rankList[0].hot||0)))],1)],1),e("v-uni-view",{staticClass:"content",on:{click:function(a){a=t.$handleEvent(a),t.goGroup(t.rankList[2]&&t.rankList[2].star.id)}}},[e("v-uni-view",{staticClass:"avatar-wrap"},[e("v-uni-image",{staticClass:"bg",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Equ3ngUPQiaWPxrVxZhgzk9WneYzo7nmBCMjaicxicaSRQKU3xytQClx6t9kuM4HTg5P4YLxNhmhzcw/0",mode:""}}),e("v-uni-image",{staticClass:"avatar position-set",attrs:{src:t.rankList[2]&&t.rankList[2].star.head_img_s,mode:"aspectFill"}})],1),e("v-uni-view",{staticClass:"starname"},[t._v(t._s(t.rankList[2]&&t.rankList[2].star.name))]),e("v-uni-view",{staticClass:"hot"},[e("v-uni-image",{attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERabwYgrRn5cjV3uoOa8BonlDPGMn7icL9icvz43XsbexzcqkCcrTcdZqw/0",mode:""}}),t._v(t._s(t.$app.formatNumber(t.rankList[2]&&t.rankList[2].hot||0)))],1)],1)],1),e("v-uni-image",{staticClass:"three-taijie",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9Equ3ngUPQiaWPxrVxZhgzk9XQawnfQibl4Xfmpv9QndYf6ImwrXWsYWuybIj1drd6FUPQlHG8iaMpOg/0",mode:"aspectFill"}})],1),e("v-uni-view",{staticClass:"rank-list-container"},t._l(t.rankList,function(a,n){return t.keywords||n>2?e("v-uni-view",{key:n,staticClass:"rank-list-item",on:{click:function(e){e=t.$handleEvent(e),t.goGroup(a.star.id)}}},[e("listItemComponent",{attrs:{rank:t.keywords?"":n+1,avatar:a.star.head_img_s},scopedSlots:t._u([{key:"left-container",fn:function(){return[e("v-uni-view",{staticClass:"left-container"},[e("v-uni-view",{staticClass:"star-name"},[t._v(t._s(a.star.name))]),e("v-uni-view",{staticClass:"bottom-text"},[e("v-uni-view",{staticClass:"hot-count"},[t._v(t._s(t.$app.formatNumber(a.hot)))]),e("v-uni-image",{staticClass:"icon-heart",attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9GT2o2aCDJf7rjLOUlbtTERabwYgrRn5cjV3uoOa8BonlDPGMn7icL9icvz43XsbexzcqkCcrTcdZqw/0",mode:""}})],1)],1)]},proxy:!0},{key:"right-container",fn:function(){return[e("v-uni-view",{staticClass:"right-container"},[e("v-uni-view",{staticClass:"btn flex-set"},[t._v("打榜")])],1)]},proxy:!0}],null,!0)})],1):t._e()}),1)]:e("v-uni-view",{staticClass:"rank-list-container"},t._l(t.rankList,function(a,n){return e("v-uni-view",{key:n,staticClass:"rank-list-item-s"},[e("v-uni-view",{staticClass:"num"},[t._v(t._s(n+1))]),e("v-uni-image",{staticClass:"avatar",attrs:{src:a.user.avatarurl,mode:""}}),e("v-uni-view",{staticClass:"content"},[e("v-uni-view",{staticClass:"top"},[e("v-uni-view",{staticClass:"name text-overflow"},[t._v(t._s(a.user.nickname))]),e("v-uni-view",{staticClass:"star flex-set text-overflow"},[t._v(t._s(a.star.name))])],1),e("v-uni-view",{staticClass:"bottom"},[t._v("贡献"+t._s(a.count||""))])],1)],1)}),1),t.$app.getData("config").version!=t.$app.VERSION&&"indexBanner"==t.modal&&t.$app.getData("config").index_open&&t.$app.getData("config").index_open.img?e("v-uni-view",{staticClass:"open-ad-container flex-set"},[e("v-uni-image",{staticClass:"main",attrs:{src:t.$app.getData("config").index_open.img,mode:"aspectFill"},on:{click:function(a){a=t.$handleEvent(a),t.modal="",t.$app.goPage(t.$app.getData("config").index_open.url)}}}),e("v-uni-view",{staticClass:"close-btn flex-set iconfont iconclose",on:{click:function(a){a=t.$handleEvent(a),t.modal=""}}})],1):t._e()],2)},i=[];e.d(a,"a",function(){return n}),e.d(a,"b",function(){return i})}}]);