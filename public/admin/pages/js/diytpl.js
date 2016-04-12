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
    		this.FileJsonArr = [];
    		this.FileHtmlToJsonArr = [];
    		this.getinfo();
    		this.clicks();
    	},
    	getinfo : function(){
    		var _this = this;
                if($('#phone').data('phoneClosed_index')){
                    $('#phone-home').click();
                }
    		//获取页面列表
			$http.get('../template-filelist?type='+_this.type).success(function(json){
				var homelist ='',tit = '',fn = '';
				checkJSON(json, function(json){
					if(_this.type == 1 || _this.type == 2){
						$.each(json.data.filenames,function(k,v){
							var list = '';
                                                        console.log(v.files);
                                                        if(v.files){
                                                            $.each(v.files,function(i,j){
                                                                    j.match(/.*\.html$/) != null ? _this.FileHtmlToJsonArr.push(j.replace(/\.html$/,'.json')) : null;
                                                                    j.match(/.*\.json$/) != null ? _this.FileJsonArr.push(j) : null;
                                                                    list += '<dd class="made_name"><a href="javascript:void(0);">（'+j+'）</a></dd>';
                                                            });
                                                        }
							homelist += '<li class="list_a"><dl class="made_left fl">'+v.title+'：</dl><dl class="made_right fl">'+list+'</dl></li>'
						});
						$('.made_style').html(homelist).after('<div class="addfile-item"><a href="javascript:void(0);" class="bluebtn addfile">添加文件</a></div>');
    					_this.AddFile();
    					_this.FileListClick(json);
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
    	ModelHtml : function(files,filename){
    		var preview = '<h1 class="made_style_edite_top"><a href="javascript:void(0);" class="made_btn resh_btn fr">保存文件</a><a href="../homepage-preview" class="made_btn Preview_btn fr">预览首页</a><a onclick="javascript:history.go(-1);" class="made_btn resh_btn fr">返回</a><span class="fl">'+files+'：（'+filename+'）</span><a href="javascript:void(0);" class="made_btn up_load fl">上传图片</a></h1>\n\
                    <textarea class="made_edite"></textarea>';
        	$('.made_style_edite').html(preview);
        	this.$made_edite = $('.made_edite')
        	this._UploadImg();
    	},
    	AddFile : function(){// 添加文件的编辑按钮
    		var _this = this;
    		$('.home-content .addfile').click(function(event) {
    			var target = $(event.target),filename;
    			if(target.prev().hasClass('newfile')){
    				document.body.scrollTop = 0;
    				filename = $('.filename:not(:hidden)').val() ? $('.filename:not(:hidden)').val()+$('.filesuffix').text() : $('.fullname:not(:hidden)').val();
					_this.ModelHtml('编辑文件',filename);
					_this.EditSave(filename);
    			}else{
    				target.text('编辑文件');
    				target.before('<div class="newfile">\
						<select type="text" class="filetype">\
							<option value="css">样式文件</option>\
							<option value="js">脚本文件</option>\
							<option selected="selected" value="html">HTML模板</option>\
							<option value="filedatachoose" class="filedatachoose">模板数据</option>\
						</select>-<input type="text" class="filename" placeholder="文件别名" />\
						<select type="text" class="fullname none"></select>\
						<label type="text" class="filesuffix">.html</label></div>');
					_this.AddFileSelect();
    			}
			});
    	},
    	AddFileSelect : function(){
    		var _this = this;
    		$('.filetype').change(function(event) {
				var target = $(this),
					type = target.val(),
					suffix = target.siblings('.filesuffix'),
					fullname = $('.fullname'),filename = fullname.siblings('.filename');
				switch (type) {
                                        case 'css':
                                                suffix.text('.css');
						fullname.hide();
						filename.show();
						break;
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
						var OPhtml = (_this.type = 1 ? '<option value="global.json">global.json</option>' : '');
						for(var i in _this.FileJsonArr){
							_this.FileHtmlToJsonArr.splice($.inArray(concatArr[i],_this.FileHtmlToJsonArr),1);
						}
						for(var j in _this.FileHtmlToJsonArr){
							OPhtml += '<option value="'+_this.FileHtmlToJsonArr[j]+'">'+_this.FileHtmlToJsonArr[j]+'</option>';
						}
						$('.fullname').append(OPhtml);
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
                        insertText($('.made_edite')[0],json.data.url);
                        _this.FileImgArr.push(json.data.name);
                    }
                });
            });
    	},
    	FileListClick : function(json){
    		//右侧预览页
			var file_name,file_tit,_this = this;
			$('.made_right .made_name').click(function(){
				var target = $(this),$made_left = target.parent().siblings('.made_left');
				file_name = target.text().substring(target.text().indexOf('（')+1,target.text().indexOf('）'));
				file_tit = $made_left.text().substring(0,$made_left.text().indexOf('：'));
				_this.ModelHtml(file_tit,file_name);
            	$http.get('../template-fileget?type='+_this.type+'&filename='+file_name+'').success(function(json){
					checkJSON(json, function(json){
						$('.made_style_edite .made_edite').html(json.data.code);
					});
				});
            	target.find('a').addClass('red').end().siblings().find('a').removeClass('red').end().closest('.list_a').siblings().find('a').removeClass('red');
            	_this.EditSave(file_name);
			});
			var paddinHeight = $('.made_style_edite').outerHeight() - $('.made_style_edite').height();
			$('.made_style_edite').css('height', $('#home-diy').innerHeight() - paddinHeight);
    	},
    	EditSave : function(filename){
    		var _this = this;
    		$('.made_style_edite_top .resh_btn').click(function(){
    			if(!filename) return false;
				$http.post('../template-fileedit',{
					type 	: _this.type,
					filename: filename,
					code 	: $('.made_style_edite .made_edite').val(),
					fileimg : _this.FileImgArr
				}).success(function(json){
					checkJSON(json, function(json){
						var hint_box = new Hint_box();
            			hint_box;
					});
				});
			});
    	}
    }
    var init = new $scope.DiytplInit();
}