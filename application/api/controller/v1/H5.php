<?php
namespace app\api\controller\v1;

use app\base\controller\Base;
use app\api\model\StarRank as StarRankModel;

class H5 extends Base
{

    private $starUrl = 'https://ouridol.xiaolishu.com/h5/star/';
    
    // star首页
    public function star()
    {
        $page = input('page', 1);
        $size = input('size', 10);
        $keywords = input('keywords', '');
        $sign = input('sign', 0); // 韩星榜
        $rankField = input('rankField', 'week_hot');
        $type = input('type', 0);
        $getStrFlag = input('getStrFlag',0);
        
        $list = StarRankModel::getRankList($page, $size, $rankField, $keywords, $sign);
        $list = json_decode(json_encode($list), true);
        
        if ($page == 1 && empty($keywords)) {
            $data3 = array_slice($list, 0, 3);
            $pop[0] = array_shift($data3);
            array_splice($data3, 1, 0, $pop);
            $list = array_slice($list, 3);
        } else $data3 = [];
        
        if ($getStrFlag && $list) {
            $datastr = '';
            foreach ($list as $key => $val) {
                $datastr .= '<div class="star-other box box-lr box-align-center" onclick="linked(' . $val['id'] . ')">';
               $keywords || $datastr .= '<span class="rank">' . ((($page-1)*$size)+$key+1) . '</span> ';
               $datastr .= '<img src="' . $val['star']['head_img_s'] . '" class="avatar-big" /> 
               <div class="star-info box box-tb flex">
                <span class="name ellipsis">' .  $val['star']['name']  . '</span> 
                <div class="box box-lr box-align-center">
                 <span class="hot">' . $val['week_hot'] . '</span> 
                 <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACIAAAAiCAYAAAA6RwvCAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA8RJREFUeNqsmF1IU1EcwP+bSkYoszCshzYii6LS2cejDcKKCrc9VVSkUWAR2op8M5Uggqi5rIfsQfsAoRcnQUSlTvGlMLeklJzlWuJXmVJaVFP7n90zr9Nzzr13euBwz84959zf+X+d/5kONJZpMxjwkYnVwngdwOrTecGndV2dho/bsBZRCKVCgNxYXQgVWBQQhCijAAaIrdRgLVcC0gkAyM6rVUpAqYxRmAreAD0Hgui/SRXE2XKA5HilUUSaTly3WjUIDs6jEMqquPIA4NRlgHtv1MCQkseD0THUoR7iwHH5t78D4PQ2gB8hNUAVqCYHE4R6hherSTNEbDB2hHGzVHN+QRCkpG/VoiYn3bwMgh0EoHRBENphTHTzURIpWhQI7TBFc0HyhMOLXWyI9haALDQzp4MJM7HjHMC42K1RG7YwCPUUvpfstgIcLmS/qyqRno2Pma+XXXfCyBarEow1IhGbcNjFW+x+Io3XLVK74Bp3espNF/T0LxHBWCIgRu4QG2osbY1YGsQOBLajNxrBYM2Fzh7gwZgiIHyXtdjZ/YNBWRrHShRtN8W+B0LTwIUh5qEXrrCec9RUlcrSOFSoCBK3cXX4KYAxiEFYaiHScNfI0khSkR38/T3T5MHoNR/otU653YTeUrAL4OhmyY0jda47D3dH/WTABOJpNsW3hblSqb8tt7ves+f190ZLYzQ4b0gEZtM6PPD8EshbLki3bz6IFYNU2wtUyQq5b0MWwId22YCPXJh1EDbBVFcfc3kC0/VRym8JiIcL4qkDyM6N7nM42WMv0XC0MxvVky21J77jZl7C18ZO7if+TUnf19OMm60eYpSDQWW7+YmZYEM9DYCVcn/rXYC+YRhq/SSa3TzbWO9zh91Qdk9oeSIHQHLgEbt49ShsG18qnwmz/Qwp25cSI5oX9HLPnJwcTAmv4ke2s5cjXkMM9+lngETc26uHYYihO8+VpJGfIWX5URlamSgnGRvQQ1LxGYizYzhPTccTbbnsWfvxlNi7D0M92kkfavrXHxiqblaC8CGEmZezekWZ+zc/wGTmWlh50Aw6Y6rU+Q5jREMjxpOT+HIaxj2dEKxtg1BoUkmhZgTx8UBMNG81iGD6MRAlJiXA0rRk0A2MSnNXpcCof0RtWJxRCfeCpSaTj8DEevNDiHxVNz0KUyc6mWOEcSBEhdYrp4FeOW2LABOg6vAs5BJuoUCmGGDInddFLlQZUnthf0vMAjpBJWQQwSTowYOhmwRJtxKAZhCGd5moq4ehOryS2EXiF5X/AgwA29JYPEjoiacAAAAASUVORK5CYII=" class="icon-small ml5" />
                </div>
               </div> 
               <button class="mint-button ml20 mr20 button-primary mint-button--default mint-button--small">
                <!----><label class="mint-button-text">打榜 </label></button>
              </div>';
            }
            return  $datastr;
            
        } else {
            $this->assign('data3', $data3);
            $this->assign('list', $list);
            $this->assign('starUrl', $this->starUrl);
            return view();
        }
    }
}
