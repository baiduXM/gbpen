<?php /* Smarty version Smarty-3.1.19, created on 2015-08-13 09:11:02
         compiled from "E:\yu1\unify\app\views\templates\GP0023\index.html" */ ?>
<?php /*%%SmartyHeaderCode:2630255b6e55b334707-95784699%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ed4ecfc46a3fa952adeb0bc66fe93d352402ffe3' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP0023\\index.html',
      1 => 1438054406,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2630255b6e55b334707-95784699',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b6e55b48c3b4_31628471',
  'variables' => 
  array (
    'site_another_url' => 0,
    'keywords' => 0,
    'description' => 0,
    'favicon' => 0,
    'title' => 0,
    'site_url' => 0,
    'headscript' => 0,
    'global' => 0,
    'slidepic' => 0,
    'index' => 0,
    'article' => 0,
    'lnav' => 0,
    'contact' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b6e55b48c3b4_31628471')) {function content_55b6e55b48c3b4_31628471($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include 'E:\\yu1\\unify\\vendor\\dark\\smarty-view\\src\\Dark\\SmartyView\\Smarty\\libs\\plugins\\modifier.truncate.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<script type="text/javascript">
    // 跳转手机页面
    <?php if ($_smarty_tpl->tpl_vars['site_another_url']->value) {?>
      if (!!navigator.userAgent.match(/AppleWebKit.*Mobile.*/)) {
        location.href = '<?php echo $_smarty_tpl->tpl_vars['site_another_url']->value;?>
';
      }
    <?php }?>
    </script>   

<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
"/>
<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
"/>
<link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
" type="image/x-icon" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Language" content="zh-CN">

<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<link href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/style.css" rel="styleSheet" type="text/css" />
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jquery1.42.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jquery.SuperSlide.2.1.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/common.js"></script>
<script type="text/javascript" src="http://common.mn.sina.com.cn/public/resource/lib/jquery.jcarousellite.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/function.js"></script>

<?php echo $_smarty_tpl->tpl_vars['headscript']->value;?>

</head>

