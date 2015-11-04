<?php /* Smarty version Smarty-3.1.19, created on 2015-10-14 09:00:53
         compiled from "E:\yu1\unify\app\views\templates\GP0026\list-page.html" */ ?>
<?php /*%%SmartyHeaderCode:4348561e19c565f759-30562328%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '475f688ba339623874f6784a44eb5c41ed5d0f99' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP0026\\list-page.html',
      1 => 1441100731,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4348561e19c565f759-30562328',
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
    'title' => 0,
    'global' => 0,
    'QQ' => 0,
    'posnavs' => 0,
    'nav' => 0,
    'list' => 0,
    'flink' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_561e19c571af82_31574500',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_561e19c571af82_31574500')) {function content_561e19c571af82_31574500($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<script type="text/javascript">
    // 跳转手机页面
    <?php if ($_smarty_tpl->tpl_vars['site_another_url']->value) {?>
      if (!!navigator.userAgent.match(/AppleWebKit.*Mobile.*/)) {
        location.href = '<?php echo $_smarty_tpl->tpl_vars['site_another_url']->value;?>
';
      }
    <?php }?>
    </script>   

<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
"/>
<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Language" content="zh-CN">
<link rel="shortcut icon" href="<?php echo $_smarty_tpl->tpl_vars['favicon']->value;?>
" type="image/x-icon" />
<link href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/css.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jquery.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/yao.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jquery.SuperSlide.2.1.1.js"></script>
<script type="text/javascript" src="http://common.mn.sina.com.cn/public/resource/lib/jquery.jcarousellite.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/function.js"></script>
<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>

</head>

<body>
<div class="wrap">
  <div class=" public">
<!-- 头部文件引入 -->
<?php echo $_smarty_tpl->getSubTemplate ("./_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    <div class="content">
      <div class="slideleft fl">
          <div class="class">
              <?php echo $_smarty_tpl->getSubTemplate ("./_pagenavs_sub3.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

            </div>
            <div class="class contact">
              <h1 class="contact-top">联系方式 \ <span class="en">Contact Us</span></h1>
                <div class="contact-m">
                  <img src="<?php echo $_smarty_tpl->tpl_vars['global']->value['contact_img']['image'];?>
" width="245" height="115" />
                    <dl class="contact-ed">
                      <?php echo $_smarty_tpl->tpl_vars['global']->value['contact_us'];?>

                    </dl>
        <div class="contact-qq">
                  <?php  $_smarty_tpl->tpl_vars['QQ'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['QQ']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['global']->value['QQ']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['QQ']->key => $_smarty_tpl->tpl_vars['QQ']->value) {
$_smarty_tpl->tpl_vars['QQ']->_loop = true;
?>
                     <a target=blank href=http://wpa.qq.com/msgrd?V=3&uin=<?php echo $_smarty_tpl->tpl_vars['QQ']->value['title'];?>
&Site=QQ客服&Menu=yes><img border="0" SRC=http://wpa.qq.com/pa?p=1:<?php echo $_smarty_tpl->tpl_vars['QQ']->value['title'];?>
:1 alt="点击这里给我发消息"></a>
                      
                  <?php } ?>
                </div>
                </div>
            </div>
        </div>
        <div class="right fr">
          <h1 class="contact-top"><strong class="lujin">您现在的位置：<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
">首页</a>
            <?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['posnavs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?>
             &gt;&gt; <a href="<?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a>
            <?php } ?>

          </strong><?php echo $_smarty_tpl->tpl_vars['list']->value['name'];?>
 \ <span class="en"><?php echo $_smarty_tpl->tpl_vars['list']->value['en_name'];?>
</span></h1>
            <div class="right-m">
             <?php echo htmlspecialchars_decode($_smarty_tpl->tpl_vars['list']->value['content'], ENT_QUOTES);?>

            </div>
        </div>

<div class="link fl">
        <b>友情链接:</b>
        <?php  $_smarty_tpl->tpl_vars['flink'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['flink']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['global']->value['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['flink']->key => $_smarty_tpl->tpl_vars['flink']->value) {
$_smarty_tpl->tpl_vars['flink']->_loop = true;
?>
         <a href="<?php echo $_smarty_tpl->tpl_vars['flink']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['flink']->value['title'];?>
</a> 
        <?php } ?>
        </div>
    </div>
    </div>
<?php echo $_smarty_tpl->getSubTemplate ('./_footer.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


</div

</body>
</html>










 
<?php }} ?>
