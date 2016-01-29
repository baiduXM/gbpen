function diytplController($scope, $http, $location) {  
	$scope.$parent.showbox = "diy";
	$scope.$parent.menu = [];
	$_GET = $location.search();
    
    $scope.DiytplInit = function(){
    	this.type = $_GET['type'];
    	this.init();
    }
    $scope.DiytplInit.prototype = {
    	init : function(){
    		this.FileImgArr = [];
    		this.getinfo();
    		this.clicks();
    	},
    	getinfo : function(){
    		var _this = this;
    		//获取页面列表
			$http.get('../template-filelist').success(function(json){
				var homelist ='';
				var tit = '';
				var fn = '';
				checkJSON(json, function(json){
					if(_this.type == 1){
						var data_byTitle = [], data_byFilename = [], result = [];
						$.each(json.data.files,function(k,v){
							if (typeof data_byTitle[v.title] === 'undefined') data_byTitle[v.title] = [];
							data_byTitle[v.title].push(v);
							var filename = v.filename.substr(0, v.filename.lastIndexOf('.'));
							if (typeof data_byFilename[filename] === 'undefined') data_byFilename[filename] = [];
							data_byFilename[filename].push(v);
						});
						//根据文件名、title归类删除重复文件
						for (i in data_byTitle){
							if (data_byTitle[i].length > 1) {
								$.each(data_byTitle[i],function(k,v){
									delete data_byFilename[v.filename.substr(0, v.filename.lastIndexOf('.'))];
								});
								result.push({
									title: i,
									type: data_byTitle[i].length == 2 ? 2 : 1, 
									data: data_byTitle[i]
								});
							}
						}
						for (i in data_byFilename){
							result.push({
								title: data_byFilename[i][0].title,
								type: data_byFilename[i].length == 2 ? 2 : 1,
								data: data_byFilename[i]
							});
						}
						$.each(result,function(k,v){
							var list = '';
							$.each(v.data,function(i,j){
								list += '<dd class="made_name"><a href="javascript:void(0);">'+(v.type == 1?'':'一')+j.title+'（'+j.filename+'）</a></dd>';
							});
							homelist += '<li class="list_a"><dl class="'+(v.type == 1?'made_left fl':'text_box')+'">'+v.title+'：</dl>'+(v.type == 1?'':'<dl class="wid_box"></dl>')+'<dl class="made_right fl">'+list+'</dl></li>'
						});
						$('.made_style').html(homelist).after('<div class="addfile-item"><a href="javascript:void(0);" class="bluebtn addfile">添加文件</a></div>');
    					_this.AddFile();
    					_this.EditSave(json);
					}
				});//checkJSON结束
			});//GET请求结束
    	},
    	clicks : function(){
    		$(".made_style li a").click(function(){
				$("ul.made_style li").addClass("cu")
				$(this).parents("ul.made_style li").siblings().removeClass("cu").find("a,.made_name").removeClass("cu")
			});
    	},
    	ModelHtml : function(files,filetitle,filename){
    		var preview = '<h1 class="made_style_edite_top"><a href="javascript:void(0);" class="made_btn resh_btn fr">保存文件</a><a href="../homepage-preview" class="made_btn Preview_btn fr">预览首页</a><a onclick="javascript:history.go(-1);" class="made_btn resh_btn fr">返回</a><span class="fl">'+files+'：'+filetitle+'（'+filename+'）</span><a href="javascript:void(0);" class="made_btn up_load fl">上传图片</a></h1>\n\
                    <textarea class="made_edite">厦门易尔通厦门易</textarea>';
        	$('.made_style_edite').html(preview);
        	this.$made_edite = $('.made_edite')
        	this._UploadImg();
    	},
    	AddFile : function(){
    		var _this = this;
    		$('.home-content .addfile').click(function(event) {
    			var target = $(event.target),filename;
    			if(target.prev().hasClass('newfile')){
    				$('.made_style_edite .made_edite').html('');
    				document.body.scrollTop = 0;
    				filename = $('.filename:not(:hidden)').val() || $('.fullname:not(:hidden)').val();
    				filename += $('.filesuffix').text();
					_this.ModelHtml('编辑文件','',filename);
    			}else{
    				target.text('编辑文件');
    				target.before('<div class="newfile">\
						<select type="text" class="filetype">\
							<option selected="selected">文件类型</option>\
							<option value="js">脚本文件</option>\
							<option value="html">HTML模板</option>\
							<option class="filedatachoose">模板数据</option>\
						</select>-<input type="text" class="filename" placeholder="文件别名" />\
						<select type="text" class="fullname none"></select>\
						<label type="text" class="filesuffix"></label></div>');
					_this.AddFileSelect();
    			}
			});
    	},
    	AddFileSelect : function(){
    		$('.filetype').change(function(event) {
				var target = $(this),
					type = target.val(),
					suffix = target.siblings('.filesuffix'),
					fullname = $('.fullname'),filename = fullname.siblings('.filename');
				switch (type) {
					case 'js':
						suffix.text('.js');
						fullname.hide();
						filename.show();
						break;
					case 'html':
						suffix.text('.html');
						fullname.hide();
						filename.show();
						break;	
					case 'filedatachoose':
						suffix.text('');
						fullname.show();
						filename.hide();
						$http.get('').success(function(json){
							checkJSON(json,function(json){
								var OPhtml = '<option value="index.json">index.json<option/><option value="global.json">global.json<option/>';
								for(var key in json){
									OPhtml += '<option value="'+json[key]+'">'+json[key]+'<option/>';
								};
								$('.fullname').append(OPhtml);
							});
						});
						break;	
				}
			});
    	},
    	_UploadImg : function(){
    		var _this = this;
    		$('.up_load').on('click',function(event) {
                var targe = $(this);
                var warningbox = new WarningBox();
                warningbox._upImage({
                	IsOneNatural : true,
                    ajaxurl    : '../file-upload?target=imgcache',
                    oncallback : function(json){
                        insertText($('.made_edite')[0],json.data[0].url);
                        _this.FileImgArr.push(json.data[0].url);
                    }
                });
            });
    	},
    	EditSave : function(json){
    		//右侧预览页
			var file_name,file_tit,_this = this;
			$('.made_right .made_name').on('click',function(){
				var target = $(this);
				file_name = target.text().substring(target.text().indexOf('（')+1,target.text().indexOf('）'));
				file_tit = target.text().substring(0,target.text().indexOf('（'));
				files = target.parents('.list_a').find('dl:first').text().substring(0,target.parents('.list_a').find('dl:first').text().indexOf('：'));
				$.each(json.data.files,function(k,v){
					$('.made_style_edite_top .made_edite').text(''+v.content+'');
				});
				_this.ModelHtml(files,file_tit,file_name);
            	$http.get('../template-fileget?type='+_this.type+'&filename='+file_name+'').success(function(json){
					checkJSON(json, function(json){
						$('.made_style_edite .made_edite').html(json.data.code);
					});
				});
            	target.find('a').addClass('red').end().siblings().find('a').removeClass('red').end().closest('.list_a').siblings().find('a').removeClass('red');
            	$('.made_style_edite_top .resh_btn').on('click',function(){
					$http.post('../template-fileedit',{
						type 	: type,
						filename: file_name,
						code 	: $('.made_style_edite .made_edite').val(),
						fileimg : _this.FileImgArr
					}).success(function(json){
						checkJSON(json, function(json){
							var hint_box = new Hint_box();
                			hint_box;
						});
					});
				});
			});
			var paddinHeight = $('.made_style_edite').outerHeight() - $('.made_style_edite').height();
			$('.made_style_edite').css('height', $('#home-diy').innerHeight() - paddinHeight);
    	}
    }
    var init = new $scope.DiytplInit();
}