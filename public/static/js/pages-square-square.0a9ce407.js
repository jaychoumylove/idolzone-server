(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-square-square"],{"21ec":function(t,a,e){"use strict";e.r(a);var i=e("43ab"),n=e("3507");for(var r in n)"default"!==r&&function(t){e.d(a,t,function(){return n[t]})}(r);e("e627");var s=e("2877"),o=Object(s["a"])(n["default"],i["a"],i["b"],!1,null,"58e0bb92",null);a["default"]=o.exports},3507:function(t,a,e){"use strict";e.r(a);var i=e("a664"),n=e.n(i);for(var r in i)"default"!==r&&function(t){e.d(a,t,function(){return i[t]})}(r);a["default"]=n.a},"43ab":function(t,a,e){"use strict";var i=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"square-page-container"},[e("v-uni-view",{staticClass:"top-container",style:{backgroundColor:t.starInfo.square_bg_color||"#856B5C"}},[e("v-uni-view",{staticClass:"content-wrap"},[e("v-uni-view",{staticClass:"starname"},[e("v-uni-view",{staticClass:"name"},[t._v(t._s(t.starInfo.name||""))]),e("v-uni-image",{staticClass:"avatar",attrs:{src:t.starInfo.head_img_s,mode:"aspectFill"}})],1),e("v-uni-view",{staticClass:"score"},[e("v-uni-view",{staticClass:"item"},[e("v-uni-view",{staticClass:"no"},[t._v("NO."+t._s(t.starInfo.star_rank&&t.starInfo.star_rank.week_hot_rank?t.starInfo.star_rank.week_hot_rank:""))]),e("v-uni-view",{staticClass:"tips"},[t._v("明星排行榜>")])],1),e("v-uni-view",{staticClass:"item"},[e("v-uni-view",{staticClass:"no"},[t._v(t._s(t.starInfo.star_rank.week_hot||"")),e("v-uni-text",{staticClass:"wan"},[t._v("万")])],1),e("v-uni-view",{staticClass:"tips"},[t._v("本周人气>")])],1)],1),e("v-uni-view",{staticClass:"bottom-wrap"},[e("v-uni-view",{staticClass:"left flex-set"},[e("v-uni-view",[t._v(t._s(t.starInfo.name||"")+"热搜实时通知")]),e("v-uni-view",{staticClass:"subscribe-btn",class:{sub:1==t.isSub},on:{click:function(a){a=t.$handleEvent(a),t.subscribe(a)}}},[t._v(t._s(1==t.isSub?"已订阅":"马上订阅+"))])],1),e("v-uni-view",{staticClass:"right send-btn",on:{click:function(a){a=t.$handleEvent(a),t.$app.goPage("/pages/group/group")}}},[t._v("为TA打榜")])],1)],1)],1),e("v-uni-view",{staticClass:"article-list-container"},t._l(t.artList,function(a,i){return e("v-uni-view",{key:i,staticClass:"article-item"},[e("v-uni-view",{staticClass:"top-wrap"},[e("v-uni-view",{staticClass:"left flex-set"},[e("v-uni-view",{staticClass:"dot",class:{red:i%3==0,yellow:i%3==1,green:i%3==2}}),e("v-uni-view",{staticClass:"avatar"},[e("v-uni-image",{attrs:{src:a.avatarurl,mode:"aspectFill"}})],1),e("v-uni-view",{staticClass:"text"},[e("v-uni-view",{staticClass:"name"},[t._v(t._s(a.nickname))]),e("v-uni-view",{staticClass:"tips"},[t._v(t._s(a.tips))])],1)],1),t.$app.getData("userStar").captain?e("v-uni-view",{staticClass:"right",on:{click:function(e){e=t.$handleEvent(e),t.deleteArticle(a.id)}}},[e("v-uni-view",{staticClass:"iconfont iconclose"})],1):t._e()],1),e("v-uni-view",{staticClass:"content-wrap transmit"},[e("v-uni-rich-text",{attrs:{nodes:a.transmit_title}})],1),e("v-uni-view",{staticClass:"content-wrap"},[e("v-uni-rich-text",{attrs:{nodes:a.title}}),a.imgs?e("v-uni-view",{staticClass:"imgs"},t._l(a.imgs,function(i,n){return e("v-uni-view",{key:n,staticClass:"img-wrap"},[e("v-uni-image",{class:{l:1==a.imgs.length||2==a.imgs.length||4==a.imgs.length},attrs:{"lazy-load":"",src:i,mode:"aspectFill"},on:{click:function(e){e.stopPropagation(),e=t.$handleEvent(e),t.preImgs(a.imgs,n)}}}),~i.indexOf(".gif")?e("v-uni-view",{staticClass:"extra-icon"},[t._v("动图")]):t._e()],1)}),1):t._e(),a.video&&~a.video.indexOf("http")?e("v-uni-video",{staticClass:"video",attrs:{id:"video_"+a.id,src:a.video},on:{play:function(a){a=t.$handleEvent(a),t.videoPlay(a)},error:function(e){e=t.$handleEvent(e),t.videoError(a.id,i)}}}):t._e()],1)],1)}),1)],1)},n=[];e.d(a,"a",function(){return i}),e.d(a,"b",function(){return n})},"673e":function(t,a,e){"use strict";e("386b")("sub",function(t){return function(){return t(this,"sub","","")}})},a3c2:function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,'.square-page-container .top-container[data-v-58e0bb92]{height:%?300?%;position:relative;color:#fff}.square-page-container .top-container .star-img[data-v-58e0bb92]{position:absolute}.square-page-container .top-container .content-wrap[data-v-58e0bb92]{height:100%;padding:0 %?40?%;position:relative;z-index:1}.square-page-container .top-container .content-wrap .starname[data-v-58e0bb92]{font-size:%?42?%;padding-top:%?30?%;margin-bottom:%?-125?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:start;-webkit-align-items:flex-start;-ms-flex-align:start;align-items:flex-start;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between}.square-page-container .top-container .content-wrap .starname .avatar[data-v-58e0bb92]{width:%?160?%;height:%?160?%;border-radius:50%;margin-right:%?40?%;border:%?4?% solid #fff}.square-page-container .top-container .content-wrap .score[data-v-58e0bb92]{padding-top:%?45?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex}.square-page-container .top-container .content-wrap .score .item[data-v-58e0bb92]{margin-right:%?50?%}.square-page-container .top-container .content-wrap .score .item .no[data-v-58e0bb92]{font-family:Impact;font-size:%?44?%;line-height:1.1}.square-page-container .top-container .content-wrap .score .item .no .wan[data-v-58e0bb92]{font-size:%?28?%;margin:0 %?5?%}.square-page-container .top-container .content-wrap .score .item .tips[data-v-58e0bb92]{font-size:%?24?%}.square-page-container .top-container .content-wrap .bottom-wrap[data-v-58e0bb92]{padding-top:%?32?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;font-size:%?24?%}.square-page-container .top-container .content-wrap .bottom-wrap .subscribe-btn[data-v-58e0bb92]{background-color:#fbcc3e;color:#333;border-radius:%?20?%;padding:0 %?20?%;margin:0 %?20?%}.square-page-container .top-container .content-wrap .bottom-wrap .subscribe-btn.sub[data-v-58e0bb92]{background-color:#fff9e5}.square-page-container .top-container .content-wrap .bottom-wrap .send-btn[data-v-58e0bb92]{background-color:#fff9e5;color:#333;border-radius:%?20?%;padding:0 %?20?%}.square-page-container .tab-wrap[data-v-58e0bb92]{padding:%?25?% %?40?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:end;-webkit-align-items:flex-end;-ms-flex-align:end;align-items:flex-end;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;font-size:%?32?%}.square-page-container .tab-wrap .item[data-v-58e0bb92]{position:relative;line-height:1.2;padding:0 %?20?%}.square-page-container .tab-wrap .item.active[data-v-58e0bb92]{font-size:%?42?%;font-weight:700}.square-page-container .tab-wrap .item.active[data-v-58e0bb92]:after{content:"";position:absolute;bottom:0;height:%?18?%;width:100%;left:50%;-webkit-transform:translateX(-50%);-ms-transform:translateX(-50%);transform:translateX(-50%);border-radius:%?20?%;background-color:#ffd75e;z-index:-1}.square-page-container .article-list-container[data-v-58e0bb92]{padding:%?20?% %?40?%}.square-page-container .article-list-container .article-item[data-v-58e0bb92]{margin-bottom:%?20?%}.square-page-container .article-list-container .article-item .top-wrap[data-v-58e0bb92]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between}.square-page-container .article-list-container .article-item .top-wrap .dot[data-v-58e0bb92]{width:%?16?%;height:%?16?%;border-radius:50%;margin-right:%?14?%}.square-page-container .article-list-container .article-item .top-wrap .dot.red[data-v-58e0bb92]{background-color:#db4437}.square-page-container .article-list-container .article-item .top-wrap .dot.yellow[data-v-58e0bb92]{background-color:#ffcd40}.square-page-container .article-list-container .article-item .top-wrap .dot.green[data-v-58e0bb92]{background-color:#0f9d58}.square-page-container .article-list-container .article-item .top-wrap .avatar[data-v-58e0bb92]{border-radius:50%;overflow:hidden;width:%?70?%;height:%?70?%;z-index:1}.square-page-container .article-list-container .article-item .top-wrap .text[data-v-58e0bb92]{padding:0 %?10?%;line-height:1.3}.square-page-container .article-list-container .article-item .top-wrap .text .name[data-v-58e0bb92]{font-weight:700;padding-top:%?16?%}.square-page-container .article-list-container .article-item .top-wrap .text .tips[data-v-58e0bb92]{font-size:%?24?%;color:#a8a8a8}.square-page-container .article-list-container .article-item .top-wrap .icon[data-v-58e0bb92]{font-size:%?20?%;padding:0 %?5?%;border-radius:%?2?%;color:#fff;background-color:#f3b90b}.square-page-container .article-list-container .article-item .top-wrap .right .iconfont[data-v-58e0bb92]{color:#999}.square-page-container .article-list-container .article-item .content-wrap[data-v-58e0bb92]{background-color:#fffbf8;padding:%?10?% %?30?% %?10?% %?30?%;margin:%?10?% 0;border-radius:%?20?%}.square-page-container .article-list-container .article-item .content-wrap .imgs[data-v-58e0bb92]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-flex-wrap:wrap;-ms-flex-wrap:wrap;flex-wrap:wrap;margin:%?4?% 0}.square-page-container .article-list-container .article-item .content-wrap .imgs .img-wrap[data-v-58e0bb92]{position:relative;margin:%?4?%}.square-page-container .article-list-container .article-item .content-wrap .imgs .img-wrap uni-image[data-v-58e0bb92]{width:%?190?%;height:%?190?%;border-radius:%?4?%}.square-page-container .article-list-container .article-item .content-wrap .imgs .img-wrap uni-image.l[data-v-58e0bb92]{width:%?200?%;height:%?200?%}.square-page-container .article-list-container .article-item .content-wrap .imgs .img-wrap .extra-icon[data-v-58e0bb92]{position:absolute;background-color:rgba(33,163,241,.8);bottom:0;right:0;color:#fff;font-size:%?22?%;padding:0 %?8?%;border-radius:%?5?%}.square-page-container .article-list-container .article-item .content-wrap .video[data-v-58e0bb92]{width:100%;height:%?280?%}.square-page-container .article-list-container .article-item .content-wrap .date[data-v-58e0bb92]{font-size:%?20?%;color:#999}.square-page-container .article-list-container .article-item .content-wrap.transmit[data-v-58e0bb92]{background-color:#fff;padding:0 %?60?% 0 %?110?%}.square-page-container .article-list-container .article-item.hide[data-v-58e0bb92]{-webkit-animation:hide-data-v-58e0bb92 1s forwards;animation:hide-data-v-58e0bb92 1s forwards}@-webkit-keyframes hide-data-v-58e0bb92{50%{-webkit-transform:translateX(%?-750?%);transform:translateX(%?-750?%)}to{-webkit-transform:translateX(%?-750?%);transform:translateX(%?-750?%);height:0}}@keyframes hide-data-v-58e0bb92{50%{-webkit-transform:translateX(%?-750?%);transform:translateX(%?-750?%)}to{-webkit-transform:translateX(%?-750?%);transform:translateX(%?-750?%);height:0}}',""])},a664:function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0,e("a481"),e("673e");var i={data:function(){return{tabActive:0,page:1,artList:[],starInfo:{},isSub:0}},onLoad:function(t){this.video_id=""},onShow:function(){var t=this;this.$app.getData("userStar").id?!this.loadSuccess&&this.loadData():uni.showModal({content:"请先加入一个圈子",confirmText:"去加圈子",showCancel:!1,success:function(a){a.confirm&&t.$app.goPage("/pages/group/group")}})},onShareAppMessage:function(){return this.$app.commonShareAppMessage()},onPullDownRefresh:function(){this.page=1,this.loadData()},onReachBottom:function(){this.page++,this.loadData()},methods:{deleteArticle:function(t){var a=this;this.$app.modal("是否删除该文章",function(){a.$app.request("article/delete",{id:t},function(e){for(var i in a.$app.toast("删除成功"),a.artList)a.artList[i].id==t&&a.artList.splice(i,1)},"POST",!0)})},videoPlay:function(t){t.currentTarget.id!=this.video_id&&(uni.createVideoContext(this.video_id).pause(),this.video_id=t.currentTarget.id)},videoError:function(t,a){var e=this;this.$app.request("article/refrashVideo",{id:t},function(t){e.artList[a].video=t.data})},subscribe:function(){var t=this;this.$app.request("article/subscribe",{},function(a){t.isSub=a.data.sub,1==a.data.sub&&t.$app.toast("订阅成功","success")},"POST",!0)},preImgs:function(t,a){var e=[].concat(t);for(var i in e)e[i]=e[i].replace("/small/","/large/");uni.previewImage({urls:e,current:a})},loadData:function(){var t=this;this.$app.request("page/square",{star_id:this.$app.getData("userStar").id,page:this.page,size:3},function(a){t.loadSuccess=!0,uni.stopPullDownRefresh();var e=a.data.article;1==t.page?t.artList=e:t.artList=t.artList.concat(e),t.isSub=a.data.subscribe,a.data.starInfo.star_rank.week_hot=(a.data.starInfo.star_rank.week_hot/1e4).toFixed(1),t.starInfo=a.data.starInfo,uni.setNavigationBarColor({backgroundColor:t.starInfo.square_bg_color||"#856B5C",frontColor:"#ffffff"})})}}};a.default=i},a6fe:function(t,a,e){var i=e("a3c2");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=e("4f06").default;n("334881b2",i,!0,{sourceMap:!1,shadowMode:!1})},e627:function(t,a,e){"use strict";var i=e("a6fe"),n=e.n(i);n.a}}]);