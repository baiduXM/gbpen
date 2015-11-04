<?php /* Smarty version Smarty-3.1.19, created on 2015-09-18 03:03:33
         compiled from "E:\yu1\unify\app\views\templates\GP0028\_header.html" */ ?>
<?php /*%%SmartyHeaderCode:333955fb7f05ade7e9-11664861%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2691527dd847c18c0c62fd7c06f6a3cce740deaa' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP0028\\_header.html',
      1 => 1442538260,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '333955fb7f05ade7e9-11664861',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'logo' => 0,
    'contact' => 0,
    'global' => 0,
    'search_action' => 0,
    'navs' => 0,
    'litmit' => 0,
    'site_url' => 0,
    'nav' => 0,
    'nav_list' => 0,
    'slidepic' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55fb7f05bd6925_55223140',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55fb7f05bd6925_55223140')) {function content_55fb7f05bd6925_55223140($_smarty_tpl) {?>

        <div class='header '>
            <div class="public">
                <h1 class='logo fl'><img src="<?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
" width="241" height="162"></h1>
                <div class="h_right fr">
                        <div class="right_top">
                            <span class="tel fl "><i>尊贵预约 / </i><?php echo $_smarty_tpl->tpl_vars['contact']->value['telephone'];?>
<a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['onlin']['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['global']->value['onlin']['description'];?>
</a></span>
                             <div class='search fr'><form action="<?php echo $_smarty_tpl->tpl_vars['search_action']->value;?>
" method="GET"> <input name="" type="text" class='text_01' value="请输入产品关键字"><input name="" type="submit" class='submit_01' value=""></form>
                             </div> 
                        </div>
                    <div class='mune'>
                        <ul id="nav" class="nav" >
                         <?php $_smarty_tpl->tpl_vars['litmit'] = new Smarty_variable(8, null, 0);?>
                            <?php if (count($_smarty_tpl->tpl_vars['navs']->value)<$_smarty_tpl->tpl_vars['litmit']->value) {?><?php $_smarty_tpl->tpl_vars['litmit'] = new Smarty_variable(count($_smarty_tpl->tpl_vars['navs']->value), null, 0);?><?php }?>
                            <li class="nLi li_bg" >
                                 <a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
" >网站首页<em>HOME</em></a>    
                            </li>
                             <?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['navs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?>
                                <li class="nLi cur li_bg <?php if ($_smarty_tpl->tpl_vars['nav']->value['current']) {?>current<?php }?>">
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
" ><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
 <em><?php echo $_smarty_tpl->tpl_vars['nav']->value['en_name'];?>
</em></a>
                                         <?php if ($_smarty_tpl->tpl_vars['nav']->value['childmenu']) {?>
                                            <ul class="sub">
                                                <?php  $_smarty_tpl->tpl_vars['nav_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav_list']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['nav']->value['childmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav_list']->key => $_smarty_tpl->tpl_vars['nav_list']->value) {
$_smarty_tpl->tpl_vars['nav_list']->_loop = true;
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
                    </div>

                </div>
            </div>
        </div>

        <div class='banner '>
            <div id="slideBox" class="slideBox">
            
                <!-- <div class="hd">
                    <ul><li></li><li></li><li></li></ul>
                </div> -->
                <div class="bd">
                    <ul>
                         <?php  $_smarty_tpl->tpl_vars['slidepic'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['slidepic']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['global']->value['slidepics']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['slidepic']->key => $_smarty_tpl->tpl_vars['slidepic']->value) {
$_smarty_tpl->tpl_vars['slidepic']->_loop = true;
?>
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['slidepic']->value['link'];?>
" target="_blank"><img src="<?php echo $_smarty_tpl->tpl_vars['slidepic']->value['image'];?>
" /></a></li>
                          <?php } ?>
                    </ul>
                </div>
                <!-- 下面是前/后按钮代码，如果不需要删除即可 -->
                <a class="prev" href="javascript:void(0)"></a>
                <a class="next" href="javascript:void(0)"></a>
            </div>
        </div><?php }} ?>
