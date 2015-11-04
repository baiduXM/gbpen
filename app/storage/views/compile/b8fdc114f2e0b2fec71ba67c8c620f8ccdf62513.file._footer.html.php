<?php /* Smarty version Smarty-3.1.19, created on 2015-09-18 08:28:29
         compiled from "E:\yu1\unify\app\views\templates\GP0028\_footer.html" */ ?>
<?php /*%%SmartyHeaderCode:612055fbcb2d399cf4-12950677%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b8fdc114f2e0b2fec71ba67c8c620f8ccdf62513' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP0028\\_footer.html',
      1 => 1442538260,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '612055fbcb2d399cf4-12950677',
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
  'unifunc' => 'content_55fbcb2d3d8501_36327244',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55fbcb2d3d8501_36327244')) {function content_55fbcb2d3d8501_36327244($_smarty_tpl) {?>    
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
