<?php /* Smarty version Smarty-3.1.19, created on 2015-07-28 08:04:01
         compiled from "E:\yu1\unify\app\views\templates\GP0024\index.html" */ ?>
<?php /*%%SmartyHeaderCode:2011855b73771d4d838-62389259%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e0bd870e40b350df4e97448e1b5c9dbd325ee03c' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP0024\\index.html',
      1 => 1438070630,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2011855b73771d4d838-62389259',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'keywords' => 0,
    'description' => 0,
    'site_url' => 0,
    'stylecolor' => 0,
    'site_another_url' => 0,
    'title' => 0,
    'headscript' => 0,
    'index' => 0,
    'article' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b73771e3bce8_16231353',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b73771e3bce8_16231353')) {function content_55b73771e3bce8_16231353($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include 'E:\\yu1\\unify\\vendor\\dark\\smarty-view\\src\\Dark\\SmartyView\\Smarty\\libs\\plugins\\modifier.truncate.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="x-ua-compatible" content="ie=8" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" />
<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
" />

<script type="text/javascript" src="http://common.mn.sina.com.cn/public/resource/lib/jquery.jcarousellite.js"></script>

<link rel="stylesheet" rev="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/style_<?php echo $_smarty_tpl->tpl_vars['stylecolor']->value;?>
.css" type="text/css" />
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jquery_sz.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jquery1.42.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jquery.SuperSlide.2.1.js"></script>


<!--[if IE 6]>
<script type="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
text/javascript" src="js/dd_delatedpng.js" ></script>
<script type="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
text/javascript">//如果多个element使用PNG,则用','分隔
DD_belatedPNG.fix('div,ul,li,a,p,img');
</script>
<![endif]-->


<link href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/index.js"></script>
<script type="text/javascript">
	// 跳转手机页面
	<?php if ($_smarty_tpl->tpl_vars['site_another_url']->value) {?>
		if (!!navigator.userAgent.match(/AppleWebKit.*Mobile.*/)) {
			location.href = '<?php echo $_smarty_tpl->tpl_vars['site_another_url']->value;?>
';
		}
	<?php }?>
	</script>


	
<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<?php echo $_smarty_tpl->tpl_vars['headscript']->value;?>

</head>

<body>
	<div class="wappers">
    	<?php echo $_smarty_tpl->getSubTemplate ("./_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

       

         <div class="containers">
         	<div class="index">
            	<div class="about">
                	<div class="titla"><span><a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['about']['link'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
images/more.jpg" height="12" width="39" /></a></span><img src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
images/icona.jpg" /><?php echo $_smarty_tpl->tpl_vars['index']->value['about']['name'];?>
</div>
                    <div class="imga"><img src="<?php echo $_smarty_tpl->tpl_vars['index']->value['about']['image'];?>
" height="74" width="305" /></div>
<div class="word">  <?php echo $_smarty_tpl->tpl_vars['index']->value['about']['description'];?>
<span><a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['about']['link'];?>
">[详情]</a></span></div>                    
                </div>
                <div class="news">
                	<div class="titla"><span><a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['list1']['link'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
images/more.jpg" height="12" width="39" /></a></span><img src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
images/iconb.jpg" /><?php echo $_smarty_tpl->tpl_vars['index']->value['list1']['name'];?>
</div>                
                    <div class="imga"><img src="<?php echo $_smarty_tpl->tpl_vars['index']->value['list1']['image'];?>
" height="74" width="305" /></div>
                      <div class="tn_detal">
                            <ul> 
						<?php  $_smarty_tpl->tpl_vars['article'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['article']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['list1']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['article']->key => $_smarty_tpl->tpl_vars['article']->value) {
$_smarty_tpl->tpl_vars['article']->_loop = true;
?>							
                                <li><a href="<?php echo $_smarty_tpl->tpl_vars['article']->value['link'];?>
"><span>[<?php echo $_smarty_tpl->tpl_vars['index']->value['list1']['data'][0]['pubdate'];?>
]</span><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['article']->value['title'],12,"…");?>
 </a></li>
                               <?php } ?>                                                     
                              </ul>                          
                        </div>                     
                </div>
                <div class="lianxi2">
                	<div class="titla"><span><a href=" <?php echo $_smarty_tpl->tpl_vars['index']->value['contact']['link'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
images/more.jpg" height="12" width="39" /></a></span><img src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
images/iconc.jpg" />联系我们</div>                
                    <div class="imgb"><img src="<?php echo $_smarty_tpl->tpl_vars['index']->value['contacter']['image'];?>
" height="74" width="305" /></div>
                  <div class="teala_show">
                <?php echo $_smarty_tpl->tpl_vars['index']->value['contact'];?>

                  </div>
                </div>
            </div>
         </div>   
        


<?php echo $_smarty_tpl->getSubTemplate ("./_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
		 
    </div>    
</body>
</html>
















<?php }} ?>
