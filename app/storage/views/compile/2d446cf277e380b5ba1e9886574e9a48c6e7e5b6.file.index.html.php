<?php /* Smarty version Smarty-3.1.19, created on 2015-09-02 07:33:59
         compiled from "E:\yu1\unify\app\views\templates\GP007\index.html" */ ?>
<?php /*%%SmartyHeaderCode:2012655e6a66755dee0-98335210%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2d446cf277e380b5ba1e9886574e9a48c6e7e5b6' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP007\\index.html',
      1 => 1441100483,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2012655e6a66755dee0-98335210',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site_another_url' => 0,
    'keywords' => 0,
    'description' => 0,
    'favicon' => 0,
    'site_url' => 0,
    'stylecolor' => 0,
    'title' => 0,
    'headscript' => 0,
    'pagenavs' => 0,
    'nav' => 0,
    'contact' => 0,
    'index' => 0,
    'article' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55e6a667be5e79_11380004',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55e6a667be5e79_11380004')) {function content_55e6a667be5e79_11380004($_smarty_tpl) {?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <script type="text/javascript">
		// 跳转手机页面
		<?php if ($_smarty_tpl->tpl_vars['site_another_url']->value) {?>
			if (!!navigator.userAgent.match(/AppleWebKit.*Mobile.*/)) {
				location.href = '<?php echo $_smarty_tpl->tpl_vars['site_another_url']->value;?>
';
			}
		<?php }?>
		</script>
<meta http-equiv="x-ua-compatible" content="ie=7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" />
<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
" />
<link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
" type="image/x-icon" />
<link rel="stylesheet" rev="stylesheet" href="http://chanpin.xm12t.com.cn/css/global.css" type="text/css" /> 
<link href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" rev="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/style_<?php echo $_smarty_tpl->tpl_vars['stylecolor']->value;?>
.css" type="text/css" />
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/index.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="js/dd_delatedpng.js" ></script>
<script type="text/javascript">//如果多个element使用PNG,则用','分隔
DD_belatedPNG.fix('div,ul,li,a,p,img');
</script>
<![endif]--> 
<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<?php echo $_smarty_tpl->tpl_vars['headscript']->value;?>
 
</head>

