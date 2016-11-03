<?php

Route::get('/', function() {//登录
    return View::make('login');
})->before('guest');

Route::get('get-remind', [//忘记密码
    'as' => 'get-remind',
    'uses' => 'RemindersController@getRemind'
]);
Route::get('homepage-preview-no-auth', [//首页预览
        'as' => 'homepage-preview-no-auth',
        'uses' => 'TemplatesController@homepagePreview_no_auth'
]);

Route::get('category-no-auth/{id}', [//栏目页预览
    'uses' => 'TemplatesController@categoryPreview_no_auth'
])->where('id', '[0-9]+');

Route::get('category-no-auth/{id}_{page}', [//栏目页分页预览
    'uses' => 'TemplatesController@categoryPreview_no_auth'
])->where(['id' => '[0-9]+', 'page' => '[0-9]+']);

Route::get('detail-no-auth/{id}', [//详情页预览
    'uses' => 'TemplatesController@articlePreview_no_auth'
])->where('id', '[0-9]+');

Route::get('mobile/homepage-preview-no-auth', [//首页预览
    'uses' => 'TemplatesController@mhomepagePreview_no_auth'
]);

Route::get('mobile/category-no-auth/{id}', [//栏目页预览
    'uses' => 'TemplatesController@mcategoryPreview_no_auth'
])->where('id', '[0-9]+');

Route::get('mobile/category-no-auth/{id}_{page}', [//栏目页分页预览
    'uses' => 'TemplatesController@mcategoryPreview_no_auth'
])->where(['id' => '[0-9]+', 'page' => '[0-9]+']);

Route::get('mobile/detail-no-auth/{id}', [//详情页预览
    'uses' => 'TemplatesController@marticlePreview_no_auth'
])->where('id', '[0-9]+');

Route::post('post-remind', [//忘记密码处理
    'as' => 'post-remind',
    'before' => 'csrf',
    'uses' => 'RemindersController@postRemind'
]);

Route::get('password/reset/{token?}', [//密码重置
    'as' => 'get-reset',
    'uses' => 'RemindersController@getReset'
]);

Route::post('post-reset', [//密码重置处理
    'as' => 'post-reset',
    'uses' => 'RemindersController@postReset'
]);

Route::post('login-post', [//登录处理
    'as' => 'login-post',
    'before' => 'csrf',
    'uses' => 'SignController@loginPost'
]);

Route::post('modify-password', [//修改密码
    'as' => 'modify-password',
    'uses' => 'SignController@modifyPassword'
]);

Route::get('login-info', [//用户名称
    'as' => 'login-info',
    'uses' => 'SignController@loginInfo'
]);

