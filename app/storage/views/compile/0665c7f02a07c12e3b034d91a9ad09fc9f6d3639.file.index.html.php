<?php /* Smarty version Smarty-3.1.19, created on 2015-07-29 00:59:37
         compiled from "E:\yu1\unify\app\views\templates\GM001\index.html" */ ?>
<?php /*%%SmartyHeaderCode:830155b82579b34a76-75073940%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0665c7f02a07c12e3b034d91a9ad09fc9f6d3639' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GM001\\index.html',
      1 => 1433921978,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '830155b82579b34a76-75073940',
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
    'stylecolor' => 0,
    'headscript' => 0,
    'logo' => 0,
    'global' => 0,
    'slidepic' => 0,
    'mIndexCats' => 0,
    'mIndexCat' => 0,
    'mIndexCat_list' => 0,
    'footprint' => 0,
    'footscript' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b8257a0038d1_03661483',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b8257a0038d1_03661483')) {function content_55b8257a0038d1_03661483($_smarty_tpl) {?><!DOCTYPE html> 
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
js/function.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/share.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/idangerous.swiper-2.0.min.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/simple-app.js"></script>
 <link rel="stylesheet" rev="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/style_<?php echo $_smarty_tpl->tpl_vars['stylecolor']->value;?>
.css" type="text/css" />
<!-- 大图轮滚插件等 -->
		<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/script.js"></script>
		<?php echo $_smarty_tpl->tpl_vars['headscript']->value;?>

</head>
<body>
<div class="body public_bg1">
<div class="opacity2"></div>
	<?php echo $_smarty_tpl->getSubTemplate ("./_nav1.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<div class="wrap">
	<?php echo $_smarty_tpl->getSubTemplate ("./_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<div class="index-wrap">
	<div class="heaer">
    	<div class="logo public_bg"><img src="<?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
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
   </div>   
   <?php echo $_smarty_tpl->getSubTemplate ("./_nav2.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
    
    <div class="main">
	 <?php  $_smarty_tpl->tpl_vars['mIndexCat'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['mIndexCat']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['mIndexCats']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['mIndexCat']->key => $_smarty_tpl->tpl_vars['mIndexCat']->value) {
$_smarty_tpl->tpl_vars['mIndexCat']->_loop = true;
?>
   <?php if ($_smarty_tpl->tpl_vars['mIndexCat']->value['type']==4) {?>
    	<div class="box-id">
        	<h1 class="box-top public_border"><span class="more"><a href="<?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['link'];?>
">+more<br>查看更多内容</a></span><span class="tt public_color"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['title'];?>
<b>BRAND STORY</b></span></h1>			
            <div class="about index_mar">
            	<div class="about-edite"> <?php echo htmlspecialchars_decode($_smarty_tpl->tpl_vars['mIndexCat']->value['content'], ENT_QUOTES);?>

                </div>
                <div class="about-img"><img src="<?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['image'];?>
"></div>
            </div>			
        </div>
		 <?php } elseif ($_smarty_tpl->tpl_vars['mIndexCat']->value['type']==3) {?>
         <div class="box-id">
        	<h1 class="box-top public_border"><span class="more"><a href="<?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['link'];?>
">+more<br>查看更多内容</a></span><span class="tt public_color"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['title'];?>
<b>BRAND STORY</b></span></h1>
            <div class="news index_mar">
			
               <ul class="news-list1">
			   <?php  $_smarty_tpl->tpl_vars['mIndexCat_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['mIndexCat_list']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['mIndexCat']->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['mIndexCat_list']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['mIndexCat_list']->key => $_smarty_tpl->tpl_vars['mIndexCat_list']->value) {
$_smarty_tpl->tpl_vars['mIndexCat_list']->_loop = true;
 $_smarty_tpl->tpl_vars['mIndexCat_list']->index++;
?>
                	<li onclick="javascript:window.location.href='<?php echo $_smarty_tpl->tpl_vars['mIndexCat_list']->value['link'];?>
'" class="public_bg1">
                    <a href="#">
                      <div class="news_img public_border"><img src="<?php echo $_smarty_tpl->tpl_vars['mIndexCat_list']->value['image'];?>
" title="105*95"/></div>
                      <div class="news_content">
                       <div class="news_title clearfix"><b class=" public_color"><?php echo $_smarty_tpl->tpl_vars['mIndexCat_list']->value['title'];?>
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
		 <?php } elseif ($_smarty_tpl->tpl_vars['mIndexCat']->value['type']==2) {?>
		 <?php if ($_smarty_tpl->tpl_vars['mIndexCat']->value['showtype']==1) {?>
        <div class="box-id">
                	<h1 class="box-top public_border"><span class="more"><a href="<?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['link'];?>
">+more<br>查看更多内容</a></span><span class="tt public_color"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['title'];?>
<b>WEDDING PACKAGE</b></span></h1>
            <div class="products index_mar">
                <ul class="picture1">
				<?php  $_smarty_tpl->tpl_vars['mIndexCat_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['mIndexCat_list']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['mIndexCat']->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['mIndexCat_list']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['mIndexCat_list']->key => $_smarty_tpl->tpl_vars['mIndexCat_list']->value) {
$_smarty_tpl->tpl_vars['mIndexCat_list']->_loop = true;
 $_smarty_tpl->tpl_vars['mIndexCat_list']->index++;
?>
                     <li><div><a href="<?php echo $_smarty_tpl->tpl_vars['mIndexCat_list']->value['link'];?>
"><span class="pro_text public_color1 public_bg"><?php echo $_smarty_tpl->tpl_vars['mIndexCat_list']->value['title'];?>
</span><p class="img"><img src="<?php echo $_smarty_tpl->tpl_vars['mIndexCat_list']->value['image'];?>
" width="100%" title="300*374"  /></p></a></div></li>
                   <?php if ($_smarty_tpl->tpl_vars['mIndexCat_list']->index+1==6) {?><?php break 1?><?php }?>
					<?php } ?>
                  </ul>  
            </div>
        </div>
         <?php } elseif ($_smarty_tpl->tpl_vars['mIndexCat']->value['showtype']==2) {?>		
        <div class="box-id">
                	<h1 class="box-top public_border"><span class="more"><a href="<?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['link'];?>
">+more<br>查看更多内容</a></span><span class="tt public_color"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['title'];?>
<b>CUSTOMERS APPRECIATE</b></span></h1>
            <div class="products index_mar">
                <ul class="picture2">
               		<?php  $_smarty_tpl->tpl_vars['mIndexCat_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['mIndexCat_list']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['mIndexCat']->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['mIndexCat_list']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['mIndexCat_list']->key => $_smarty_tpl->tpl_vars['mIndexCat_list']->value) {
$_smarty_tpl->tpl_vars['mIndexCat_list']->_loop = true;
 $_smarty_tpl->tpl_vars['mIndexCat_list']->index++;
?>
               		 <li><div><a href="<?php echo $_smarty_tpl->tpl_vars['mIndexCat_list']->value['link'];?>
"><p class="img"><img src="<?php echo $_smarty_tpl->tpl_vars['mIndexCat_list']->value['image'];?>
" width="100%" title="200*140" /></p></a></div></li>
					 <?php if ($_smarty_tpl->tpl_vars['mIndexCat_list']->index+1==5) {?><?php break 1?><?php }?>
					<?php } ?>
                  </ul>  
            </div>
        </div>
     
		<?php }?>
		<?php }?>
		<?php } ?>
    </div>	
    <div class="public_bg foot"><div class="back-top public_bg"><A href="#">TOP</A></div>
   		<span class="public_color1">版权所有：<?php echo $_smarty_tpl->tpl_vars['footprint']->value;?>
</span>
   </div>   
</div><?php echo $_smarty_tpl->tpl_vars['footscript']->value;?>

</div>
</div>
</body>
</html>
<?php }} ?>
