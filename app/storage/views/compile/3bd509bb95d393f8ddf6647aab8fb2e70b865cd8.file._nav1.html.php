<?php /* Smarty version Smarty-3.1.19, created on 2015-09-01 09:47:47
         compiled from "E:\yu1\unify\app\views\templates\GM0012\_nav1.html" */ ?>
<?php /*%%SmartyHeaderCode:632555b5f1faa319c0-34256191%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3bd509bb95d393f8ddf6647aab8fb2e70b865cd8' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GM0012\\_nav1.html',
      1 => 1441100483,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '632555b5f1faa319c0-34256191',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b5f1faa5c941_26609274',
  'variables' => 
  array (
    'site_url' => 0,
    'navs' => 0,
    'nav' => 0,
    'son' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b5f1faa5c941_26609274')) {function content_55b5f1faa5c941_26609274($_smarty_tpl) {?><div class="class public_bg">
  <h1 class="class-top"><span class="class-close">×</span>快速导航</h1>
  <div class="class-m">
  <div class="swiper-container scroll-container">
					<div class="swiper-wrapper">
						<div class="swiper-slide">
  	<ul class="class-list">
    	<li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
">首 页</a></li>
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
">&gt;<?php echo $_smarty_tpl->tpl_vars['son']->value['name'];?>
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
