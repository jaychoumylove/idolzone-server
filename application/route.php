<?php

use think\Route;

// Test
Route::rule('testaa', 'api/Test/index');
Route::rule('getToken', 'api/Test/getToken');
Route::rule('getUid', 'api/Test/getUid');

// APP热更新
Route::rule('api/:version/app/update', 'api/v1.H5/update');
// H5
Route::rule('h5/star', 'api/v1.H5/star');
Route::rule('api/:version/h5/getSign', 'api/v1.H5/getSign');

// Clearner
Route::rule('api/:version/clean', 'api/v1.Cleaner/index');

Route::rule('api/:version/createMenu', 'api/v1.Notify/createMenu');

// AutoRun 
Route::rule('api/:version/auto', 'api/v1.AutoRun/index');// 定时任务
Route::rule('api/:version/auto/i', 'api/v1.AutoRun/minuteHandle');// 每分钟定期执行
Route::rule('api/:version/auto/d', 'api/v1.AutoRun/dayHandle');// 每日定期执行
Route::rule('api/:version/auto/w', 'api/v1.AutoRun/weekHandle');// 每周定期执行
Route::rule('api/:version/auto/m', 'api/v1.AutoRun/monthHandle');// 每月定期执行
//临时活动
Route::rule('api/:version/auto/activeOn', 'api/v1.AutoRun/activeOn');//开启
Route::rule('api/:version/auto/activeOff', 'api/v1.AutoRun/activeOff');//关闭

Route::rule('api/:version/auto/clear', 'api/v1.AutoRun/clearDb');// 清除数据表
Route::rule('api/:version/_autorunmy/pk_settle', 'api/v1.AutoRun/pk_settle'); // pk结算

Route::rule('api/:version/auto/sendTmp', 'api/v1.AutoRun/sendTmp');// 打卡消息推送

Route::rule('api/:version/auto/temp', 'api/v1.AutoRun/temp');// 

// Notify
Route::rule('api/:version/notify/receive', 'api/v1.Notify/receive');// 客服消息推送
Route::rule('api/:version/notify/auth', 'api/v1.Notify/getAuth');// 

// Page 
Route::rule('api/:version/page/app', 'api/v1.Page/app');
Route::rule('api/:version/page/group', 'api/v1.Page/group');
Route::rule('api/:version/page/game', 'api/v1.Page/game');
Route::rule('api/:version/page/groupMass', 'api/v1.Page/groupMass');
Route::rule('api/:version/page/wxgroup', 'api/v1.Page/wxgroup');// 
Route::rule('api/:version/page/square', 'api/v1.Page/square');// 
Route::rule('api/:version/page/gzhSubscribe', 'api/v1.Page/gzhSubscribe');// 公众号订阅推送

// Remote
Route::rule('api/:version/remote/zuimei', 'api/v1.Remote/zuimei');

// Star
Route::rule('api/:version/star/info', 'api/v1.Star/getInfo');// 获取单个明星信息
Route::rule('api/:version/star/chart', 'api/v1.Star/getChart');// 获取明星圈子聊天内容
Route::rule('api/:version/star/joinchart', 'api/v1.Star/joinChart');// 加入明星聊天室socket
Route::rule('api/:version/star/leavechart', 'api/v1.Star/leaveChart');// 离开明星聊天室socket
Route::rule('api/:version/star/sendmsg', 'api/v1.Star/sendMsg');// 在圈子中发言
Route::rule('api/:version/star/sendhot', 'api/v1.Star/sendHot');// 给明星贡献人气
Route::rule('api/:version/star/follow', 'api/v1.Star/follow');// 加入明星圈子
Route::rule('api/:version/star/steal', 'api/v1.Star/steal');// 偷花
Route::rule('api/:version/star/dynamic', 'api/v1.Star/dynamic');// 动态
Route::rule('api/:version/star/editimg', 'api/v1.Star/editimg');// 修改明星图片

// StarRank
Route::rule('api/:version/star/rank', 'api/v1.StarRank/getRankList');// 明星排名
Route::rule('api/:version/star/r', 'api/v1.StarRank/getRank');// 明星排名微博抓取

