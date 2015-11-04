<?php /* Smarty version Smarty-3.1.19, created on 2015-09-02 07:34:12
         compiled from "E:\yu1\unify\app\views\templates\GP0022\index.html" */ ?>
<?php /*%%SmartyHeaderCode:2410455b1e3a99deeb8-65201245%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '133bbddd9ac241c01b787473f53ad765cca60541' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP0022\\index.html',
      1 => 1441100483,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2410455b1e3a99deeb8-65201245',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b1e3a9b32c80_70069594',
  'variables' => 
  array (
    'site_another_url' => 0,
    'keywords' => 0,
    'description' => 0,
    'site_url' => 0,
    'title' => 0,
    'headscript' => 0,
    'index' => 0,
    'lnav' => 0,
    'article' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b1e3a9b32c80_70069594')) {function content_55b1e3a9b32c80_70069594($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<meta http-equiv="x-ua-compatible" content="ie=7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" />
<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
" />
<link href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jquery1.42.min.js"></script>
<script type="text/javascript" src="http://common.mn.sina.com.cn/public/resource/lib/jquery.jcarousellite.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jquery.SuperSlide.2.1.js"></script>


<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/function.js"></script>
<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<?php echo $_smarty_tpl->tpl_vars['headscript']->value;?>

</head>

<body>
<?php echo $_smarty_tpl->getSubTemplate ("./_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<div class='content index'>
	<div class='container'>
		<div class='aside'>
 

		    <h4><strong><?php echo $_smarty_tpl->tpl_vars['index']->value['left_nav']['name'];?>
</strong><br /><span><?php echo $_smarty_tpl->tpl_vars['index']->value['left_nav']['en_name'];?>
</span><a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['left_nav']['link'];?>
" class='more-btn'></a></h4>    
			<ul class="list clearfix">
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
<div class='main'>
			<h4><span><?php echo $_smarty_tpl->tpl_vars['index']->value['prizesa']['name'];?>
</span><a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['prizesa']['link'];?>
" class='more-btn'></a></h4>
			<ul class="picList">
			<?php echo count($_smarty_tpl->tpl_vars['index']->value['prizesa']['data']);?>

            <?php  $_smarty_tpl->tpl_vars['article'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['article']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['prizesa']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['article']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['article']->key => $_smarty_tpl->tpl_vars['article']->value) {
$_smarty_tpl->tpl_vars['article']->_loop = true;
 $_smarty_tpl->tpl_vars['article']->index++;
?>
				<li>
					<div class="pic"><a href="<?php echo $_smarty_tpl->tpl_vars['article']->value['link'];?>
"><img width='158' height='158' src="<?php echo $_smarty_tpl->tpl_vars['article']->value['image'];?>
" /></a></div>
					<div class="title"><a href="###"><?php echo $_smarty_tpl->tpl_vars['article']->value['title'];?>
</a></div>
				</li>
                  <?php if ($_smarty_tpl->tpl_vars['article']->index+1==8) {?><?php break 1?><?php }?>  
             <?php } ?>   
             
			</ul>
		</div>        

		
	</div>
</div>



<?php echo $_smarty_tpl->getSubTemplate ("./_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

</body>
</html><?php }} ?>