Route::get('pushlogin', [//推送时验证登录
    'uses' => 'HTMLController@pushLogin'
]);
Route::get('grad_push', [//分组推送
    'uses' => 'HTMLController@grad_push'
]);
Route::get('push-classify-ids', [//推送时验证登录
    'uses' => 'HTMLController@push_classify_ids'
]);
//路由组--所有登录后的操作放入本组
Route::group(array('before' => 'auth'), function() {

    //===获取用户统计数据-start===
    Route::get('statis-get', [//首页统计数据
        'as' => 'statis',
        'uses' => 'StatisController@getCount'
    ]);
    //===获取用户统计数据-end===

    Route::get('log-out', [//用户登出
        'as' => 'log-out',
        'uses' => 'SignController@logOut'
    ]);
    //-----------------------------------------------
    //--------------------用户配置路由---------------
    Route::get('customer-info', [//获取用户信息
        'as' => 'customer-info',
        'uses' => 'CustomerController@customerInfo'
    ]);

    Route::post('customer-setting', [//保存用户信息
        'as' => 'customer-setting',
        'uses' => 'CustomerController@customerSetting'
    ]);
    //===容量====
    Route::get('capacity-info', [//保存用户信息
        'uses' => 'CapacityController@getInfo'
    ]);

    Route::any('capacity-init', [//初始化容量
        'uses' => 'CapacityController@init'
    ]);

    Route::any('capacity-release', [//释放空间
        'uses' => 'CapacityController@release'
    ]);
    //===end===
    //-----------------------------------------------
    //--------------------栏目路由--------------------
    Route::post('classify-create', [//栏目添加
        'as' => 'classify-list',
        'uses' => 'ClassifyController@classifyCreate'
    ]);

    Route::get('classify-list', [//栏目列表
        'as' => 'classify-list',
        'uses' => 'ClassifyController@classifyList'
    ]);

    Route::post('classify-delete', [//栏目删除
        'as' => 'classify-list',
        'uses' => 'ClassifyController@classifyDelete'
    ]);

    Route::get('classify-info', [//栏目详情
        'as' => 'classify-list',
        'uses' => 'ClassifyController@classifyInfo'
    ]);

    Route::post('classify-modify', [//栏目修改
        'as' => 'classify-list',
        'uses' => 'ClassifyController@classifyModify'
    ]);
    
    Route::post('classify-batch', [//===栏目批量添加===
        'uses' => 'ClassifyController@classifyBatch'
    ]);

    Route::post('classify-name-modify', [//栏目标题修改
        'as' => 'classify-name-modify',
        'uses' => 'ClassifyController@classifyNameModify'
    ]);

    Route::post('classify-show', [//栏目显隐
        'as' => 'classify-show',
        'uses' => 'ClassifyController@classifyShow'
    ]);

    Route::post('classify-sort', [//栏目排序
        'as' => 'classify-list',
        'uses' => 'ClassifyController@classifySort'
    ]);
    Route::get('classify-ids', [//栏目id列表
        'as' => 'classify-ids',
        'uses' => 'ClassifyController@classifyids'
    ]);
    //-----------------------------------------------
    //--------------------文章路由--------------------
    Route::post('article-create', [//文章添加
        'uses' => 'ArticleController@articleAdd'
    ]);

    Route::get('article-list', [//文章列表
        'as' => 'article-list',
        'uses' => 'ArticleController@articleList'
    ]);

    Route::get('article-manage', [//文章列表
        'uses' => 'ArticleController@articleManage'
    ]);

    Route::get('article-info', [//文章详情
        'uses' => 'ArticleController@articleInfo'
    ]);

    Route::post('article-delete', [//文章删除
        'uses' => 'ArticleController@articleDelete'
    ]);

    Route::post('article-sort-modify', [//文章排序修改
        'as' => 'article-sort-modify',
        'uses' => 'ArticleController@articleSortModify'
    ]);

    Route::post('article-title-modify', [//文章标题修改
        'as' => 'article-title-modify',
        'uses' => 'ArticleController@articleTitleModify'
    ]);

    Route::post('article-sort', [//文章排序
        'as' => 'article-list',
        'uses' => 'ArticleController@articleSort'
    ]);

    Route::post('article-move-classify', [//文章显示改变
        'as' => 'article-list',
        'uses' => 'ArticleController@articleMoveClassify'
    ]);

    Route::post('article-batch-modify', [//文章批量设置
        'as' => 'article-list',
        'uses' => 'ArticleController@articleBatchModify'
    ]);

    Route::post('article-batch-add', [//文章批量添加
        'as' => 'article-batch-add',
        'uses' => 'ArticleController@articleBatchAdd'
    ]);
    Route::post('article-word-search', [//文章关键词搜索
        'uses' => 'ArticleController@articleWordSearch'
    ]);

    //-----------------------------------------------
    //--------------------首页内容--------------------
    Route::get('templates/GP{num}', [//PC预览 首页跳转
        'uses' => 'TemplatesController@homepagePreview'
    ])->where('num', '[0-9_]+');

    Route::get('templates/GM{num}', [//手机预览 首页跳转
        'uses' => 'TemplatesController@mhomepagePreview'
    ])->where('num', '[0-9_]+');

    //--------------------PC部分----------------------
    Route::get('homepage-preview', [//首页预览
        'as' => 'homepage-preview',
        'uses' => 'TemplatesController@homepagePreview'
    ]);
    Route::get('homepage-rewrite', [//首页配置重写
        'as' => 'homepage-rewrite',
        'uses' => 'TemplatesController@homepageRewrite'
    ]);
    Route::get('homepage-list', [//首页编辑保存
        'uses' => 'TemplatesController@homepageList'
    ]);

    Route::post('homepage-modify', [//首页编辑保存
        'uses' => 'TemplatesController@homepageModify'
    ]);
    Route::post('homepage-bannerorder', [//===首页轮播图片排序===
        'uses' => 'TemplatesController@homepageBannerOrder'
    ]);

    Route::get('homepage-manage', [//首页管理
        'uses' => 'TemplatesController@homepageManage'
    ]);


    Route::get('category/{id}', [//栏目页预览
        'uses' => 'TemplatesController@categoryPreview'
    ])->where('id', '[0-9]+');

    Route::get('category/{id}_{page}', [//栏目页分页预览
        'uses' => 'TemplatesController@categoryPreview'
    ])->where(['id' => '[0-9]+', 'page' => '[0-9]+']);

    Route::get('detail/{id}', [//详情页预览
        'uses' => 'TemplatesController@articlePreview'
    ])->where('id', '[0-9]+');

    Route::get('downloadTemplate', [//模板下载
        'uses' => 'TemplatesController@downloadTemplate'
    ]);
    
    Route::get('checkChange', [
        'uses' => 'HTMLController@checkChange'
    ]);
    
    Route::get('getremeber_token', [
        'uses' => 'HTMLController@getRemeber_token'
    ]);
    
    Route::get('pushold', [
        'uses' => 'HTMLController@pushPrecent'
    ]);
    Route::get('push', [
        'uses' => 'HTMLController@pushPrecent'
    ]);
    Route::get('isneedpush', [
        'uses' => 'HTMLController@isNeedPush'
    ]);
    /*
      Route::get('push',[
      'uses' => 'WebsiteController@pushFile'
      ]);
     */
    Route::get('pagemodify/{themename?}', [//首页内容编辑
        'uses' => 'TemplatesController@homepageInfo'
    ]);

    //--------------------手机部分----------------------
    Route::post('mhomepage-modify', [//首页编辑保存
        'uses' => 'TemplatesController@mhomepageModify'
    ]);
    Route::get('mhomepage-data', [//首页内容
        'uses' => 'TemplatesController@getMobilePageData'
    ]);

    Route::post('mhomepage-batchmodify', [//栏目批量修改
        'uses' => 'TemplatesController@homepageBatchModify'
    ]);

    Route::post('mhomepage-sortmodify', [//栏目排序修改
        'uses' => 'TemplatesController@homepageSortModify'
    ]);

    Route::get('mobile/homepage-preview', [//首页预览
        'uses' => 'TemplatesController@mhomepagePreview'
    ]);

    Route::get('mobile/category/{id}', [//栏目页预览
        'uses' => 'TemplatesController@mcategoryPreview'
    ])->where('id', '[0-9]+');

    Route::get('mobile/category/{id}_{page}', [//栏目页分页预览
        'uses' => 'TemplatesController@mcategoryPreview'
    ])->where(['id' => '[0-9]+', 'page' => '[0-9]+']);

    Route::get('mobile/detail/{id}', [//详情页预览
        'uses' => 'TemplatesController@marticlePreview'
    ])->where('id', '[0-9]+');

    //---------------icons文字图标-------------------
    Route::get('icon-list', [//文字图标库
        'uses' => 'CommonController@IconsList'
    ]);
    //---------------quickbar导航--------------------
    Route::get('quickbar.json', [
        'as' => 'quickbar.json',
        'uses' => 'TemplatesController@quickBarJson'
    ]);
    Route::get('pushquickbar', [
        'as' => 'pushquickbar',
        'uses' => 'HtmlController@pushQuickbar'
    ]);
    Route::get('quickbar.jsoninit', [
        'as' => 'quickbar.jsoninit',
        'uses' => 'CommonController@quickBarJsonInit'
    ]);
    Route::post('quickbar.jsonmodify', [
        'as' => 'quickbar.json',
        'uses' => 'CommonController@quickBarJsonModify'
    ]);
    Route::post('quickbar.rewrite', [
        'as' => 'quickbar.rewrite',
        'uses' => 'CommonController@quickBarRewrite'
    ]);
    Route::post('quickbar-colorsclear', [//还原quickbar颜色
        'as' => 'quickbar-colorsclear',
        'uses' => 'CommonController@quickBarColorClear'
    ]);
    Route::post('getqrcode', [
        'as' => 'getqrcode',
        'uses' => 'CommonController@getqrcode'
    ]);
    //-----------------------------------------------
    //--------------------模版操作--------------------
    Route::post('template-upload-zip', [//定制模板上传
        'as' => 'template-upload-zip',
        'uses' => 'WebsiteController@templateUploadZip'
    ]);
    Route::post('template-operate', [//更换操作
        'as' => 'template-operate',
        'uses' => 'WebsiteController@templateChage'
    ]);

    Route::post('template-delete', [//删除模板
        'as' => 'template-delete',
        'uses' => 'WebsiteController@templateDelete'
    ]);

    Route::get('template-query', [//模版查询
        'as' => 'template-query',
        'uses' => 'WebsiteController@templatesQuery'
    ]);

    Route::get('template-list', [//模版列表
        'as' => 'template-list',
        'uses' => 'WebsiteController@templatesListGet'
    ]);

    Route::get('mytemplate-list', [//定制模版和模版列表
        'as' => 'mytemplate-list',
        'uses' => 'WebsiteController@myTemplateList'
    ]);
    /*
      Route::get('template-save', [//模板入库
      'as' => 'template-save',
      'uses' => 'WebsiteController@saveTemplate'
      ]);
     */
    Route::get('template-copy', [//文件复制
        'as' => 'template-copy',
        'uses' => 'WebsiteController@copy'
    ]);

    Route::get('template-filelist', [//文件列表
        'as' => 'template-filelist',
        'uses' => 'WebsiteController@fileList'
    ]);

    Route::get('template-fileadd', [//文件添加
        'as' => 'template-fileadd',
        'uses' => 'WebsiteContorller@fileAdd'
    ]);

    Route::get('template-fileget', [//文件提取
        'as' => 'template-fileget',
        'uses' => 'WebsiteController@fileget'
    ]);

    Route::post('template-fileedit', [//文件编辑保存
        'as' => 'template-fileedit',
        'uses' => 'WebsiteController@fileedit'
    ]);

    Route::get('search-preview', [//搜索结果也数据
        'uses' => 'TemplatesController@searchPreview'
    ]);

    Route::get('mobile/search-preview', [//搜索结果也数据
        'uses' => 'TemplatesController@searchPreview'
    ]);

    //===万用表单start===
    Route::get('form-list', [//获取表单列表
        'as' => 'form-list',
        'uses' => 'FormController@getFormList'
    ]);
    Route::get('form-data', [//获取表单信息
        'as' => 'form-list',
        'uses' => 'FormController@getFormData'
    ]);
    Route::get('form-view', [//获取表单信息
        'as' => 'form-list',
        'uses' => 'FormController@getFormView'
    ]);
    Route::post('form-create', [//创建表单
        'as' => 'form-list',
        'uses' => 'FormController@createForm'
    ]);
    Route::post('form-delete', [//删除表单
        'as' => 'form-list',
        'uses' => 'FormController@deleteForm'
    ]);
    Route::post('form-edit', [//保存表单，仅表单信息
        'as' => 'form-list',
        'uses' => 'FormController@editForm'
    ]);
    Route::post('form-save', [//保存表单，表单信息包括栏目信息
        'as' => 'form-list',
        'uses' => 'FormController@saveForm'
    ]);

    Route::get('form-element-list', [//获取组件元素
        'as' => 'form-list',
        'uses' => 'FormController@getFormElementList'
    ]);

    Route::get('form-column-list', [//获取组件列表
        'as' => 'form-list',
        'uses' => 'FormController@getFormColumnList'
    ]);
    Route::get('form-column', [//获取组件
        'as' => 'form-list',
        'uses' => 'FormController@getFormColumn'
    ]);
    Route::post('form-column-add', [//添加组件
        'as' => 'form-list',
        'uses' => 'FormController@addFormColumn'
    ]);
    Route::post('form-column-delete', [//删除组件
        'as' => 'form-list',
        'uses' => 'FormController@deleteFormColumn'
    ]);
    Route::post('form-column-edit', [//编辑组件
        'as' => 'form-list',
        'uses' => 'FormController@editFormColumn'
    ]);
    Route::post('form-column-move', [//移动组件
        'as' => 'form-list',
        'uses' => 'FormController@moveFormColumn'
    ]);

    Route::get('form-userdata-list', [//用户表单数据列表
        'as' => 'form-list',
        'uses' => 'FormController@getFormUserdataList'
    ]);
    Route::get('form-userdata', [//用户单条数据
        'as' => 'form-list',
        'uses' => 'FormController@getFormUserdata'
    ]);
    Route::post('form-userdata-delete', [//用户数据删除
        'as' => 'form-list',
        'uses' => 'FormController@deleteFormUserdata'
    ]);
    Route::any('form-userdata-submit', [//用户数据提交
        'as' => 'form-list',
        'uses' => 'FormController@submitFormUserdata'
    ]);
    //===万用表单end===
    //===切换绑定用户===
    Route::any('bind-auto-login', [//切换绑定用户
        'uses' => 'SignController@autoLogin'
    ]);
    Route::any('init-bind', [//查看是否有绑定双用户
        'uses' => 'CustomerController@isSwitchcus'
    ]);
    //===end===
    //-----------留言板--------
    Route::get('feedback-data', [//获取表单数据
        'uses' => 'FeedbackController@getFeedbackData'
    ]);

    Route::get('message-board', [
        'uses' => 'FeedbackController@getmessageboard'
    ]);

    Route::post('message-state', [
        'uses' => 'FeedbackController@messagestate'
    ]);
});

