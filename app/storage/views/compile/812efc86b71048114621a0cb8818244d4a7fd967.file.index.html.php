<?php /* Smarty version Smarty-3.1.19, created on 2015-09-18 02:55:36
         compiled from "E:\yu1\unify\app\views\templates\GP003\index.html" */ ?>
<?php /*%%SmartyHeaderCode:1521255fb7d28a22b10-06428992%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '812efc86b71048114621a0cb8818244d4a7fd967' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP003\\index.html',
      1 => 1441100481,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1521255fb7d28a22b10-06428992',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site_another_url' => 0,
    'title' => 0,
    'site_url' => 0,
    'keywords' => 0,
    'description' => 0,
    'favicon' => 0,
    'stylecolor' => 0,
    'headscript' => 0,
    'index' => 0,
    'nav' => 0,
    'global' => 0,
    'article' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55fb7d28e8db10_98053826',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55fb7d28e8db10_98053826')) {function content_55fb7d28e8db10_98053826($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <script type="text/javascript">
		// 跳转手机页面
		<?php if ($_smarty_tpl->tpl_vars['site_another_url']->value) {?>
			if (!!navigator.userAgent.match(/AppleWebKit.*Mobile.*/)) {
				location.href = '<?php echo $_smarty_tpl->tpl_vars['site_another_url']->value;?>
';
			}
		<?php }?>
		</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<link href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/css.css" rel="stylesheet" type="text/css" />
<!--[if IE 6]>
<script type="text/javascript" src="js/dd_delatedpng.js" ></script>
<script type="text/javascript">//如果多个element使用PNG,则用','分隔
DD_belatedPNG.fix('div,ul,li,p,img,h1,ul.nav li a:hover,.more,');
</script>
<![endif]-->
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jquery1.42.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jcarousellite.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/myfocus-2.0.4.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/index.js"></script>

<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" />
<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
" />
<link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
" type="image/x-icon" />
<link rel="stylesheet" rev="stylesheet" href="http://chanpin.xm12t.com.cn/css/global.css" type="text/css" />
<link rel="stylesheet" rev="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/style_<?php echo $_smarty_tpl->tpl_vars['stylecolor']->value;?>
.css" type="text/css" />
<?php echo $_smarty_tpl->tpl_vars['headscript']->value;?>

</head>

<body>
<div class="main">
	<?php echo $_smarty_tpl->getSubTemplate ('./_header.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    <div class="content">
    	<div class="left">
        	<div class=" class">
            	<h1 class="class_top"><?php echo $_smarty_tpl->tpl_vars['index']->value['list4']['name'];?>
</h1>
                <div class="class_m">
                	<div class="class_m1">
                    <ul class="first">
	                <?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['list4']['childmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?> 
	                    <li><a href="<?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a></li>
	    			<?php } ?>
                    </ul>
                    </div>
                </div>
            </div>
            <div class="class contact">
            	<p><img src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
images/contact.jpg" /></p>
                <div class="contact_m">
                <?php echo $_smarty_tpl->tpl_vars['global']->value['contact_us'];?>

                </div>
            </div>
        </div>
        <div class="right">
        	<div class="pro">
            	<h1 class="pro_top"><a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['list1']['link'];?>
" class="more"></a><?php echo $_smarty_tpl->tpl_vars['index']->value['list1']['name'];?>
</h1>
                <div class="pro_m">
                <!--下面是向左滚动代码-->
<div id="colee_left" class="colee_left">
<table cellpadding="0" cellspacing="0" border="0">
<tr><td id="colee_left1" valign="top" align="center">
<table cellpadding="2" cellspacing="0" border="0">
<tr align="center">
<?php  $_smarty_tpl->tpl_vars['article'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['article']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['list1']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['article']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['article']->key => $_smarty_tpl->tpl_vars['article']->value) {
$_smarty_tpl->tpl_vars['article']->_loop = true;
 $_smarty_tpl->tpl_vars['article']->index++;
?>
<td><a href="<?php echo $_smarty_tpl->tpl_vars['article']->value['link'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['article']->value['image'];?>
" width="154" height="122" title="img1" /><p class="title"><?php echo $_smarty_tpl->tpl_vars['article']->value['title'];?>
</p></a></td>
<?php } ?> 
</tr>
</table>
</td>
<td id="colee_left2" valign="top"></td>
</tr>
</table>
</div>
 
<!--向左滚动代码结束-->
        </div>
            </div>
            <div class="about">
            	<p class="mouse"><img src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
images/mouse.png" /></p>
            	<h1 class="about_top"><a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['page1']['link'];?>
" class="more1">更多</a><?php echo $_smarty_tpl->tpl_vars['index']->value['page1']['name'];?>
</h1>
                <div class="about_m">
                	<img src="<?php echo $_smarty_tpl->tpl_vars['index']->value['page1']['image'];?>
" width="154" height="122" />
                    <p class="nr"><?php echo $_smarty_tpl->tpl_vars['index']->value['page1']['description'];?>
<a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['page1']['link'];?>
">[查看详细]</a></p>
                </div>
            </div>
            <div class="about news">
            	<h1 class="about_top"><a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['list2']['link'];?>
" class="more1">更多</a><?php echo $_smarty_tpl->tpl_vars['index']->value['list2']['name'];?>
</h1>
                <div class="news_m">
                 <ul class="news_list" style="height:140px;">
                 <?php  $_smarty_tpl->tpl_vars['article'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['article']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['list2']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['article']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['article']->key => $_smarty_tpl->tpl_vars['article']->value) {
$_smarty_tpl->tpl_vars['article']->_loop = true;
 $_smarty_tpl->tpl_vars['article']->index++;
?>	 
                    <li><a href="<?php echo $_smarty_tpl->tpl_vars['article']->value['link'];?>
"><span class="date">[<?php echo $_smarty_tpl->tpl_vars['article']->value['pubdate'];?>
]</span><?php echo $_smarty_tpl->tpl_vars['article']->value['title'];?>
</a></li>
                    <?php if ($_smarty_tpl->tpl_vars['article']->index+1==5) {?><?php break 1?><?php }?>
                 <?php } ?>       
                </ul>
                </div>
            </div>
        </div>
        <div class="Equipment">
        	<h1 class="Equipment_top"><?php echo $_smarty_tpl->tpl_vars['index']->value['list3']['name'];?>
</h1>
            <div class="picScroll-left">
            <a class="prev"></a>
			<div class="bd">
				<ul class="picList">
					<?php  $_smarty_tpl->tpl_vars['article'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['article']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['list3']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['article']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['article']->key => $_smarty_tpl->tpl_vars['article']->value) {
$_smarty_tpl->tpl_vars['article']->_loop = true;
 $_smarty_tpl->tpl_vars['article']->index++;
?> 
                    <li>
						<div class="pic"><a href="<?php echo $_smarty_tpl->tpl_vars['article']->value['link'];?>
" target="_blank"><img src="<?php echo $_smarty_tpl->tpl_vars['article']->value['image'];?>
" title="img1" width="159" height="123" /></a></div>
						<div class="title"><a href="<?php echo $_smarty_tpl->tpl_vars['article']->value['link'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['article']->value['title'];?>
</a></div>
					</li>
                	<?php } ?> 
				</ul>
			</div>
            <a class="next"></a>
		</div>
        </div>
    </div>
    <?php echo $_smarty_tpl->getSubTemplate ("./_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

</div>
</body>
</html>
<?php }} ?>
