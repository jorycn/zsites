function get_pcs()
{
	return new Array(
	"请选择", "省份",
	"安徽",   "AnHui",
	"北京",   "BeiJing",
	"重庆",   "ChongQing",
	"福建",   "FuJian",
	"甘肃",   "GanSu",
	"广东",   "GuangDong",
	"广西",   "GuangXi",
	"贵州",   "GuiZhou",
	"海南",   "HaiNan",
	"河北",   "HeBei",
	"黑龙江", "HeiLongJiang",
	"河南",   "HeNan",
	"香港",   "HongKong",
	"湖北",   "HuBei",
	"湖南",   "HuNan",
	"江苏",   "JiangSu",
	"江西",   "JiangXi",
	"吉林",   "JiLin",
	"辽宁",   "LiaoNing",
	"澳门",   "Macao",
	"内蒙古", "NeiMengGu",
	"宁夏",   "NingXia",
	"青海",   "QingHai",
	"山东",   "ShanDong",
	"上海",   "ShangHai",
	"山西",   "ShanXi",
	"陕西",   "ShaanXi",
	"四川",   "SiChuan",
	"台湾",   "TaiWan",
	"天津",   "TianJin",
	"新疆",   "XinJiang",
	"西藏",   "XiZang",
	"云南",   "YunNan",
	"浙江",  "ZheJiang",
	"海外",  "Abroad"
);
}
	

