(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-pk-pk_rank"],{"2aeb7":function(t,e,i){"use strict";i.r(e);var a=i("d15a"),n=i.n(a);for(var s in a)"default"!==s&&function(t){i.d(e,t,function(){return a[t]})}(s);e["default"]=n.a},"2e1c":function(t,e,i){var a=i("fb98");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var n=i("4f06").default;n("29f77d43",a,!0,{sourceMap:!1,shadowMode:!1})},"7ede":function(t,e,i){"use strict";i.r(e);var a=i("8cb2"),n=i("2aeb7");for(var s in n)"default"!==s&&function(t){i.d(e,t,function(){return n[t]})}(s);i("f18c");var r=i("2877"),o=Object(r["a"])(n["default"],a["a"],a["b"],!1,null,"4428744c",null);e["default"]=o.exports},"8cb2":function(t,e,i){"use strict";var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"rank-page-container"},[i("v-uni-view",{staticClass:"tab-container"},[i("v-uni-view",{staticClass:"tab-item",class:{active:0==t.current},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.current=0}}},[t._v("钻石争夺战")]),i("v-uni-view",{staticClass:"tab-item",class:{active:1==t.current},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.current=1}}},[t._v("鲜花争夺战")])],1),i("v-uni-swiper",{staticClass:"swiper",attrs:{current:t.current},on:{change:function(e){arguments[0]=e=t.$handleEvent(e),t.swiperChange.apply(void 0,arguments)}}},[t._l(2,function(e){return[i("v-uni-swiper-item",[0==t.pkStatus||t.pkTime?t._e():i("v-uni-view",{staticClass:"explain-long"},[i("v-uni-view",{},[t._v(t._s(1==t.pkStatus?"报名":"当前")+"场次："+t._s(t.timeSpace.start_time)+"-"+t._s(t.timeSpace.end_time))]),i("v-uni-view",{},[t._v(t._s(1==t.pkStatus?"开场倒计时":"距本场结束")+"："+t._s(t.timeLeft))])],1),t.pkTime?i("v-uni-view",{staticClass:"explain-long"},[i("v-uni-view",{},[t._v("场次："+t._s(t.yestoday?"昨天":"今天")+t._s(t.showTime)+"（已结束）")])],1):t._e(),i("v-uni-scroll-view",{staticClass:"rankscrollbox",attrs:{"scroll-y":""},on:{scrolltolower:function(e){arguments[0]=e=t.$handleEvent(e),t.lower.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"rank-box"},[i("v-uni-view",{staticClass:"week-rank"},[1==t.pkStatus?i("v-uni-view",{staticClass:"bi-title"},[t._v("已有"),i("v-uni-text",{},[t._v(t._s(t.joinNum))]),t._v("人报名")],1):t._e(),i("v-uni-view",{staticClass:"flex-set"},[i("v-uni-button",{staticClass:"invit-btn",attrs:{"open-type":"share","data-opentype":"share"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.buttonHandler.apply(void 0,arguments)}}},[t._v("召集好友一起参加团战")])],1),t._l(t.list,function(e,a){return[i("v-uni-view",{key:a+"_0",staticClass:"rank-item",attrs:{"data-mid":e.star_id,"data-page":"/pages/pk/pk_rank_user?starname="+e.name+"&current="+t.current+"&pkTime="+t.pkTime+"&mid="+e.star_id+"&yestoday="+t.yestoday},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.goPage.apply(void 0,arguments)}}},[i("v-uni-text",{staticClass:"index1"},[t._v(t._s(2==t.pkStatus?a+1:""))]),i("v-uni-view",{staticClass:"head-img"},[i("v-uni-image",{attrs:{src:e.avatarurl?e.avatarurl:this.AVATAR}}),2==t.pkStatus&&a<3?i("v-uni-image",{staticClass:"s",attrs:{src:"/static/image/rank/p"+(a+1)+".png"}}):t._e()],1),i("v-uni-view",{staticClass:"visiting-card"},[i("v-uni-view",{staticClass:"nickname"},[i("v-uni-view",{staticClass:"nickname-text"},[t._v(t._s(e.name?e.name:"神秘粉丝"))]),e.adm?i("v-uni-view",{staticClass:"level ling"},[i("v-uni-image",{attrs:{src:"/image/ling.png"}})],1):t._e(),e.level?i("v-uni-image",{staticClass:"level",attrs:{src:"/static/image/user_level/lv"+e.level+".png"}}):t._e()],1),i("v-uni-view",{staticClass:"flower"},[1==t.pkStatus?[t._v("报名参加了本场团战")]:t._e(),2==t.pkStatus?[i("v-uni-text",{},[t._v("本场人气")]),i("v-uni-text",{staticClass:"color"},[t._v(t._s(e.hot))]),2==t.pkStatus?i("v-uni-image",{attrs:{src:"https://mmbiz.qpic.cn/mmbiz_png/w5pLFvdua9F3NAxlopF2oyvfuiaEjgJItws1tcmzFFLo4WGc38l7kibxxk1atGAcjALuqvyvLib3icFPyAicbsOOl3g/0"}}):t._e()]:t._e()],2)],1),t.mymid==e.star_id&&2==t.pkStatus?i("v-uni-view",{staticClass:"right-btn"},[t._v("粉丝贡献")]):t._e(),1!=t.pkStatus||!t.captain&&e.uid!=t.userInfo.id?t._e():i("v-uni-view",{staticClass:"out iconfont iconclose",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.out(e,a)}}})],1)]})],2)],1)],1)],1)]})],2),i("shareModalComponent",{ref:"shareModal"})],1)},n=[];i.d(e,"a",function(){return a}),i.d(e,"b",function(){return n})},d15a:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a={data:function(){return{starid:this.$app.getData("userStar").id,pkTimeList:[],curPkTime:{},myJoinType:"",current:0,rankList:[],tabList:[{name:"钻石争夺战"},{name:"鲜花争夺战"}],pkStatus:0,timeSpace:{},list:[],pkTime:"",mymid:this.$app.getData("userStar").id,showTime:"",yestoday:0,joinNum:0,timeLeft:"",userInfo:this.$app.getData("userInfo"),captain:this.$app.getData("userStar").captain}},onShareAppMessage:function(t){var e=t.target&&t.target.dataset.share;return this.$app.commonShareAppMessage(e)},onLoad:function(t){this.pkTime=t.pkTime||"",this.showTime=t.time||"",this.yestoday=t.yestoday||0,this.current=t.current,this.page=1,this.loadData()},methods:{buttonHandler:function(t){var e=t.target.dataset.opentype;if("share"==e)t.target&&t.target.dataset.share},out:function(t,e){var i=this;this.$app.modal("将".concat(t.name,"移出本次团战?"),function(){i.$app.request("rank/pk_out",{mid:i.starid,uid:t.uid},function(t){i.$app.toast(t.msg),i.list.splice(e,1)})})},lower:function(){console.log(11),this.page++,this.page>10||this.loadData()},goPage:function(t){2==this.pkStatus&&(t.currentTarget.dataset.mid!=this.$app.getData("userStar").id&&1!=this.sAdm||this.$app.goPage(t.currentTarget.dataset.page))},swiperChange:function(t){this.page=1,this.rankList=[],this.loadData()},loadData:function(){var t=this;this.$app.request("rank/pk",{page:this.page,type:this.current||0,mid:this.$app.getData("userStar")["id"],pkTime:this.pkTime||"",yestoday:this.yestoday||0},function(e){var i=e.data;t.pkTime&&(i.status=2),1==t.page?1==i.status?t.list=i.userList:2==i.status&&(t.list=i.starList):1==i.status?t.list=t.list.concat(i.userList):2==i.status&&(t.list=t.list.concat(i.starList)),t.pkStatus=i.status,t.timeSpace=i.timeSpace||null,t.isJoin=i.isJoin||0,t.joinNum=i.joinNum||0,t.adm=i.isAdm||0,t.sAdm=i.sAdm||0,t.uid=i.uid;var a=t.$app.timeGethms(i.timeLeft);t.timeLeft=a.hour+"小时"+a.min+"分"+a.sec+"秒",clearInterval(t.timeId),t.timeId=setInterval(function(){var e=t.$app.timeGethms(--i.timeLeft);t.timeLeft=e.hour+"小时"+e.min+"分"+e.sec+"秒",i.timeLeft<=0&&clearInterval(t.timeId)},1e3)})}}};e.default=a},f18c:function(t,e,i){"use strict";var a=i("2e1c"),n=i.n(a);n.a},fb98:function(t,e,i){e=t.exports=i("2350")(!1),e.push([t.i,".rank-page-container[data-v-4428744c]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;height:100%}.rank-page-container .tab-container[data-v-4428744c]{padding:%?25?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-justify-content:space-around;justify-content:space-around;border-bottom:%?1?% solid #eee}.rank-page-container .tab-container .tab-item[data-v-4428744c]{border-radius:%?32?%;border:%?1?% solid #ff7e00;padding:%?10?% %?30?%;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;display:-webkit-box;display:-webkit-flex;display:flex;font-size:%?30?%;margin:0 %?20?%;color:#ff7e00}.rank-page-container .tab-container .tab-item.active[data-v-4428744c]{background-color:#ff7e00;text-align:center;color:#fff}uni-page-body[data-v-4428744c]{background-color:#fff;height:100%;overflow:hidden}.title[data-v-4428744c]{padding:%?25?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-justify-content:space-around;justify-content:space-around;border-bottom:%?1?% solid #eee}.tab-active[data-v-4428744c],.tab-nomal[data-v-4428744c]{border-radius:%?32?%;border:%?1?% solid #ff7e00;padding:%?10?% %?20?%;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;display:-webkit-box;display:-webkit-flex;display:flex;font-size:%?30?%}.tab-active[data-v-4428744c]{background-color:#ff7e00;text-align:center;color:#fff}.tab-nomal[data-v-4428744c]{color:#ff7e00}.all-rank[data-v-4428744c]{height:100%;width:100%;-webkit-transform:translateX(750px);transform:translateX(750px)}.week-rank[data-v-4428744c]{height:100%;width:100%\n  /* position: absolute;\n  top:0;\n  left:0;\n  transform: translateX(-750px); */}.rank-item[data-v-4428744c]{height:%?140?%;display:-webkit-box;display:-webkit-flex;display:flex;font-size:%?32?%;color:#323232;-webkit-box-align:center;-webkit-align-items:center;align-items:center;padding:%?6?% %?35?%}.rank-item .index1[data-v-4428744c]{margin:0 %?20?%;font-size:%?34?%;color:#686868}.pai[data-v-4428744c]{width:%?55?%;height:%?55?%}.rank-item .index2[data-v-4428744c]{color:#f78da6}.rank-item .index3[data-v-4428744c]{color:#ba8cf7}.rank-item .index4[data-v-4428744c]{color:#9dc0ee}.visiting-card .index4[data-v-4428744c]{margin-right:%?20?%}.rank-item .index5[data-v-4428744c]{color:#94edb5}.active.rank-item[data-v-4428744c]{background-color:#fff}.head-img[data-v-4428744c]{width:%?111?%;height:%?107?%;position:relative;top:%?6?%}.head-img uni-image[data-v-4428744c]{width:%?95?%;height:%?95?%;border-radius:50%}.head-img uni-image.s[data-v-4428744c]{width:%?58?%;height:%?58?%;position:absolute;right:0;bottom:%?-2?%}.visiting-card[data-v-4428744c]{line-height:150%}.my-rank .visiting-card[data-v-4428744c]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;-webkit-box-pack:start;-webkit-justify-content:flex-start;justify-content:flex-start;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.visiting-card .nickname[data-v-4428744c]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.visiting-card .level[data-v-4428744c]{width:%?30?%;height:%?30?%}.visiting-card .flower[data-v-4428744c]{color:#686868;font-size:%?24?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.visiting-card .share[data-v-4428744c]{font-size:%?22?%;width:%?334?%}.flower uni-image[data-v-4428744c]{width:%?30?%;height:%?30?%;vertical-align:middle;margin-right:%?6?%;margin-left:%?6?%}.my-rank[data-v-4428744c]{background-color:#fff;border-top:%?1?% solid #eee;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;-webkit-justify-content:space-around;justify-content:space-around;height:auto;bottom:0;position:fixed}.swiper[data-v-4428744c]{-webkit-box-flex:1;-webkit-flex:1;flex:1}.rankscrollbox[data-v-4428744c]{height:100%}.rank-box[data-v-4428744c]{padding-bottom:%?100?%}.starname[data-v-4428744c]{background:-webkit-linear-gradient(#ff7e00,#fccd9f);color:#fff;padding:0 %?12?%;border-radius:%?12?%;font-size:%?20?%;box-shadow:0 0 1px rgba(0,0,0,.3);line-height:%?34?%}.nickname-text[data-v-4428744c]{white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:%?320?%}.nickname-text.self[data-v-4428744c]{max-width:%?250?%}.explain-long[data-v-4428744c]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-justify-content:space-around;justify-content:space-around;font-size:%?26?%;padding:%?20?%}.explain-row[data-v-4428744c]{position:relative;margin:%?10?% 0}.explain-row .explain[data-v-4428744c]{position:absolute;right:%?50?%;top:50%;-webkit-transform:translateY(-50%);transform:translateY(-50%);font-size:%?20?%}.give-btn[data-v-4428744c]{width:%?160?%;height:%?65?%;margin:auto;background:#ff7e00;border-radius:%?50?%;text-align:center;line-height:%?65?%;color:#fff;font-size:%?28?%}.rank-item .right-btn[data-v-4428744c]{position:absolute;right:%?80?%;border-bottom:%?2?% solid #ff7e00;color:#ff7e00;padding:%?10?% %?20?%}.bi-title[data-v-4428744c]{text-align:center;font-size:%?26?%}.bi-title uni-text[data-v-4428744c]{color:#ff7e00}.rank-item .out[data-v-4428744c]{position:absolute;right:%?70?%;color:#999}.invit-btn[data-v-4428744c]{font-size:%?32?%;background-color:#ff7e00;color:#fff;display:inline-block;border-radius:%?50?%;line-height:2;box-shadow:0 1px 2px rgba(0,0,0,.3);margin:%?10?%;padding:0 %?20?%}.color[data-v-4428744c]{color:#fa7d09}body.?%PAGE?%[data-v-4428744c]{background-color:#fff}",""])}}]);