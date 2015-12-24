function phone_indexController($scope,$http ,$location) {
    $scope.$parent.showbox = "main";
    $scope.$parent.homepreview = true;
    $scope.$parent.menu = [];
    $_GET = $location.search();

    $scope.phoneIndexInit = function(){
    	this.templePage = 'index';
    	this.maininfourl = '../mhomepage-data';
    	// this.maininfourl = 'json/phone_index.json';
    	this.init();
    }
    $scope.phoneIndexInit.prototype = {
    	init : function(){
    		this.imageType = {};
    		this.quickbarType = [];
    		this.otherType = {};
    		this._loading();
    		this._getInfo();
    	},
    	_loading : function(){
    		$('#phone').data('phoneClosed_index', true);
		    $('body').removeClass('closephone');
		    setTimeout(function(){ width_resize(); },4);
    	},
    	_getInfo : function(){
		    var Area_name = $_GET['Area'] || 'index',_this = this;
		    if(Area_name == 'quickbar'){
				$('.phone_index_btn .delcolumn,.phone_index-banner .navs').remove();
				$('.phone_index_btn .save').show();
				$('#phone_index .phone_index_btn').width(122)
			}
			$('#phone_index-'+Area_name).show();
    		// 获取手机切换标签
		    $http.get(this.maininfourl).success(function(json){
		    	checkJSON(json,function(json){ 
		    		var data = json.data.pagelist,mpagetype,type;
		    		// 标签切换修改保存按钮类名
				    $('.phone_index-banner .navs li a').on('click',function(){
						var page = $(this).data('sub').split("-");
						window.location.hash = '#/phone_index?Area=' + page[1];
				    });
					if(Area_name != _this.templePage){
						$.each(json.data.templatedata,function(idx, ele) {
				    		switch(ele.type){
				    			case "image":
					    		case "images":
					    			$('.page_navs a[data-sub="phone_index-images"]').removeClass('not-allowed');
					    			_this.imageType[idx] = ele;
					    			break;
				    			case "quickbar":
					    			_this.quickbarType = ele;
					    			break;
				    			case "text":
				    			case "textarea":
				    			case "navs":
				    				$('.page_navs a[data-sub="phone_index-other"]').removeClass('not-allowed');
					    			_this.otherType[idx] = ele;
					    			break;
					    	}
					    });
					    if(Area_name == 'images'){
					    	mpagetype = 'images';
					    	var SlidepicsInit = new $scope.phoneIndexSlidepics(_this.imageType);
					    }
				    	if(Area_name == 'quickbar'){
				    		mpagetype = 'quickbar';
				    		$http.get('../quickbar.jsoninit').success(function(json){
					    		checkJSON(json,function(json){
					    			var QuickbarInit = new $scope.phoneIndexQuickbar(json.data);
					    		});
					    	});
				    	}
					    if(Area_name == 'other'){
					    	mpagetype = 'other';
				    		var OtherInit = new $scope.phoneIndexOther(_this.otherType);
					    }
					}else{
					    // 栏目排序
		    			var init = new $scope.phoneIndexPageList(json.data.m_catlist);
						mpagetype = 'index';
					}
					// 标签切换
					_this.change_sub(Area_name,mpagetype);
		    	});
		    });
    	},
    	Save_hint : function(){
			// 保存延时效果
    		var Countdown = 2;
			function inside_hint(){
		    	$(".tpl_mask").show();
				$(".text_tishi").show();
				if(Countdown > 0){
					$('#phone_index .text_tishi').text('努力保存中...('+Countdown+')')
					Countdown--;
					setTimeout(inside_hint,1000);
				}else{
					$('#phone_index .text_tishi').text('保存成功！')
					setTimeout(function(){
						$(".tpl_mask").hide();
						$(".text_tishi").hide();
					},1000);
	                $('#phone_preview').attr('src','../mobile/homepage-preview');
				}
		    }
		    inside_hint();
    	},
    	change_sub : function(phonename,mpagetype){
    		// 标签切换
    		// 标签选中状态
	    	$('.phone_index-banner .page_navs li a').each(function(){
				var page = $(this).data('sub').split("-");
				if(phonename == page[1]){
					$(this).addClass('border_red').siblings().removeClass('border_red');
	    		}
	    	});
    	}
    };
    var phoneindexinit = new $scope.phoneIndexInit();
    // 手机栏目排序
    $scope.phoneIndexPageList = function(ele){
    	this.jsonData = ele;
    	this.init();
    };
    $scope.phoneIndexPageList.prototype = {
    	init : function(){
    		this.pagelist_info();
    	},
    	pagelist_info : function(){
    		var _divT = '',_divF = '',
    			data = this.jsonData,
    			_this = this;
			$.each(data,function(k,v){
				if(v.showtypetotal != 0){// 可选
	                _this.column_icon(v.type);
					_divT += '<tr class="firsttab" data-aid="'+v.id+'">\n\
		            	<td>\n\
	            			<i class="fa iconfont icon-phonehome btn '+(v.index_show == 1?'blue':'grey')+'"></i>\n\
	            			<div class="style_choose"><select>\n\
								<option '+(v.showtype == 1 ? 'selected' : '')+' '+(v.showtypetotal >= 1?'' : 'class="none"')+' value="1">'+(v.showtypetotal >= 1?'样式一' : '')+'</option>\n\
								<option '+(v.showtype == 2 ? 'selected' : '')+' '+(v.showtypetotal >= 2?'' : 'class="none"')+' value="2">'+(v.showtypetotal >= 2?'样式二' : '')+'</option>\n\
								<option '+(v.showtype == 3 ? 'selected' : '')+' '+(v.showtypetotal >= 3?'' : 'class="none"')+' value="3">'+(v.showtypetotal >= 3?'样式三' : '')+'</option>\n\
								<option '+(v.showtype == 4 ? 'selected' : '')+' '+(v.showtypetotal >= 4?'' : 'class="none"')+' value="4">'+(v.showtypetotal >= 4?'样式四' : '')+'</option>\n\
	    					</select></div>\n\
		            	</td>\n\
		            	<td><div class="tit_info">'+v.name+'</div>'+layout+'</td>\n\
		            	<td><i herf="javascript:void(0);" class="fa iconfont icon-xingxing star'+(v.star_only == 1 ? ' checked' : '')+'"></i>限<input type="text" value="'+v.show_num+'" class="show_num" />条</td>\n\
		            	<td><i class="fa iconfont icon-yidong"></i></td>\n\
		            </tr>';
	            }else{// 不可选
	                _this.column_icon(v.type);
	                _divF += '<tr class="firsttab" data-aid="'+v.id+'">\n\
		            	<td>\n\
	            			<i class="fa iconfont icon-phonehome '+(v.index_show == 1?'blue':'grey')+' not-allowed"></i>\n\
	            			<div class="style_choose"><select disabled class="not-allowed">\n\
								<option >无样式</option>\n\
	    					</select></div>\n\
		            	</td>\n\
		            	<td><div class="tit_info">'+v.name+'</div>'+layout+'</td>\n\
		            	<td></td>\n\
		            	<td></td>\n\
		            </tr>';
	            }
			});
			$('#phone_index-index .phone_table .sapces').after(_divT);
	        $('#phone_index-index .a-table .disArea').after(_divF);
		    this.IsShow();
		    this.IsStar();
		    this.ShowNum();
		    this.ChangeStyle();
    	},
    	IsShow : function(){
			// 是否展示
    		$('#phone_index-index .icon-phonehome').click(function(){
		    	var btn = $(this),
		    		vid = $(this).parents('tr').attr('data-aid'),
		    		isshow = ($(this).hasClass('blue') ? 0 : 1);
		    	if(!btn.hasClass('not-allowed')){
		    		$http.post('../mhomepage-batchmodify',{id:vid,show:isshow}).success(function(json){
		                checkJSON(json,function(json){
		                    if(btn.hasClass('blue')){
		                        btn.removeClass('blue').addClass('grey');
		                    }else{
		                        btn.removeClass('grey').addClass('blue');
		                    }
		                    phoneindexinit.Save_hint();
		                });
		            });
		    	}
		    });
    	},
    	IsStar : function(){
    		// 星标文章
	        $('#phone_index-index .firsttab .star').click(function(){
	            var id = $(this).parents('.firsttab').data('aid');
	            if(!$(this).hasClass("checked")){
	                $(this).addClass("checked");
	                $http.post('../mhomepage-batchmodify',{id:id,star_only:1}).success(function(json){
	                    checkJSON(json,function(json){
	                        var hint_box = new Hint_box();
	                        hint_box;
	                    });
	                });
	            }else{
	                $(this).removeClass("checked");
	                $http.post('../mhomepage-batchmodify',{id:id,star_only:0}).success(function(json){
	                    checkJSON(json,function(json){
	                        var hint_box = new Hint_box();
	                        hint_box;
	                    });
	                });
	            }
	        });
    	},
    	ShowNum : function(){
    		// 显示数量
		    $('#phone_index-index tr td .show_num').change(function(){
		    	var total = $(this).val(),
		    		id = $(this).parent().parent().data('aid');
		   		$http.post('../mhomepage-batchmodify',{id:id,total:total}).success(function(json){
		   			checkJSON(json,function(json){phoneindexinit.Save_hint();});
		   		});
		    });
    	},
    	ChangeStyle : function(){
    		// 更改样式
		    $('.firsttab .style_choose select').change(function(){
		    	var style = $(this).val(),
		    		id = $(this).parents('tr').data('aid');
		   		$http.post('../mhomepage-batchmodify',{id:id,showtype:style}).success(function(json){
		   			checkJSON(json,function(json){phoneindexinit.Save_hint();});
		   		});
		    });
	       	$('#phone_index-index tr .icon-yidong').TreeList({
	       		rootNode    : 'firsttab',
	       		parentNode  : 'phone_index-index',
	       		'oncallback':function(indexlist){
	       			$http.post('../mhomepage-sortmodify', {indexlist:indexlist}).success(function(json) {
		                checkJSON(json, function(json){
		                    phoneindexinit.Save_hint();
		                });
		            });
	       		}
	       	});
    	},
    	column_icon : function(type){
    		// 获取对应栏目图标
    		switch(parseInt(type)){
	            case 1: layout = '<i class="fa iconfont icon-liebiao"></i>';break;
	            case 3: layout = '<i class="fa iconfont icon-list"></i>';break;
	            case 2: layout = '<i class="fa iconfont icon-graph"></i>';break;
	            case 4: layout = '<i class="fa iconfont icon-wenjian"></i>';break;
	            case 6: layout = '<i class="fa iconfont icon-lianjie"></i>';break;
	            case 7: layout = '<i class="fa iconfont icon-weixin"></i>';break;
	            case 8: layout = '<i class="fa iconfont icon-dingwei"></i>';break;
	        }
    	}
    };
    // 幻灯片
    $scope.phoneIndexSlidepics = function(ele){
    	this.jsonData = ele;
    	this.count = 0;
    	this.init();
    };
    $scope.phoneIndexSlidepics.prototype = {
    	init : function(){
    		this.ModelSlidepicsInfo = function(parameter){
    			var _div = '<'+(parameter.Tag || 'li')+' class="phone_index-field'+(parameter.IsNew ? ' new' : '')+'">\n\
						<div class="materlist-first">\n\
			            	<dt class="title">'+(parameter.title || '')+'</dt>\n\
			                <dd class="msgimg">\n\
			                <img src="'+parameter.image+'" alt="" width="100%">\n\
			                </dd>\n\
			                <div class="concrol">\n\
			                	<dl><dd class="zz"></dd><dd class="concrol-edite"><i class="iconfont icon-bianji"></i></dd></dl>\n\
			                	<dl class="i1"><dd class="zz"></dd><dd class="concrol-del" name="'+(parameter.id || '')+'"><i class="iconfont icon-delete"></i></dd></dl> \n\
			                </div>\n\
			            </div>\n\
			            <div class="del-box">\n\
		                	<span class="del-sanjiao"></span>\n\
		                    <dd class="del-btn">删除</dd>\n\
			            </div>\n\
			            <div class="materlist-secondbox" style="dispaly:block;">\n\
			            <span class="sanjiao"></span>\n\
			            <div class="materlist-second">\n\
			            	<dt class="title">编辑名称</dt>\n\
			                <dd class="input"><input name="'+parameter.key+(parameter.num == undefined ? '[0]' : '['+parameter.num+']')+'[PC_name]" type="text" value="'+(parameter.title || '')+'" /></dd>\n\
			                <dt class="title">编辑链接</dt>\n\
			                <dd class="input"><input name="'+parameter.key+(parameter.num == undefined ? '[0]' : '['+parameter.num+']')+'[PC_link]" type="text" value="'+(parameter.link || '')+'" /></dd>\n\
			                <dl class="btnbox">\n\
			                	<dd class="surebtn">确定</dd>\n\
			                    <dd class="cancebtn">取消</dd>\n\
			                </dl>\n\
			            </div>\n\
			            </div>\n\
			            <div class="detailbox">\n\
			            	<span class="sanjiao"></span>\n\
			            	<div class="detailbox-main">\n\
			                	<dt class="title">确定删除此素材？</dt>\n\
			                    <dl class="btnbox">\n\
				                	<dd class="surebtn">确定</dd>\n\
				                    <dd class="cancebtn">取消</dd>\n\
			                	</dl>\n\
			                </div>\n\
			            </div><input type="hidden" name="'+parameter.key+(parameter.num == undefined ? '[0]' : '['+parameter.num+']')+'[phone_info_pic]" value="'+(parameter.subimage || '')+'" />\n\
					</'+(parameter.Tag || 'li')+'>';
				return _div;
    		};
    		this.slidepics_info();
    		this.EditBtn();
    	},
    	slidepics_info : function(){
    		// 幻灯片列表
    		var htmlColumn = '',num = 1,
    			_this = this;
			$.each(this.jsonData,function(index, ele) {
				var aspectRatio = ele.config.width/ele.config.height || '';
				if(ele.type == 'images'){
					var data = ele.value,
						oddColumnWidth = 315,									// 单列宽
		    			mainWidth = $('#phone_index').width(),					// 区域总宽
		    			ColumnNum = Math.floor(mainWidth/oddColumnWidth),		// 列数
		    			lastColumnNum = (data.length%ColumnNum);				// 记录最终添加完的是第几列
					// 添加瀑布流列
					$('#phone_index_image').before('<form id="phone_index_images_'+num+'" data-key="'+index+'"></form>');
					$('#phone_index_images_'+num).append('<div class="pictitle">多图文'+num+'</div>').data({'lastColumnNum':lastColumnNum,'ColumnNum':ColumnNum,'aspectRatio':aspectRatio});
					for(var i = 1;i <= ColumnNum;i++){
						$('#phone_index_images_'+num).append('<ul id="phone_index_col_'+num+'_'+i+'" class="phone_index_col" style="width:'+oddColumnWidth+'px"></ul>');
					}
					$.each(data,function(k,v){
						_this.count = k;
						// 除余取列
						var C_num = (k+1)%ColumnNum == 0 ? (k+1) : (k+1)%ColumnNum;
						var _div = _this.ModelSlidepicsInfo({
							key 	: index,
							title	: v.title,
							image	: v.image,
							subimage: v.image,
							id		: v.id,
							link	: v.link,
							num		: k
						});
						$('#phone_index_col_'+num+'_'+C_num+'').append(_div);
					});
					// 添加按钮
					var addButton = '<div class="phone_index-add">\
										<div class="up_pic up_phone"></div>\
									</div>';
					$('#phone_index_col_'+num+'_'+(lastColumnNum+1)+'').append(addButton);
					num++;
				}else{
					var data = ele.value,aspectRatio = ele.config.width/ele.config.height || '',
						_div = _this.ModelSlidepicsInfo({
							key 	: index,
							title	: data.title,
							image	: data.image,
							subimage: data.image,
							id		: data.id,
							link	: data.link,
							Tag 	: 'div'
						}),
						addButton = '<div class="phone_index-add">\
										<div class="up_pic up_phone"></div>\
									</div>';
					$('#phone_index_image').append('<div class="pictitle">单图</div>');
					$('#phone_index_image').append(_div+addButton).data('aspectRatio', aspectRatio);
				}
			});
			_this.slidepics_upload();
			this.IsDelete();
    	},
    	layoutChange : function(itemIdNum){
    		// 改变列表布局
    		// 创建数组记录总个数
			var arrTemp = [],arrHtml = [],maxLength = 0,changeNum = 0,newColumnNum;
			// 新栏个数		
			newColumnNum = Math.floor($('#phone_index').width() / 315);
			for (var start = 1; start <= newColumnNum; start++) {
				var arrColumn = $("#phone_index_col_"+itemIdNum+'_'+start).html().match(/<li(?:.|\n|\r|\s)*?li>/gi);
				if (arrColumn) {
					maxLength = Math.max(maxLength, arrColumn.length);
					arrTemp.push(arrColumn);
					changeNum++;
				}
			};
			// 重新排序
			var rowStart, colStart;
			for (rowStart = 0; rowStart<maxLength; rowStart++) {
				for (colStart = 0; colStart<changeNum; colStart++) {
					if (arrTemp[colStart][rowStart]) {
						arrHtml.push(arrTemp[colStart][rowStart]);	
					}
				}	
			}
			var lastColumnNum = arrHtml.length%newColumnNum;
			// 添加按钮
			arrHtml.push('<div class="phone_index-add"><div class="up_pic up_phone"></div></div>');
			if(arrHtml.length !== 0){
				// 计算每列的行数
				var line = Math.ceil(arrHtml.length / newColumnNum);
				// 重组HTML
				var newStart = 0, htmlColumn = '<div class="pictitle">多图文'+itemIdNum+'</div>';
				for (newStart; newStart < newColumnNum; newStart++) {
					htmlColumn = htmlColumn + '<ul id="phone_index_col_'+itemIdNum+'_'+(newStart+1) +'" class="phone_index_col" style="width:315px">'+ 
						function() {
							var html = '';
							for (var i=0; i<line; i++) {
								html += arrHtml[newStart + newColumnNum * i] || '';
							}
							return html;	
						}() + '</ul> ';	
				}
				$('#phone_index_images_'+itemIdNum).html(htmlColumn);
				this.slidepics_upload();
			}
    	},
    	slidepics_upload : function(){
    		var _this = this,_newpic,i = 1;
			// 添加图片弹框
    		$('.up_pic').on('click',function(event) {
		        var warningbox = new WarningBox(),eventitemnum = $(this).closest('form').attr('id').split('_')[3],
		        	lastColumnNum = $(this).closest('form').data('lastColumnNum'),
		        	aspectRatio = $(this).closest('form').data('aspectRatio'),
		        	ColumnNum = $(this).closest('form').data('ColumnNum'),
		        	event_this = this;
		        warningbox._upImage({
		        	aspectRatio: aspectRatio,
		            ajaxurl    : '../file-upload?target=page_index',
		            oncallback : function(json){
		            	if($(event_this).closest('#phone_index_image').length){
		            		$('#phone_index_image div.phone_index-field').remove();
		            		_newpic = _this.ModelSlidepicsInfo({
		            			key 	: event_this.closest('form').data('key'),
								image	: json.data.url,
								subimage: json.data.name,
								Tag 	: 'div',
								IsNew   : true
							});
		            		$(event_this).parent().before(_newpic);
		            	}else{
			                _newpic = _this.ModelSlidepicsInfo({
			                	key 	: event_this.closest('form').data('key'),
								image	: json.data.url,
								subimage: json.data.name,
								num		: ++(_this.count),
								IsNew   : true
							});
							var addBtn = (lastColumnNum+1)%ColumnNum,
								addpic = (lastColumnNum+2)%ColumnNum;
			                $('#phone_index_col_'+eventitemnum+'_'+(addBtn == 0 ? (lastColumnNum+1) : addBtn)+'').append(_newpic);
			                $('#phone_index_col_'+eventitemnum+'_'+(addpic == 0 ? (lastColumnNum+2) : addpic)+'').append($(event_this).parent());
			                lastColumnNum++;
		            	}
						_this.IsDelete();
		            }
		        });
		    });
		},
		IsDelete : function(){
			var _this = this;
    		// 确定、取消按钮效果
			$(".concrol-del").hover(function(){
				$(this).parents(".phone_index-field").find(".del-box").slideDown();
			},function(){
				$(this).parents(".phone_index-field").find(".del-box").slideUp();
			}).click(function(){
				$(this).parents(".phone_index-field").find(".detailbox").slideDown();
				$(this).parents(".concrol").find(".zz").show();
			})
			$('.concrol-edite').click(function(){
				$(this).parents(".phone_index-field").find(".materlist-secondbox").slideDown();
				$(this).parents(".concrol").find(".zz").show();
			})
			$('.cancebtn').click(function(){
				$(this).parents(".phone_index-field").find(".materlist-secondbox,.detailbox").slideUp();
				$(this).parents(".phone_index-field").find(".zz").hide();
			})
			$('.detailbox .surebtn').click(function(){
				var itemIdNum = $(this).closest('.phone_index_col').attr('id').split('_')[3];
				$(this).parents('.phone_index-field').fadeOut('400', function() { 
					$(this).remove();
					_this.layoutChange(itemIdNum);
					//幻灯片删除
					if(!$(this).hasClass('new')){
						var data = $('#phone_index_images_'+itemIdNum).serializeJson();
						var data1 = ($('#phone_index_images_'+itemIdNum).serializeArray().length > 0?data:{slidepics : ""});
						$http.post('../mhomepage-modify',data1).success(function(){
							phoneindexinit.Save_hint();
						});
					}
				});
			})
			$('.materlist-secondbox .surebtn').click(function(){
				var _this = $(this).closest('.phone_index-field');
				$(this).parents(".phone_index-field").find(".materlist-secondbox,.detailbox").slideUp();
				$(this).parents(".phone_index-field").find(".zz").hide();
				if($(this).parents('div').hasClass('materlist-secondbox')){
					//幻灯片保存
					var data = $(this).closest('form').serializeJson();
					$http.post('../mhomepage-modify',data).success(function(){
						phoneindexinit.Save_hint();
						_this.removeClass('new');
					});
				}
			});
		},
		EditBtn : function(){
			// 编辑功能
			$('.materlist-second .input:nth-of-type(1) input').on('keyup',function(event) {
				$(this).parents('.materlist-secondbox').siblings('.materlist-first').children('.title').text($(this).val());
			});
		}
    };
    // Other数据
    $scope.phoneIndexOther = function(ele){
    	this.jsonData = ele;
    	this.init();
    };
    $scope.phoneIndexOther.prototype = {
    	init : function(){
    		this.GetOtherData();
    		this.SaveOtherDataUrl = '';
    	},
    	GetOtherData : function(){
    		$.each(this.jsonData,function(k, v) {
    			var _div = '';
    			switch(v.type){
	    			case 'text':
	    				_div += '<li>\
									<dl class="leftblock">'+v.description+':</dl>\
									<dl class="rightblock">\
										<input type="text" name="'+k+'" value="'+v.value+'"></input>\
									</dl>\
								</li>';
						$('#phone_index_text ul').append(_div);
	    				break;
					case 'textarea':
	    				_div += '<li>\
									<dl class="leftblock">'+v.description+':</dl>\
									<dl class="rightblock">\
										<textarea name="'+k+'" cols="52" rows="4">'+v.value+'</textarea>\
									</dl>\
								</li>';
						$('#phone_index_textarea ul').append(_div);
	    				break;
					case 'navs':
	    				_div += '<li>\
									<dl class="leftblock">'+v.description+':</dl>\
									<dl class="rightblock">\
										<div id="move_navs">\
										'+function(){
											var html = '',_rel = '',list1 = '',pname = '',sign = [];
											$.each(v.config.ids,function(idx, ele) {
												$.each(v.config.list,function(i,vlist){
													var _this = $(this);
													if(vlist.p_id == 0){
														if(vlist.id == ele) {sign = '<input class="selectBox_val" type="hidden" value="'+vlist.id+'" name="data['+k+'][ids]['+idx+']" />';pname = vlist.name}
														list1 += '<li><a class="'+(vlist.childmenu == null ? 'lastchild ' : '')+'parents'+((v.config.filter.toLowerCase() == 'all' || v.config.filter == 'list,page') ? '' : v.config.filter == 'page' ? '' : vlist.type == 4 ? ' not-allowed' : '')+'" data-id="'+vlist.id+'">'+vlist.name+'</a></li>';
										                var NextChild = vlist;
										                var num = 2;
														var LoopChlid = function(NextChild,num){
					                                        if(NextChild.childmenu != null){
					                                            $.each(NextChild.childmenu,function(kk,vv){
					                                            	if(vv.id == ele) {sign = '<input class="selectBox_val" type="hidden" value="'+vv.id+'" name="data['+rootNodeName+'][ids]['+idx+']" />';pname = vv.name}
					                                            	list1 += '<li><a class="'+(vv.childmenu == null ? 'lastchild ' : '')+'LevelChild'+num+((vv.type == 4) && (v.config.filter == 'list')?' not-allowed':'')+'" data-pid="'+vv.p_id+'" data-id="'+vv.id+'">├ '+vv.name+'</a></li>';
					                                                NextChild = vv;
					                                                num++;
					                                                LoopChlid(NextChild,num);
					                                                num--;
					                                            });
					                                        }
					                                    }
					                                    v.config.filter == 'page' ? '' : LoopChlid(NextChild,num);
													}
												});
												_rel += '<div class="dropdown" style="margin-bottom:10px;">\
					                            <div class="selectBox" type="text">'+pname+'</div><span class="arrow"></span>'+sign+'\
					                            <ul>'+list1+'</ul><span class="move_icon"><i class="iconfont icon-liebiao"></i><i class="iconfont icon-guanbi"></i></span></div>'+(idx == (v.config.ids.length-1) ? '<div class="add_icon"><i class="iconfont icon-add" data-limit="'+v.config.limit+'"></i></div>' : '')+'';
											});
											return _rel;
										}()+'</div>\
									</dl>\
								</li>';
						$('#phone_index_navs ul').append(_div);
	    				break;
	    		}
    		});
			//下拉框更改
			DropdownEvent();
			// 提示移动框
			$('.not-allowed').MoveBox({context:'此为单页类型或者父级分类下带有子级，不支持选择！'});
    		$scope.phoneIndexQuickbar.prototype.InputStyle();// 拖拽效果
			$('.dropdown .icon-liebiao').TreeList({
				parentNode  : 'move_navs',
				rootNode 	: 'dropdown'
	       	});
	       	this.DeleteDropList();
	       	this.AddDropList();
	       	this.SaveOtherData();
    	},
    	DeleteDropList : function(){
    		$('#move_navs .icon-guanbi').on('click',function(){
	       		$(this).closest('.dropdown').remove();
	       	});
    	},
    	AddDropList : function(){
    		// 添加拖拽栏目
			$('.add_icon i').on('click', function(event) {
	       		if($(this).parent().siblings('.dropdown').length >= $(this).data('limit')){
	       			alert('超出数量！')
	       		}else{
					var lastNum = parseInt($('#move_navs .dropdown').last().find('.selectBox_val').attr('name').match(/\[(\d*)\]/)[1])+1,
	       				clone_cell = $('#move_navs .dropdown').last().clone(true);
					$('#move_navs .add_icon').before(clone_cell);
					clone_cell.find('.selectBox').text('空').end().find('.selectBox_val').val('');
					var word = clone_cell.find('.selectBox_val').attr('name').replace(/data\[(.*)\]\[(.*)\]\[(\d*)\]/,'data[$1][$2]['+lastNum+']');
					clone_cell.find('.selectBox_val').attr('name',word);
	       		}
			});
    	},
    	SaveOtherData : function(){
    		$('.save').click(function(){
    			var data = $(this).closest('form').serializeJson();
    			$http.post(this.SaveOtherDataUrl,data).success(function(){
		    		checkJSON(json,function(json){
		    			phoneindexinit.Save_hint();
		    		});
		    	});
    		})
    	}
    }
    // 底部导航
    $scope.phoneIndexQuickbar = function(ele){
    	this.jsonData = ele;
    	this.QuickBarShowTypeUrl = 'json/bottomnavsType.json';
    	this.init();
    };
    $scope.phoneIndexQuickbar.prototype = {
    	init : function(){
    		this.QuickBarInfo();
    		this.QuickBarShowType();
    	},
    	QuickBarInfo : function(){
    		var data = (this.jsonData == undefined ? null : this.jsonData.value),_this = this,
				_div1 = '',num,info;
			$.each(this.jsonData,function(k,v){
				switch(v.type){
					case 'tel':
					case 'sms':
						info = '<div class="quicklist-r inline-block"><span class="contact ml5"><input type="text" value="'+v.data+'" class="message-num" /></span></div>';
						break;
					case 'im':
						info = '<div class="quicklist-r inline-block">\
									<div class="consultation ml5">\
									<ul class="fl">'
									+function(){
										var newArr = v.data.split('|'),_li = '';
							    		for(var x in newArr){
							    			var fg = newArr[x].split('@'),
												kfname = fg[0].split(':')[0],kfnum = fg[0].split(':')[1];
							    			_li += '<li class="consultation-item">\
														<select><option value="qq"'+(fg[1] == 'qq' ? 'selected' : '')+'>QQ</option></select>\
														<span><input class="consultation-name message-num" value="'+(kfname||'')+'" />-<input class="consultation-num message-num" value="'+(kfnum||'')+'" /></span>\
														<div class="crl_icon fr"><i class="iconfont icon-guanbi"></i></div>\
													</li>';
							    		} 
							    		return _li;
									}()+'</ul><div class="crl_icon"><i class="iconfont icon-add"></i></div>\
								</div></div>';
						break;
					case 'share':
						info = '<div class="quicklist-r inline-block">\
								<span class="shareicon ml5">\
									<i class="iconfonts '+(v.data.indexOf('tsina') == -1 ? 'grey' : 'blue')+'" data-name="tsina">&#xe653;</i>\
									<i class="iconfonts '+(v.data.indexOf('ibaidu') == -1 ? 'grey' : 'blue')+'"  data-name="ibaidu">&#xe651;</i>\
									<i class="iconfonts '+(v.data.indexOf('qzone') == -1 ? 'grey' : 'blue')+'"  data-name="qzone">&#xe652;</i>\
									<i class="iconfonts '+(v.data.indexOf('tqq') == -1 ? 'grey' : 'blue')+'"  data-name="tqq">&#xe650;</i>\
								</span></div>';
						break;
					case 'link':
						var point = v.data.split('|')[1] || '';
						_this.pointX = point.split(',')[0] || '';
						_this.pointY = point.split(',')[1] || '';
						info = '<div class="quicklist-r inline-block">\
									<div class="linktop"><input class="message-num" value="'+v.data.split('|')[0]+'" data-point="'+_this.pointX+','+_this.pointY+'" /><a class="search">搜索</a></div>\
									<div id="bdmap"></div>\
								</div>';
						break;
					case 'search':
						info = '<div class="quicklist-r inline-block"></div>';
						break;
				}
				_div1 += '<li class="move_feild">\n\
							<div class="quicklist-l inline-block">\
							<i class="fa iconfont icon-yidong"></i>\n\
							<span><i class="fa icon-pc iconfont btn btn-show btn-desktop '+(v.enable_pc == 1?'blue':'grey')+'"></i><i class="fa iconfont icon-snimicshouji btn btn-show btn-mobile '+(v.enable_mobile == 1?'blue':'grey')+'"></i></span>\n\
							<label class="message-name" data-type="'+v.type+'">'+v.name+'</label>\
							<span class="icon_box pr">\
								<i class="iconfonts'+(v.icon ? '' : ' icon-dengpao')+'">'+(v.icon || '')+'</i>\
								<input type="hidden" name="'+v.type+'_icons" value="'+v.icon.replace('&', '&amp;')+'" class="icon_input" />\
	                            <span class="arrow"></span>\
	                            <div class="icon_ousidebox">\
	                                <div class="box_content">\
	                                    <ul></ul>\
	                                </div>\
	                            </div>\
                            </span></div>'+info+'\n\
						</li>';
			});
			$('.phone_quickbar_item .phone_func').append(_div1);
			// 栏目图标
            var columnicon = new icon_choose(780);
            columnicon.clicks();
			this.InputStyle();
			this.ShowPos();
			this.DragBlock();
			this.SaveData();
			this.QuickBarListFuc();
    	},
    	QuickBarListFuc : function(){
    		// 咨询  
    		$('.icon-add').click(function(){
    			var clone_cell = $(this).closest('.consultation').find('.consultation-item').last().clone(true);
    			$('.consultation ul').append(clone_cell);
    		});
    		$('.icon-guanbi').on('click',function(){
    			$('.consultation .consultation-item').length == 1 ? alert('请至少保留一个！') : $(this).closest('.consultation-item').remove();
	       	});
    		// 百度地图
    		this.BdMap();
    	},
    	BdMap : function(){
    		// 百度地图API功能
    		var pointX = this.pointX || null,pointY = this.pointY || null,
				map = new BMap.Map("bdmap");          
			map.centerAndZoom(new BMap.Point(116.404, 39.915), 15);
			var local = new BMap.LocalSearch(map, {
				renderOptions:{map: map}
			});
			var dragMarker = function(pointX,pointY){
    			var new_point = new BMap.Point(pointX, pointY);
				map.panTo(new_point);
				// 拖拽坐标
    			var marker = new BMap.Marker(new_point);  // 创建标注
				map.addOverlay(marker);              // 将标注添加到地图中
				var label = new BMap.Label("拖拽坐标确定位置",{offset:new BMap.Size(20,-10)});
				marker.setLabel(label);
				map.addOverlay(marker); 
				marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画 
				marker.enableDragging();    //可拖拽
				marker.addEventListener("dragend", function(e){//将结果进行拼接并显示到对应的容器内
					pointX = e.point.lng;
					pointY = e.point.lat;
					$('.quicklist-r .linktop .message-num').attr('data-point',pointX+','+pointY)
				});
			}
    		if(pointX){
    			map.clearOverlays(); 
				dragMarker(pointX,pointY);
    		}
    		map.addEventListener("click", function(e){
				pointX = e.point.lng;
				pointY = e.point.lat;
				$('.quicklist-r .linktop .message-num').attr('data-point',pointX+','+pointY)
			});
    		var keyword;
    		$('.linktop .search').click(function(){
    			keyword = $(this).siblings('.message-num').val();
				local.search(keyword);
    		}); 
    	},
    	InputStyle : function(){
    		$('input').focus(function(){
				$(this).addClass('input_border').css('border','solid 1px #639cfb');
			}).blur(function(){
				$(this).removeClass('input_border').css('border','solid 1px #999');
			});
    	},
    	ShowPos : function(){
    		$('.phone_func span').not('.icon_box').find('i').click(function(){
				$(this).hasClass('blue') ? $(this).removeClass('blue').addClass('grey') : $(this).removeClass('grey').addClass('blue');
			});
    	},
    	DragBlock : function(){
    		$('#phone_index-quickbar li .icon-yidong').TreeList({
				parentNode  : 'phone_func',
				rootNode 	: 'move_feild',
				oncallback 	: function(indexlist){}
	       	});
    	},
    	QuickBarShowType : function(){
    		var _this = this;
    		$('.phone_quickbar_style .all_button').click(function(event) {
    			var html = '';
		    	var ModelGetQuickBar = function(platform){
		    		$http.post(_this.QuickBarShowTypeUrl,{platform: platform}).success(function(json){
			    		checkJSON(json,function(json){
			    			var _div = '',name;
			    			$.each(json.data,function(k, v) {
			    				_div += '<li'+(v.selected ? ' class="cu"' : '')+'><div class="nsvshowtype-item-border"><img src="'+v.url+'" alt="" /><span'+(v.selected ? ' class="red"' : '')+'>'+v.name+'</span></div></li>';
			    			});
			    			html = '<div class="nsvshowtype">\
										<div class="nsvshowtype-title"><span>'+(platform ? 'PC导航风格' : '手机导航风格')+'</span></div>\
										<div class="nsvshowtype-content">\
											<ul class="nsvshowtype-info-box">'+_div+'</ul>\
										</div>\
				    				</div>';
							var warningbox = new WarningBox('',{warning_context : html});
							warningbox.ng_fuc();
							$('.nsvshowtype-info-box li').click(function(){
								$(this).addClass('cu').siblings().removeClass('cu');
								name = $(this).find('span').text();
							});
			    			$('.button .save').click(function(){
			    				$http.post('',{platform:platform,name:name}).success(function(json){
						    		checkJSON(json);
						    	});
			    			});
			    		});
			    	});
		    	}
    			if($(this).closest('.pc_check_btn').length){
    				ModelGetQuickBar(1);
    			}else if($(this).closest('.mob_check_btn').length){
    				ModelGetQuickBar(0);
    			}
    		});
    	},
    	SaveData : function(){
    		$('.phone_index-banner .save').click(function(){
	    		var navsArray = new Array();
		    	$('.phone_quickbar_item .phone_func .move_feild').each(function() {
		    		var show = [],data = [],icons,
		    			type = $(this).find('.quicklist-l>.message-name').data('type');
		    		icons = $(this).find('.icon_input').val();
		    		switch(type){
		    			case 'tel':
	    				case 'sms':
		    				data = $(this).find('.quicklist-r .message-num').val();
		    				break;
		    			case 'im':
		    				var info = '',name,num,fs,
		    					count = $(this).find('.consultation li').length;
		    				$(this).find('.consultation li').each(function(i, j) {
		    					name = $(this).find('.consultation-name').val();
		    					num = $(this).find('.consultation-num').val();
		    					fs = $(this).find('select').val();
		    					info += (name+':'+num+'@'+fs+(count == 0 ? null : i == count-1 ? null : '|'));
		    				});
		    				data = info;
		    				break;
	    				case 'link':
		    				data = $(this).find('.quicklist-r .linktop .message-num').val()+'|'+$(this).find('.quicklist-r .linktop .message-num').data('point')
		    				break;
	    				case 'share':
		    				$(this).find('.quicklist-r .shareicon i').each(function(index, el) {
		    					$(this).hasClass('blue') ? data.push($(this).data('name')) : null;
		    				});
		    				break;
		    		}
	    			navsArray.push({
		    			name  : $(this).find('.quicklist-l>.message-name').text(),
		    			icon  : icons,
						data  : data.toString(),
						enable_pc: $(this).find('.quicklist-l span:eq(0) i').eq(0).hasClass('blue') ? 1 : 0,
						enable_mobile: $(this).find('.quicklist-l span:eq(0) i').eq(1).hasClass('blue') ? 1 : 0,
						type  : type
		    		});				
				});
		    	$http.post('../quickbar.jsonmodify',{QuickBar: navsArray}).success(function(json){
		    		checkJSON(json,function(json){
		    			phoneindexinit.Save_hint();
		    		});
		    	});
		    });
    	}
    };
}