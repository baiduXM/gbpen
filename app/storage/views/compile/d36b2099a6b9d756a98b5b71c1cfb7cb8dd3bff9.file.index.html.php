<?php /* Smarty version Smarty-3.1.19, created on 2015-09-01 09:47:58
         compiled from "E:\yu1\unify\app\views\templates\GP0026\index.html" */ ?>
<?php /*%%SmartyHeaderCode:1861655e5744e70d913-71869191%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd36b2099a6b9d756a98b5b71c1cfb7cb8dd3bff9' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP0026\\index.html',
      1 => 1441100731,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1861655e5744e70d913-71869191',
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
    'headscript' => 0,
    'index' => 0,
    'lnav' => 0,
    'global' => 0,
    'QQ' => 0,
    'showimg' => 0,
    'flink' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_55e5744e8e2574_78914746',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55e5744e8e2574_78914746')) {function content_55e5744e8e2574_78914746($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <script type="text/javascript">
    // 跳转手机页面
    <?php if ($_smarty_tpl->tpl_vars['site_another_url']->value) {?>
    if (!!navigator.userAgent.match(/AppleWebKit.*Mobile.*/)) {
        location.href = '<?php echo $_smarty_tpl->tpl_vars['site_another_url']->value;?>
';
    } <?php }?>
    </script>
    
    <meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" />
    <meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
" />
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
    <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/MSClass.js"></script>
    <script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/function.js"></script>
    <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
    <?php echo $_smarty_tpl->tpl_vars['headscript']->value;?>

</head>

