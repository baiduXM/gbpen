<?php /* Smarty version Smarty-3.1.19, created on 2015-10-14 06:40:42
         compiled from "E:\yu1\unify\app\views\templates\GP0029\_links.html" */ ?>
<?php /*%%SmartyHeaderCode:23503561df8ea169897-92515062%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '761fa81d1e835d33dfa0dc4750087b2c034b1e20' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP0029\\_links.html',
      1 => 1444793318,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '23503561df8ea169897-92515062',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '_links' => 0,
    'link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_561df8ea175419_88050681',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_561df8ea175419_88050681')) {function content_561df8ea175419_88050681($_smarty_tpl) {?><select name="" size="1" class="select1">
 
 <option>友情链接</option>	

	<?php  $_smarty_tpl->tpl_vars['link'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['link']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_links']->value['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['link']->key => $_smarty_tpl->tpl_vars['link']->value) {
$_smarty_tpl->tpl_vars['link']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['link']->key;
?>
	 <option><a href="<?php echo $_smarty_tpl->tpl_vars['link']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['link']->value['title'];?>
</a></option>	
	<?php } ?>

 </select><?php }} ?>
