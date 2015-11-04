<?php /* Smarty version Smarty-3.1.19, created on 2015-07-29 01:03:50
         compiled from "E:\yu1\unify\app\views\templates\GM001\_footer.html" */ ?>
<?php /*%%SmartyHeaderCode:556955b82676e09da4-33893294%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bd442076ef66b4d06ab7a2dc1f79a162ebd0490d' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GM001\\_footer.html',
      1 => 1433921978,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '556955b82676e09da4-33893294',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'global' => 0,
    'bottomnav' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b82676e73552_30366823',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b82676e73552_30366823')) {function content_55b82676e73552_30366823($_smarty_tpl) {?><div class="share-con">
	<a title="复制链接" href="javascript:void(0)" onclick="copyLink()"><span class="copylink"></span></a>
	<a title="分享到腾讯微博" href="javascript:void(0)" onclick="postToWb();"><span class="tengxunweibo"></span></a>
	<a title="分享到新浪微博" href="javascript:void(0);"><span class="xinlang"></span></a>
	<a title="分享到Qzone空间" href="javascript:void(0);"><span class="qqzone"></span></a>
	<a title="分享到人人网" href="javascript:void(0);"><span class="renren"></span></a>
	<a title="分享到开心网" href="javascript:void(0);"><span class="kaixin"></span></a>
	<a title="分享到淘江湖" href="javascript:void(0);"><span class="taojianghu"></span></a>

	<a title="分享到豆瓣" href="javascript:void(0);"><span class="douban"></span></a>
	<a title="分享到百度收藏" href="javascript:void(0);"><span class="baidusoucang"></span></a>
</div>
<div class="fixed public_bg">
	<ul class=" box" id="box">
	
    <li><a href="#" id="class"><p class="fix_icon"><img src="icon/6.png" width="33" height="33"></p><p class="title">导航</p></a></li>
	<?php  $_smarty_tpl->tpl_vars['bottomnav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['bottomnav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['global']->value['bottomnavs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['bottomnav']->key => $_smarty_tpl->tpl_vars['bottomnav']->value) {
$_smarty_tpl->tpl_vars['bottomnav']->_loop = true;
?>
	<?php if ($_smarty_tpl->tpl_vars['bottomnav']->value['enable']) {?>
	
    <li><a href="<?php echo $_smarty_tpl->tpl_vars['bottomnav']->value['link'];?>
" id="<?php echo $_smarty_tpl->tpl_vars['bottomnav']->value['id'];?>
"><p class="<?php echo $_smarty_tpl->tpl_vars['bottomnav']->value['type'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['bottomnav']->value['image'];?>
" width="33" height="33"></p><p class="title"><?php echo $_smarty_tpl->tpl_vars['bottomnav']->value['name'];?>
</p></a></li>
	<?php }?>
	<?php } ?>
    </ul>
</div>
<?php }} ?>
