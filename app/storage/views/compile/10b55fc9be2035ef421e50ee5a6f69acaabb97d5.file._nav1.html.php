<?php /* Smarty version Smarty-3.1.19, created on 2015-10-10 07:16:51
         compiled from "E:\yu1\unify\app\views\templates\GM003\_nav1.html" */ ?>
<?php /*%%SmartyHeaderCode:246725618bb6394cc94-88328967%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '10b55fc9be2035ef421e50ee5a6f69acaabb97d5' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GM003\\_nav1.html',
      1 => 1441100481,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '246725618bb6394cc94-88328967',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'navs' => 0,
    'nav' => 0,
    'son' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5618bb639b6422_50361698',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5618bb639b6422_50361698')) {function content_5618bb639b6422_50361698($_smarty_tpl) {?> <div class="class page-prev public_bg1">
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
		<?php if ($_smarty_tpl->tpl_vars['nav']->value['children']) {?>
		<?php  $_smarty_tpl->tpl_vars['son'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['son']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['nav']->value['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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
