<?php /* Smarty version Smarty-3.1.19, created on 2015-08-20 08:55:22
         compiled from "E:\yu1\unify\app\views\templates\GM0012\list-text.html" */ ?>
<?php /*%%SmartyHeaderCode:1968155b60569015c77-32755264%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '032d56ef22cc70a717754709430ae0fd58627f3b' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GM0012\\list-text.html',
      1 => 1439448000,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1968155b60569015c77-32755264',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b605690a2691_68630582',
  'variables' => 
  array (
    'site_another_url' => 0,
    'keywords' => 0,
    'description' => 0,
    'favicon' => 0,
    'title' => 0,
    'site_url' => 0,
    'logo' => 0,
    'global' => 0,
    'slidepic' => 0,
    'list' => 0,
    'pagenavs' => 0,
    'nav' => 0,
    'article' => 0,
    'page_links' => 0,
    'footprint' => 0,
    'footscript' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b605690a2691_68630582')) {function content_55b605690a2691_68630582($_smarty_tpl) {?><!DOCTYPE html>
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

<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" />
<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
" />
<link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
" type="image/x-icon" />
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
js/share.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/idangerous.swiper-2.0.min.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/idangerous.swiper.3dflow-2.0.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/simple-app.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/function.js"></script>
<script type="text/javascript">
	window.addEventListener('load', function(){
   setTimeout(function(){ window.scrollTo(0, 1); }, 100);
});//safari浏览器可以通过此代码来隐藏地址栏
</script>

</head>

<body>

      <header>
      	<div class="logo"><img src="<?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
" width="100%"><div class="topnav" id="class1"><img src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
images/topnav.png"></div></div>
        
            <div class="baner public-bg">
	<div id="slideBox" class="slideBox1">
    <div class="hd">
            <ul></ul>
      </div>
      <span class="next">&gt;</span>
      <span class="prev">&lt;</span>
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
 <?php echo $_smarty_tpl->getSubTemplate ("./_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    </header>  
      <div class="mainbox">
      	  <div class="aboutbox">
          <h1 class="aboutboxtop"><div class="jx public-bg public-color1"><?php echo $_smarty_tpl->tpl_vars['list']->value['en_name'];?>
</div>
          <div class="right"><span class="more2 public-bg public-color1" id="first">分类<span id="down"> &darr;</span><span id="up"> &uarr; </span></span>
          <div class="wz public-bg"><span class="cn public-color1"><?php echo $_smarty_tpl->tpl_vars['list']->value['name'];?>
</span></div>
          <div class="line public-bor"></div></div>
          </h1>
            <div class="hide-class">
			<?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pagenavs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?>
              <div class="classbox public-bg">
              <dl><a href="<?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a></dl>
              </div>
			  <?php } ?>
          </div>
          <ul class="newslist">
		  <?php  $_smarty_tpl->tpl_vars['article'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['article']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['list']->value['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['article']->key => $_smarty_tpl->tpl_vars['article']->value) {
$_smarty_tpl->tpl_vars['article']->_loop = true;
?>
             <li onclick="javascript:window.location.href='<?php echo $_smarty_tpl->tpl_vars['article']->value['link'];?>
'" class="public-bg1">
                <div class="date">
                    <div class="zy public-bg"><div class="xy"></div></div>
                </div>
                <div class="list">
                	<dt><?php echo $_smarty_tpl->tpl_vars['article']->value['pubdate'];?>
</dt>
                    <dd><?php echo $_smarty_tpl->tpl_vars['article']->value['title'];?>
</dd>
                </div>
             </li>  
			<?php } ?>			 
               
          </ul>
          <div class="next1"><a href="<?php echo $_smarty_tpl->tpl_vars['page_links']->value['first_link'];?>
">首页</a><a href="<?php echo $_smarty_tpl->tpl_vars['page_links']->value['prev_link'];?>
">上一页</a><a href="<?php echo $_smarty_tpl->tpl_vars['page_links']->value['next_link'];?>
">下一页</a><a href="<?php echo $_smarty_tpl->tpl_vars['page_links']->value['last_link'];?>
">尾页</a></div>
          </div>
      </div>
      <footer class="foot public-bg public-color1"><div class="back-top"><a href="#">TOP</a></div><?php echo $_smarty_tpl->tpl_vars['footprint']->value;?>
</footer>

<?php echo $_smarty_tpl->tpl_vars['footscript']->value;?>

</body>

</html>
<?php }} ?>