<body>
    <div class="wrap">
        <div class=" public">
            <!-- 头部文件引入 -->
            <?php echo $_smarty_tpl->getSubTemplate ("./_header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

            <div class="content">
                <div class="slideleft fl">
                    <div class="class">
                        <h3><span><?php echo $_smarty_tpl->tpl_vars['index']->value['left_nav']['name'];?>
</span></h3>
                        <ul class="clearfix">
                            <?php  $_smarty_tpl->tpl_vars['lnav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['lnav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['left_nav']['childmenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['lnav']->key => $_smarty_tpl->tpl_vars['lnav']->value) {
$_smarty_tpl->tpl_vars['lnav']->_loop = true;
?>
                            <li><a href="<?php echo $_smarty_tpl->tpl_vars['lnav']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['lnav']->value['name'];?>
</a></li>
                            <?php } ?>
                        </ul>
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
                                <a target="_blank" href="http://wpa.qq.com/msgrd?V=3&amp;uin=<?php echo $_smarty_tpl->tpl_vars['QQ']->value['title'];?>
&amp;Site=QQ客服&amp;Menu=yes"><img border="0" src="<?php echo $_smarty_tpl->tpl_vars['QQ']->value['image'];?>
"></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="right fr">
                    <div class="about fl">
                        <h1 class="contact-top"><a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['about_us']['link'];?>
" class="more fr"></a><?php echo $_smarty_tpl->tpl_vars['index']->value['about_us']['name'];?>
 \ <span class="en"><?php echo $_smarty_tpl->tpl_vars['index']->value['about_us']['en_name'];?>
</span></h1>
                        <div class="about-m">
                            <img src="<?php echo $_smarty_tpl->tpl_vars['index']->value['about_us']['image'];?>
" width="134" height="107" /> <?php echo $_smarty_tpl->tpl_vars['index']->value['about_us']['description'];?>

                        </div>
                    </div>
                    <div class="about show fr">
                        <h1 class="contact-top"><a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['show']['link'];?>
" class="more fr"></a><?php echo $_smarty_tpl->tpl_vars['index']->value['show']['name'];?>
 \ <span class="en"><?php echo $_smarty_tpl->tpl_vars['index']->value['show']['en_name'];?>
</span></h1>
                        <div class="show-m">
                            <div id="YSlide">
                                <?php  $_smarty_tpl->tpl_vars['showimg'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['showimg']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['show']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['showimg']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['showimg']->key => $_smarty_tpl->tpl_vars['showimg']->value) {
$_smarty_tpl->tpl_vars['showimg']->_loop = true;
 $_smarty_tpl->tpl_vars['showimg']->index++;
?>
                                <div class="YSample">
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['showimg']->value['link'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['showimg']->value['image'];?>
" width="268" height="192" />
                                        <dd><?php echo $_smarty_tpl->tpl_vars['showimg']->value['title'];?>
</dd>
                                    </a>
                                </div>
                                <?php if ($_smarty_tpl->tpl_vars['showimg']->index+1==4) {?><?php break 1?><?php }?> <?php } ?>
                                <p id="YSIndex">
                                    <?php  $_smarty_tpl->tpl_vars['showimg'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['showimg']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['show']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['showimg']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['showimg']->key => $_smarty_tpl->tpl_vars['showimg']->value) {
$_smarty_tpl->tpl_vars['showimg']->_loop = true;
 $_smarty_tpl->tpl_vars['showimg']->index++;
?>
                                    <a href="javascript:void(0);"><?php echo $_smarty_tpl->tpl_vars['showimg']->index+1;?>
</a> <?php if ($_smarty_tpl->tpl_vars['showimg']->index+1==4) {?><?php break 1?><?php }?> <?php } ?>
                                    <!-- <a href="#1">4</a><a href="#1">3</a><a href="#1">2</a><a href="#1" class="current">1</a> --></p>
                            </div>
                        </div>
                    </div>
                    <div class="about pro fl">
                        <h1 class="contact-top"><a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['pro']['link'];?>
" class="more fr"></a><?php echo $_smarty_tpl->tpl_vars['index']->value['pro']['name'];?>
 \ <span class="en"><?php echo $_smarty_tpl->tpl_vars['index']->value['pro']['en_name'];?>
</span></h1>
                        <div id="prizes">
                            <div class="photos">
                                <a href="javascript:void(0);" class="photos-button photos-prev"></a>
                                <div class="photos-content ">
                                    <ul id="MarqueeDiv1">
                                        <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['key'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['key']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['name'] = 'key';
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['index']->value['pro']['data']) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['start'] = (int) 0;
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['step'] = ((int) 2) == 0 ? 1 : (int) 2;
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['loop'];
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['start'] < 0)
    $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['start'] = max($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['step'] > 0 ? 0 : -1, $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['loop'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['start']);
else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['start'] = min($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['loop'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['loop']-1);
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['total'] = min(ceil(($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['loop'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['start'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['start']+1)/abs($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['step'])), $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['max']);
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['total']);
?>
                                        <li>
                                            <a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['pro']['data'][$_smarty_tpl->getVariable('smarty')->value['section']['key']['index']]['link'];?>
"><img alt="" src="<?php echo $_smarty_tpl->tpl_vars['index']->value['pro']['data'][$_smarty_tpl->getVariable('smarty')->value['section']['key']['index']]['image'];?>
" width="174" height="131" />
                                                <h2><?php echo $_smarty_tpl->tpl_vars['index']->value['pro']['data'][$_smarty_tpl->getVariable('smarty')->value['section']['key']['index']]['title'];?>
</h2></a>
                                        </li>
                                        <?php if ($_smarty_tpl->getVariable('smarty')->value['section']['key']['rownum']>2) {?><?php break 1?><?php }?> <?php endfor; endif; ?>
                                    </ul>
                                </div>
                                <div class="photos-content photo2 <?php if (count($_smarty_tpl->tpl_vars['index']->value['pro']['data'])>4) {?> enable<?php }?>">
                                    <ul id="MarqueeDiv2">
                                        <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['key'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['key']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['name'] = 'key';
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['index']->value['pro']['data']) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['start'] = (int) 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['step'] = ((int) 2) == 0 ? 1 : (int) 2;
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['loop'];
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['start'] < 0)
    $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['start'] = max($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['step'] > 0 ? 0 : -1, $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['loop'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['start']);
else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['start'] = min($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['loop'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['loop']-1);
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['total'] = min(ceil(($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['loop'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['start'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['start']+1)/abs($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['step'])), $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['max']);
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['key']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['key']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['key']['total']);
?>
                                        <li>
                                            <a href="<?php echo $_smarty_tpl->tpl_vars['index']->value['pro']['data'][$_smarty_tpl->getVariable('smarty')->value['section']['key']['index']]['link'];?>
"><img alt="" src="<?php echo $_smarty_tpl->tpl_vars['index']->value['pro']['data'][$_smarty_tpl->getVariable('smarty')->value['section']['key']['index']]['image'];?>
" width="174" height="131" />
                                                <h2><?php echo $_smarty_tpl->tpl_vars['index']->value['pro']['data'][$_smarty_tpl->getVariable('smarty')->value['section']['key']['index']]['title'];?>
</h2></a>
                                        </li>
                                        <?php if ($_smarty_tpl->getVariable('smarty')->value['section']['key']['rownum']>1) {?><?php break 1?><?php }?> <?php endfor; endif; ?>
                                    </ul>
                                </div>
                                <a href="javascript:void(0);" class="photos-button photos-next"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="link fl">
                    <b>友情链接:</b> <?php  $_smarty_tpl->tpl_vars['flink'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['flink']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['global']->value['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['flink']->key => $_smarty_tpl->tpl_vars['flink']->value) {
$_smarty_tpl->tpl_vars['flink']->_loop = true;
?>
                    <a href="<?php echo $_smarty_tpl->tpl_vars['flink']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['flink']->value['title'];?>
</a> <?php } ?>
                </div>
            </div>
        </div>
        <?php echo $_smarty_tpl->getSubTemplate ('./_footer.html', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

    </div>
</body>

</html>
<?php }} ?>
