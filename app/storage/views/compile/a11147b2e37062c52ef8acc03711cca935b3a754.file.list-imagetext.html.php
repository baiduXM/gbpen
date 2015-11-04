<?php /* Smarty version Smarty-3.1.19, created on 2015-10-19 09:20:19
         compiled from "E:\yu1\unify\app\views\templates\GM0012\list-imagetext.html" */ ?>
<?php /*%%SmartyHeaderCode:160855b605679d1a93-74690358%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a11147b2e37062c52ef8acc03711cca935b3a754' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GM0012\\list-imagetext.html',
      1 => 1441100483,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '160855b605679d1a93-74690358',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b60567a5e4c9_20804452',
  'variables' => 
  array (
    'site_another_url' => 0,
    'keywords' => 0,
    'description' => 0,
    'favicon' => 0,
    'title' => 0,
    'site_url' => 0,
    'headscript' => 0,
    'logo' => 0,
    'list' => 0,
    'pagenavs' => 0,
    'nav' => 0,
    'article' => 0,
    'page_links' => 0,
    'footprint' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b60567a5e4c9_20804452')) {function content_55b60567a5e4c9_20804452($_smarty_tpl) {?><!DOCTYPE html>
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
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta content="telephone=no" name="format-detection">
<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<link href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/swipe.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/public.css" />
<link href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/css.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/TouchSlide.1.1.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/share.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/idangerous.swiper-2.0.min.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/simple-app.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/function.js"></script>
<?php echo $_smarty_tpl->tpl_vars['headscript']->value;?>

</head>
<body>
<div class="body">
<div class="opacity2"></div>
<?php echo $_smarty_tpl->getSubTemplate ("./_nav1.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<div class="wrap">
<?php echo $_smarty_tpl->getSubTemplate ("./_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<div class="index-wrap">
	<div class="heaer public_bg">
    	<div class="logo"><img src="<?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
" width="100%"></div>
    </div>
    
  <?php echo $_smarty_tpl->getSubTemplate ("./_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    <div class="main">
    	<div class="box-id">
        	<h1 class="box-top public_bg public_border1"><span class="more-ny" id="first">分类<span id="down"> &darr;</span><span id="up"> &uarr; </span></span><div class="text public_bg"><?php echo $_smarty_tpl->tpl_vars['list']->value['name'];?>
</div></h1>
            <div class="hide-class">
			<?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pagenavs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?>
              <dl><a href="<?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a></dl>
             <?php } ?>
          </div>
            <div class=" about-m">
            	<ul class="picture2">
				 <?php  $_smarty_tpl->tpl_vars['article'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['article']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['list']->value['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['article']->key => $_smarty_tpl->tpl_vars['article']->value) {
$_smarty_tpl->tpl_vars['article']->_loop = true;
?>
                     <li><div><a href="<?php echo $_smarty_tpl->tpl_vars['article']->value['link'];?>
"><p class="img"><img src="<?php echo $_smarty_tpl->tpl_vars['article']->value['image'];?>
" width="100%" title="192*264" /></p><p class=" title public_bg"><?php echo $_smarty_tpl->tpl_vars['article']->value['title'];?>
</p></a></div></li>
                   <?php } ?>
				   </ul>   <div class="next1"><a href="<?php echo $_smarty_tpl->tpl_vars['page_links']->value['prev_link'];?>
">上一页</a><a href="<?php echo $_smarty_tpl->tpl_vars['page_links']->value['next_link'];?>
">下一页</a></div>
            </div>
        </div>
    </div>
    <div class="foot public_bg"><div class="back-top"><A href="#">TOP</A></div>
   		<div class="text">版权所有：<?php echo $_smarty_tpl->tpl_vars['footprint']->value;?>
</div>
   </div>
</div>
</div></div>
</body>
</html>
<?php }} ?>
