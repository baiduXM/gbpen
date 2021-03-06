<?php

class ArticleSeeder extends Seeder {

    public function run() {
        DB::table('article')->truncate();
        $c_id1 = DB::table('classify')
                        ->select('id')
                        ->where('name', '风气')
                        ->first()->id;
        $c_id2 = DB::table('classify')
                        ->select('id')
                        ->where('name', '奇闻轶事')
                        ->first()->id;
        $c_id3 = DB::table('classify')
                        ->select('id')
                        ->where('name', '道德')
                        ->first()->id;
        $cus_id = DB::table('customer')
                        ->select('id')
                        ->where('name', 'test')
                        ->first()->id;
        $time = date('Y-m-d H:i:s');
        DB::table('article')->insert([
            [
                'title' => '刘诗诗被曝欲解约 不满被安排带新人',
                'img' => 'liushishi.jpg',
                'keywords' => '刘诗诗解约,刘诗诗,刘诗诗不满带新人',
                'introduction' => '刘诗诗不满唐人安排她带新人',
                'content' => '新浪娱乐讯 11月25日，微博认证为“红泽文化传媒有限公司，影评娱评人谢树华”的网友“国际娱乐协会”爆料刘诗诗[微博]将与唐人解约：“刚收到消息确有此事，透露出的讯息是‘刘诗诗不满唐人安排她带新人’，估计是想单飞了吧，反正有吴奇隆[微博]罩着。 ”对此，新浪娱乐分别联系了唐人宣传总监孙吉顺与刘诗诗经纪人Miko，截止发稿前均无人接听。',
                'cus_id' => $cus_id,
                'c_id' => $c_id2,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => '没有刘诗诗的《新步步惊心》你还看吗? ',
                'img' => 'liushishi.jpg',
                'keywords' => '步步惊心,新步步惊心,新步步惊心电影',
                'introduction' => '根据桐华小说《步步惊心》改编的爱情电影《新步步惊心》近日在横店宣布开机。如今拍成电影版，则选择了较为年轻的组合——陈意涵扮演若曦，窦骁和杨祐宁则是男主角。',
                'content' => '电影同样改编自桐华的小说《步步惊心》，剧本将原作中关于爱情的主题展开，利用穿越的特质，为女主角营造了一段每一步都惊心的爱情奇遇冒险。新版的剧情中，将有一个神秘角色全程知晓女主角的现代人身份，也为她整个穿越之旅披上了更惊心动魄的色彩。此外，若曦的角色定位也有了明显突破，新版若曦全程都带有更明显的现代人价值观、世界观，她的现代思维和生活方式将为剧情发展不断带来更大的戏剧冲突。',
                'cus_id' => $cus_id,
                'c_id' => $c_id2,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => '凌潇肃夫妇与爆料人巨春雷聚会',
                'img' => 'cczmvun0336026.jpg',
                'keywords' => '刘诗诗解约,刘诗诗,刘诗诗不满带新人',
                'introduction' => '此前巨春雷在微博爆出姚晨与凌潇肃离婚是因为姚晨多次出轨，被质疑拿姚晨凌潇肃炒作，这次巨春雷与凌潇肃夫妻惬意聚会，打破炒作嫌疑。',
                'content' => '据知情人透露，凌潇肃、巨春雷等人聚餐后便驾车来到一家四惠桥下华贸中心对面的一家搏击俱乐部欣赏格斗。格斗开场前，凌潇肃和巨春雷在音乐声中聊天喝酒，而唐一菲和巨春雷的女朋友则静坐聆听。之后格斗开场，凌潇肃全神贯注地观看，甚至在激动时忍不住欢呼鼓掌，四人一直看到凌晨才愉快告别离开。',
                'cus_id' => $cus_id,
                'c_id' => $c_id2,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => '韩寒杭州捞金避谈二胎传言：我自己的事',
                'img' => 'liushishi.jpg',
                'keywords' => '韩寒谈二胎,韩寒杭州代言,韩寒二胎传闻',
                'introduction' => '韩寒现身杭州出席某代言活动。对于二胎传闻，现场韩寒不愿回应，称不管几胎都是他自己的事情。',
                'content' => '11月25日下午，韩寒现身杭州出席某代言活动。对于二胎传闻，现场韩寒不愿回应，称不管几胎都是他自己的事情。据悉，日前，有知情人爆料称韩寒妻子金丽华早已怀孕并于日前产一子。其实，早在今年9月微博就传出过韩寒妻子将生二胎。韩寒对此传闻一直没有回应。',
                'cus_id' => $cus_id,
                'c_id' => $c_id3,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => '美国1家酒吧拒亚裔非裔入内涉歧视被调查',
                'img' => '1402882182_MqBYNK.jpg',
                'keywords' => '亚裔非裔涉歧,酒吧涉歧视,酒吧涉歧视被调查',
                'introduction' => '美国达拉斯颇受欢迎的酒吧“功夫酒吧”(Kung Fu Saloon)因涉及歧视亚裔与非裔而正在接受市政府官员的调查。',
                'content' => '月18日，厄普肖由于穿着一双高帮运动鞋而被功夫酒吧拒之门外，酒吧工作人员声称他的穿着不够正式。然而，厄普肖表示，他发现几名同样穿着高帮运动鞋的白人顾客被容许进入该酒吧。目前，市议会正在对此事进行调查。达拉斯市政经理冈萨雷斯(A.C.Gonzalez)表示，相关政府部门会积极调查此事，目前城市检察官办公室与警察局已经介入。',
                'cus_id' => $cus_id,
                'c_id' => $c_id3,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => '男子晨练时突然倒地猝死 生前喜欢熬夜',
                'img' => 'liushishi.jpg',
                'keywords' => '晨练猝死,熬夜猝死,生前熬夜猝死',
                'introduction' => '38岁男子在晨练时，突然栽倒在地，急送四院新北院区抢救不治。',
                'content' => '据姚先生家属介绍，姚先生平时生活不规律，喜欢熬夜，最近一段时间，他感觉身体素质有点下降，就开始晨练，谁知道却发生意外了。四院心血管科主任徐正平介绍说，秋冬是猝死高发季节。以往发生猝死的大多数是50岁以上的中老年，近年来，40岁以下年轻人发生猝死的越来越多，可能与生活习惯不良、社会压力大有关，一月内，四院抢救40岁以下的猝死病人就有3例。',
                'cus_id' => $cus_id,
                'c_id' => $c_id2,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => '公务员上班网购被通报批评后立即上火嗓子变哑',
                'img' => 'liushishi.jpg',
                'keywords' => '公务员上班网购,公务员网购被通报,公务员上班',
                'introduction' => '四平市纪委在《四平日报》头版刊文，对近期辖区内公职人员违反工作纪律的有关问题进行了通报。',
                'content' => '在检查中发现，铁东区教育局、铁西区统计局、四平市工商局经济开发区分局、红嘴经济开发区、市工信局等5家单位的7名工作人员存在工作时间网上购物、玩游戏、看电影等违反工作纪律的问题；在对全市重点项目建设观摩总结大会会风会纪检查中，发现市妇联、市统计局等部门的4名干部存在不认真听会、打瞌睡的问题。',
                'cus_id' => $cus_id,
                'c_id' => $c_id3,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => '无聊的测试文章1',
                'img' => 'liushishi.jpg',
                'keywords' => '刘诗诗解约,刘诗诗,刘诗诗不满带新人',
                'introduction' => '根据桐华小说《步步惊心》改编的爱情电影《新步步惊心》近日在横店宣布开机。如今拍成电影版，则选择了较为年轻的组合——陈意涵[微博]扮演若曦，窦骁[微博]和杨祐宁则是男主角。',
                'content' => '电影同样改编自桐华的小说《步步惊心》，剧本将原作中关于爱情的主题展开，利用穿越的特质，为女主角营造了一段每一步都惊心的爱情奇遇冒险。新版的剧情中，将有一个神秘角色全程知晓女主角的现代人身份，也为她整个穿越之旅披上了更惊心动魄的色彩。此外，若曦的角色定位也有了明显突破，新版若曦全程都带有更明显的现代人价值观、世界观，她的现代思维和生活方式将为剧情发展不断带来更大的戏剧冲突。',
                'cus_id' => $cus_id,
                'c_id' => $c_id1,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => '更无聊的测试文章2',
                'img' => 'liushishi.jpg',
                'keywords' => '刘诗诗解约,刘诗诗,刘诗诗不满带新人',
                'introduction' => '根据桐华小说《步步惊心》改编的爱情电影《新步步惊心》近日在横店宣布开机。如今拍成电影版，则选择了较为年轻的组合——陈意涵[微博]扮演若曦，窦骁[微博]和杨祐宁则是男主角。',
                'content' => '电影同样改编自桐华的小说《步步惊心》，剧本将原作中关于爱情的主题展开，利用穿越的特质，为女主角营造了一段每一步都惊心的爱情奇遇冒险。新版的剧情中，将有一个神秘角色全程知晓女主角的现代人身份，也为她整个穿越之旅披上了更惊心动魄的色彩。此外，若曦的角色定位也有了明显突破，新版若曦全程都带有更明显的现代人价值观、世界观，她的现代思维和生活方式将为剧情发展不断带来更大的戏剧冲突。',
                'cus_id' => $cus_id,
                'c_id' => $c_id1,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => '更更无聊的测试文章3',
                'img' => 'liushishi.jpg',
                'keywords' => '刘诗诗解约,刘诗诗,刘诗诗不满带新人',
                'introduction' => '根据桐华小说《步步惊心》改编的爱情电影《新步步惊心》近日在横店宣布开机。如今拍成电影版，则选择了较为年轻的组合——陈意涵[微博]扮演若曦，窦骁[微博]和杨祐宁则是男主角。',
                'content' => '电影同样改编自桐华的小说《步步惊心》，剧本将原作中关于爱情的主题展开，利用穿越的特质，为女主角营造了一段每一步都惊心的爱情奇遇冒险。新版的剧情中，将有一个神秘角色全程知晓女主角的现代人身份，也为她整个穿越之旅披上了更惊心动魄的色彩。此外，若曦的角色定位也有了明显突破，新版若曦全程都带有更明显的现代人价值观、世界观，她的现代思维和生活方式将为剧情发展不断带来更大的戏剧冲突。',
                'cus_id' => $cus_id,
                'c_id' => $c_id1,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => '好无聊的测试文章4',
                'img' => 'liushishi.jpg',
                'keywords' => '刘诗诗解约,刘诗诗,刘诗诗不满带新人',
                'introduction' => '根据桐华小说《步步惊心》改编的爱情电影《新步步惊心》近日在横店宣布开机。如今拍成电影版，则选择了较为年轻的组合——陈意涵[微博]扮演若曦，窦骁[微博]和杨祐宁则是男主角。',
                'content' => '电影同样改编自桐华的小说《步步惊心》，剧本将原作中关于爱情的主题展开，利用穿越的特质，为女主角营造了一段每一步都惊心的爱情奇遇冒险。新版的剧情中，将有一个神秘角色全程知晓女主角的现代人身份，也为她整个穿越之旅披上了更惊心动魄的色彩。此外，若曦的角色定位也有了明显突破，新版若曦全程都带有更明显的现代人价值观、世界观，她的现代思维和生活方式将为剧情发展不断带来更大的戏剧冲突。',
                'cus_id' => $cus_id,
                'c_id' => $c_id1,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => '真的无聊的测试文章5',
                'img' => 'liushishi.jpg',
                'keywords' => '刘诗诗解约,刘诗诗,刘诗诗不满带新人',
                'introduction' => '根据桐华小说《步步惊心》改编的爱情电影《新步步惊心》近日在横店宣布开机。如今拍成电影版，则选择了较为年轻的组合——陈意涵[微博]扮演若曦，窦骁[微博]和杨祐宁则是男主角。',
                'content' => '电影同样改编自桐华的小说《步步惊心》，剧本将原作中关于爱情的主题展开，利用穿越的特质，为女主角营造了一段每一步都惊心的爱情奇遇冒险。新版的剧情中，将有一个神秘角色全程知晓女主角的现代人身份，也为她整个穿越之旅披上了更惊心动魄的色彩。此外，若曦的角色定位也有了明显突破，新版若曦全程都带有更明显的现代人价值观、世界观，她的现代思维和生活方式将为剧情发展不断带来更大的戏剧冲突。',
                'cus_id' => $cus_id,
                'c_id' => $c_id2,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => '非常更无聊的测试文章6',
                'img' => 'liushishi.jpg',
                'keywords' => '刘诗诗解约,刘诗诗,刘诗诗不满带新人',
                'introduction' => '根据桐华小说《步步惊心》改编的爱情电影《新步步惊心》近日在横店宣布开机。如今拍成电影版，则选择了较为年轻的组合——陈意涵[微博]扮演若曦，窦骁[微博]和杨祐宁则是男主角。',
                'content' => '电影同样改编自桐华的小说《步步惊心》，剧本将原作中关于爱情的主题展开，利用穿越的特质，为女主角营造了一段每一步都惊心的爱情奇遇冒险。新版的剧情中，将有一个神秘角色全程知晓女主角的现代人身份，也为她整个穿越之旅披上了更惊心动魄的色彩。此外，若曦的角色定位也有了明显突破，新版若曦全程都带有更明显的现代人价值观、世界观，她的现代思维和生活方式将为剧情发展不断带来更大的戏剧冲突。',
                'cus_id' => $cus_id,
                'c_id' => $c_id3,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => '极更无聊的测试文章7',
                'img' => 'liushishi.jpg',
                'keywords' => '刘诗诗解约,刘诗诗,刘诗诗不满带新人',
                'introduction' => '根据桐华小说《步步惊心》改编的爱情电影《新步步惊心》近日在横店宣布开机。如今拍成电影版，则选择了较为年轻的组合——陈意涵[微博]扮演若曦，窦骁[微博]和杨祐宁则是男主角。',
                'content' => '电影同样改编自桐华的小说《步步惊心》，剧本将原作中关于爱情的主题展开，利用穿越的特质，为女主角营造了一段每一步都惊心的爱情奇遇冒险。新版的剧情中，将有一个神秘角色全程知晓女主角的现代人身份，也为她整个穿越之旅披上了更惊心动魄的色彩。此外，若曦的角色定位也有了明显突破，新版若曦全程都带有更明显的现代人价值观、世界观，她的现代思维和生活方式将为剧情发展不断带来更大的戏剧冲突。',
                'cus_id' => $cus_id,
                'c_id' => $c_id1,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'title' => '最更无聊的测试文章8',
                'img' => 'liushishi.jpg',
                'keywords' => '刘诗诗解约,刘诗诗,刘诗诗不满带新人',
                'introduction' => '根据桐华小说《步步惊心》改编的爱情电影《新步步惊心》近日在横店宣布开机。如今拍成电影版，则选择了较为年轻的组合——陈意涵[微博]扮演若曦，窦骁[微博]和杨祐宁则是男主角。',
                'content' => '电影同样改编自桐华的小说《步步惊心》，剧本将原作中关于爱情的主题展开，利用穿越的特质，为女主角营造了一段每一步都惊心的爱情奇遇冒险。新版的剧情中，将有一个神秘角色全程知晓女主角的现代人身份，也为她整个穿越之旅披上了更惊心动魄的色彩。此外，若曦的角色定位也有了明显突破，新版若曦全程都带有更明显的现代人价值观、世界观，她的现代思维和生活方式将为剧情发展不断带来更大的戏剧冲突。',
                'cus_id' => $cus_id,
                'c_id' => $c_id2,
                'created_at' => $time,
                'updated_at' => $time,
            ]
        ]);
    }

}