Route::rule('api/:version/star/rank/history', 'api/v1.StarRank/getRankHistory');// 明星排名历史

// Banner
Route::rule('api/:version/banner/list', 'api/v1.Banner/getList');// 获取轮播图列表
Route::rule('api/:version/banner/top', 'api/v1.Banner/getTop');// 风云榜

// User
Route::rule('api/:version/user/login', 'api/v1.User/login');// 登录
Route::rule('api/:version/user/login_app', 'api/v1.User/login_app');// 登录

Route::rule('api/:version/user/saveinfo', 'api/v1.User/saveInfo');// 保存用户详细信息
Route::rule('api/:version/user/savephone', 'api/v1.User/savePhone');// 保存用户详细信息
Route::rule('api/:version/user/edit', 'api/v1.User/edit');// 修改用户头像和昵称
Route::rule('api/:version/user/info', 'api/v1.User/getInfo');// 获取用户详细信息
Route::rule('api/:version/user/currency', 'api/v1.User/getCurrency');// 获取用户货币
Route::rule('api/:version/user/userBlessingBag', 'api/v1.User/getBlessingBag');// 获取用户福袋信息
Route::rule('api/:version/user/item', 'api/v1.User/getItem');// 获取用户道具

Route::rule('api/:version/user/star', 'api/v1.User/getStar');// 用户加入的爱豆
Route::rule('api/:version/user/invitlist', 'api/v1.User/invitList');// 用户邀请列表
Route::rule('api/:version/user/invitaward', 'api/v1.User/invitAward');// 用户邀请奖励
Route::rule('api/:version/user/steal/time', 'api/v1.User/stealTime');// 用户偷花倒计时
Route::rule('api/:version/user/rank', 'api/v1.UserRank/getRank');// 用户贡献排行

Route::rule('api/:version/user/father', 'api/v1.Share/father');// 师徒关系
Route::rule('api/:version/user/sonearn', 'api/v1.Share/sonEarn');// 领取徒弟收益
Route::rule('api/:version/user/checkearn', 'api/v1.Share/checkEarn');// 检查是否有徒弟收益
Route::rule('api/:version/user/breakFather', 'api/v1.Share/breakFather');// 脱离师傅

Route::rule('api/:version/user/sayworld', 'api/v1.User/sayworld');// 世界喊话
Route::rule('api/:version/user/bind', 'api/v1.User/bindClientId');// 绑定client_id

Route::rule('api/:version/user/saveformid', 'api/v1.Ext/saveFormId');// 保存formId

Route::rule('api/:version/user/exit', 'api/v1.User/exit');// 退出圈子
Route::rule('api/:version/user/signin', 'api/v1.User/signin');// 连续签到

Route::rule('api/:version/user/recharge', 'api/v1.User/recharge');// 礼物兑换金豆

Route::rule('api/:version/user/addFriend', 'api/v1.User/addFriend');// 加好友
Route::rule('api/:version/user/delFriend', 'api/v1.User/delFriend');// 删好友
Route::rule('api/:version/user/sendToOther', 'api/v1.User/sendToOther');// 送给别人
Route::rule('api/:version/user/biddenTime', 'api/v1.User/biddenTime');// 时间
Route::rule('api/:version/user/forbidden', 'api/v1.User/forbidden');// 禁言
Route::rule('api/:version/user/extraCurrency', 'api/v1.User/extraCurrency');// 团战积分 喇叭
Route::rule('api/:version/user/like', 'api/v1.User/like');// 点赞
Route::rule('api/:version/user/level', 'api/v1.User/level');// 用户等级

