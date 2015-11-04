<?php /* Smarty version Smarty-3.1.19, created on 2015-07-24 07:05:12
         compiled from "E:\yu1\unify\app\views\templates\GM004\_header_hidenav.html" */ ?>
<?php /*%%SmartyHeaderCode:1523655b1e3a8e63036-80974967%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e236b6697b65a14e79a41d9c402dcdbdccf1bc58' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GM004\\_header_hidenav.html',
      1 => 1437128649,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1523655b1e3a8e63036-80974967',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site_url' => 0,
    'navs' => 0,
    'nav' => 0,
    'nav_list' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b1e3a8e95cc9_98807369',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b1e3a8e95cc9_98807369')) {function content_55b1e3a8e95cc9_98807369($_smarty_tpl) {?><div class="class page-prev public-bg1">
  <h1 class="class-top public-color1"><span class="class-close">×</span>快速导航</h1>
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
          <?php if ($_smarty_tpl->tpl_vars['nav']->value['childmenu']) {?><dl class="icon"><span class="icon1">-</span><span class="icon2">+</span></dl><?php }?>
          <a href="<?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a>
        </li>
        <?php if ($_smarty_tpl->tpl_vars['nav']->value['childmenu']) {?>
          <li class="menu_body">
            <?php  $_smarty_tpl->tpl_vars['nav_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav_list']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['nav']->value['childmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav_list']->key => $_smarty_tpl->tpl_vars['nav_list']->value) {
$_smarty_tpl->tpl_vars['nav_list']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['nav_list']->key;
?>
            <dl><a href="<?php echo $_smarty_tpl->tpl_vars['nav_list']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav_list']->value['name'];?>
</a></dl>
            <?php } ?>
          </li>
        <?php }?>
    <?php } ?>
    </ul>
    </div></div></div>
  </div>
</div><?php }} ?>
