<?php

Route::get('/', function() {//登录
    return View::make('login');
})->before('guest');

Route::get('get-remind', [//忘记密码
    'as' => 'get-remind',
    'uses' => 'RemindersController@getRemind'
]);

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

Route::get('login-info', [//用户名称
        'as' => 'login-info',
        'uses' => 'SignController@loginInfo'
    ]);

//路由组--所有登录后的操作放入本组
Route::group(array('before' => 'auth'), function() {
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
    
    Route::post('classify-show', [//栏目显隐
        'as' => 'classify-show',
        'uses' => 'ClassifyController@classifyShow'
    ]);
    
    Route::post('classify-sort', [//栏目排序
        'as' => 'classify-list',
        'uses' => 'ClassifyController@classifySort'
    ]);
    //-----------------------------------------------
    //--------------------文章路由--------------------
    Route::post('article-create', [//文章添加
        'as' => 'article-list',
        'uses' => 'ArticleController@articleAdd'
    ]);

    Route::get('article-list', [//文章列表
        'as' => 'article-list',
        'uses' => 'ArticleController@articleList'
    ]);

    Route::get('article-manage', [//文章列表
        'as' => 'article-list',
        'uses' => 'ArticleController@articleManage'
    ]);

    Route::get('article-info', [//文章详情
        'as' => 'article-list',
        'uses' => 'ArticleController@articleInfo'
    ]);
        
    Route::post('article-delete', [//文章删除
        'as' => 'article-list',
        'uses' => 'ArticleController@articleDelete'
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
    
    //-----------------------------------------------
    //--------------------首页内容--------------------
    Route::get('templates/GP{num}', [//PC预览 首页跳转
        'uses' => 'TemplatesController@homepagePreview'
    ])->where('num', '[0-9]+');

    Route::get('templates/GM{num}', [//手机预览 首页跳转
        'uses' => 'TemplatesController@mhomepagePreview'
    ])->where('num', '[0-9]+');
   
    //--------------------PC部分----------------------
    Route::get('homepage-preview', [//首页预览
        'as' => 'homepage-preview',
        'uses' => 'TemplatesController@homepagePreview'
    ]);

    Route::get('homepage-list', [//首页编辑保存
        'uses' => 'TemplatesController@homepageList'
    ]);

    
    Route::post('homepage-modify', [//首页编辑保存
        'uses' => 'TemplatesController@homepageModify'
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
    
    Route::get('checkChange',[
        'uses' => 'HTMLController@checkChange'
    ]);
        
    Route::get('push',[
        'uses' => 'HTMLController@pushPrecent'
        ]);
    Route::get('isneedpush',[
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
    Route::get('icon-list',[//文字图标库
        'uses' => 'CommonController@IconsList'
    ]);
    //---------------quickbar导航--------------------
    Route::get('quickbar.json', [
        'as' => 'quickbar.json',
        'uses' => 'TemplatesController@quickBarJson'
    ]);
    Route::get('quickbar.jsoninit', [
        'as' => 'quickbar.jsoninit',
        'uses' => 'CommonController@quickBarJsonInit'
    ]);
    Route::post('quickbar.jsonmodify', [
        'as' => 'quickbar.json',
        'uses' => 'CommonController@quickBarJsonModify'
    ]);
    //-----------------------------------------------
    //--------------------模版操作--------------------
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
    
    Route::get('template-fileadd',[//文件添加
        'as'=>'template-fileadd',
        'uses' => 'WebsiteContorller@fileAdd'
    ]);
    
    Route::get('template-addimg',[//文件添加之图片
        'as'=>'template-addimg',
        'uses' => 'WebsiteContorller@fileAddImg'
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
    
    //-----------万用表单--------
    Route::get('feedback-data', [//获取表单数据
        'uses' => 'FeedbackController@getFeedbackData'
    ]);
    
    Route::get('message-board',[
            'uses' => 'FeedbackController@getmessageboard'
    ]);
    
    Route::post('message-state',[
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

Route::post('file-upload',[//文件上传
        'as' => 'file-upload',
        'uses' => 'UploadController@fileRead'
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