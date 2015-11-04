<?php /* Smarty version Smarty-3.1.19, created on 2015-10-14 08:59:44
         compiled from "E:\yu1\unify\app\views\templates\GP0026\_pagenavs_sub3.html" */ ?>
<?php /*%%SmartyHeaderCode:30470561e19800683c7-41159413%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2df580e43be88d3f0b270da6474d7fd8733d5474' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP0026\\_pagenavs_sub3.html',
      1 => 1441100731,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '30470561e19800683c7-41159413',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'pagenavs' => 0,
    'posnavs' => 0,
    'nav' => 0,
    'nav_list' => 0,
    'nav_next_list' => 0,
    'nav_next_listt' => 0,
    '_pagenavs_sub3' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_561e198011fd73_62928400',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_561e198011fd73_62928400')) {function content_561e198011fd73_62928400($_smarty_tpl) {?><!-- 二级栏目列表（展示到三级子栏目） -->
<?php if ($_smarty_tpl->tpl_vars['pagenavs']->value) {?>
<nav>
	<h3><span><?php echo $_smarty_tpl->tpl_vars['posnavs']->value[0]['name'];?>
</span></h3>	<!-- 下表为0的面包屑导航正好是一级栏目的当前栏目 -->
	<ul class="first">
		<?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pagenavs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?>
		<li class="<?php if ($_smarty_tpl->tpl_vars['nav']->value['current']) {?>current<?php }?>">
			<a href="<?php if ($_smarty_tpl->tpl_vars['nav']->value['childmenu']) {?>javascript:;<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
<?php }?>"><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a>
			<?php if ($_smarty_tpl->tpl_vars['nav']->value['childmenu']) {?>
			<ul class="sub">
				<?php  $_smarty_tpl->tpl_vars['nav_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav_list']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['nav']->value['childmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav_list']->key => $_smarty_tpl->tpl_vars['nav_list']->value) {
$_smarty_tpl->tpl_vars['nav_list']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['nav_list']->key;
?>
				<li class="<?php if ($_smarty_tpl->tpl_vars['nav_list']->value['current']) {?>current<?php }?>"><a href="<?php if ($_smarty_tpl->tpl_vars['nav_list']->value['childmenu']) {?>javascript:;<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['nav_list']->value['link'];?>
<?php }?>"><?php echo $_smarty_tpl->tpl_vars['nav_list']->value['name'];?>
</a>
					<?php if ($_smarty_tpl->tpl_vars['nav_list']->value['childmenu']) {?>
						<ul class="three">
							<?php  $_smarty_tpl->tpl_vars['nav_next_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav_next_list']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['nav_list']->value['childmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav_next_list']->key => $_smarty_tpl->tpl_vars['nav_next_list']->value) {
$_smarty_tpl->tpl_vars['nav_next_list']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['nav_next_list']->key;
?>
							<li class="<?php if ($_smarty_tpl->tpl_vars['nav_next_list']->value['current']) {?>current<?php }?>"><a href="<?php echo $_smarty_tpl->tpl_vars['nav_next_list']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav_next_listt']->value['name'];?>
</a>
							<?php } ?>
						</ul>
						<?php }?>
				</li>
				<?php } ?>
			</ul>
			<?php }?>
		</li>
		<?php } ?>
	</ul>
</nav>
<?php } else { ?>	<!-- 让客服自己选择，如果当前无子栏目 展现的目标栏目 -->
<nav>
	<h3><span><?php echo $_smarty_tpl->tpl_vars['_pagenavs_sub3']->value['pagenavs']['name'];?>
</span></h3>
	<ul class="first">
		<?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_pagenavs_sub3']->value['pagenavs']['childmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?>
		<li class="<?php if ($_smarty_tpl->tpl_vars['nav']->value['current']) {?>current<?php }?>">
			<a href="<?php if ($_smarty_tpl->tpl_vars['nav']->value['childmenu']) {?>javascript:;<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
<?php }?>"><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a>
			<?php if ($_smarty_tpl->tpl_vars['nav']->value['childmenu']) {?>
			<ul class="sub">
				<?php  $_smarty_tpl->tpl_vars['nav_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav_list']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['nav']->value['childmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav_list']->key => $_smarty_tpl->tpl_vars['nav_list']->value) {
$_smarty_tpl->tpl_vars['nav_list']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['nav_list']->key;
?>
				<li class="<?php if ($_smarty_tpl->tpl_vars['nav_list']->value['current']) {?>current<?php }?>"><a href="<?php if ($_smarty_tpl->tpl_vars['nav_list']->value['childmenu']) {?>javascript:;<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['nav_list']->value['link'];?>
<?php }?>"><?php echo $_smarty_tpl->tpl_vars['nav_list']->value['name'];?>
</a>
					<?php if ($_smarty_tpl->tpl_vars['nav_list']->value['childmenu']) {?>
						<ul class="three">
							<?php  $_smarty_tpl->tpl_vars['nav_next_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav_next_list']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['nav_list']->value['childmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav_next_list']->key => $_smarty_tpl->tpl_vars['nav_next_list']->value) {
$_smarty_tpl->tpl_vars['nav_next_list']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['nav_next_list']->key;
?>
							<li class="<?php if ($_smarty_tpl->tpl_vars['nav_next_list']->value['current']) {?>current<?php }?>"><a href="<?php echo $_smarty_tpl->tpl_vars['nav_next_list']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav_next_listt']->value['name'];?>
</a>
							<?php } ?>
						</ul>
						<?php }?>
				</li>
				<?php } ?>
			</ul>
			<?php }?>
		</li>
		<?php } ?>
	</ul>
</nav>
<?php }?><?php }} ?>
