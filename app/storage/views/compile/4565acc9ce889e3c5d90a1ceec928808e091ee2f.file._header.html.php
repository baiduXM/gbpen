<?php /* Smarty version Smarty-3.1.19, created on 2015-09-02 07:33:59
         compiled from "E:\yu1\unify\app\views\templates\GP007\_header.html" */ ?>
<?php /*%%SmartyHeaderCode:1983955e6a667c24680-73270634%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4565acc9ce889e3c5d90a1ceec928808e091ee2f' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP007\\_header.html',
      1 => 1441100483,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1983955e6a667c24680-73270634',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'navs' => 0,
    'nav' => 0,
    'index' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55e6a667c34088_97648555',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55e6a667c34088_97648555')) {function content_55e6a667c34088_97648555($_smarty_tpl) {?>

<div class="headers"> 
                 <span><a href="javascript:void(0);" onclick="SetHome(this, window.location);">设为首页</a> | <a href="javascript:void(0);" onclick="shoucang(document.title, window.location);">加入收藏</a> </span>
      </div>
     <div class="menu">
              <ul class="nav">
        <li><a href="index.html">首页</a></li> 
                  <?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['navs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?> 
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a></li> 
                          <?php } ?>                                                         
                  <!--<li style="background:none;" ><a href="#" >联系方式</a></li>-->                                                                  
              </ul>
          </div> 
          <div class="banners"><img src="<?php echo $_smarty_tpl->tpl_vars['index']->value['slidepics'][0]['image'];?>
" height="309" width="996" /></div> <?php }} ?>
