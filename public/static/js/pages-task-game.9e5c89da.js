(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-task-game"],{"123a":function(t,a,e){"use strict";e.r(a);var i=e("f561"),n=e("ca21");for(var o in n)"default"!==o&&function(t){e.d(a,t,function(){return n[t]})}(o);e("7f2c");var s,d=e("f0c5"),r=Object(d["a"])(n["default"],i["b"],i["c"],!1,null,"ead48e8c",null,!1,i["a"],s);a["default"]=r.exports},"7f2c":function(t,a,e){"use strict";var i=e("f447"),n=e.n(i);n.a},8374:function(t,a,e){"use strict";var i=e("288e");Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n=i(e("0a0d")),o=15,s={data:function(){return{list:[]}},onLoad:function(t){this.$app.getData("userExt").totalCount>=this.$app.getData("config").game_switch.min_count?this.type=0:this.type=1,this.loadData(),0==this.type&&this.$app.toast("请点击游戏进行试玩"+o+"秒以上","none",3e3)},onShow:function(){var t=this;this.openTime&&0==this.type&&((0,n.default)()-this.openTime>=1e3*o?this.$app.request(this.$app.API.TASK_SETTLE,{task_id:20},function(a){var e="领取成功";a.data.coin&&(e+="，金豆+"+a.data.coin),a.data.flower&&(e+="，鲜花+"+a.data.flower),a.data.stone&&(e+="，钻石+"+a.data.stone),a.data.trumpet&&(e+="，喇叭+"+a.data.trumpet),t.$app.toast(e);var i=t.$app.getData("gameCheck")||{};i[t.openAdId]=(new Date).toLocaleDateString(),t.$app.setData("gameCheck",i),t.loadData()}):this.$app.toast("未完成游戏试玩，请试玩至少"+o+"秒"),this.openTime=0)},methods:{openGame:function(t){this.openTime=(0,n.default)(),this.openAdId=t.id,0!=t.type&&(t.appid?uni.navigateToMiniProgram({appId:t.appid,path:t.path}):t.img_l&&uni.previewImage({urls:[t.img_l]}))},loadData:function(){var t=this;this.$app.request("page/game",{type:this.type},function(a){var e=t.$app.getData("gameCheck"),i=[];for(var n in a.data){var o=a.data[n];e[o.id]==(new Date).toLocaleDateString()&&1!=t.type||i.push(o)}t.list=i})}}};a.default=s},acfc:function(t,a,e){a=t.exports=e("2350")(!1),a.push([t.i,".game-container[data-v-ead48e8c]{padding:%?20?%}.game-container .item-wrap[data-v-ead48e8c]{margin-bottom:%?20?%;padding:%?20?%;background-color:hsla(0,0%,100%,.3);display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.game-container .item-wrap .img[data-v-ead48e8c]{width:%?120?%;height:%?120?%;border-radius:50%}.game-container .item-wrap .text[data-v-ead48e8c]{width:%?345?%;height:100%;margin:0 %?20?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between}.game-container .item-wrap .text .big[data-v-ead48e8c]{font-size:%?40?%}.game-container .item-wrap .btn[data-v-ead48e8c]{background:-webkit-linear-gradient(top,#ff7f94,#ff6684);background:linear-gradient(180deg,#ff7f94,#ff6684);border-radius:%?10?%;color:#fff;text-align:center;width:%?140?%;height:%?70?%}.game-container .item-wrap.ad[data-v-ead48e8c]{padding:0}",""])},ca21:function(t,a,e){"use strict";e.r(a);var i=e("8374"),n=e.n(i);for(var o in i)"default"!==o&&function(t){e.d(a,t,function(){return i[t]})}(o);a["default"]=n.a},f447:function(t,a,e){var i=e("acfc");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=e("4f06").default;n("d3b22e56",i,!0,{sourceMap:!1,shadowMode:!1})},f561:function(t,a,e){"use strict";var i,n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"game-container"},[t._l(t.list,function(a,i){return[0==a.type?e("v-uni-view",{key:i+"_0",staticClass:"item-wrap ad",on:{"!tap":function(e){arguments[0]=e=t.$handleEvent(e),t.openGame(a)}}},[e("v-uni-ad",{attrs:{"unit-id":a.appid}})],1):6==a.type?e("v-uni-view",{staticClass:"item-wrap ad",on:{"!tap":function(e){arguments[0]=e=t.$handleEvent(e),t.openGame(a)}}},[e("v-uni-ad",{attrs:{"unit-id":a.appid,"ad-type":"grid","grid-opacity":"0.8","grid-count":"5","ad-theme":"white"}})],1):7==a.type?e("v-uni-view",{staticClass:"item-wrap ad",on:{"!tap":function(e){arguments[0]=e=t.$handleEvent(e),t.openGame(a)}}},[e("v-uni-ad",{attrs:{"unit-id":a.appid,type:"card"}})],1):8==a.type?e("v-uni-view",{staticClass:"item-wrap ad",on:{"!tap":function(e){arguments[0]=e=t.$handleEvent(e),t.openGame(a)}}},[e("v-uni-ad",{attrs:{"unit-id":a.appid,type:"feeds"}})],1):e("v-uni-view",{staticClass:"item-wrap"},[e("v-uni-image",{staticClass:"img",attrs:{src:a.img_s,mode:"aspectFill"}}),e("v-uni-view",{staticClass:"text"},[e("v-uni-view",{staticClass:"big text-overflow"},[t._v(t._s(a.name))]),e("v-uni-view",{staticClass:"small text-overflow"},[t._v(t._s(a.desc))])],1),e("v-uni-view",{staticClass:"btn flex-set",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.openGame(a)}}},[t._v(t._s(a.button))])],1)]})],2)},o=[];e.d(a,"b",function(){return n}),e.d(a,"c",function(){return o}),e.d(a,"a",function(){return i})}}]);