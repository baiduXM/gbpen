<?php /* Smarty version Smarty-3.1.19, created on 2015-11-04 02:56:28
         compiled from "E:\yu1\unify\app\views\templates\GM0010\index.html" */ ?>
<?php /*%%SmartyHeaderCode:22817563973dcc86df8-24266513%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '68cd5c5a4830c05d1b6c7c9ae8c616482e1f5ac1' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GM0010\\index.html',
      1 => 1441100483,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '22817563973dcc86df8-24266513',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site_another_url' => 0,
    'title' => 0,
    'keywords' => 0,
    'description' => 0,
    'favicon' => 0,
    'site_url' => 0,
    'headscript' => 0,
    'logo' => 0,
    'global' => 0,
    'slidepic' => 0,
    'mIndexCats' => 0,
    'mIndexCat' => 0,
    'mIn' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_563973dd184ed4_21110723',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_563973dd184ed4_21110723')) {function content_563973dd184ed4_21110723($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>

<script type="text/javascript">
    // 跳转PC页面
    <?php if ($_smarty_tpl->tpl_vars['site_another_url']->value) {?>
      if (!navigator.userAgent.match(/AppleWebKit.*Mobile.*/)) {
        location.href = '<?php echo $_smarty_tpl->tpl_vars['site_another_url']->value;?>
';
      }
    <?php }?>
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no,minimal-ui">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta content="telephone=no, email=no" name="format-detection">
<!-- 启用360浏览器的极速模式(webkit) -->
<meta name="renderer" content="webkit">
<!-- 避免IE使用兼容模式 -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- 针对手持设备优化，主要是针对一些老的不识别viewport的浏览器，比如黑莓 -->
<meta name="HandheldFriendly" content="true">
<!-- 微软的老式浏览器 -->
<meta name="MobileOptimized" content="320">
<!-- uc强制竖屏 -->
<meta name="screen-orientation" content="portrait">
<!-- QQ强制竖屏 -->
<meta name="x5-orientation" content="portrait">
<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" />
<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
" />
<link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
" type="image/x-icon" />
<link rel="stylesheet" rev="stylesheet" href="http://chanpin.xm12t.com.cn/css/global.css" type="text/css" />

<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/reset.css" />
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/public.css" />
<link href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/css.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/swipe.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/TouchSlide.1.1.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/function.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/share.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/idangerous.swiper-2.0.min.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/idangerous.swiper.3dflow-2.0.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/simple-app.js"></script>
<script type="text/javascript">
  window.addEventListener('load', function(){
   setTimeout(function(){ window.scrollTo(0, 1); }, 100);
});//safari浏览器可以通过此代码来隐藏地址栏
</script>
<?php echo $_smarty_tpl->tpl_vars['headscript']->value;?>

</head>

<body>
<div class="body">
<!-- 隐藏导航 -->
<?php echo $_smarty_tpl->getSubTemplate ("./_header_hidenav.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<!-- 隐藏导航End -->
  <div class="wrap page-active">
<!-- 底部导航 -->
<?php echo $_smarty_tpl->getSubTemplate ("./_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<!-- 底部导航End -->
    <div class="index-wrap">
      <header>
        <div class="logo public_bg"><img src="<?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
"></div>
        <div class="box_top clearfix"><div class="y public_bg public_bor"></div><div class="text public_color">welcome</div></div>
        <div class="baner public_bg2">
        <div id="slideBox" class="slideBox">
          <div class="hd">
            <ul>
            </ul>
          </div>
          <div class="bd">
            <ul>
              <?php  $_smarty_tpl->tpl_vars['slidepic'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['slidepic']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['global']->value['slidepics']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['slidepic']->key => $_smarty_tpl->tpl_vars['slidepic']->value) {
$_smarty_tpl->tpl_vars['slidepic']->_loop = true;
?>
              <li><a class="pic" href="<?php echo $_smarty_tpl->tpl_vars['slidepic']->value['link'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['slidepic']->value['image'];?>
" /></a></li>
              <?php } ?>
            </ul>
          </div>
        </div>
        </div>
      </header>
      <section>
      <?php  $_smarty_tpl->tpl_vars['mIndexCat'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['mIndexCat']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['mIndexCats']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['mIndexCat']->key => $_smarty_tpl->tpl_vars['mIndexCat']->value) {
$_smarty_tpl->tpl_vars['mIndexCat']->_loop = true;
?>
      <?php if ($_smarty_tpl->tpl_vars['mIndexCat']->value['type']==4) {?>
      <div class="mainbox">
              <div class="box_top clearfix"><div class="y public_bg public_bor"></div><div class="text public_color"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['en_name'];?>
</div></div>
              <div class="aboutbox public_bg1">
              <div class="about_img"><img src="<?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['image'];?>
"></div>
              <div class="aboutboxm public_color"  onclick="javascript:window.location.href='about.html'"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['description'];?>

              </div>
              
              </div>
      </div>
      <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['mIndexCat']->value['type']==1) {?>
        <div class="newxbox">
        <div class="box_top clearfix"><div class="y public_bg public_bor"></div><div class="text public_color"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['en_name'];?>
</div></div>
          <ul class="news-list2">
                <li onclick="javascript:window.location.href='<?php echo $_smarty_tpl->tpl_vars['mIn']->value['link'];?>
'" >
                <a href="<?php echo $_smarty_tpl->tpl_vars['mIn']->value['link'];?>
" class="public_color clearfix ">
                  <span class="public_bg public_color1">+</span><b><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['name'];?>
</b>
                </a></li>
          </ul>
        </div>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['mIndexCat']->value['type']==2) {?>
        <div class="probox">
        <div class="box_top clearfix"><div class="y public_bg public_bor"></div><div class="text public_color"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['en_name'];?>
</div></div>
          <div class="proboxm public_bg1">
            <ul class="prolist clearfix">
              <li>
                <div class="prolistb"  onclick="javascript:window.location.href='<?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['link'];?>
'"> <img src="<?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['image'];?>
" width="100%" title="265*265">
                  <dd class="title1 public_color"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['name'];?>
</dd>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <div class="box_top clearfix"><div class="y public_bg public_bor"></div><div class="text public_color">The end</div></div>
        <?php }?>
        <?php } ?>
      </section>
      <?php echo $_smarty_tpl->getSubTemplate ("./_footprint.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    </div>
  </div>
</div>
</body>
</html>
<?php }} ?>
