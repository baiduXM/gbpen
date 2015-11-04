<?php /* Smarty version Smarty-3.1.19, created on 2015-11-04 02:56:29
         compiled from "E:\yu1\unify\app\views\templates\GM0010\_footer.html" */ ?>
<?php /*%%SmartyHeaderCode:32519563973dd1dadf4-43014176%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fe557e1a0e8660a27b6494d8833a5e1ff26599de' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GM0010\\_footer.html',
      1 => 1441100483,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '32519563973dd1dadf4-43014176',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'global' => 0,
    'bot' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_563973dd254bf0_14549995',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_563973dd254bf0_14549995')) {function content_563973dd254bf0_14549995($_smarty_tpl) {?>    <div class="opacity2"></div>
    <div class="newsclass">
      <div class="share-con">
        <div class="swiper-container thumbs-cotnainer">
          <div class="swiper-wrapper">
            <div class="swiper-slide"> <a title="分享到Qzone空间" href="javascript:void(0);" class="qqzone"><img src="icon/kongjian.gif" width="60" height="60">
              <dt class=" title">Qzone</dt>
              </a></div>
            <div class="swiper-slide"><a title="分享到腾讯微博" href="javascript:void(0);"  onclick="postToWb();" class="qqweibo"><img src="icon/tengxun.gif" width="60" height="60">
              <dt class=" title">TENGXUN</dt>
              </a></div>
            <div class="swiper-slide"> <a title="分享到百度收藏" href="javascript:void(0);" class="baidusoucang"><img src="icon/baidu.gif" width="60" height="60">
              <dt class=" title">BAIDU</dt>
              </a></div>
            <div class="swiper-slide"><a title="分享到新浪微博" href="javascript:void(0)" class="xinlang"><img src="icon/xinlang.gif" width="60" height="60">
              <dt class=" title">SINA</dt>
              </a></div>
          </div>
        </div>
      </div>
      <div class="share-cance">OFF</div>
    </div>
    <div class="fixed public_bg">
      <ul class=" box" id="box">
        <li><a href="#" id="class" class="public_color1">
          <p class="fix_icon"><img src="icon/6.png" width="33" height="33"></p>
          <p class="title">NAV</p>
          </a></li>
          <?php  $_smarty_tpl->tpl_vars['bot'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['bot']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['global']->value['bottomnavs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['bot']->key => $_smarty_tpl->tpl_vars['bot']->value) {
$_smarty_tpl->tpl_vars['bot']->_loop = true;
?>
              <?php if ($_smarty_tpl->tpl_vars['bot']->value['type']!='share') {?>
              <li><a href="<?php echo $_smarty_tpl->tpl_vars['bot']->value['link'];?>
" class="public_color1">
              <p class="fix_icon"><img src="<?php echo $_smarty_tpl->tpl_vars['bot']->value['image'];?>
" width="33" height="33"></p>
              <p class="title"><?php echo $_smarty_tpl->tpl_vars['bot']->value['name'];?>
</p>
              </a></li>
              <?php } else { ?>
              <li><a href="<?php echo $_smarty_tpl->tpl_vars['bot']->value['link'];?>
" id="share_btn" class="public_color1">
              <p class="fix_icon"><img src="<?php echo $_smarty_tpl->tpl_vars['bot']->value['image'];?>
" width="33" height="33"></p>
              <p class="title"><?php echo $_smarty_tpl->tpl_vars['bot']->value['name'];?>
</p>
              </a></li>
              <?php }?>
          <?php } ?>
      </ul>
    </div><?php }} ?>
