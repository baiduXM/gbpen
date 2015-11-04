<?php /* Smarty version Smarty-3.1.19, created on 2015-07-29 06:50:11
         compiled from "E:\yu1\unify\app\views\templates\GM0012\content-product.html" */ ?>
<?php /*%%SmartyHeaderCode:1558055b605b5d1b5b5-84934111%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f856e084ee40f8ed6bcebc9919651fe958664c81' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GM0012\\content-product.html',
      1 => 1438152497,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1558055b605b5d1b5b5-84934111',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b605b5d908d6_68301215',
  'variables' => 
  array (
    'site_another_url' => 0,
    'keywords' => 0,
    'description' => 0,
    'favicon' => 0,
    'title' => 0,
    'site_url' => 0,
    'headscript' => 0,
    'article' => 0,
    'image' => 0,
    'footprint' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b605b5d908d6_68301215')) {function content_55b605b5d908d6_68301215($_smarty_tpl) {?><!DOCTYPE html>
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
<h1 class="box-top fixed-top public_bg"><span class="back"><a href="javascript:window.history.back()">返回</a></span><span class="more-ny" id="font">字+</span><div><?php echo $_smarty_tpl->tpl_vars['article']->value['category']['name'];?>
</div></h1>
        <div class="font fix-font">
            	<dl class="big">大</dl>
                <dl class="normal">中</dl>
                <dl class="small">小</dl>
            </div>

    <div class="main ny-wrap">
    	<div class="box-id">
            <div class=" about-m">
            <div class="bigimgbox">
            	<div class=" bigimg">	
                	<div id="slideBox" class="slideBox">
    <div class="hd">
            <ul></ul>
              </div>
	<div class="bd">
		<ul>
		<?php  $_smarty_tpl->tpl_vars['image'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['image']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['article']->value['images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['image']->key => $_smarty_tpl->tpl_vars['image']->value) {
$_smarty_tpl->tpl_vars['image']->_loop = true;
?>
			<li><a class="pic" alt="<?php echo $_smarty_tpl->tpl_vars['image']->value['title'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['image']->value['image'];?>
" /></a></li>
			<?php } ?>
		</ul>
	</div>
	
			</div>
			
                </div>
            </div>
            	<div class="edite">  
                    <?php echo htmlspecialchars_decode($_smarty_tpl->tpl_vars['article']->value['content'], ENT_QUOTES);?>
   
                </div>
            
			
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