// Share
Route::rule('api/:version/share/mass', 'api/v1.Share/mass');// 分享集结
Route::rule('api/:version/share/start', 'api/v1.Share/massStart');// 分享集结开始
Route::rule('api/:version/share/joinmass', 'api/v1.Share/massJoin');// 分享集结加入
Route::rule('api/:version/share/settlemass', 'api/v1.Share/massSettle');// 集结结算
Route::rule('api/:version/share/group_award', 'api/v1.Share/groupAward');// 群奖励
Route::rule('api/:version/share/group/add', 'api/v1.Share/groupAdd');// 新增群信息
Route::rule('api/:version/share/group/join', 'api/v1.Share/groupMassJoin');// 加入群集结
Route::rule('api/:version/share/group/settle', 'api/v1.Share/groupMassSettle');// 群集结结算
Route::rule('api/:version/share/group/groupDayReback', 'api/v1.Share/groupDayReback');// 群贡献奖励

// Sprite
Route::rule('api/:version/sprite', 'api/v1.UserSprite/info');// 精灵信息
Route::rule('api/:version/sprite/settle', 'api/v1.UserSprite/settle');// 精灵收益结算
Route::rule('api/:version/sprite/upgrade', 'api/v1.UserSprite/upgrade');// 精灵升级
Route::rule('api/:version/sprite/skill', 'api/v1.UserSprite/getSkill');// 精灵技能
Route::rule('api/:version/sprite/skill_settle', 'api/v1.UserSprite/skillSettle');// 
// Route::rule('api/:version/sprite/shortEarn', 'api/v1.UserSprite/shortEarn');// 使用精灵加速卡
Route::rule('api/:version/sprite/helplist', 'api/v1.UserSprite/helplist');// 我的加速列表
Route::rule('api/:version/sprite/helpspeed', 'api/v1.UserSprite/helpspeed');// 我的加速列表
Route::rule('api/:version/sprite/helpstart', 'api/v1.UserSprite/helpstart');// 我的加速列表
Route::rule('api/:version/sprite/skill2rateIncrease', 'api/v1.UserSprite/skill2rateIncrease');// 搞点金豆概率提升


// Pay
Route::rule('api/:version/pay/order', 'api/v1.Payment/order');// 支付下单
Route::rule('api/:version/pay/notify/:platform', 'api/v1.Payment/notify');// 支付通知
Route::rule('api/:version/pay/goods', 'api/v1.Payment/goods');// 商品列表

// Newboy
Route::rule('api/:version/new/gift', 'api/v1.Newboy/gift');// 礼包
Route::rule('api/:version/new/settle', 'api/v1.Newboy/settle');// 领取礼包

// Task
Route::rule('api/:version/task', 'api/v1.Task/index');// 任务
Route::rule('api/:version/task/settle', 'api/v1.Task/settle');// 任务领取
Route::rule('api/:version/task/weibo', 'api/v1.Task/weibo');// 提交微博链接
Route::rule('api/:version/sharetext', 'api/v1.Task/sharetext');// 分享文字 

Route::rule('api/:version/task/taskgiftCategory', 'api/v1.Task/taskgiftCategory');// 任务礼包分类
Route::rule('api/:version/task/taskGift', 'api/v1.Task/taskGift');// 任务礼包
Route::rule('api/:version/task/taskGiftSettle', 'api/v1.Task/taskGiftSettle');// 任务礼包领取

// Ext
Route::rule('api/:version/config', 'api/v1.Ext/config');// 配置信息
Route::rule('api/:version/config/btn_cfg_group', 'api/v1.Ext/btn_cfg_group');// 圈子活动配置信息

Route::rule('api/:version/active/info', 'api/v1.Ext/getActiveInfo');// 活动信息
Route::rule('api/:version/active/card', 'api/v1.Ext/setCard');// 打卡
Route::rule('api/:version/active/list', 'api/v1.Ext/activeList');// 活动列表
Route::rule('api/:version/active/userrank', 'api/v1.Ext/userRank');// 用户打卡排名

Route::rule('api/:version/ext/log', 'api/v1.Ext/log');// 用户日志

Route::rule('api/:version/ext/redress', 'api/v1.Ext/redress');// 公众号补偿

Route::rule('api/:version/ext/gzhPushSubscribe', 'api/v1.Ext/gzhPushSubscribe');// 公众号订阅推送

// 

Route::rule('api/:version/uploadIndex', 'api/v1.Ext/uploadIndex');// 文件上传
Route::rule('api/:version/upload', 'api/v1.Ext/upload');// 文件上传
Route::rule('api/:version/ragreement', 'api/v1.Ext/rAgreement');// 文件上传

