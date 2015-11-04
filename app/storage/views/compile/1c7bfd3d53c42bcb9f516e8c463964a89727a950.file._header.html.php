<?php /* Smarty version Smarty-3.1.19, created on 2015-09-02 07:34:12
         compiled from "E:\yu1\unify\app\views\templates\GP0022\_header.html" */ ?>
<?php /*%%SmartyHeaderCode:2702955b1e3a9b4a387-63648962%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1c7bfd3d53c42bcb9f516e8c463964a89727a950' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP0022\\_header.html',
      1 => 1441100483,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2702955b1e3a9b4a387-63648962',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b1e3a9c6b4d1_01777528',
  'variables' => 
  array (
    'site_url' => 0,
    'logo' => 0,
    'global' => 0,
    'navs' => 0,
    'nav' => 0,
    'nav_list' => 0,
    'contact' => 0,
    'slidepic' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b1e3a9c6b4d1_01777528')) {function content_55b1e3a9c6b4d1_01777528($_smarty_tpl) {?>
<header>
	<section class="headtop">
<div class='header'>

	<div class='container'>
        <div class="logo"><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
" width="403" height="86"></a></div>
		<span class="home"><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
">返回首页</a> | <a href='<?php echo $_smarty_tpl->tpl_vars['global']->value['map']['link'];?>
'>网站地图</a></span>
	</div>
</div>
        		
<div class="nav-box"> 
<div class="menu">      
<ul id="nav" class="nav clearfix">
			<li class="nLi on" style="width:<?php echo 100/(count($_smarty_tpl->tpl_vars['navs']->value)+1);?>
%">
				<h3><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
" target="_blank">首页</a></h3>
			</li>
            <?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['navs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?>
			<li  class="fl<?php if ($_smarty_tpl->tpl_vars['nav']->value['current']) {?> current<?php }?> nLi" style="width:<?php echo 100/(count($_smarty_tpl->tpl_vars['navs']->value)+1);?>
%">
					<h3><a href="<?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a></h3>
				<ul class="sub clearfix">
                <?php if ($_smarty_tpl->tpl_vars['nav']->value['childmenu']) {?>
					<?php  $_smarty_tpl->tpl_vars['nav_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav_list']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['nav']->value['childmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav_list']->key => $_smarty_tpl->tpl_vars['nav_list']->value) {
$_smarty_tpl->tpl_vars['nav_list']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['nav_list']->key;
?>
					<li class="<?php if ($_smarty_tpl->tpl_vars['nav']->value['current']) {?>current<?php }?>"><a href="<?php echo $_smarty_tpl->tpl_vars['nav_list']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav_list']->value['name'];?>
</a></li>
					<?php } ?>
                    <?php }?>
				</ul>
			</li>
          <?php } ?>
                                          
		</ul>
		<div class='tel'>
			<span>全国免费热线:</span><?php echo $_smarty_tpl->tpl_vars['contact']->value['telephone'];?>

		</div>        
        </div>     
       </div>
<script type="text/javascript">
	/*SuperSlide图片切换*/
	jQuery(".focusBox").slide({ mainCell:".pic",effect:"fold", autoPlay:true, delayTime:500, trigger:"click"});
</script>             
             <div class="banner">
             		<div id="slideBox" class="slideBox">
			<div class="hd">
				<ul><li>1</li><li>2</li><li>3</li></ul>
			</div>
			<div class="bd">
				<ul>
                  <?php  $_smarty_tpl->tpl_vars['slidepic'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['slidepic']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['global']->value['slidepics']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['slidepic']->key => $_smarty_tpl->tpl_vars['slidepic']->value) {
$_smarty_tpl->tpl_vars['slidepic']->_loop = true;
?>
                      <li><a href="<?php echo $_smarty_tpl->tpl_vars['slidepic']->value['link'];?>
" target="_blank"><img src="<?php echo $_smarty_tpl->tpl_vars['slidepic']->value['image'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['slidepic']->value['title'];?>
" /></a></li>
                    
                    <?php } ?>                     
					
				</ul>
			</div>	


		</div>


             </div> 
 </section>
 </header>
 
 




<?php }} ?>
