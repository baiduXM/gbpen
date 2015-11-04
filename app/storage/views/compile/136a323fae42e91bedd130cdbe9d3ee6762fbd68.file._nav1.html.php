<?php /* Smarty version Smarty-3.1.19, created on 2015-07-29 01:03:50
         compiled from "E:\yu1\unify\app\views\templates\GM001\_nav1.html" */ ?>
<?php /*%%SmartyHeaderCode:1347455b8257a05d688-00276525%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '136a323fae42e91bedd130cdbe9d3ee6762fbd68' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GM001\\_nav1.html',
      1 => 1438131828,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1347455b8257a05d688-00276525',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b8257a099f67_07279654',
  'variables' => 
  array (
    'navs' => 0,
    'nav' => 0,
    'son' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b8257a099f67_07279654')) {function content_55b8257a099f67_07279654($_smarty_tpl) {?><div class="class public_bg">
  <h1 class="class-top"><span class="class-close">×</span>快速导航</h1>
  <div class="class-m">
  <div class="swiper-container scroll-container">
					<div class="swiper-wrapper">
						<div class="swiper-slide">
  	<ul class="class-list">
    		<li><a href="index.html">网站首页</a></li>
	<?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['navs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?>
		 <li class="menu_head">
        <dl class="icon"><span class="icon1">-</span><span class="icon2">+</span></dl>
        <a href="<?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a></li>
		<?php if ($_smarty_tpl->tpl_vars['nav']->value['childmenu']) {?>
		<?php  $_smarty_tpl->tpl_vars['son'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['son']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['nav']->value['childmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['son']->key => $_smarty_tpl->tpl_vars['son']->value) {
$_smarty_tpl->tpl_vars['son']->_loop = true;
?>
		<li class="menu_body">
        	<dl><a href="<?php echo $_smarty_tpl->tpl_vars['son']->value['link'];?>
"><span>〉</span><?php echo $_smarty_tpl->tpl_vars['son']->value['name'];?>
</a></dl>
        </li>
		<?php } ?>
		<?php }?>
	<?php } ?>
        
    </ul>
    </div>
    </div>
    </div>
  </div>
</div><?php }} ?>
