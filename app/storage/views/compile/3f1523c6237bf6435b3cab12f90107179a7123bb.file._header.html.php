<?php /* Smarty version Smarty-3.1.19, created on 2015-09-01 09:47:47
         compiled from "E:\yu1\unify\app\views\templates\GM0012\_header.html" */ ?>
<?php /*%%SmartyHeaderCode:1829055b603db0da069-74040500%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3f1523c6237bf6435b3cab12f90107179a7123bb' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GM0012\\_header.html',
      1 => 1441100483,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1829055b603db0da069-74040500',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b603db0e9a61_81037085',
  'variables' => 
  array (
    'site_url' => 0,
    'navs' => 0,
    'nav' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b603db0e9a61_81037085')) {function content_55b603db0e9a61_81037085($_smarty_tpl) {?>  <div class="menu public_bg ny-menu">
            	<div class="nav">
            	<a class="arrow-left" href="#">&lt;</a> 
   				 <a class="arrow-right" href="#">&gt;</a>
               <div class="swiper-nav swiper-container">
                  <div class="swiper-wrapper">
                      <div class="swiper-slide"><span><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
">网站首页</a></span></div>
					  <?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['navs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?>
                      <div class="swiper-slide"><span><a href="<?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a></span></div>
					  <?php } ?>
                      
                  </div>
				</div>
                </div>
    </div><?php }} ?>
