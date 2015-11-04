<?php /* Smarty version Smarty-3.1.19, created on 2015-10-10 07:16:51
         compiled from "E:\yu1\unify\app\views\templates\GM003\index.html" */ ?>
<?php /*%%SmartyHeaderCode:195555618bb63345ba5-69829680%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b5b8fc5c8c78ae8aca38da83717bb4a867c6ccc3' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GM003\\index.html',
      1 => 1441100481,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '195555618bb63345ba5-69829680',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site_another_url' => 0,
    'keywords' => 0,
    'description' => 0,
    'favicon' => 0,
    'title' => 0,
    'site_url' => 0,
    'logo' => 0,
    'navs' => 0,
    'nav' => 0,
    'global' => 0,
    'slidepic' => 0,
    'footscript' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5618bb638b8573_16981389',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5618bb638b8573_16981389')) {function content_5618bb638b8573_16981389($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
		// 跳转PC页面
		<?php if ($_smarty_tpl->tpl_vars['site_another_url']->value) {?>
			if (!!navigator.userAgent.match(/AppleWebKit.*Mobile.*/)) {
				location.href = '<?php echo $_smarty_tpl->tpl_vars['site_another_url']->value;?>
';
			}
		<?php }?>
		</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no,minimal-ui">

<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" />
<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
" />
<link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
" type="image/x-icon" />
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
</head>

<body>
<div class="body">
 <?php echo $_smarty_tpl->getSubTemplate ("./_nav1.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

  <div class="wrap page-active">
    <div class="opacity2"></div>
	
<?php echo $_smarty_tpl->getSubTemplate ("./_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

	
    <div class="index-wrap"> 
       <div class="logo"><img src="<?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
"></div>
<div class="mid-nav">
  <div class="circle">
  <div class="ring">
 <?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['navs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?>
    <a href="<?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
" class="menuItem"><img src="<?php echo $_smarty_tpl->tpl_vars['nav']->value['image'];?>
" style="width:100%" title="60*40"><br>
<?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a>
<?php } ?>  
  </div>
  <a href="#" class="center"><img src="images/1.png" style="width:100%" title="80*80"></a>
</div>
<script src="js/index.js"></script>
</div>
    
  
<div class="baner">
    	<div class="swiper-container">
    <div class="swiper-wrapper">
      <?php  $_smarty_tpl->tpl_vars['slidepic'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['slidepic']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['global']->value['slidepics']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['slidepic']->key => $_smarty_tpl->tpl_vars['slidepic']->value) {
$_smarty_tpl->tpl_vars['slidepic']->_loop = true;
?>
      <div class="swiper-slide" style="background:url(<?php echo $_smarty_tpl->tpl_vars['slidepic']->value['image'];?>
) center top no-repeat; background-size:cover"></div>
	  <?php } ?>
    </div>
    <div class="pagination"></div>
  </div>

    </div>
      <footer class="foot public_bg">
        <div class="back-top "><A href="#" class="public_color1 public_bg2">↑</A></div>
        <div class="text">Copyright © 2015 技术支持：<?php echo $_smarty_tpl->tpl_vars['footscript']->value;?>
</div>
      </footer>
    </div>
  </div>
</div>
</body>
</html>
<?php }} ?>
