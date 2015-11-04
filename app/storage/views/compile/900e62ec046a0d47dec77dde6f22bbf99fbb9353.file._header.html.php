<?php /* Smarty version Smarty-3.1.19, created on 2015-09-18 02:55:36
         compiled from "E:\yu1\unify\app\views\templates\GP003\_header.html" */ ?>
<?php /*%%SmartyHeaderCode:2590955fb7d28efefd5-71471702%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '900e62ec046a0d47dec77dde6f22bbf99fbb9353' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP003\\_header.html',
      1 => 1441100481,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2590955fb7d28efefd5-71471702',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'logo' => 0,
    'global' => 0,
    'list' => 0,
    'site_url' => 0,
    'navs' => 0,
    'nav' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55fb7d28f29f69_88372637',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55fb7d28f29f69_88372637')) {function content_55fb7d28f29f69_88372637($_smarty_tpl) {?><div class="top">
    	<div class="logo"><img src="<?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
" /></div>
    	<div class="baner">
        	<div id="myFocus"><!--焦点图盒子-->
  <div class="loading"></div><!--载入画面(可删除)-->
  <div class="pic"><!--图片列表-->
  	<ul>
        <?php  $_smarty_tpl->tpl_vars['list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['list']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['global']->value['slidepics']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['list']->key => $_smarty_tpl->tpl_vars['list']->value) {
$_smarty_tpl->tpl_vars['list']->_loop = true;
?>
        <li><a href="<?php echo $_smarty_tpl->tpl_vars['list']->value['link'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['list']->value['image'];?>
" width="1003" height="301" thumb="" alt="" text="" /></a></li>
        <?php } ?>
  	</ul>
  </div>
</div>
        </div>
        <div class="menu">
        <ul class="nav">
        <li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
" style="background:none;">首页</a></li>
        <?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['navs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?>
        <li><a href="<?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
" style="background:none;"><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a></li>
        <?php } ?>
        </ul>

	</div>
</div><?php }} ?>