// FansClub
Route::rule('api/:version/fans/create', 'api/v1.FansClub/create');// 创建粉丝团
Route::rule('api/:version/fans/edit','api/v1.FansClub/edit');// 修改粉丝团
Route::rule('api/:version/fans/list', 'api/v1.FansClub/list');// 粉丝团列表
Route::rule('api/:version/fans/exit', 'api/v1.FansClub/exit');// 退出粉丝团
Route::rule('api/:version/fans/info', 'api/v1.FansClub/info');// 我加入的粉丝团
Route::rule('api/:version/fans/mass', 'api/v1.FansClub/mass');// 粉丝团集结
Route::rule('api/:version/fans/joinMass', 'api/v1.FansClub/joinMass');// 粉丝团加入集结
Route::rule('api/:version/fans/member', 'api/v1.FansClub/member');// 粉丝团成员
Route::rule('api/:version/fans/task', 'api/v1.FansClub/task');// 粉丝团任务
Route::rule('api/:version/fans/tasksettle', 'api/v1.FansClub/settle');// 粉丝团任务结算
Route::rule('api/:version/fans/editNotice', 'api/v1.FansClub/editNotice');// 修改公告
Route::rule('api/:version/fans/join', 'api/v1.FansClub/apply');// 加入粉丝团
Route::rule('api/:version/fans/apply', 'api/v1.FansClub/apply');// 申请加入
Route::rule('api/:version/fans/applylist', 'api/v1.FansClub/applylist');// 申请列表
Route::rule('api/:version/fans/applydeal', 'api/v1.FansClub/applydeal');// 申请处理
Route::rule('api/:version/fans/enter', 'api/v1.FansClub/enter');// 邀请页面
Route::rule('api/:version/fans/upAdmin', 'api/v1.FansClub/upAdmin');// 提/降管理员

Route::rule('api/:version/fans/mybox', 'api/v1.FansClub/mybox');// 粉丝团宝箱
Route::rule('api/:version/fans/sendbox', 'api/v1.FansClub/sendbox');// 发粉丝团宝箱
Route::rule('api/:version/fans/getBox', 'api/v1.FansClub/getBox');// 发粉丝团宝箱

// lottery
Route::rule('api/:version/lottery/addCount', 'api/v1.Lottery/addCount');// 增加抽奖次数
Route::rule('api/:version/lottery/start', 'api/v1.Lottery/start');// 抽奖
Route::rule('api/:version/lottery/dayEarn', 'api/v1.Lottery/dayEarn');// 抽奖今日获得
Route::rule('api/:version/lottery/log', 'api/v1.Lottery/log');// 抽奖今日明细
Route::rule('api/:version/lottery/double', 'api/v1.Lottery/double');// 双倍领取奖励
Route::rule('api/:version/lottery/getBox', 'api/v1.Lottery/getBox');// 宝箱信息
Route::rule('api/:version/lottery/getBoxOpen', 'api/v1.Lottery/getBoxOpen');// 抽奖宝箱信息

// Article
Route::rule('api/:version/article', 'api/v1.Article/getArticle');// 获取文章
Route::rule('api/:version/article/extra', 'api/v1.Article/getArticleExtra');// 获取文章的额外，评论点赞信息
Route::rule('api/:version/article/list', 'api/v1.Article/getList');// 获取文章列表
Route::rule('api/:version/article/subscribe', 'api/v1.Article/subscribe');// 订阅文章推送
Route::rule('api/:version/article/hotsearch', 'api/v1.Article/hotsearch');// 热搜
Route::rule('api/:version/article/delete', 'api/v1.Article/delete');// 删除文章
Route::rule('api/:version/article/video_exipre', 'api/v1.Article/getVideoExpire');// 获取视频过期
Route::rule('api/:version/article/notice', 'api/v1.Article/getNotice');// 官方通知
Route::rule('api/:version/article/noticelist', 'api/v1.Article/getNoticeList');// 官方通知列表
Route::rule('api/:version/article/refrashVideo', 'api/v1.Article/refrashVideo');// 更新视频地址
Route::rule('api/:version/article/formart', 'api/v1.Article/formart');// 文章格式化

