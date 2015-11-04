<?php /* Smarty version Smarty-3.1.19, created on 2015-07-24 07:05:12
         compiled from "E:\yu1\unify\app\views\templates\GM004\index.html" */ ?>
<?php /*%%SmartyHeaderCode:1986355b1e3a8b36775-66235989%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f49b3fd14b01814a0ca3c9e389817c65ccf19474' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GM004\\index.html',
      1 => 1434351280,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1986355b1e3a8b36775-66235989',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site_another_url' => 0,
    'title' => 0,
    'keywords' => 0,
    'description' => 0,
    'favicon' => 0,
    'site_url' => 0,
    'headscript' => 0,
    'mIndexCats' => 0,
    'mIndexCat' => 0,
    'mIn_list' => 0,
    'mIn' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55b1e3a8e47aa4_15459536',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b1e3a8e47aa4_15459536')) {function content_55b1e3a8e47aa4_15459536($_smarty_tpl) {?><!DOCTYPE html>
<html>
<head>

<script type="text/javascript">
    // 跳转PC页面
    <?php if ($_smarty_tpl->tpl_vars['site_another_url']->value) {?>
      if (!navigator.userAgent.match(/AppleWebKit.*Mobile.*/)) {
        location.href = '<?php echo $_smarty_tpl->tpl_vars['site_another_url']->value;?>
';
      }
    <?php }?>
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0, maximum-scale=1.0,user-scalable=no,minimal-ui">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta content="telephone=no, email=no" name="format-detection">
<!-- 启用360浏览器的极速模式(webkit) -->
<meta name="renderer" content="webkit">
<!-- 避免IE使用兼容模式 -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- 针对手持设备优化，主要是针对一些老的不识别viewport的浏览器，比如黑莓 -->
<meta name="HandheldFriendly" content="true">
<!-- 微软的老式浏览器 -->
<meta name="MobileOptimized" content="320">
<!-- uc强制竖屏 -->
<meta name="screen-orientation" content="portrait">
<!-- QQ强制竖屏 -->
<meta name="x5-orientation" content="portrait">
<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" />
<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
" />
<link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
" type="image/x-icon" />

<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/reset.css" /> 
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/public.css" /> 
<link href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/css.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/swipe.css" rel="stylesheet" type="text/css" />

<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/TouchSlide.1.1.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/function.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/share.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/idangerous.swiper-2.0.min.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/idangerous.swiper.3dflow-2.0.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/simple-app.js"></script>
<script type="text/javascript">
	window.addEventListener('load', function(){
   setTimeout(function(){ window.scrollTo(0, 1); }, 100);
});//safari浏览器可以通过此代码来隐藏地址栏
</script>
<?php echo $_smarty_tpl->tpl_vars['headscript']->value;?>

</head>

