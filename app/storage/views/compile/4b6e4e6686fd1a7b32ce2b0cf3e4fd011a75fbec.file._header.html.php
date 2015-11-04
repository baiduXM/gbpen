<?php /* Smarty version Smarty-3.1.19, created on 2015-07-28 08:04:01
         compiled from "E:\yu1\unify\app\views\templates\GP0024\_header.html" */ ?>
<?php /*%%SmartyHeaderCode:2306355b73771e57277-82478734%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4b6e4e6686fd1a7b32ce2b0cf3e4fd011a75fbec' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP0024\\_header.html',
      1 => 1438070630,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2306355b73771e57277-82478734',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site_url' => 0,
    'logo' => 0,
    'navs' => 0,
    'nav' => 0,
    'nav_list' => 0,
    '_header' => 0,
    'slidepic' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b73771e95a81_26980601',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b73771e95a81_26980601')) {function content_55b73771e95a81_26980601($_smarty_tpl) {?>
<div class="header">
        	<div class="logo"><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
" height="61" width="366" /></a></div>
            <div class="ritip"><a href="#">·返回首页</a> <a href="#">·设为首页</a>  <a href="#">· 加入收藏</a>
            </div>
        </div>
		
		 <div id="menu" class="menu">
            <ul>
	<?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['navs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?>
              
			  <li class="<?php if ($_smarty_tpl->tpl_vars['nav']->value['current']) {?>current<?php }?>"><a href="<?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a>
			  <?php if ($_smarty_tpl->tpl_vars['nav']->value['childmenu']) {?>
					<ul class="clearfix">
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
		 
		 <div class="banners">
                <div id="slideBox" class="slideBox">
                    <div class="hd">
                        <ul>  <?php  $_smarty_tpl->tpl_vars['slidepic'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['slidepic']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_header']->value['slidepics']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['slidepic']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['slidepic']->key => $_smarty_tpl->tpl_vars['slidepic']->value) {
$_smarty_tpl->tpl_vars['slidepic']->_loop = true;
 $_smarty_tpl->tpl_vars['slidepic']->index++;
?>
        <li><?php echo $_smarty_tpl->tpl_vars['slidepic']->index+1;?>
</li>
        <?php } ?>
</ul>
                    </div>
                    <div class="bd">
                        <ul>
						<?php  $_smarty_tpl->tpl_vars['slidepic'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['slidepic']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['_header']->value['slidepics']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['slidepic']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['slidepic']->key => $_smarty_tpl->tpl_vars['slidepic']->value) {
$_smarty_tpl->tpl_vars['slidepic']->_loop = true;
 $_smarty_tpl->tpl_vars['slidepic']->index++;
?>
							<li><a href="<?php echo $_smarty_tpl->tpl_vars['slidepic']->value['link'];?>
" target="_blank"><img src="<?php echo $_smarty_tpl->tpl_vars['slidepic']->value['image'];?>
"  alt="<?php echo $_smarty_tpl->tpl_vars['slidepic']->value['title'];?>
"/></a></li>
					<?php } ?>                
                        </ul>
                    </div>
        
                    <!-- 下面是前/后按钮代码，如果不需要删除即可 -->
                    <a class="prev" href="javascript:void(0)"></a>
                    <a class="next" href="javascript:void(0)"></a>
        
                </div>
        
               
               
               </div> <?php }} ?>
