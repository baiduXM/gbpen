<?php /* Smarty version Smarty-3.1.19, created on 2015-07-24 07:05:12
         compiled from "E:\yu1\unify\app\views\templates\GM004\_footer.html" */ ?>
<?php /*%%SmartyHeaderCode:1623755b1e3a8ea56c7-96055286%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1a41e22da25591440506da2b9234bc714b5c720d' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GM004\\_footer.html',
      1 => 1433921976,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1623755b1e3a8ea56c7-96055286',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'global' => 0,
    'site_url' => 0,
    'bot' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b1e3a906cd02_39966264',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b1e3a906cd02_39966264')) {function content_55b1e3a906cd02_39966264($_smarty_tpl) {?><!-- 分享 -->  
<div class="newsclass">
<div class="share-con">
<div class="swiper-container thumbs-cotnainer">
		<div class="swiper-wrapper">
            <div class="swiper-slide"> <a title="<?php echo $_smarty_tpl->tpl_vars['global']->value['Qzone']['title'];?>
" href="javascript:void(0);" class="qqzone"><img src="<?php echo $_smarty_tpl->tpl_vars['global']->value['Qzone']['image'];?>
" width="60" height="60"><dt class=" title"><?php echo $_smarty_tpl->tpl_vars['global']->value['Qzone']['description'];?>
</dt></a></div> 
            <div class="swiper-slide"><a title="<?php echo $_smarty_tpl->tpl_vars['global']->value['TCweibo']['title'];?>
" href="javascript:void(0);"  onclick="postToWb();" class="qqweibo"><img src="<?php echo $_smarty_tpl->tpl_vars['global']->value['TCweibo']['image'];?>
" width="60" height="60"><dt class=" title"><?php echo $_smarty_tpl->tpl_vars['global']->value['TCweibo']['description'];?>
</dt></a></div> 
            <div class="swiper-slide"> <a title="<?php echo $_smarty_tpl->tpl_vars['global']->value['baidu']['title'];?>
" href="javascript:void(0);" class="baidusoucang"><img src="<?php echo $_smarty_tpl->tpl_vars['global']->value['baidu']['image'];?>
" width="60" height="60"><dt class=" title"><?php echo $_smarty_tpl->tpl_vars['global']->value['baidu']['description'];?>
</dt></a></div>
            <div class="swiper-slide"><a title="<?php echo $_smarty_tpl->tpl_vars['global']->value['sinaweibo']['title'];?>
" href="javascript:void(0)" class="xinlang"><img src="<?php echo $_smarty_tpl->tpl_vars['global']->value['sinaweibo']['image'];?>
" width="60" height="60"><dt class=" title"><?php echo $_smarty_tpl->tpl_vars['global']->value['sinaweibo']['description'];?>
</dt></a></div>  
		</div>
	</div>
</div>  
<div class="share-cance">取消</div>     
</div>
<!-- 分享End -->
<!-- 底部导航 -->
<div class="fixed public-bg1">
	<ul class=" box" id="box">
        <li><a href="#" id="class"><p class="fix_icon"><img src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
icon/6.png" width="33" width="33"></p><p class="title">导航</p></a></li>
        <?php  $_smarty_tpl->tpl_vars['bot'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['bot']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['global']->value['bottomnavs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['bot']->key => $_smarty_tpl->tpl_vars['bot']->value) {
$_smarty_tpl->tpl_vars['bot']->_loop = true;
?>
            <?php if ($_smarty_tpl->tpl_vars['bot']->value['type']!='share') {?>
            <li><a href="#"><p class="fix_icon"><img src="<?php echo $_smarty_tpl->tpl_vars['bot']->value['image'];?>
" width="33" width="33"></p><p class="title"><?php echo $_smarty_tpl->tpl_vars['bot']->value['name'];?>
</p></a></li>
            <?php } else { ?>
            <li><a href="#" id="share_btn"><p class="fix_icon"><img src="<?php echo $_smarty_tpl->tpl_vars['bot']->value['image'];?>
" width="33" width="33"></p><p class="title"><?php echo $_smarty_tpl->tpl_vars['bot']->value['name'];?>
</p></a></li>
            <?php }?>
        <?php } ?>
    </ul>
</div>
<!-- 底部导航End -->
<?php }} ?>
