<?php /* Smarty version Smarty-3.1.19, created on 2015-09-14 01:29:13
         compiled from "E:\yu1\unify\app\views\templates\GP0026\_footer.html" */ ?>
<?php /*%%SmartyHeaderCode:3107755f622e901cb82-59045553%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7c47c299a5590d9216aeed277528dabba95c4f67' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP0026\\_footer.html',
      1 => 1441100731,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3107755f622e901cb82-59045553',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'global' => 0,
    'footprint' => 0,
    'contact' => 0,
    'footscript' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55f622e907a792_59925686',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55f622e907a792_59925686')) {function content_55f622e907a792_59925686($_smarty_tpl) {?>
    <div class="bottom">
    	<dl>

		<a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['list1']['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['global']->value['list1']['name'];?>
</a> |
		<a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['list2']['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['global']->value['list2']['name'];?>
</a> |
		<a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['list3']['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['global']->value['list3']['name'];?>
</a> |
		<a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['list4']['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['global']->value['list4']['name'];?>
</a> |
		<a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['list5']['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['global']->value['list5']['name'];?>
</a> |
		<a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['list6']['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['global']->value['list6']['name'];?>
</a> |
		<a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['list7']['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['global']->value['list7']['name'];?>
</a> 



        </dl>
		<dl><?php echo $_smarty_tpl->tpl_vars['footprint']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['contact']->value['company'];?>
 地址：<?php echo $_smarty_tpl->tpl_vars['contact']->value['address'];?>
 电话：<?php echo $_smarty_tpl->tpl_vars['contact']->value['telephone'];?>
 手机：<?php echo $_smarty_tpl->tpl_vars['contact']->value['mobile'];?>
</dl>
<dl class=" banquan">技术支持：<a href="http://www.12t.cn">厦门易尔通网络科技有限公司</a> 人才支持：<a href="http://www.xmrc.com.cn">厦门人才网</a> </dl>
    </div>
<?php echo $_smarty_tpl->tpl_vars['footscript']->value;?>
  <?php }} ?>