//gift
Route::rule('api/:version/page/gift_package', 'api/v1.Page/giftPackage');// 礼物背包
Route::rule('api/:version/page/gift_num', 'api/v1.Page/giftCount');// 礼物数量

Route::rule('api/:version/subscribe', 'api/v1.Subscribe/index');// 订阅消息

// Prop 积分兑换
Route::rule('api/:version/page/prop', 'api/v1.Page/prop');//兑换列表
Route::rule('api/:version/page/myprop', 'api/v1.Page/myprop');//我的卡券
Route::rule('api/:version/page/propexchange', 'api/v1.Page/propExchange');//积分兑换
Route::rule('api/:version/page/propuse', 'api/v1.Page/propUse');//使用道具

Route::rule('api/:version/page/sendSms', 'api/v1.Page/sendSms');//使用道具

// Open 
Route::rule('api/:version/open/upload', 'api/v1.Open/upload');// 上传开屏
Route::rule('api/:version/open/select', 'api/v1.Open/select');// 开屏图列表
Route::rule('api/:version/open/settle', 'api/v1.Open/settle');// 开屏图数据结算
Route::rule('api/:version/open/today', 'api/v1.Open/today');// 今日当前开屏

// Android
Route::rule('api/:version/android/createView', 'api/v1.Android/createView'); // 
Route::rule('api/:version/android/create', 'api/v1.Android/create');// 新建一个机器人用户
Route::rule('api/:version/android/sendHotView', 'api/v1.Android/sendHotView'); // 
Route::rule('api/:version/android/sendHot', 'api/v1.Android/sendHot');// 让一个机器人打榜
Route::rule('api/:version/android/infoView', 'api/v1.Android/infoView'); // 

// PK
// Route::rule('api/:version/pk/index', 'api/v1.Pk/index'); // 团战首页
// Route::rule('api/:version/pk/join', 'api/v1.Pk/join'); // 参加团战
// Route::rule('api/:version/pk/starrank', 'api/v1.Pk/starrank'); // 团战明星排名

Route::post('api/:version/rank/pk_status', 'api/v1.Pk/getPkTime'); // 团战场次
Route::post('api/:version/rank/pk', 'api/v1.Pk/pk'); // 团战信息
Route::post('api/:version/rank/pk_join', 'api/v1.Pk/pkJoin'); // 参加团战
Route::post('api/:version/rank/pk_out', 'api/v1.Pk/pkOut'); // 领袖粉踢人
Route::post('api/:version/rank/pk_user_rank', 'api/v1.Pk/pkUserRank'); // 用户排名
Route::post('api/:version/rank/pk_dianzan', 'api/v1.Pk/pkDianzan'); // 点赞用户
Route::rule('api/:version/rank/pk_settle', 'api/v1.Pk/autoSettle'); // 结算
Route::post('api/:version/rank/pk_subscribe', 'api/v1.Pk/pkSubscribe'); // 团战订阅
Route::rule('api/:version/rank/pk_push', 'api/v1.Pk/pkPush'); // 团战订阅推送

// Headwear
Route::rule('api/:version/headwear/select', 'api/v1.Headwear/select'); // 头饰
Route::rule('api/:version/headwear/buy', 'api/v1.Headwear/buy'); // 头饰购买
Route::rule('api/:version/headwear/use', 'api/v1.Headwear/use'); // 头饰佩戴
Route::rule('api/:version/headwear/cancel', 'api/v1.Headwear/cancel'); // 头饰取消佩戴

// Badge
Route::rule('api/:version/badge/achieve', 'api/v1.Badge/achieve');// 徽章获得弹窗
Route::rule('api/:version/badge/use', 'api/v1.Badge/use');// 徽章使用
Route::rule('api/:version/badge/cancel', 'api/v1.Badge/cancel');// 徽章摘下
Route::rule('api/:version/badge/select', 'api/v1.Badge/select');// 选择
Route::rule('api/:version/badge/rank', 'api/v1.Badge/getRank');// 圈内排行


