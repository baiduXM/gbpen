<?php /* Smarty version Smarty-3.1.19, created on 2015-09-01 09:47:46
         compiled from "E:\yu1\unify\app\views\templates\GM0012\index.html" */ ?>
<?php /*%%SmartyHeaderCode:330855b5f1fa718981-64215343%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '582e60dbdfb740a61ca8e6c7551fb711705e5968' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GM0012\\index.html',
      1 => 1441100483,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '330855b5f1fa718981-64215343',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b5f1fa985b92_58956434',
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
    'global' => 0,
    'slidepic' => 0,
    'mIndexCats' => 0,
    'mIndexCat' => 0,
    'mIndexCat_list' => 0,
    'footprint' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b5f1fa985b92_58956434')) {function content_55b5f1fa985b92_58956434($_smarty_tpl) {?><!DOCTYPE html>
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
	<div class="heaer  public_bg">
    	<div class="logo"><img src="<?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
" width="100%"></div>
        
    </div>
    <div class="baner">
	<div id="slideBox" class="slideBox">
    <div class="hd">
            <ul></ul>
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
			<script type="text/javascript">
				
			</script>
       <?php echo $_smarty_tpl->getSubTemplate ("./_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
    
   </div>
   
    <div class="main">
	<?php  $_smarty_tpl->tpl_vars['mIndexCat'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['mIndexCat']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['mIndexCats']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['mIndexCat']->key => $_smarty_tpl->tpl_vars['mIndexCat']->value) {
$_smarty_tpl->tpl_vars['mIndexCat']->_loop = true;
?>
	<?php if ($_smarty_tpl->tpl_vars['mIndexCat']->value['type']==1) {?>
    <div class="box-id">
        	<h1 class="box-top public_bg clearfix"><span class="more"><a href="<?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['link'];?>
">+</a></span>
        	  <div class="text public_bg"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['name'];?>
</div>
        	</h1>
               <div class="news about-m">
               <ul class="news-list1">
			   <?php  $_smarty_tpl->tpl_vars['mIndexCat_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['mIndexCat_list']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['mIndexCat']->value['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['mIndexCat_list']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['mIndexCat_list']->key => $_smarty_tpl->tpl_vars['mIndexCat_list']->value) {
$_smarty_tpl->tpl_vars['mIndexCat_list']->_loop = true;
 $_smarty_tpl->tpl_vars['mIndexCat_list']->index++;
?>
                	<li onclick="javascript:window.location.href='<?php echo $_smarty_tpl->tpl_vars['mIndexCat_list']->value['link'];?>
'">
                    <a href="#">
                      <div class="news_img "><img src="<?php echo $_smarty_tpl->tpl_vars['mIndexCat_list']->value['image'];?>
" title="160*116"/></div>
                      <div class="news_content">
                       <div class="news_title clearfix public_color"><span><?php echo $_smarty_tpl->tpl_vars['mIndexCat_list']->value['pubdate'];?>
</span><b><?php echo $_smarty_tpl->tpl_vars['mIndexCat_list']->value['title'];?>
</b></div>
                       <p> <?php echo $_smarty_tpl->tpl_vars['mIndexCat_list']->value['description'];?>
</p>
                      </div>
                    </a></li>
					<?php if ($_smarty_tpl->tpl_vars['mIndexCat_list']->index+1==4) {?><?php break 1?><?php }?>
					<?php } ?>
                	
                </ul>
            </div>
        </div>
			<?php } elseif ($_smarty_tpl->tpl_vars['mIndexCat']->value['type']==4) {?>
       		<div class="box-id">
        	<h1 class="box-top public_bg clearfix"><span class="more"><a href="<?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['link'];?>
">+</a></span>
        	  <div class="text public_bg"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['name'];?>
</div>
        	</h1>
            <div class="box-about clearfix">
            	<img src="<?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['image'];?>
" width="188" height="245" title="188*245" >
                <dl class="nr"> <?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['description'];?>
</dl>
            </div>
        </div>
    	
       <?php } elseif ($_smarty_tpl->tpl_vars['mIndexCat']->value['type']==2) {?>
        <div class="box-id">
        	<h1 class="box-top1 clearfix public_bg"><a href="#"><div class="text public_color"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['name'];?>
<span><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['en_name'];?>
</span></div></a></h1>
            <div class="about-m">
                <ul class="picture2">
				<?php  $_smarty_tpl->tpl_vars['mIndexCat_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['mIndexCat_list']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['mIndexCat']->value['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['mIndexCat_list']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['mIndexCat_list']->key => $_smarty_tpl->tpl_vars['mIndexCat_list']->value) {
$_smarty_tpl->tpl_vars['mIndexCat_list']->_loop = true;
 $_smarty_tpl->tpl_vars['mIndexCat_list']->index++;
?>
                     <li><div><a href="<?php echo $_smarty_tpl->tpl_vars['mIndexCat_list']->value['link'];?>
"><p class="img"><img src="<?php echo $_smarty_tpl->tpl_vars['mIndexCat_list']->value['image'];?>
" width="100%" title="306*420" /></p><p class=" title public_bg"><?php echo $_smarty_tpl->tpl_vars['mIndexCat_list']->value['title'];?>
</p></a></div></li>
                    <?php if ($_smarty_tpl->tpl_vars['mIndexCat_list']->index+1==6) {?><?php break 1?><?php }?>
					<?php } ?>
					</ul>  
            </div>
        </div>
		<?php }?>
		<?php } ?>
    </div>
    <div class="foot public_bg"><div class="back-top"><A href="#">TOP</A></div>
   		<div class="text">版权所有：<?php echo $_smarty_tpl->tpl_vars['footprint']->value;?>
</div>
   </div>
   
</div>
</div>
</div>
</body>
</html>
<?php }} ?>
