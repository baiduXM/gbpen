<?php /* Smarty version Smarty-3.1.19, created on 2015-09-02 07:34:21
         compiled from "E:\yu1\unify\app\views\templates\GP0022\_pagenavs_sub3.html" */ ?>
<?php /*%%SmartyHeaderCode:169155b1e8bf977481-49640710%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '39949f44f80ed42fcdd2ad73730ddce134db6cfb' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP0022\\_pagenavs_sub3.html',
      1 => 1441100483,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '169155b1e8bf977481-49640710',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b1e8bf9d8f16_75019385',
  'variables' => 
  array (
    'pagenavs' => 0,
    'posnavs' => 0,
    'nav' => 0,
    'nav_list' => 0,
    '_pagenavs_sub3' => 0,
    'site_url' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b1e8bf9d8f16_75019385')) {function content_55b1e8bf9d8f16_75019385($_smarty_tpl) {?><!-- 二级栏目列表（展示到三级子栏目） -->
<?php if ($_smarty_tpl->tpl_vars['pagenavs']->value) {?>
<nav>
<h4><strong><?php echo $_smarty_tpl->tpl_vars['posnavs']->value[0]['name'];?>
</strong><br /><span><?php echo $_smarty_tpl->tpl_vars['posnavs']->value[0]['en_name'];?>
</span><a href="<?php echo $_smarty_tpl->tpl_vars['posnavs']->value[0]['link'];?>
" class='more-btn'></a></h4>    
	<ul class="list clearfix">
<?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pagenavs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?>
		<li class="<?php if ($_smarty_tpl->tpl_vars['nav']->value['current']) {?>current<?php }?>">
			<a href="javascript:;<?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a>
			<?php if ($_smarty_tpl->tpl_vars['nav']->value['childmenu']) {?>
			<ul class="second clearfix">
				<?php  $_smarty_tpl->tpl_vars['nav_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav_list']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['nav']->value['childmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav_list']->key => $_smarty_tpl->tpl_vars['nav_list']->value) {
$_smarty_tpl->tpl_vars['nav_list']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['nav_list']->key;
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
</nav>
<?php } else { ?>	<!-- 让客服自己选择，如果当前无子栏目 展现的目标栏目 -->
<nav>
	<h4><strong><?php echo $_smarty_tpl->tpl_vars['_pagenavs_sub3']->value['pagenavs']['name'];?>
</strong><br /><span><?php echo $_smarty_tpl->tpl_vars['_pagenavs_sub3']->value['pagenavs']['en_name'];?>
</span><a href="<?php echo $_smarty_tpl->tpl_vars['posnavs']->value[0]['link'];?>
" class='more-btn'></a></h4>
	<ul class="list clearfix">
		<?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_pagenavs_sub3']->value['pagenavs']['childmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?>
		<li class="<?php if ($_smarty_tpl->tpl_vars['nav']->value['current']) {?>current<?php }?>">
			<a href="<?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a>
			<?php if ($_smarty_tpl->tpl_vars['nav']->value['childmenu']) {?>
			<ul class="second clearfix">
				<?php  $_smarty_tpl->tpl_vars['nav_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav_list']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['nav']->value['childmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav_list']->key => $_smarty_tpl->tpl_vars['nav_list']->value) {
$_smarty_tpl->tpl_vars['nav_list']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['nav_list']->key;
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
</nav>

	
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/function.js"></script>
<?php }?>


<?php }} ?>
