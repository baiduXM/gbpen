<?php /* Smarty version Smarty-3.1.19, created on 2015-09-18 03:03:33
         compiled from "E:\yu1\unify\app\views\templates\GP0028\index.html" */ ?>
<?php /*%%SmartyHeaderCode:2581855fb7f05a154b0-72725295%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2e00c5e457dad670d4a16136b77bc38ce8dd803e' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP0028\\index.html',
      1 => 1442538260,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2581855fb7f05a154b0-72725295',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'keywords' => 0,
    'description' => 0,
    'site_url' => 0,
    'stylecolor' => 0,
    'title' => 0,
    'site_another_url' => 0,
    'headscript' => 0,
    'index' => 0,
    'navho' => 0,
    'global' => 0,
    'naver' => 0,
    'contact' => 0,
    'product' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55fb7f05ac5196_98976757',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55fb7f05ac5196_98976757')) {function content_55fb7f05ac5196_98976757($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" />
<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
" />
<script type="text/javascript" src="http://common.mn.sina.com.cn/public/resource/lib/jquery.jcarousellite.js"></script>

<link rel="stylesheet" rev="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/style_<?php echo $_smarty_tpl->tpl_vars['stylecolor']->value;?>
.css" type="text/css" />

<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/style.css" />
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/dd_delatedpng.js" type="text/javascript"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jquery-1.9.1.min.js" type="text/javascript"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jquery.SuperSlide.2.1.js" type="text/javascript"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/index.js" type="text/javascript"></script>

<!--[if IE 6]>
<script type="text/javascript" src="js/dd_delatedpng.js" ></script>
<script type="text/javascript">//如果多个element使用PNG,则用','分隔
DD_belatedPNG.fix('div,ul,li,a,p,img,h3');
</script>
<![endif]-->
</head>
<script type="text/javascript">
    // 跳转手机页面
    <?php if ($_smarty_tpl->tpl_vars['site_another_url']->value) {?>
      if (!!navigator.userAgent.match(/AppleWebKit.*Mobile.*/)) {
        location.href = '<?php echo $_smarty_tpl->tpl_vars['site_another_url']->value;?>
';
      }
    <?php }?>
    </script>
<?php echo $_smarty_tpl->tpl_vars['headscript']->value;?>

<body>
<div class='wrapper'>
	<?php echo $_smarty_tpl->getSubTemplate ("./_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
	
		<div class="content">
			<div class="public">
				<div class="content_top">
					<div class="list_lf fl">
						<ul class="">
							<?php  $_smarty_tpl->tpl_vars['navho'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['navho']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['homenavs']['childmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['navho']->key => $_smarty_tpl->tpl_vars['navho']->value) {
$_smarty_tpl->tpl_vars['navho']->_loop = true;
?>
							<li class="<?php if ($_smarty_tpl->tpl_vars['navho']->value['current']) {?>current<?php }?>"><a href="<?php echo $_smarty_tpl->tpl_vars['navho']->value['link'];?>
" class="choose"><?php echo $_smarty_tpl->tpl_vars['navho']->value['name'];?>
</a>
                             </li>
							<?php } ?>
						</ul>
					</div>
					<div class="Village fl">
						<h3 class="c_title"><a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['about']['link'];?>
" class="more">MORE>></a><?php echo $_smarty_tpl->tpl_vars['global']->value['about']['name'];?>
<i><?php echo $_smarty_tpl->tpl_vars['global']->value['about']['en_name'];?>
</i></h3>
						<div class="Village_art">
							<img src="<?php echo $_smarty_tpl->tpl_vars['global']->value['about']['image'];?>
"><?php echo $_smarty_tpl->tpl_vars['global']->value['about']['description'];?>

						</div>
						<ul class="Village_list">
							
							<?php  $_smarty_tpl->tpl_vars['naver'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['naver']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['global']->value['second_navs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['naver']->key => $_smarty_tpl->tpl_vars['naver']->value) {
$_smarty_tpl->tpl_vars['naver']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['naver']->key;
?>
								<li><a href="<?php echo $_smarty_tpl->tpl_vars['naver']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['naver']->value['link'];?>
</a></li>
							<?php } ?>
							
						</ul>
					</div>
					<div class="video fr">
						<div class="video_img"><embed src="<?php echo $_smarty_tpl->tpl_vars['index']->value['video'];?>
" allowFullScreen="true" quality="high" width="238" height="238" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed></div>
						<span class="tel margin_15 "><i>尊贵预约 / </i><?php echo $_smarty_tpl->tpl_vars['contact']->value['telephone'];?>
<a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['onlin']['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['global']->value['onlin']['description'];?>
</a></span>
					</div>
				</div>

				<div class="products">
					<h3 class="c_title"><a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['products']['link'];?>
" class="more">MORE>></a><?php echo $_smarty_tpl->tpl_vars['index']->value['products']['name'];?>
<i><?php echo $_smarty_tpl->tpl_vars['index']->value['products']['en_name'];?>
</i></h3>
					<div class="picMarquee-left">			
						<div class="bd">
							<ul class="picList">
							<?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['products']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->_loop = true;
?>
								<li>
									<a href="<?php echo $_smarty_tpl->tpl_vars['product']->value['link'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['product']->value['image'];?>
" /><span class='case_title'><?php echo $_smarty_tpl->tpl_vars['product']->value['title'];?>
</span></a>
								</li>
								
								<?php } ?>
							</ul>
						</div>
					</div>
				</div> 
			</div>
		</div>		
	<?php echo $_smarty_tpl->getSubTemplate ("./_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
	
</div>
</body>
</html><?php }} ?>
