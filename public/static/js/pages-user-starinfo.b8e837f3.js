(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-starinfo"],{"0b49":function(t,a,e){var i=e("9fd5");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=e("4f06").default;n("3c8da774",i,!0,{sourceMap:!1,shadowMode:!1})},"3f67":function(t,a,e){"use strict";var i=e("0b49"),n=e.n(i);n.a},5172:function(t,a,e){"use strict";var i,n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"starinfo-page-container"},[e("v-uni-view",{staticClass:"title"},[t._v("爱豆信息直接修改（无需审核），每次修改一项"),e("br"),t._v("图片尺寸务必按要求提交"),e("br"),t._v("提交之前请征求大多数粉丝的意见"),e("br"),t._v("恶意提交图片经粉丝举报，将撤销领袖粉")]),e("v-uni-view",{staticClass:"item",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.chooseImg("head_img_s","头像")}}},[e("v-uni-view",{staticClass:"left"},[t._v("头像")]),e("v-uni-image",{staticClass:"right avatar",attrs:{mode:"aspectFill",src:t.starInfo.head_img_s}}),e("v-uni-view",{staticClass:"tips"},[t._v("建议尺寸：120x120")])],1),e("v-uni-view",{staticClass:"item",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.chooseImg("open_img","开屏图")}}},[e("v-uni-view",{staticClass:"left"},[t._v("开屏图")]),e("v-uni-image",{staticClass:"right open-img",attrs:{src:t.starInfo.open_img,mode:"aspectFill"}}),e("v-uni-view",{staticClass:"tips"},[t._v("建议尺寸：750x1450")])],1),e("v-uni-view",{staticClass:"item",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.chooseImg("share_img","分享海报")}}},[e("v-uni-view",{staticClass:"left"},[t._v("分享海报")]),e("v-uni-image",{staticClass:"right share-img",attrs:{mode:"aspectFill",src:t.starInfo.share_img}}),e("v-uni-view",{staticClass:"tips"},[t._v("建议尺寸：500x400")])],1),e("v-uni-view",{staticClass:"item",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.chooseImg("head_img_l","首页banner")}}},[e("v-uni-view",{staticClass:"left"},[t._v("首页banner")]),e("v-uni-image",{staticClass:"right index-banner",attrs:{mode:"aspectFill",src:t.starInfo.head_img_l}}),e("v-uni-view",{staticClass:"tips"},[t._v("建议尺寸：690x250")])],1),t.targetText?e("v-uni-view",{staticClass:"btn-wrap flex-set",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.submit.apply(void 0,arguments)}}},[e("btnComponent",{attrs:{type:"default"}},[e("v-uni-view",{staticClass:"btn-inner"},[t._v("修改"+t._s(t.targetText))])],1)],1):t._e()],1)},s=[];e.d(a,"b",function(){return n}),e.d(a,"c",function(){return s}),e.d(a,"a",function(){return i})},"7d07":function(t,a,e){"use strict";e.r(a);var i=e("e8d6"),n=e.n(i);for(var s in i)"default"!==s&&function(t){e.d(a,t,function(){return i[t]})}(s);a["default"]=n.a},"9fd5":function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".starinfo-page-container .title[data-v-527b4764]{background-color:#fcf2f1;padding:%?30?%;font-size:%?30?%;color:#686868}.starinfo-page-container .item[data-v-527b4764]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;align-items:center;padding:%?20?%;margin:0 %?20?%;border-bottom:1px solid #eee;min-height:%?120?%;position:relative}.starinfo-page-container .item .right[data-v-527b4764]{box-shadow:0 %?2?% %?8?% hsla(0,0%,40%,.3)}.starinfo-page-container .item .tips[data-v-527b4764]{position:absolute;bottom:%?16?%;font-size:%?26?%;color:#bbb;width:%?400?%}.starinfo-page-container .item .avatar[data-v-527b4764]{width:%?120?%;height:%?120?%;border-radius:50%}.starinfo-page-container .item .open-img[data-v-527b4764]{width:%?180?%;height:%?348?%}.starinfo-page-container .item .share-img[data-v-527b4764]{width:%?250?%;height:%?200?%}.starinfo-page-container .item .index-banner[data-v-527b4764]{width:%?345?%;height:%?125?%}.starinfo-page-container .btn-wrap[data-v-527b4764]{margin:%?40?% auto %?80?%}.starinfo-page-container .btn-wrap .btn-inner[data-v-527b4764]{padding:%?10?% %?40?%;font-size:%?32?%}",""])},c5bb:function(t,a,e){"use strict";e.r(a);var i=e("5172"),n=e("7d07");for(var s in n)"default"!==s&&function(t){e.d(a,t,function(){return n[t]})}(s);e("3f67");var o,r=e("f0c5"),c=Object(r["a"])(n["default"],i["b"],i["c"],!1,null,"527b4764",null,!1,i["a"],o);a["default"]=c.exports},e8d6:function(t,a,e){"use strict";var i=e("288e");Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n=i(e("f741")),s=i(e("961a")),o=i(e("bda9")),r={components:{badgeComponent:n.default,modalComponent:o.default,btnComponent:s.default},data:function(){return this.postData={},{starInfo:this.$app.getData("userStar"),targetText:""}},onLoad:function(){},onShow:function(){},onShareAppMessage:function(t){var a=t.target&&t.target.dataset.share;return this.$app.commonShareAppMessage(a)},methods:{submit:function(){var t=this;this.$app.request("star/editimg",this.postData,function(a){a.code?t.$app.toast("修改失败","none"):(t.$app.setData("userStar",a.data.userStar),t.$app.toast("修改成功","success"))},"POST",!0)},chooseImg:function(t,a){var e=this;uni.chooseImage({count:1,success:function(i){var n=i.tempFiles[0];n.size>2097152?e.$app.toast("图片过大，请上传2M以下的图片"):e.$app.upload(n.path,function(i){e.$set(e.starInfo,t,i[0]),e.targetText=a,e.postData.key=t,e.postData.value=i[0]})}})}}};a.default=r}}]);