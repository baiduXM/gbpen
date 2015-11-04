<?php /* Smarty version Smarty-3.1.19, created on 2015-10-29 06:15:08
         compiled from "E:\yu1\unify\app\views\templates\GP0029\index.html" */ ?>
<?php /*%%SmartyHeaderCode:27539561dcc069a67e6-97040844%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '414ef66ec935c25d928a750a09932649aa2aed02' => 
    array (
      0 => 'E:\\yu1\\unify\\app\\views\\templates\\GP0029\\index.html',
      1 => 1446081457,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '27539561dcc069a67e6-97040844',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_561dcc06e135e6_92857457',
  'variables' => 
  array (
    'site_another_url' => 0,
    'title' => 0,
    'keywords' => 0,
    'description' => 0,
    'site_url' => 0,
    'headscript' => 0,
    'logo' => 0,
    'global' => 0,
    'nav' => 0,
    'search_action' => 0,
    'contact' => 0,
    'footprint' => 0,
    'index' => 0,
    'slidepic' => 0,
    'new' => 0,
    'limit' => 0,
    'product' => 0,
    'case_list' => 0,
    'footscript' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_561dcc06e135e6_92857457')) {function content_561dcc06e135e6_92857457($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'E:\\yu1\\unify\\vendor\\dark\\smarty-view\\src\\Dark\\SmartyView\\Smarty\\libs\\plugins\\modifier.date_format.php';
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<script type="text/javascript">
  // 跳转手机页面
  <?php if ($_smarty_tpl->tpl_vars['site_another_url']->value) {?>
  if (!!navigator.userAgent.match(/AppleWebKit.*Mobile.*/)) {
      location.href = '<?php echo $_smarty_tpl->tpl_vars['site_another_url']->value;?>
';
  } <?php }?>
</script>

<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" />
<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
" />

<link rel="stylesheet" rev="stylesheet" href="http://chanpin.xm12t.com.cn/css/global.css" type="text/css" />

<link href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
css/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jquery.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/jarallax-0.2.3b.js"></script>
<style>
  body{
    height: 2000px;
    background-color: #214760;
  }
  
</style>

<?php echo $_smarty_tpl->tpl_vars['headscript']->value;?>

</head>
<body>
  <!-- fixed_nav layer start -->
  <div class="fixed_nav">         
    <div class="top_fix_wp">
      <h1><a class="logo" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
" alt="<?php echo $_smarty_tpl->tpl_vars['title']->value;?>
"></a></h1>
    </div>
    <nav>
      <ul id="nav">
        <li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
" onClick="jarallax.jumpToProgress( 0, 2000, 30);return false;">首页</a></li>
        <li><a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['about']['link'];?>
" onClick="jarallax.jumpToProgress( 10, 2000, 30);return false;"><?php echo $_smarty_tpl->tpl_vars['global']->value['about']['name'];?>
</a></li>
        <li><a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['news']['link'];?>
" onClick="jarallax.jumpToProgress( 20, 2000, 30);return false;"><?php echo $_smarty_tpl->tpl_vars['global']->value['news']['name'];?>
</a></li>
        <li><a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['products']['link'];?>
" onClick="jarallax.jumpToProgress( 30, 2000, 30);return false;"><?php echo $_smarty_tpl->tpl_vars['global']->value['products']['name'];?>
</a></li>
        <li><a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['case']['link'];?>
" onClick="jarallax.jumpToProgress( 40, 2000, 30);return false;"><?php echo $_smarty_tpl->tpl_vars['global']->value['case']['name'];?>
</a></li>
        <li><a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['feedback']['link'];?>
" onClick="jarallax.jumpToProgress( 50, 2000, 30);return false;"><?php echo $_smarty_tpl->tpl_vars['global']->value['feedback']['name'];?>
</a></li>
        <li><a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['contact_us']['link'];?>
