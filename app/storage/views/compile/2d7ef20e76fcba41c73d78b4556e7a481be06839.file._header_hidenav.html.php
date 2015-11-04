<?php /* Smarty version Smarty-3.1.19, created on 2015-11-04 02:56:29
         compiled from "E:\yu1\unify\app\views\templates\GM0010\_header_hidenav.html" */ ?>
<?php /*%%SmartyHeaderCode:12614563973dd1b7b62-78300002%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2d7ef20e76fcba41c73d78b4556e7a481be06839' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GM0010\\_header_hidenav.html',
      1 => 1441100483,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12614563973dd1b7b62-78300002',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'navs' => 0,
    'nav' => 0,
    'nav_list' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_563973dd1dadf3_38698274',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_563973dd1dadf3_38698274')) {function content_563973dd1dadf3_38698274($_smarty_tpl) {?>  <div class="class page-prev public_bg1">
    <h1 class="class-top"><span class="class-close">×</span>Quick</h1>
    <div class="class-m">
      <div class="swiper-container scroll-container">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <ul class="class-list">
              <li><a href="index.html">Home</a></li>
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
