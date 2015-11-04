<?php /* Smarty version Smarty-3.1.19, created on 2015-09-18 08:28:29
         compiled from "E:\yu1\unify\app\views\templates\GP0028\_links.html" */ ?>
<?php /*%%SmartyHeaderCode:358555fbcb2d3e4088-25327784%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c8c4fd8ef435b3261c9eb2f26f9ce62226f3ffde' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP0028\\_links.html',
      1 => 1442538260,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '358555fbcb2d3e4088-25327784',
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
  'unifunc' => 'content_55fbcb2d3efc06_07912058',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55fbcb2d3efc06_07912058')) {function content_55fbcb2d3efc06_07912058($_smarty_tpl) {?><select name="" size="1" class="select1">
 
 <option>友情链接</option>	

	<?php  $_smarty_tpl->tpl_vars['link'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['link']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['_links']->value['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['link']->key => $_smarty_tpl->tpl_vars['link']->value) {
$_smarty_tpl->tpl_vars['link']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['link']->key;
?>
	 <option data-link="<?php echo $_smarty_tpl->tpl_vars['link']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['link']->value['title'];?>
</option>	
	<?php } ?>

 </select>

<?php }} ?>
