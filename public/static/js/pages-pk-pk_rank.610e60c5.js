(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-pk-pk_rank"],{"0e53":function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"rank-page-container"},[a("v-uni-view",{staticClass:"tab-container"},[a("v-uni-view",{staticClass:"tab-item",class:{active:0==t.current},on:{click:function(e){e=t.$handleEvent(e),t.current=0}}},[t._v("钻石争夺战")]),a("v-uni-view",{staticClass:"tab-item",class:{active:1==t.current},on:{click:function(e){e=t.$handleEvent(e),t.current=1}}},[t._v("鲜花争夺战")])],1),a("v-uni-swiper",{staticClass:"swiper",attrs:{current:t.current},on:{change:function(e){e=t.$handleEvent(e),t.swiperChange(e)}}},[t._l(2,function(e){return[a("v-uni-swiper-item",[0==t.pkStatus||t.pkTime?t._e():a("v-uni-view",{staticClass:"explain-long"},[a("v-uni-view",{},[t._v(t._s(1==t.pkStatus?"报名":"当前")+"场次："+t._s(t.timeSpace.start_time)+"-"+t._s(t.timeSpace.end_time))]),a("v-uni-view",{},[t._v(t._s(1==t.pkStatus?"开场倒计时":"距本场结束")+"："+t._s(t.timeLeft))])],1),t.pkTime?a("v-uni-view",{staticClass:"explain-long"},[a("v-uni-view",{},[t._v("场次："+t._s(t.yestoday?"昨天":"今天")+t._s(t.showTime)+"（已结束）")])],1):t._e(),a("v-uni-scroll-view",{staticClass:"rankscrollbox",attrs:{"scroll-y":""},on:{scrolltolower:function(e){e=t.$handleEvent(e),t.lower(e)}}},[a("v-uni-view",{staticClass:"rank-box"},[a("v-uni-view",{staticClass:"week-rank"},[1==t.pkStatus?a("v-uni-view",{staticClass:"bi-title"},[t._v("已有"),a("v-uni-text",{},[t._v(t._s(t.joinNum))]),t._v("人报名")],1):t._e(),a("v-uni-view",{staticClass:"flex-set"},[a("v-uni-button",{staticClass:"invit-btn",attrs:{"open-type":"share"}},[t._v("召集好友一起参加团战")])],1),t._l(t.list,function(e,i){return[a("v-uni-view",{key:i+"_0",staticClass:"rank-item",attrs:{"data-mid":e.star_id,"data-page":"/pages/pk/pk_rank_user?starname="+e.name+"&current="+t.current+"&pkTime="+t.pkTime+"&mid="+e.star_id+"&yestoday="+t.yestoday},on:{click:function(e){e=t.$handleEvent(e),t.goPage(e)}}},[a("v-uni-text",{staticClass:"index1"},[t._v(t._s(2==t.pkStatus?i+1:""))]),a("v-uni-view",{staticClass:"head-img"},[a("v-uni-image",{attrs:{src:e.avatarurl?e.avatarurl:this.AVATAR}}),2==t.pkStatus&&i<3?a("v-uni-image",{staticClass:"s",attrs:{src:"/static/image/rank/p"+(i+1)+".png"}}):t._e()],1),a("v-uni-view",{staticClass:"visiting-card"},[a("v-uni-view",{staticClass:"nickname"},[a("v-uni-view",{staticClass:"nickname-text"},[t._v(t._s(e.name?e.name:"神秘粉丝"))]),e.adm?a("v-uni-view",{staticClass:"level ling"},[a("v-uni-image",{attrs:{src:"/image/ling.png"}})],1):t._e(),e.level?a("v-uni-image",{staticClass:"level",attrs:{src:"/static/image/user_level/lv"+e.level+".png"}}):t._e()],1),a("v-uni-view",{staticClass:"flower"},[1==t.pkStatus?[t._v("报名参加了本场团战")]:t._e(),2==t.pkStatus?[a("v-uni-text",{},[t._v("本场人气")]),a("v-uni-text",{staticClass:"color"},[t._v(t._s(e.hot))]),2==t.pkStatus?a("v-uni-image",{attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9F3NAxlopF2oyvfuiaEjgJItws1tcmzFFLo4WGc38l7kibxxk1atGAcjALuqvyvLib3icFPyAicbsOOl3g/0"}}):t._e()]:t._e()],2)],1),t.mymid==e.star_id&&2==t.pkStatus?a("v-uni-view",{staticClass:"right-btn"},[t._v("粉丝贡献")]):t._e(),1!=t.pkStatus||!t.captain&&e.uid!=t.userInfo.id?t._e():a("v-uni-view",{staticClass:"out iconfont iconclose",on:{click:function(a){a=t.$handleEvent(a),t.out(e,i)}}})],1)]})],2)],1)],1)],1)]})],2)],1)},n=[];a.d(e,"a",function(){return i}),a.d(e,"b",function(){return n})},"2aeb7":function(t,e,a){"use strict";a.r(e);var i=a("86fa"),n=a.n(i);for(var s in i)"default"!==s&&function(t){a.d(e,t,function(){return i[t]})}(s);e["default"]=n.a},"3c9b":function(t,e,a){var i=a("b82b");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=a("4f06").default;n("17eb3934",i,!0,{sourceMap:!1,shadowMode:!1})},"7ede":function(t,e,a){"use strict";a.r(e);var i=a("0e53"),n=a("2aeb7");for(var s in n)"default"!==s&&function(t){a.d(e,t,function(){return n[t]})}(s);a("9766");var r=a("2877"),o=Object(r["a"])(n["default"],i["a"],i["b"],!1,null,"791c9a56",null);e["default"]=o.exports},"86fa":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,a("7f7f");var i={data:function(){return{starid:this.$app.getData("userStar").id,pkTimeList:[],curPkTime:{},myJoinType:"",current:0,rankList:[],tabList:[{name:"钻石争夺战"},{name:"鲜花争夺战"}],pkStatus:0,timeSpace:{},list:[],pkTime:"",mymid:this.$app.getData("userStar").id,showTime:"",yestoday:0,joinNum:0,timeLeft:"",userInfo:this.$app.getData("userInfo"),captain:this.$app.getData("userStar").captain}},onShareAppMessage:function(t){var e=t.target&&t.target.dataset.share;return this.$app.commonShareAppMessage(e)},onLoad:function(t){console.log(t),this.pkTime=t.pkTime||"",this.showTime=t.time||"",this.yestoday=t.yestoday||0,this.page=1,t.current&&0!=t.current?this.current=t.current:this.loadData()},methods:{out:function(t,e){var a=this;this.$app.modal("将".concat(t.name,"移出本次团战?"),function(){a.$app.request("rank/pk_out",{mid:a.starid,uid:t.uid},function(t){a.$app.toast(t.msg),a.list.splice(e,1)})})},lower:function(){console.log(11),this.page++,this.page>10||this.loadData()},goPage:function(t){2==this.pkStatus&&(t.currentTarget.dataset.mid!=this.$app.getData("userStar").id&&1!=this.sAdm||this.$app.goPage(t.currentTarget.dataset.page))},swiperChange:function(t){this.current=t.detail.current,this.page=1,this.rankList=[],this.loadData()},loadData:function(){var t=this;this.$app.request("rank/pk",{page:this.page,type:this.current||0,mid:this.$app.getData("userStar")["id"],pkTime:this.pkTime||"",yestoday:this.yestoday||0},function(e){var a=e.data;t.pkTime&&(a.status=2),1==t.page?1==a.status?t.list=a.userList:2==a.status&&(t.list=a.starList):1==a.status?t.list=t.list.concat(a.userList):2==a.status&&(t.list=t.list.concat(a.starList)),t.pkStatus=a.status,t.timeSpace=a.timeSpace||null,t.isJoin=a.isJoin||0,t.joinNum=a.joinNum||0,t.adm=a.isAdm||0,t.sAdm=a.sAdm||0,t.uid=a.uid;var i=t.$app.timeGethms(a.timeLeft);t.timeLeft=i.hour+"小时"+i.min+"分"+i.sec+"秒",clearInterval(t.timeId),t.timeId=setInterval(function(){var e=t.$app.timeGethms(--a.timeLeft);t.timeLeft=e.hour+"小时"+e.min+"分"+e.sec+"秒",a.timeLeft<=0&&clearInterval(t.timeId)},1e3)})}}};e.default=i},9766:function(t,e,a){"use strict";var i=a("3c9b"),n=a.n(i);n.a},b82b:function(t,e,a){e=t.exports=a("2350")(!1),e.push([t.i,".rank-page-container[data-v-791c9a56]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;height:100%}.rank-page-container .tab-container[data-v-791c9a56]{padding:%?25?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-justify-content:space-around;-ms-flex-pack:distribute;justify-content:space-around;border-bottom:%?1?% solid #eee}.rank-page-container .tab-container .tab-item[data-v-791c9a56]{border-radius:%?32?%;border:%?1?% solid #ff7e00;padding:%?10?% %?30?%;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;font-size:%?30?%;margin:0 %?20?%;color:#ff7e00}.rank-page-container .tab-container .tab-item.active[data-v-791c9a56]{background-color:#ff7e00;text-align:center;color:#fff}uni-page-body[data-v-791c9a56]{background-color:#fff;height:100%;overflow:hidden}.title[data-v-791c9a56]{padding:%?25?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-justify-content:space-around;-ms-flex-pack:distribute;justify-content:space-around;border-bottom:%?1?% solid #eee}.tab-active[data-v-791c9a56],.tab-nomal[data-v-791c9a56]{border-radius:%?32?%;border:%?1?% solid #ff7e00;padding:%?10?% %?20?%;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;font-size:%?30?%}.tab-active[data-v-791c9a56]{background-color:#ff7e00;text-align:center;color:#fff}.tab-nomal[data-v-791c9a56]{color:#ff7e00}.all-rank[data-v-791c9a56]{height:100%;width:100%;-webkit-transform:translateX(750px);-ms-transform:translateX(750px);transform:translateX(750px)}.week-rank[data-v-791c9a56]{height:100%;width:100%\n  /* position: absolute;\n  top:0;\n  left:0;\n  transform: translateX(-750px); */}.rank-item[data-v-791c9a56]{height:%?140?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;font-size:%?32?%;color:#323232;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;padding:%?6?% %?35?%}.rank-item .index1[data-v-791c9a56]{margin:0 %?20?%;font-size:%?34?%;color:#686868}.pai[data-v-791c9a56]{width:%?55?%;height:%?55?%}.rank-item .index2[data-v-791c9a56]{color:#f78da6}.rank-item .index3[data-v-791c9a56]{color:#ba8cf7}.rank-item .index4[data-v-791c9a56]{color:#9dc0ee}.visiting-card .index4[data-v-791c9a56]{margin-right:%?20?%}.rank-item .index5[data-v-791c9a56]{color:#94edb5}.active.rank-item[data-v-791c9a56]{background-color:#fff}.head-img[data-v-791c9a56]{width:%?111?%;height:%?107?%;position:relative;top:%?6?%}.head-img uni-image[data-v-791c9a56]{width:%?95?%;height:%?95?%;border-radius:50%}.head-img uni-image.s[data-v-791c9a56]{width:%?58?%;height:%?58?%;position:absolute;right:0;bottom:%?-2?%}.visiting-card[data-v-791c9a56]{line-height:150%}.my-rank .visiting-card[data-v-791c9a56]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;-ms-flex-direction:row;flex-direction:row;-webkit-box-pack:start;-webkit-justify-content:flex-start;-ms-flex-pack:start;justify-content:flex-start;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.visiting-card .nickname[data-v-791c9a56]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.visiting-card .level[data-v-791c9a56]{width:%?30?%;height:%?30?%}.visiting-card .flower[data-v-791c9a56]{color:#686868;font-size:%?24?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center}.visiting-card .share[data-v-791c9a56]{font-size:%?22?%;width:%?334?%}.flower uni-image[data-v-791c9a56]{width:%?30?%;height:%?30?%;vertical-align:middle;margin-right:%?6?%;margin-left:%?6?%}.my-rank[data-v-791c9a56]{background-color:#fff;border-top:%?1?% solid #eee;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;-ms-flex-direction:row;flex-direction:row;-webkit-justify-content:space-around;-ms-flex-pack:distribute;justify-content:space-around;height:auto;bottom:0;position:fixed}.swiper[data-v-791c9a56]{-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1}.rankscrollbox[data-v-791c9a56]{height:100%}.rank-box[data-v-791c9a56]{padding-bottom:%?100?%}.starname[data-v-791c9a56]{background:-webkit-linear-gradient(#ff7e00,#fccd9f);color:#fff;padding:0 %?12?%;border-radius:%?12?%;font-size:%?20?%;-webkit-box-shadow:0 0 1px rgba(0,0,0,.3);box-shadow:0 0 1px rgba(0,0,0,.3);line-height:%?34?%}.nickname-text[data-v-791c9a56]{white-space:nowrap;overflow:hidden;-o-text-overflow:ellipsis;text-overflow:ellipsis;max-width:%?320?%}.nickname-text.self[data-v-791c9a56]{max-width:%?250?%}.explain-long[data-v-791c9a56]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-justify-content:space-around;-ms-flex-pack:distribute;justify-content:space-around;font-size:%?26?%;padding:%?20?%}.explain-row[data-v-791c9a56]{position:relative;margin:%?10?% 0}.explain-row .explain[data-v-791c9a56]{position:absolute;right:%?50?%;top:50%;-webkit-transform:translateY(-50%);-ms-transform:translateY(-50%);transform:translateY(-50%);font-size:%?20?%}.give-btn[data-v-791c9a56]{width:%?160?%;height:%?65?%;margin:auto;background:#ff7e00;border-radius:%?50?%;text-align:center;line-height:%?65?%;color:#fff;font-size:%?28?%}.rank-item .right-btn[data-v-791c9a56]{position:absolute;right:%?80?%;border-bottom:%?2?% solid #ff7e00;color:#ff7e00;padding:%?10?% %?20?%}.bi-title[data-v-791c9a56]{text-align:center;font-size:%?26?%}.bi-title uni-text[data-v-791c9a56]{color:#ff7e00}.rank-item .out[data-v-791c9a56]{position:absolute;right:%?70?%;color:#999}.invit-btn[data-v-791c9a56]{font-size:%?32?%;background-color:#ff7e00;color:#fff;display:inline-block;border-radius:%?50?%;line-height:2;-webkit-box-shadow:0 1px 2px rgba(0,0,0,.3);box-shadow:0 1px 2px rgba(0,0,0,.3);margin:%?10?%;padding:0 %?20?%}.color[data-v-791c9a56]{color:#fa7d09}body.?%PAGE?%[data-v-791c9a56]{background-color:#fff}",""])}}]);