<body>
	<div class="wappers">
             <?php echo $_smarty_tpl->getSubTemplate ('./_header.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
 
          
          <div class="containers">
          	<div class="left_list">
                <div class="list">
                     <h3><span><a href="<?php echo $_smarty_tpl->tpl_vars['pagenavs']->value[0]['link'];?>
"><img src="images/more.gif" height="5" width="29" /></a></span><?php echo $_smarty_tpl->tpl_vars['pagenavs']->value[0]['name'];?>
</h3>
                     <div class="listbg">
                         <ul >
                                 
                            <?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['pagenavs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?>    
                                <li><a href="<?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a></li>
                            <?php } ?>                                           
                       </ul>
                     </div>
                </div>     
                <div class="contact">
                	<h3>联系我们</h3>
                    <div class="lixit">
                    	<h1><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h1>
                      地  址：<?php echo $_smarty_tpl->tpl_vars['contact']->value['address'];?>
<br />
手  机：<?php echo $_smarty_tpl->tpl_vars['contact']->value['mobile'];?>
 <br />
电  话：<?php echo $_smarty_tpl->tpl_vars['contact']->value['telephone'];?>
<br />
<!--传  真：0597-2665981<br />-->
联系人：<?php echo $_smarty_tpl->tpl_vars['contact']->value['name'];?>
<br />
邮  箱：<?php echo $_smarty_tpl->tpl_vars['contact']->value['mail'];?>
<br />
                    </div>
                </div>           
            </div>
            <div class="right_t">
            	<div class="tcopnmts">
                    <div class="about_t">
                    <h3><span><a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['about_image']['link'];?>
">更多 &gt;&gt;</a></span><?php echo $_smarty_tpl->tpl_vars['index']->value['about_title'];?>
</h3>
                <div class="about_op"><img src="<?php echo $_smarty_tpl->tpl_vars['index']->value['about_image']['image'];?>
" height="103" width="136" />
                     <?php echo $_smarty_tpl->tpl_vars['index']->value['about_description'];?>

                    <span><a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['about_image']['link'];?>
">【查看更多】</a></span>
                </div>                    
                    </div>
                	
                    <div  class="news">
                    <h3><span><a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['list1']['link'];?>
">更多&gt;&gt;</a></span><?php echo $_smarty_tpl->tpl_vars['index']->value['list1']['name'];?>
</h3>
                    <div class="zx_news">
                         <ul> 	
                             <?php  $_smarty_tpl->tpl_vars['article'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['article']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['list1']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['article']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['article']->key => $_smarty_tpl->tpl_vars['article']->value) {
$_smarty_tpl->tpl_vars['article']->_loop = true;
 $_smarty_tpl->tpl_vars['article']->index++;
?>
                      <li><a href="<?php echo $_smarty_tpl->tpl_vars['article']->value['link'];?>
"><span><?php echo $_smarty_tpl->tpl_vars['article']->value['pubdate'];?>
</span><?php echo $_smarty_tpl->tpl_vars['article']->value['title'];?>
</a></li>
                      <?php if ($_smarty_tpl->tpl_vars['article']->index+1==5) {?><?php break 1?><?php }?>
                    <?php } ?>  
                          </ul>  
                    </div>                    
                    </div>
                </div>
<div class="pic_show">
            	<h3><span><a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['list2']['link'];?>
">更多&gt;&gt;</a></span><?php echo $_smarty_tpl->tpl_vars['index']->value['list2']['name'];?>
</h3>
<div class="showpro">
              <!--下面是向左滚动代码-->
        <div id="colee_left" style="overflow:hidden;width:97%; margin:0px 10px;  ">
          <table cellpadding="0" cellspacing="0" border="0" style="margin-top:10px;">
            <tr>
              <td id="colee_left1" valign="top" align="center"><table cellpadding="2" cellspacing="0" border="0">
                <tr align="center">
                   <?php  $_smarty_tpl->tpl_vars['article'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['article']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['list2']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['article']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['article']->key => $_smarty_tpl->tpl_vars['article']->value) {
$_smarty_tpl->tpl_vars['article']->_loop = true;
 $_smarty_tpl->tpl_vars['article']->index++;
?>
                      <td><a href="<?php echo $_smarty_tpl->tpl_vars['article']->value['link'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['article']->value['image'];?>
" height="150" width="159"/><br /><?php echo $_smarty_tpl->tpl_vars['article']->value['title'];?>
 </a></td>
                       <?php if ($_smarty_tpl->tpl_vars['article']->index+1==$_smarty_tpl->tpl_vars['article']->count/2) {?><?php break 1?><?php }?>
                    <?php } ?>   
                  
                </tr>
              </table></td>
              <td id="colee_left2" valign="top"></td>
            </tr>
          </table>
            <table cellpadding="0" cellspacing="0" border="0" style="margin-top:0px;">
            <tr>
              <td id="colee_left1" valign="top" align="center">
                  <table cellpadding="2" cellspacing="0" border="0">
                <tr align="center">
                  <?php  $_smarty_tpl->tpl_vars['article'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['article']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['list2']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['article']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['article']->key => $_smarty_tpl->tpl_vars['article']->value) {
$_smarty_tpl->tpl_vars['article']->_loop = true;
 $_smarty_tpl->tpl_vars['article']->index++;
?>
                   <?php if ($_smarty_tpl->tpl_vars['article']->index+1>$_smarty_tpl->tpl_vars['article']->count/2) {?>
                      <td><a href="<?php echo $_smarty_tpl->tpl_vars['article']->value['link'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['article']->value['image'];?>
" height="150" width="159"/><br /><?php echo $_smarty_tpl->tpl_vars['article']->value['title'];?>
</a></td> 
                      <?php }?>
                    <?php } ?>   
                </tr>
              </table></td>
              <td id="colee_left2" valign="top"></td>
            </tr>
          </table>         
        </div>
       
                
                </div>                
        
        </div>
            </div>
          </div>
          <?php echo $_smarty_tpl->getSubTemplate ('./_footer.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
 
                   
      </div>
</body>
</html>
















<?php }} ?>
