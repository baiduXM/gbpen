<?php /* Smarty version Smarty-3.1.19, created on 2015-08-13 09:11:02
         compiled from "E:\yu1\unify\app\views\templates\GP0023\_header.html" */ ?>
<?php /*%%SmartyHeaderCode:2127955b6e55b4ab7c7-79678065%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '632e13cc5ffeb0bc574a4b314efe09d438f9e4fd' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP0023\\_header.html',
      1 => 1438054407,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2127955b6e55b4ab7c7-79678065',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b6e55b4de467_71404898',
  'variables' => 
  array (
    'logo' => 0,
    'contact' => 0,
    'navs' => 0,
    'nav' => 0,
    'nav_list' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b6e55b4de467_71404898')) {function content_55b6e55b4de467_71404898($_smarty_tpl) {?><div class='header'>
	<div class='header-box'>
		<a href='index.html' class="logo"><img src="<?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
" width="164" height="74"></a>
		<div class='headerR'>
			<div class='set'>
				<a href='###'>设为首页</a> | <a href='###'>加入收藏</a>
			</div>
			<div class='tel'>
				服务热线：<span><?php echo $_smarty_tpl->tpl_vars['contact']->value['telephone'];?>
</span>
			</div>
		</div>
	</div>
</div>

<div class='nav-box'>
    <ul id="nav" class="nav clearfix">
      <li class="nLi on">
        <h3><a href="#" target="_blank">网站首页</a></h3>
      </li>

      <?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['navs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?>
      <li class="nLi <?php if ($_smarty_tpl->tpl_vars['nav']->value['current']) {?>current<?php }?>">
        <h3><a href="<?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a></h3>
        <?php if ($_smarty_tpl->tpl_vars['nav']->value['childmenu']) {?>
          <ul class="sub">
            <?php  $_smarty_tpl->tpl_vars['nav_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav_list']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['nav']->value['childmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav_list']->key => $_smarty_tpl->tpl_vars['nav_list']->value) {
$_smarty_tpl->tpl_vars['nav_list']->_loop = true;
?>
            <li class="<?php if ($_smarty_tpl->tpl_vars['nav_list']->value['current']) {?>current<?php }?>"><a href="<?php echo $_smarty_tpl->tpl_vars['nav_list']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav_list']->value['name'];?>
</a></li>
            <?php } ?>
          </ul>
        <?php }?>
      </li>
      <?php } ?>
                                                    
    </ul>

</div>
            <?php }} ?>