" onClick="jarallax.jumpToProgress( 60, 2000, 30);return false;"><?php echo $_smarty_tpl->tpl_vars['global']->value['contact_us']['name'];?>
</a></li>


        <?php  $_smarty_tpl->tpl_vars['nav'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nav']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['global']->value['fixed_navs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nav']->key => $_smarty_tpl->tpl_vars['nav']->value) {
$_smarty_tpl->tpl_vars['nav']->_loop = true;
?>          
          <li>
            <a href="<?php echo $_smarty_tpl->tpl_vars['nav']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['nav']->value['name'];?>
</a>
          </li>              
        <?php } ?>
      </ul>
    </nav>

    <!--  -->

    <div class="search">
      <form id="form" class="fm" action="<?php echo $_smarty_tpl->tpl_vars['search_action']->value;?>
" method="GET" name="fm">
        <span class="s_ipt_wr">
          <input type="text" id="kw" name="s" class="s_ipt" placeholder=""/>
        </span>
        <span class="s_btn_wr">
          <input type="submit" class="s_btn" id="submit" value="">
        </span>
      </form>
    </div>

    <p class="hot_phone">服务热线:<span><?php echo $_smarty_tpl->tpl_vars['contact']->value['telephone'];?>
</span></p>
    <div class="copyright"><?php echo $_smarty_tpl->tpl_vars['footprint']->value;?>
</div>
  </div>   
  <!-- fixed layer end -->


  <!-- home layer start -->
  <div id="home" class="slide container">
    <div class="hot_slider"> 
      <div class="banner">
      <ul>
          <?php  $_smarty_tpl->tpl_vars['slidepic'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['slidepic']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['index']->value['slidepics']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['slidepic']->key => $_smarty_tpl->tpl_vars['slidepic']->value) {
$_smarty_tpl->tpl_vars['slidepic']->_loop = true;
?>
          <li>
            <input type="hidden" class="EditInfo" value="<?php echo $_smarty_tpl->tpl_vars['slidepic']->value['description'];?>
">
            <img src="<?php echo $_smarty_tpl->tpl_vars['slidepic']->value['image'];?>
" height="736" width="1920" alt="<?php echo preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['slidepic']->value['title']);?>
" />
            <div class="box_inner ">
              <div class="description">
                <p class="yellow"></p>
                <p class="sub_title"><?php echo $_smarty_tpl->tpl_vars['slidepic']->value['title'];?>
</p>
                <p class="p"></p>
              </div>              
              <div class="shap">
                <div class="shap_inner"><span class="txt_b"></span><span class="txt_s">。</span></div>
              </div>
            </div>
          </li>
          <?php } ?> 
          </ul>     
      </div> 
    </div>
  </div>
  <!-- home layer end -->

  
  <!-- about layer start -->
  <div class="container" id="slide1">
    <div class="slide_inner">
    <div class="inner_wrap clearfix">
      <h2><span><?php echo $_smarty_tpl->tpl_vars['global']->value['about']['en_name'];?>
</span><?php echo $_smarty_tpl->tpl_vars['global']->value['about']['name'];?>
</h2>
      <div class="c_l">
        <div class="txt">
          <p><?php echo $_smarty_tpl->tpl_vars['global']->value['about']['description'];?>
</p>
        </div>
        <a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['about']['link'];?>
" class="read_more">详情+</a>
      </div>
      <div class="c_r">
        <img src="<?php echo $_smarty_tpl->tpl_vars['global']->value['about']['image'];?>
" height="344" width="459" alt="<?php echo $_smarty_tpl->tpl_vars['title']->value;?>
">
      </div>
    </div>
    </div>
  </div><!--#about-->
  <!-- about layer end -->

  <!-- news layer start -->
  <div class="container" id="slide2">
    <!-- <div class="slide_inner animate-build" data-animation="fly-in-right" data-build="1"> -->
    <div class="slide_inner">
    <div class="inner_wrap clearfix">
      <div class="c_l">
        <div class="img_wrap">
          <img src="<?php echo $_smarty_tpl->tpl_vars['global']->value['news']['image'];?>
" height="305" width="244" alt="">
        </div>
      </div>
      <div class="c_r">
        <h2><span><?php echo $_smarty_tpl->tpl_vars['global']->value['news']['en_name'];?>