function get_citys(pc)
{
	switch (pc) {	
	     case "请选择":
	       var citys  = new Array(
	       "请选择",                                    "城市"
	       ); break;
	       
         case "安徽" :
         case "AnHui":
             var citys  = new Array(
             "合肥",                                    "HeFei",
             "安庆",                                    "AnQing",
             "蚌埠",                                    "BangBu",
             "亳州",                                    "HaoZhou",
             "巢湖",                                    "ChaoHu",
             "滁州",                                    "ChuZhou",
             "阜阳",                                    "FuYang",
             "贵池",                                    "GuiChi",
             "淮北",                                    "HuaiBei",
             "淮化",                                    "HuaiHua",
             "淮南",                                    "HuaiNan",
             "黄山",                                    "HuangShan",
             "九华山",                                  "MaHuaShan",
             "六安",                                    "LiuAn",
             "马鞍山",                                  "MaAnShan",
             "宿州",                                    "XuZhou",
             "铜陵",                                    "TongLing",
             "屯溪",                                    "TunXi",
             "芜湖",                                    "WuHu",
             "宣城",                                    "XuanCheng"
              ); break;

         case "北京" :
         case "BeiJing":
             var citys  = new Array(
             "东城",                                    "DongCheng",
             "西城",                                    "XiCheng",
             "崇文",                                    "CongWen",
             "宣武",                                    "XuanWu",
             "朝阳",                                    "ChaoYang",
             "丰台",                                    "FengTai",
             "石景山",                                  "ShiJingShan",
             "海淀",                                    "HaiDing",
             "门头沟",                                  "MenTouGou",
             "房山",                                    "FangShan",
             "通州",                                    "TongZhou",
             "顺义",                                    "ShunYi",
             "昌平",                                    "ChangPing",
             "大兴",                                    "DaXing",
             "平谷",                                    "PingGu",
             "怀柔",                                    "HuaiRou",
             "密云",                                    "MiYun",
             "延庆",                                    "YanQing"
             ); break;

         case "重庆" :
         case "ChongQing":
             var citys  = new Array(
             "万州",                                    "WanZhou",
             "涪陵",                                    "FuLing",
             "渝中",                                    "YuZhong",
             "大渡口",                                  "DaDuKou",
             "江北",                                    "JiangBei",
             "沙坪坝",                                  "ShApingBa",
             "九龙坡",                                  "JiuLongPo",
             "南岸",                                    "NanAn",
             "北碚",                                    "BeiBei",
             "万盛",                                    "WanSheng",
             "双挢",                                    "ShuangQiao",
             "渝北",                                    "YuBei",
             "巴南",                                    "BaNan",
             "黔江",                                    "QianJiang",
             "长寿",                                    "ChangShou",
             "綦江",                                    "QiJiang",
             "潼南",                                    "TongNan",
             "铜梁",                                    "TongLiang",
             "大足",                                    "DaZu",
             "荣昌",                                    "RongChang",
             "壁山",                                    "BiShan",
             "梁平",                                    "LiangPing",
             "城口",                                    "ChengKou",
             "丰都",                                    "FengDu",
             "垫江",                                    "DianJiang",
             "武隆",                                    "WuLong",
             "忠县",                                    "ZhongXian",
             "开县",                                    "KaiXian",
             "云阳",                                    "YunYang",
             "奉节",                                    "FengJie",
             "巫山",                                    "WuShan",
             "巫溪",                                    "WuXi",
             "石柱",                                    "ShiZhu",
             "秀山",                                    "XiuShan",
             "酉阳",                                    "YouYang",
             "彭水",                                    "PengShui",
             "江津",                                    "JiangJin",
             "合川",                                    "HeChuan",
             "永川",                                    "YongZhou",
             "南川",                                    "NanChuan"
             ); break;

         case "福建" :
         case "FuJian":
             var citys  = new Array(
             "福州",                                 "FuZhou",
             "福安",                                    "FuAn",
             "龙岩",                                    "LongYan",
             "南平",                                    "NanPing",
             "宁德",                                    "NingDe",
             "莆田",                                    "PuTian",
             "泉州",                                    "QuanZhou",
             "三明",                                    "SanMing",
             "邵武",                                    "ShaoWu",
             "石狮",                                    "ShiShi",
             "永安",                                    "YongAn",
             "武夷山",                                  "WuYiShan",
             "厦门",                                    "XiaMen",
             "漳州",                                    "ZhangZhou"
              ); break;

         case "甘肃" :
         case "GanSu":
             var citys  = new Array(
             "兰州",                                 "LanZhou",
             "白银",                                    "BaiYin",
             "定西",                                    "DingXi",
             "敦煌",                                    "DunHuang",
             "甘南",                                    "GanNan",
             "金昌",                                    "JinChang",
             "酒泉",                                    "JiuQuan",
             "临夏",                                    "LinXia",
             "平凉",                                    "PingLiang",
             "天水",                                    "TianShui",
             "武都",                                    "WuDu",
             "武威",                                    "WuWei",
             "西峰",                                    "XiFeng",
             "张掖",                                    "ZhangYe"
             ); break;

         case "广东" :
         case "GuangDong":
             return new Array(
             "广州",                                 "GuangZhou",
             "潮阳",                                    "ChaoYang",
             "潮州",                                    "ChaoZhou",
             "澄海",                                    "ChengHai",
             "东莞",                                    "DongGuan",
             "佛山",                                    "FoShan",
             "河源",                                    "HeYuan",
             "惠州",                                    "HuiZhou",
             "江门",                                    "JiangMen",
             "揭阳",                                    "JieYang",
             "开平",                                    "KaiPing",
             "茂名",                                    "MaoMing",
             "梅州",                                    "MeiZhou",
             "清远",                                    "QingYuan",
             "汕头",                                    "ShanTou",
             "汕尾",                                    "ShanWei",
             "韶关",                                    "ShaoGuan",
             "深圳",                                    "ShenZhen",
             "顺德",                                    "ShunDe",
             "阳江",                                    "YangJiang",
             "英德",                                    "YingDe",
             "云浮",                                    "YunFu",
             "增城",                                    "ZengCheng",
             "湛江",                                    "ZhanJiang",
             "肇庆",                                    "ZhaoQing",
             "中山",                                    "ZhongShan",
             "珠海",                                    "ZhiHai"
             ); break;

         case "广西" :
         case "GuangXi":
             var citys  = new Array(
             "南宁",                                 "NanNing",
             "百色",                                    "BaiSe",
             "北海",                                    "BeiHai",
             "桂林",                                    "GuiLin",
             "防城港",                                  "FangChengGang",
             "河池",                                    "HeChi",
             "贺州",                                    "HeZhou",
             "柳州",                                    "LiuZhou",
             "钦州",                                    "QinZhou",
             "梧州",                                    "WuZhou",
             "玉林",                                    "YuLin"
             ); break;

         case "贵州" :
         case "GuiZhou":
             var citys  = new Array(
             "贵阳",                                 "GuiYang",
             "安顺",                                    "AnShun",
             "毕节",                                    "BiJie",
             "都匀",                                    "DuYun",
             "凯里",                                    "KaiLi",
             "六盘水",                                  "LiuPanShui",
             "铜仁",                                    "TongRen",
             "兴义",                                    "XingYi",
             "玉屏",                                    "YuPing",
             "遵义",                                    "ZunYi"
             ); break;

         case "海南" :
         case "HaiNan":
             var citys  = new Array(
             "海口",                                 "HaiKou",
             "儋县",                                    "DanXian",
             "陵水",                                    "LingShui",
             "琼海",                                    "QiongHai",
             "三亚",                                    "SanYa",
             "通什",                                    "TongShi",
             "万宁",                                    "WanNing"
             ); break;

         case "河北" :
         case "HeBei":
             var citys  = new Array(
             "石家庄",                               "ShiJiaZhuang",
             "保定",                                    "BaoDing",
             "北戴河",                                  "BeiDaiHe",
             "沧州",                                    "CangZhou",
             "承德",                                    "ChengDe",
             "丰润",                                    "FengRun",
             "邯郸",                                    "HanDan",
             "衡水",                                    "HengShui",
             "廊坊",                                    "LangFang",
             "南戴河",                                  "NanDaiHe",
             "秦皇岛",                                  "QinHuangDao",
             "唐山",                                    "TangShan",
             "新城",                                    "XinCheng",
             "邢台",                                    "XingTai",
             "张家口",                                  "ZhangJiaKou"
             ); break;

         case "黑龙江" :
         case "HeiLongJiang":
             var citys  = new Array(
             "哈尔滨",                               "HaErBin",
             "北安",                                    "BeiAn",
             "大庆",                                    "DaQing",
             "大兴安岭",                                "DaXingAnLing",
             "鹤岗",                                    "HeGang",
             "黑河",                                    "HeiHe",
             "佳木斯",                                  "JiaMuSi",
             "鸡西",                                    "JiXi",
             "牡丹江",                                  "MuDanJiang",
             "齐齐哈尔",                                "QiQiHaEr",
             "七台河",                                  "QiTaiHe",
             "双鸭山",                                  "ShuangYaShan",
             "绥化",                                    "SuiHua",
             "伊春",                                    "YiChun"
             ); break;

         case "河南" :
         case "HeNan":
             var citys  = new Array(
             "郑州",                                 "ZhengZhou",
             "安阳",                                    "AnYang",
             "鹤壁",                                    "HeBi",
             "潢川",                                    "HuangChuan",
             "焦作",                                    "JiaoZuo",
             "济源",                                    "JiYuan",
             "开封",                                    "KaiFeng",
             "漯河",                                    "LuoHe",
             "洛阳",                                    "LuoYang",
             "南阳",                                    "NanYang",
             "平顶山",                                  "PingDingShan",
             "濮阳",                                    "PuYang",
             "三门峡",                                  "SanMenXia",
             "商丘",                                    "ShangQiu",
             "新乡",                                    "XinXiang",
             "信阳",                                    "XinYang",
             "许昌",                                    "XuChang",
             "周口",                                    "ZhouKou",
             "驻马店",                                  "ZhuMaDian"
             ); break;

         case "香港" :
         case "HongKong":
             var citys  = new Array(
             "香港",                                    "HongKong",
             "九龙",                                    "JiuLong",
             "新界",                                    "XinJie"
             ); break;

         case "湖北" :
         case "HuBei":
             var citys  = new Array(
             "武汉",                                 "WuHan",
             "恩施",                                    "EnShi",
             "鄂州",                                    "EZhou",
             "黄冈",                                    "HuangGang",
             "黄石",                                    "HuangShi",
             "荆门",                                    "JingMen",
             "荆州",                                    "JingZhou",
             "潜江",                                    "QianJiang",
             "十堰",                                    "ShiYan",
             "随州",                                    "SuiZhou",
             "武穴",                                    "WuXue",
             "仙桃",                                    "XianTao",
             "咸宁",                                    "XianNing",
             "襄阳",                                    "XiangYang",
             "襄樊",                                    "XiangFan",
             "孝感",                                    "XiaoGan",
             "宜昌",                                    "YiChang"
             ); break;

         case "湖南" :
         case "HuNan":
             var citys  = new Array(
             "长沙",                                 "ChangSha",
             "常德",                                    "ChangDe",
             "郴州",                                    "ChenZhou",
             "衡阳",                                    "HengYang",
             "怀化",                                    "HuaiHua",
             "吉首",                                    "JiShou",
             "娄底",                                    "LouDi",
             "邵阳",                                    "ShaoYang",
             "湘潭",                                    "XiangTan",
             "益阳",                                    "YiYang",
             "岳阳",                                    "YueYang",
             "永州",                                    "YongZhou",
             "张家界",                                  "ZhangJiaJie",
             "株洲",                                    "ZhuZhou"
             ); break;

         case "江苏" :
         case "JiangSu":
             var citys  = new Array(
             "南京",                                 "NanJing",
             "常熟",                                    "ChangShu",
             "常州",                                    "ChangZhou",
             "海门",                                    "HaiMen",
             "淮安",                                    "HuaiAn",
             "江都",                                    "JiangDu",
             "江阴",                                    "JiangYin",
             "昆山",                                    "KunShan",
             "连云港",                                  "LianYunGang",
             "南通",                                    "NanTong",
             "启东",                                    "QiDong",
             "沭阳",                                    "ShuYang",
             "宿迁",                                    "SuQian",
             "苏州",                                    "SuZhou",
             "太仓",                                    "TaiCang",
             "泰州",                                    "TaiZhou",
             "同里",                                    "TongLi",
             "无锡",                                    "WuXi",
             "徐州",                                    "XuZhou",
             "盐城",                                    "YanCheng",
             "扬州",                                    "YangZhou",
             "宜兴",                                    "YiXing",
             "仪征",                                    "YiZheng",
             "张家港",                                  "ZhangJiaGang",
             "镇江",                                    "ZhenJiang",
             "周庄",                                    "ZhouZhuang"
             ); break;

         case "江西" :
         case "JiangXi":
             var citys  = new Array(
             "南昌",                                 "NanChang",
             "抚州",                                    "FuZhou",
             "赣州",                                    "GanZhou",
             "吉安",                                    "JiAn",
             "景德镇",                                  "JingDeZhen",
             "井冈山",                                  "JingGangShan",
             "九江",                                    "JiuJiang",
             "庐山",                                    "LuShan",
             "萍乡",                                    "PingXiang",
             "上饶",                                    "ShangRao",
             "新余",                                    "XinYu",
             "宜春",                                    "YiChun",
             "鹰潭",                                    "YingTan"
             ); break;

         case "吉林" :
         case "JiLin":
             var citys  = new Array(
             "长春",                                 "ChangChun",
             "白城",                                    "BaiCheng",
             "白山",                                    "BaiShan",
             "珲春",                                    "HunChun",
             "辽源",                                    "LiaoYuan",
             "梅河",                                    "MeiHe",
             "四平",                                    "SiPing",
             "松原",                                    "SongYuan",
             "通化",                                    "TongHua",
             "延吉",                                    "YanJi",
 			"吉林市",                                   "JiLinShi"
             ); break;

         case "辽宁" :
         case "LiaoNing":
             var citys  = new Array(
             "沈阳",                                 "ShenYang",
             "鞍山",                                    "AnShan",
             "本溪",                                    "BenXi",
             "朝阳",                                    "ChaoYang",
             "大连",                                    "DaLian",
             "丹东",                                    "DanDong",
             "抚顺",                                    "FuShun",
             "阜新",                                    "FuXin",
             "葫芦岛",                                  "HuLuDao",
             "锦州",                                    "JinZhou",
             "辽阳",                                    "LiaoYang",
             "盘锦",                                    "PanJin",
             "铁岭",                                    "TieLing",
             "营口",                                    "YingKou"
             ); break;

         case "澳门" :
         case "Macao":
             var citys  = new Array(
             "澳门",                                    "Macao"
             ); break;

         case "内蒙古" :
         case "NeiMengGu":
             var citys  = new Array(
             "呼和浩特",                             "HuHeHaoTe",
             "阿拉善盟",                                "ALaShanMeng",
             "包头",                                    "BaoTou",
             "赤峰",                                    "ChiFeng",
             "东胜",                                    "DongSheng",
             "海拉尔",                                  "HaiLaEr",
             "集宁",                                    "JiNing",
             "临河",                                    "LinHe",
             "通辽",                                    "TongLiao",
             "乌海",                                    "WuHai",
             "乌兰浩特",                                "WuLanHaoTe",
             "锡林浩特",                                "XiLinHaoTe"
             ); break;

         case "宁夏" :
         case "NingXia":
             var citys  = new Array(
             "银川",                                 "YinChuan",
             "固源",                                    "GuYuan",
             "石嘴山",                                  "ShiZuiShan",
             "吴忠",                                    "WuZhong"
             ); break;

         case "青海" :
         case "QingHai":
             var citys  = new Array(
             "西宁",                                 "XiNing",
             "德令哈",                                  "DeLingHa",
             "格尔木",                                  "GeErMu",
             "共和",                                    "GongHe",
             "海东",                                    "HaiDong",
             "海晏",                                    "HaiYan",
             "玛沁",                                    "MaQin",
             "同仁",                                    "TongRen",
             "玉树",                                    "YuShu"
             ); break;

         case "山东" :
         case "ShanDong":
             var citys  = new Array(
             "济南",                                 "JiNan",
             "滨州",                                    "BinZhou",
             "兖州",                                    "YanZhou",
             "德州",                                    "DeZhou",
             "东营",                                    "DongYing",
             "菏泽",                                    "HeZe",
             "济宁",                                    "JiNing",
             "莱芜",                                    "LaiWu",
             "聊城",                                    "LiaoCheng",
             "临沂",                                    "LinYi",
             "蓬莱",                                    "PengLai",
             "青岛",                                    "QingDao",
             "曲阜",                                    "QuFu",
             "日照",                                    "RiZhao",
             "泰安",                                    "TaiAn",
             "潍坊",                                    "WeiFang",
             "威海",                                    "WeiHai",
             "烟台",                                    "YanTai",
             "枣庄",                                    "ZaoZhuang",
             "淄博",                                    "ZiBo"
             ); break;

         case "上海" :
         case "ShangHai":
             var citys  = new Array(
             "崇明",                                    "CongMing",
             "黄浦",                                    "HuangPu",
             "卢湾",                                    "LuWan",
             "徐汇",                                    "XuHui",
             "长宁",                                    "ChangNing",
             "静安",                                    "JingAn",
             "普陀",                                    "PuTuo",
             "闸北",                                    "ZhaBei",
             "虹口",                                    "HongKou",
             "杨浦",                                    "YangPu",
             "闵行",                                    "MinXing",
             "宝山",                                    "BaoShan",
             "嘉定",                                    "JiaDing",
             "浦东",                                    "PuDong",
             "金山",                                    "JinShan",
             "松江",                                    "SongJiang",
             "青浦",                                    "QingPu",
             "南汇",                                    "NanHui",
             "奉贤",                                    "FengXian"
             ); break;

         case "山西" :
         case "ShanXi":
             var citys  = new Array(
             "太原",                                 "TaiYuan",
             "长治",                                    "ChangZhi",
             "大同",                                    "DaTong",
             "候马",                                    "HouMa",
             "晋城",                                    "JinCheng",
             "离石",                                    "LiShi",
             "临汾",                                    "LinWen",
             "宁武",                                    "NingWu",
             "朔州",                                    "ShuoZhou",
             "忻州",                                    "XinZhou",
             "阳泉",                                    "YangQuan",
             "榆次",                                    "YuCi",
             "运城",                                    "YunCheng"
             ); break;

         case "陕西" :
         case "ShaanXi":
             var citys  = new Array(
             "西安",                                 "XiAn",
             "安康",                                    "AnKang",
             "宝鸡",                                    "BaoJi",
             "汉中",                                    "HanZhong",
             "渭南",                                    "WeiNan",
             "商州",                                    "ShangZhou",
             "绥德",                                    "SuiDe",
             "铜川",                                    "TongChuan",
             "咸阳",                                    "XianYang",
             "延安",                                    "YanAn",
             "榆林",                                    "YuLin"
             ); break;

         case "四川" :
         case "SiChuan":
             var citys  = new Array(
             "成都",                                 "ChengDu",
             "巴中",                                    "BaZhong",
             "达川",                                    "DaChuan",
             "德阳",                                    "DeYang",
             "都江堰",                                  "DuJiangYan",
             "峨眉山",                                  "EMeiShan",
             "涪陵",                                    "PeiLing",
             "广安",                                    "GuangAn",
             "广元",                                    "GuangYuan",
             "九寨沟",                                  "JiuZhaiGou",
             "康定",                                    "KangDing",
             "乐山",                                    "LeShan",
             "泸州",                                    "LuZhou",
             "马尔康",                                  "MaErKang",
             "绵阳",                                    "MianYang",
             "眉山",                                    "MeiShan",
             "南充",                                    "NanChong",
             "内江",                                    "NeiJiang",
             "攀枝花",                                  "PanZhiHua",
             "遂宁",                                    "SuiNing",
             "汶川",                                    "WenChuan",
             "西昌",                                    "XiChang",
             "雅安",                                    "YaAn",
             "宜宾",                                    "YiBin",
             "自贡",                                    "ZiGong",
             "资阳",                                    "ZiYang"
             ); break;

         case "台湾" :
         case "TaiWan":
             var citys  = new Array(
             "台北",                                 "TaiBei",
             "基隆",                                    "JiLong",
             "台南",                                    "TaiNan",
             "台中",                                    "TaiZhong",
             "高雄",                                    "GaoXiong",
             "屏东",                                    "PingDong",
             "南投",                                    "NanTou",
             "云林",                                    "YunLin",
             "新竹",                                    "XinZhu",
             "彰化",                                    "ZhangHua",
             "苗栗",                                    "MiaoLi",
             "嘉义",                                    "JiaYi",
             "花莲",                                    "HuaLian",
             "桃园",                                    "TaoYuan",
             "宜兰",                                    "YiLan",
             "台东",                                    "TaiDong",
             "金门",                                    "JinMen",
             "马祖",                                    "MaZu",
             "澎湖",                                    "PengHu"
             ); break;

         case "天津" :
         case "TianJin":
             var citys  = new Array(
             "天津",                                    "TianJin",
             "和平",                                    "HePing",
             "东丽",                                    "DongLi",
             "河东",                                    "HeDong",
             "西青",                                    "XiQing",
             "河西",                                    "HeXi",
             "津南",                                    "JinNan",
             "南开",                                    "NanKai",
             "北辰",                                    "BeiChen",
             "河北",                                    "HeBei",
             "武清",                                    "WuQing",
             "红挢",                                    "HongJiao",
             "塘沽",                                    "TangGu",
             "汉沽",                                    "HanGu",
             "大港",                                    "DaGang",
             "宁河",                                    "NingHe",
             "静海",                                    "JingHai",
             "宝坻",                                    "BaoDi",
             "蓟县",                                    "JiXian"
             ); break;

         case "新疆" :
         case "XinJiang":
             var citys  = new Array(
             "乌鲁木齐",                             "WuLuMuQi",
             "阿克苏",                                  "AKeSu",
             "阿勒泰",                                  "ALeTai",
             "阿图什",                                  "ATuShi",
             "博乐",                                    "BoLe",
             "昌吉",                                    "ChangJi",
             "东山",                                    "DongShan",
             "哈密",                                    "HaMi",
             "和田",                                    "HeTian",
             "喀什",                                    "KaShi",
             "克拉玛依",                                "KeLaMaYi",
             "库车",                                    "KuChe",
             "库尔勒",                                  "KuErLe",
             "奎屯",                                    "KuiTun",
             "石河子",                                  "ShiHeZi",
             "塔城",                                    "TaCheng",
             "吐鲁番",                                  "TuLuFan",
             "伊宁",                                    "YiNing"
             ); break;

         case "西藏" :
         case "XiZang":
             var citys  = new Array(
             "拉萨",                                 "LaSa",
             "阿里",                                    "ALi",
             "昌都",                                    "ChangDu",
             "林芝",                                    "LinZhi",
             "那曲",                                    "NeQu",
             "日喀则",                                  "RiKaZe",
             "山南",                                    "ShanNan"
             ); break;

         case "云南" :
         case "YunNan":
             var citys  = new Array(
             "昆明",                                 "KunMing",
             "大理",                                    "DaLi",
             "保山",                                    "BaoShan",
             "楚雄",                                    "ChuXiong",
             "大理",                                    "DaLi",
             "东川",                                    "DongZhou",
             "个旧",                                    "GeJiu",
             "景洪",                                    "JingHong",
             "开远",                                    "KaiYuan",
             "临沧",                                    "LinCang",
             "丽江",                                    "LiJiang",
             "六库",                                    "LiuKu",
             "潞西",                                    "LuXi",
             "曲靖",                                    "QuJing",
             "思茅",                                    "SiMao",
             "文山",                                    "WenShan",
             "西双版纳",                                "XiShuangBanNa",
             "玉溪",                                    "YuXi",
             "中甸",                                    "ZhongDian",
             "昭通",                                    "ZhaoTong"
             ); break;

         case "浙江" :
         case "ZheJiang":
             var citys  = new Array(
             "杭州",                                    "HangZhou",
             "安吉",                                    "AnJi",
             "慈溪",                                    "CiXi",
             "定海",                                    "DingHai",
             "奉化",                                    "FengHua",
             "海盐",                                    "HaiYan",
             "海宁",                                    "HaiNing",
             "黄岩",                                    "HuangYan",
             "湖州",                                    "HuZhou",
             "嘉兴",                                    "JiaXing",
             "金华",                                    "JinHua",
             "临安",                                    "LinAn",
             "临海",                                    "LinHai",
             "丽水",                                    "LiShui",
             "宁波",                                    "NingBo",
             "瓯海",                                    "OuHai",
             "平湖",                                    "PingHu",
             "千岛湖",                                  "QianDaoHu",
             "衢州",                                    "QuZhou",
             "江山",                                    "JiangShan",
             "瑞安",                                    "RuiAn",
             "绍兴",                                    "ShaoXing",
             "嵊州",                                    "ShengZhou",
             "台州",                                    "TaiZhou",
             "温岭",                                    "WenLing",
             "温州",                                    "WenZhou",
				"舟山",                          		"ZhouShan"
             ); break;

         case "海外" :
         case "Abroad":
             var citys  = new Array(
             "欧洲",                                    "Europe",
             "北美",                                    "North America",
             "南美",                                    "Latin America",
             "亚洲",                                    "Asia",
             "非洲",                                    "Africa",
             "大洋洲",                                  "Oceania"
             ); break;
	}
	return citys;
}

function fill_opt(obj, vals)
{
	obj.options.length = 0;
	for (var j = 0; j < vals.length / 2; j++) {
		var v = vals[j*2] ;//+ '-' + vals[j*2+1];
		var t = vals[j*2] ;//+ '  (' + vals[j*2+1] + ')';
		obj.options[j] = new Option(t, v);
	}
}

function set_opt(obj, val)
{
	for(var j = 0; j < obj.options.length; j++) {
		var v1 = obj.options[j].value.split('-');
		if (v1[0] == val || v1[1] == val) {
			obj.selectedIndex = j;	
		}
    }		
}

fill_opt(document.getElementById("b_province"),get_pcs());
//alert(document.getElementById("b_province").value);
fill_opt(document.getElementById("b_city"),get_citys("请选择"));
try{
document.getElementById("b_province").onchange=function(){
fill_opt(document.getElementById("b_city"),get_citys(document.getElementById("b_province").value.split("-")[0])); } ;
}catch(e){}

//document.getElementById("b_city").attachEvent("onload",function(){fill_opt(document.getElementById("b_city"),get_citys(document.getElementById("b_province").value.split("-")[0])); }) ;