<body>
<!--头部-->
<?php echo $_smarty_tpl->getSubTemplate ("./_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<!--banner-->
<div class='banner'>
	<div class="focus">
		<div id="pic">
			<ul>
				<?php  $_smarty_tpl->tpl_vars['slidepic'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['slidepic']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['global']->value['slidepics']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['slidepic']->key => $_smarty_tpl->tpl_vars['slidepic']->value) {
$_smarty_tpl->tpl_vars['slidepic']->_loop = true;
?>
                      <li><a href="<?php echo $_smarty_tpl->tpl_vars['slidepic']->value['link'];?>
" target="_blank"><img src="<?php echo $_smarty_tpl->tpl_vars['slidepic']->value['image'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['slidepic']->value['title'];?>
" /></a></li>
                    
                <?php } ?>
			</ul>
		</div>
		<div id="tip">
			<ul>
				<li></li>
				<li></li>
				<li></li>
			</ul>
		</div>
	</div>
</div>



<div class='content'>
	<!-- 文字滚动 -->
	<div class='recom'>
		<div class='recom-right'>
			<div class='recom-bottom'>
				<div class="txtMarquee-top">
					<div class="hd">
						<a href='###' class="next"></a>
						<a href='###' class="prev"></a>
					</div>
					<div class="bd">
						<ul class="infoList">
						  <?php  $_smarty_tpl->tpl_vars['article'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['article']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['left_scoll']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['article']->key => $_smarty_tpl->tpl_vars['article']->value) {
$_smarty_tpl->tpl_vars['article']->_loop = true;
?>
                                 <li><a href="<?php echo $_smarty_tpl->tpl_vars['article']->value['link'];?>
" target="_blank" title="<?php echo $_smarty_tpl->tpl_vars['article']->value['title'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['article']->value['title'],20,"…");?>
</a></li>
                                   <?php if ($_smarty_tpl->tpl_vars['article']->global+1==10) {?><?php break 1?><?php }?>
                          <?php } ?>
						</ul>
				</div>
				</div>
	
			</div>
		</div>
	</div>
<!-- 首页产品分类 -->
	<div class='left-col'>
		<div class="listnav">
           
	<h1><?php echo $_smarty_tpl->tpl_vars['index']->value['left_nav']['name'];?>
<span><?php echo $_smarty_tpl->tpl_vars['index']->value['left_nav']['en_name'];?>
</span></h1>	
	<ul class="left_list">
		<?php  $_smarty_tpl->tpl_vars['lnav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['lnav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['left_nav']['childmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['lnav']->key => $_smarty_tpl->tpl_vars['lnav']->value) {
$_smarty_tpl->tpl_vars['lnav']->_loop = true;
?>
		   <li><a href="<?php echo $_smarty_tpl->tpl_vars['lnav']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['lnav']->value['name'];?>
</a></li>
		<?php } ?>
	</ul>


		</div>
		  
			

			<div class='contact-list'>
				<?php echo $_smarty_tpl->tpl_vars['global']->value['contact_us'];?>

			</div>

		
	</div>
	<div class='main'>
		<!--关于我们-->
		<div class='about-us'>
			<h2 class='tit'>
				<strong><?php echo $_smarty_tpl->tpl_vars['index']->value['about_us']['name'];?>
<em><?php echo $_smarty_tpl->tpl_vars['index']->value['about_us']['en_name'];?>
</em></strong>
				<a href='<?php echo $_smarty_tpl->tpl_vars['index']->value['about_us']['link'];?>
' class='more'>more>></a>
				<span></span>				
			</h2>
			<h3><?php echo $_smarty_tpl->tpl_vars['contact']->value['company'];?>
</h3>
			<div class='infos'>
				<p><?php echo $_smarty_tpl->tpl_vars['index']->value['about_us']['description'];?>
<a href='<?php echo $_smarty_tpl->tpl_vars['index']->value['about_us']['link'];?>
'>[了解更多]</a>	</p>
			</div>
		</div>
		<!--新闻中心-->
		<div class='news-center'>
			<h2 class='tit'>
				<strong><?php echo $_smarty_tpl->tpl_vars['index']->value['news']['name'];?>
<em><?php echo $_smarty_tpl->tpl_vars['index']->value['news']['en_name'];?>
</em></strong>
				<a href='<?php echo $_smarty_tpl->tpl_vars['index']->value['news']['link'];?>
' class='more'>more>></a>
				<span></span>
			</h2>
			<ul class='news-list'>
						<?php  $_smarty_tpl->tpl_vars['article'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['article']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['news']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['article']->key => $_smarty_tpl->tpl_vars['article']->value) {
$_smarty_tpl->tpl_vars['article']->_loop = true;
?>
                                 <li><a href="<?php echo $_smarty_tpl->tpl_vars['article']->value['link'];?>
" target="_blank" title="<?php echo $_smarty_tpl->tpl_vars['article']->value['title'];?>
"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['article']->value['title'],7,"…");?>
</a></li>
                                   <?php if ($_smarty_tpl->tpl_vars['article']->global+1==10) {?><?php break 1?><?php }?>
                        <?php } ?>			    
				
			</ul>
		</div>
		<!--产品展示-->
		<div class='case'>
			<h2 class='tit'>
				<strong><?php echo $_smarty_tpl->tpl_vars['index']->value['pro']['name'];?>
<em><?php echo $_smarty_tpl->tpl_vars['index']->value['pro']['en_name'];?>
</em></strong>
				<a href='<?php echo $_smarty_tpl->tpl_vars['index']->value['pro']['link'];?>
' class='more'>more>></a>
			</h2>
			

<div id="prizes">
	<div class="photos">
		
		<div class="photos-content" style="visibility:hidden">
			<ul>

                       <?php  $_smarty_tpl->tpl_vars['article'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['article']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['pro']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['article']->key => $_smarty_tpl->tpl_vars['article']->value) {
$_smarty_tpl->tpl_vars['article']->_loop = true;
?>
                           <li><a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['pro']['link'];?>
"><img alt="" src="<?php echo $_smarty_tpl->tpl_vars['index']->value['pro']['image'];?>
" width="174" height="131" /><h2><?php echo $_smarty_tpl->tpl_vars['index']->value['pro']['data'][0]['title'];?>
</h2></a></li>
                          
                        <?php } ?>
			</ul>
		</div>
	
	</div>
</div>


		</div>
	</div>
</div>
<?php echo $_smarty_tpl->getSubTemplate ("./_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

</body>
</html><?php }} ?>
