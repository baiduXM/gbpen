<?php /* Smarty version Smarty-3.1.19, created on 2015-10-14 06:40:42
         compiled from "E:\yu1\unify\app\views\templates\GP0029\_footer.html" */ ?>
<?php /*%%SmartyHeaderCode:25646561df8ea156002-90896129%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0cf3cd482808cc2d2f56e6c641eb55d72c753e0c' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP0029\\_footer.html',
      1 => 1444793318,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '25646561df8ea156002-90896129',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'global' => 0,
    'footprint' => 0,
    'footscript' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_561df8ea161b81_01392698',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_561df8ea161b81_01392698')) {function content_561df8ea161b81_01392698($_smarty_tpl) {?>    
        <div class='footer'>
            
            <div class="public">
            <img src="<?php echo $_smarty_tpl->tpl_vars['global']->value['ftlogo']['image'];?>
">
            <span class="footer_li"><?php echo $_smarty_tpl->tpl_vars['footprint']->value;?>
</span>

        <?php echo $_smarty_tpl->getSubTemplate ("./_links.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

            </div>
            
                
        </div>
  <?php echo $_smarty_tpl->tpl_vars['footscript']->value;?>
<?php }} ?>