// Active
Route::rule('api/:version/active/laren', 'api/v1.Active/laren');// 活动
Route::rule('api/:version/active/sendAixin', 'api/v1.Active/sendAixin');// 送爱心
//618
Route::rule('api/:version/active/blessingTaskList', 'api/v1.Active/blessingTaskList');// 618活动任务列表
Route::rule('api/:version/active/blessingList', 'api/v1.Active/blessingList');// 618活动福气榜列表
Route::rule('api/:version/active/getBlessingBag', 'api/v1.Active/getBlessingBag');// 618活动福气领取
Route::rule('api/:version/active/useBlessingBag', 'api/v1.Active/useBlessingBag');// 618活动福袋使用
Route::rule('api/:version/active/logBlessingBag', 'api/v1.Active/logBlessingBag');// 618活动福袋日志

Route::rule('api/:version/active/dragon_boat_festival', 'api/v1.ActiveDragonBoatFestival/index');// 端午活动列表
Route::rule('api/:version/active/dragon_boat_festival_join', 'api/v1.ActiveDragonBoatFestival/joinIt');// 加入端午活动
Route::rule('api/:version/active/dragon_boat_festival_exit', 'api/v1.ActiveDragonBoatFestival/exitIt');// 退出端午活动
Route::rule('api/:version/active/dragon_boat_festival_fanclub', 'api/v1.ActiveDragonBoatFestival/fanclubList');// 端午活动粉丝团列表
Route::rule('api/:version/active/dragon_boat_festival_fanclub_user', 'api/v1.ActiveDragonBoatFestival/fanclubUserList');// 端午活动粉丝团用户列表

// Father
Route::rule('api/:version/father/info', 'api/v1.Father/info');// 师徒
Route::rule('api/:version/father/fatherList', 'api/v1.Father/fatherList');// 师父列表
Route::rule('api/:version/father/baishi', 'api/v1.Father/baishi');// 拜师
Route::rule('api/:version/father/editMsg', 'api/v1.Father/editMsg');// 修改收徒宣言
Route::rule('api/:version/father/editNotice', 'api/v1.Father/editNotice');// 修改公告
Route::rule('api/:version/father/taskList', 'api/v1.Father/taskList');// 徒弟任务列表
Route::rule('api/:version/father/taskSettle', 'api/v1.Father/taskSettle');// 
Route::rule('api/:version/father/sonList', 'api/v1.Father/sonList');// 徒弟列表
Route::rule('api/:version/father/applyList', 'api/v1.Father/applyList');// 申请列表
Route::rule('api/:version/father/applyDeal', 'api/v1.Father/applyDeal');// 申请处理
Route::rule('api/:version/father/exit', 'api/v1.Father/exit');// 

// Family
Route::rule('api/:version/family/create', 'api/v1.FamilyClub/create');// 创建家族
Route::rule('api/:version/family/edit','api/v1.FamilyClub/edit');// 修改家族
Route::rule('api/:version/family/list', 'api/v1.FamilyClub/rank');// 家族列表
Route::rule('api/:version/family/apply', 'api/v1.FamilyClub/apply');// 申请加入家族
Route::rule('api/:version/family/applylist', 'api/v1.FamilyClub/applylist');// 申请列表
Route::rule('api/:version/family/applydeal', 'api/v1.FamilyClub/applydeal');// 申请列表
Route::rule('api/:version/family/join', 'api/v1.FamilyClub/join');// 加入家族
Route::rule('api/:version/family/exit', 'api/v1.FamilyClub/quit');// 退出家族
Route::rule('api/:version/family/info', 'api/v1.FamilyClub/info');// 我加入的家族
Route::rule('api/:version/family/member', 'api/v1.FamilyClub/member');// 家族成员
Route::rule('api/:version/family/enter', 'api/v1.FamilyClub/enter');// 邀请页面
Route::rule('api/:version/family/settle', 'api/v1.FamilyClub/settle');// 领取奖励