<body>
<div class="body public-bg2">
<!-- 隐藏导航 -->
<?php echo $_smarty_tpl->getSubTemplate ("./_header_hidenav.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<!-- 隐藏导航End -->
<div class="wrap page-active">
<div class="opacity2"></div>
<!-- 底部导航分享 -->
<?php echo $_smarty_tpl->getSubTemplate ("./_footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<!-- 底部导航分享End -->
<div class="index-wrap">
    <!-- 顶部导航 -->
    <?php echo $_smarty_tpl->getSubTemplate ("./_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    <!-- 顶部导航End -->
      <section>
      <?php  $_smarty_tpl->tpl_vars['mIndexCat'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['mIndexCat']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['mIndexCats']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['mIndexCat']->key => $_smarty_tpl->tpl_vars['mIndexCat']->value) {
$_smarty_tpl->tpl_vars['mIndexCat']->_loop = true;
?>
      <?php if ($_smarty_tpl->tpl_vars['mIndexCat']->value['type']>4) {?>
      <div class="proclass">
      	<ul>
          
          <li>
            <div class="prolist">
            <a href="<?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['link'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['image'];?>
" width="100%"><dt class="title"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['name'];?>
</dt></a>
            </div>
          </li>
        </ul>
      </div>
      <?php }?>
      <?php if ($_smarty_tpl->tpl_vars['mIndexCat']->value['type']==4) {?>
      <div class="mainbox">
      	  <div class="aboutbox">
      	  <h1 class="aboutboxtop"><div class="jx"></div>
          <div class="right"><span class="more"><a href="#">+</a></span>
          <div class="wz"><span class="cn">关于我们</span><p class="en public-bor">about us</p><div style="clear:both"></div></div>
          <div class="line public-bg2"></div>
          <div class="line1 public-bg2"></div></div>
          </h1>
            
              <div class="aboutboxm"  onclick="javascript:window.location.href='<?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['link'];?>
'">
              <img src="<?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['image'];?>
"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['description'];?>

              </div>
          </div>
      </div>
      <?php }?>
      <?php if ($_smarty_tpl->tpl_vars['mIndexCat']->value['type']==3) {?>
      <div class="probox newxbox">
      <h1 class="aboutboxtop"><div class="jx"></div>
          <div class="right"><span class="more"><a href="#">+</a></span>
          <div class="wz"><span class="cn"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['name'];?>
</span><p class="en public-bor"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['en_name'];?>
</p><div style="clear:both"></div></div>
          <div class="line public-bg2"></div>
          <div class="line1 public-bg2"></div></div>
          </h1>
      	  <ul class="newslist">
              <?php  $_smarty_tpl->tpl_vars['mIn_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['mIn_list']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['mIndexCat']->value['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['mIn_list']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['mIn_list']->key => $_smarty_tpl->tpl_vars['mIn_list']->value) {
$_smarty_tpl->tpl_vars['mIn_list']->_loop = true;
 $_smarty_tpl->tpl_vars['mIn_list']->index++;
?>
                <li onclick="javascript:window.location.href='<?php echo $_smarty_tpl->tpl_vars['mIn_list']->value['link'];?>
'" class="public-bg1">
                  <?php if (($_smarty_tpl->tpl_vars['mIn_list']->index+1)%2==0) {?>
                    <div class="list">
                      <dt class="public-color2"><?php echo $_smarty_tpl->tpl_vars['mIn_list']->value['title'];?>
</dt>
                        <dd><?php echo $_smarty_tpl->tpl_vars['mIn_list']->value['description'];?>
</dd>
                    </div>
                    <div class="date public-color1">
                      <dd class="day">18</dd>
                        <dd class="year"><?php echo $_smarty_tpl->tpl_vars['mIn_list']->value['pubdate'];?>
</dd>
                    </div>
                    <?php } else { ?>
                    <div class="date public-color1">
                      <dd class="day">18</dd>
                        <dd class="year"><?php echo $_smarty_tpl->tpl_vars['mIn_list']->value['pubdate'];?>
</dd>
                    </div>
                    <div class="list">
                      <dt class="public-color2"><?php echo $_smarty_tpl->tpl_vars['mIn_list']->value['title'];?>
</dt>
                        <dd><?php echo $_smarty_tpl->tpl_vars['mIn_list']->value['description'];?>
</dd>
                    </div>
                  <?php }?>
                </li> 
              <?php } ?>
          </ul>
      </div>
      <?php }?>
      <?php if ($_smarty_tpl->tpl_vars['mIndexCat']->value['type']==2) {?>
      <div class="probox">
      	 <h1 class="aboutboxtop"><div class="jx"></div>
          <div class="right"><span class="more"><a href="#">+</a></span>
          <div class="wz"><span class="cn"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['name'];?>
</span><p class="en public-bor"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['en_name'];?>
</p><div style="clear:both"></div></div>
          <div class="line public-bg2"></div>
          <div class="line1 public-bg2"></div></div>
          </h1>
         <div class="proboxm">
         	<ul class="prolist">
                <li>
                  <div class="prolistb"  onclick="javascript:window.location.href='<?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['link'];?>
'">
                    <img src="<?php echo $_smarty_tpl->tpl_vars['mIn']->value['image'];?>
" width="100%"><dd class="title1"><?php echo $_smarty_tpl->tpl_vars['mIndexCat']->value['name'];?>
</dd>
                  </div>
                </li>
            </ul>
         </div>
      </div>
    <?php }?>
    <?php } ?>
		</section>
    <?php echo $_smarty_tpl->getSubTemplate ("./_footprint.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

</div>
</div>
</div>

</body>

</html>
<?php }} ?>