</span><?php echo $_smarty_tpl->tpl_vars['global']->value['news']['name'];?>
</h2>
        <ul>
          <?php $_smarty_tpl->tpl_vars['limit'] = new Smarty_variable(5, null, 0);?>
          <?php  $_smarty_tpl->tpl_vars['new'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['new']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['global']->value['news']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['new']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['new']->key => $_smarty_tpl->tpl_vars['new']->value) {
$_smarty_tpl->tpl_vars['new']->_loop = true;
 $_smarty_tpl->tpl_vars['new']->index++;
?>
          <li><a class="clearfix" href="<?php echo $_smarty_tpl->tpl_vars['new']->value['link'];?>
"><span class="title"><?php echo $_smarty_tpl->tpl_vars['new']->value['title'];?>
</span><span class="date">（<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['new']->value['pubtimestamp'],'%Y-%m-%d');?>
）</span></a></li>
          <?php if ($_smarty_tpl->tpl_vars['new']->index+1==$_smarty_tpl->tpl_vars['limit']->value) {?><?php break 1?><?php }?>
          <?php } ?>
        </ul>
        <a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['news']['link'];?>
" class="read_more">详情+</a>
      </div>
      </div>
    </div>
  </div>
  <!-- news layer end -->

  <!-- product layer start -->
  <div class="container" id="slide3">
    <div class="slide_inner">
    <div class="inner_wrap">
      <h2><span><?php echo $_smarty_tpl->tpl_vars['global']->value['products']['en_name'];?>
</span><?php echo $_smarty_tpl->tpl_vars['global']->value['products']['name'];?>
</h2>
      <ul class="clearfix">

          <?php $_smarty_tpl->tpl_vars['limit'] = new Smarty_variable(4, null, 0);?>
          <?php  $_smarty_tpl->tpl_vars['product'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['product']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['global']->value['products']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['product']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['product']->key => $_smarty_tpl->tpl_vars['product']->value) {
$_smarty_tpl->tpl_vars['product']->_loop = true;
 $_smarty_tpl->tpl_vars['product']->index++;
?>
          
           <li>
            <img src="<?php echo $_smarty_tpl->tpl_vars['product']->value['image'];?>
" height="271" width="220" alt="<?php echo preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['product']->value['title']);?>
">
            <div class="smak">
              <h5><?php echo $_smarty_tpl->tpl_vars['product']->value['title'];?>
</h5>
              <p><?php echo $_smarty_tpl->tpl_vars['product']->value['description'];?>
</p>
              <a href="<?php echo $_smarty_tpl->tpl_vars['product']->value['link'];?>
" class="more">+</a>
            </div>
           </li>
          
          <?php if ($_smarty_tpl->tpl_vars['product']->index+1==$_smarty_tpl->tpl_vars['limit']->value) {?><?php break 1?><?php }?>
          <?php } ?>
        
      </ul>
      <a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['products']['link'];?>
" class="read_more">详情+</a>
    </div>
    </div>
  </div>
  <!-- product layer end -->

  <!-- case layer start -->
  <div class="container" id="slide4">
    <div class="slide_inner">
    <div class="inner_wrap">
      <h2><span><?php echo $_smarty_tpl->tpl_vars['global']->value['case']['en_name'];?>