Route::get('seach-seachinfo', [//推送文章到搜索平台
    'as' => 'seach-seachinfo',
    'uses' => 'SeachController@articleListToSeachWeb'
]);
Route::get('seach-seachinfoclass', [//推送文章分类到搜索平台
    'as' => 'seach-seachinfoclass',
    'uses' => 'SeachController@articleListClassToSeachWeb'
]);
Route::get('seach-userinfo', [//
    'as' => 'seach-userinfo',
    'uses' => 'SeachController@userInfoToSeachWeb'
]);

Route::post('file-upload', [//文件上传
    'as' => 'file-upload',
    'uses' => 'UploadController@fileRead'
]);
Route::post('fileupload', [//文件上传
    'as' => 'fileupload',
    'uses' => 'UploadController@fileupload'
]);
Route::post('imgupload', [//图片同步
    'as' => 'imgupload',
    'uses' => 'UploadController@img_upload'
]);

Route::post('batchAdd', [//批量上传
    'as' => 'batchAdd',
    'uses' => 'UploadController@batchAdd'
]);

Route::post('upload_template', [//模板上传
    'as' => 'upload_template',
    'uses' => 'WebsiteController@uploadTemplate'
]);

//-----------------------------------------------
//--------------------代理平台接口--------------------

Route::post('api/loginuser', [//用户登录
    'as' => 'template-fileedit',
    'uses' => 'ApiController@login'
]);

Route::post('api/createuser', [//创建用户
    'as' => 'template-fileedit',
    'uses' => 'ApiController@createCustomer'
]);

Route::post('api/modifyuser', [//修改用户
    'as' => 'template-fileedit',
    'uses' => 'ApiController@modifyCustomer'
]);

Route::post('api/deleteuser', [//删除用户
    'as' => 'template-fileedit',
    'uses' => 'ApiController@deleteCustomer'
]);

Route::get('test/{cid}', [
    'uses' => 'PrintController@getChirldenCid'
]);

Route::get('deletemytest', [
    'uses' => 'ApiController@deletemytest'
]);