</span><?php echo $_smarty_tpl->tpl_vars['global']->value['case']['name'];?>
</h2>
      <div class="img_list">
        <ul>
          <?php $_smarty_tpl->tpl_vars['limit'] = new Smarty_variable(6, null, 0);?>
          <?php  $_smarty_tpl->tpl_vars['case_list'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['case_list']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['global']->value['case']['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['case_list']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['case_list']->key => $_smarty_tpl->tpl_vars['case_list']->value) {
$_smarty_tpl->tpl_vars['case_list']->_loop = true;
 $_smarty_tpl->tpl_vars['case_list']->index++;
?>
          <li>
            <div class="inner mask">
              <img src="<?php echo $_smarty_tpl->tpl_vars['case_list']->value['image'];?>
" alt=""/>
              <div class="mask_content"></div>
              <div class="title">
                <h5><a href="<?php echo $_smarty_tpl->tpl_vars['case_list']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['case_list']->value['title'];?>
</a></h5>
              </div>                       
            </div>
          </li>
          <?php if ($_smarty_tpl->tpl_vars['case_list']->index+1==$_smarty_tpl->tpl_vars['limit']->value) {?><?php break 1?><?php }?>
          <?php } ?>
            </ul>
      </div>
      <a href="<?php echo $_smarty_tpl->tpl_vars['global']->value['case']['link'];?>
" class="read_more">详情+</a>
    </div>
    </div>
  </div>
  <!-- case layer end -->

  <!-- feedback layer start -->
  <div class="container" id="slide5">
    <div class="slide_inner">
    <div class="inner_wrap clearfix">
    <h2><span><?php echo $_smarty_tpl->tpl_vars['global']->value['feedback']['en_name'];?>
</span><?php echo $_smarty_tpl->tpl_vars['global']->value['feedback']['name'];?>
</h2>
    <div class="c_l">
      <form action="" id="f" class="form" onsubmit="return feedback();">
        <div class="f_item">
          <label for="username">姓名<span>NAME</span></label>
          <input type="text" name="username" id="username" />
        </div>
        <div class="f_item">
          <label for="phone">电话<span>PHONE</span></label>
          <input type="text" name="phone" id="phone" />
        </div>
        <div class="f_item">
          <label for="info">信息<span>Information</span></label>
          <textarea name="info" id="info" cols="30" rows="13"></textarea>
        </div>

        <div class="f_submit">
          <input type="submit" name="submit" id="feedback_smt"  value="提交留言" />
        </div>

      </form>
    </div>
    <div class="c_r"><?php echo $_smarty_tpl->tpl_vars['index']->value['feedback_txt'];?>
</div>
    </div>
    </div>
  </div>
  <!-- feedback layer end -->

  <!-- contactus layer start -->
  <div class="container" id="slide6">
    <div class="slide_inner">
    
    <div class="inner_wrap clearfix">
    <h2><span><?php echo $_smarty_tpl->tpl_vars['global']->value['contact_us']['en_name'];?>
</span><?php echo $_smarty_tpl->tpl_vars['global']->value['contact_us']['name'];?>
</h2>
    <div class="c_l">
      <div class="follow_us_w clearfix">
        <img class="follow_us" src="<?php echo $_smarty_tpl->tpl_vars['index']->value['qrcode']['image'];?>
" height="164" width="165" alt=""/>
        <img class="arrow" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
images/arrow.png" height="78" width="155" alt=""/>
        <span><?php echo $_smarty_tpl->tpl_vars['index']->value['qrcode']['title'];?>
</span>
      </div>
    </div>
    <div class="c_r">
      <div class="contactus_w">
      <div>
        <h3><?php echo $_smarty_tpl->tpl_vars['index']->value['phone_title'];?>
</h3>
        <p>业务专线：<?php echo $_smarty_tpl->tpl_vars['index']->value['phone_1'];?>
</p>
        <p>客服专线：<?php echo $_smarty_tpl->tpl_vars['index']->value['phone_2'];?>
</p>
        <p>客服传真：<?php echo $_smarty_tpl->tpl_vars['contact']->value['fax'];?>
</p>
        <p>客户邮件：<?php echo $_smarty_tpl->tpl_vars['contact']->value['mail'];?>
</p>
        <p>招聘热线：<?php echo $_smarty_tpl->tpl_vars['index']->value['phone_5'];?>
</p>
        <p>投诉中心：<?php echo $_smarty_tpl->tpl_vars['index']->value['phone_6'];?>
</p>
      </div>
      </div>
    </div>
    </div>
    </div>
  </div>
  <?php echo $_smarty_tpl->tpl_vars['footscript']->value;?>

  <!-- contactus layer end -->
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/unslider.min.js"></script>
<script src="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
js/demo.js" type="text/javascript"></script>

</body>
</html>
<?php }} ?>
