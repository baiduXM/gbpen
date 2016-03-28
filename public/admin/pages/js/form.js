function formController($scope, $http, $location) {
	$scope.$parent.showbox = "main"; //===主页
	$scope.$parent.homepreview = true; //===右边导航
	$scope.$parent.menu = []; //===？不知道

	getFormList();
	$('.add_form').click(function () {
		$('#bomb-box').show();
	});
	$('.cancel').click(function () {
		cancelForm();
	});
	$('.close').click(function () {
		cancelForm();
	});
	$('.submit_add').click(function () {
		createForm();
		$('#bomb-box').hide();
	});

	//===表单列表===
	function getFormList() {
		$http.get('../form-list').success(function (json) {
			checkJSON(json, function (json) {
				if (json.data !== null) {
					var form_list_data = json.data;//表单列表数据资料
					var _div = '<tr>\n\
							<th>表单名<div class="fr">|</div></th>\n\
							<th>标题<div class="fr">|</div></th>\n\
							<th>描述<div class="fr">|</div></th>\n\
							<th>提交后动作<div class="fr">|</div></th>\n\
							<th>表单状态<div class="fr">|</div></th>\n\
							<th>创建时间<div class="fr">|</div></th>\n\
							<th>操作</th>\n\
						</tr>\n\
						<tr class="sapces"></tr>';
					$.each(form_list_data, function (k, v) {
						_div += '<tr class="form-check" data-id="' + v.id + '">\n\
								<td style="text-align: left">\n\
									<dl class="fl checkclass">\n\
										<input type="checkbox" name="checks" value="Bike1" style=" display:none;">\n\
										<label class="label"></label>\n\
									</dl>\n\
									<div class="tit_info"><span class="sap_tit">' + v.name + '</span></div>\n\
								</td>\n\
								<td>' + v.title + '</td>\n\
								<td>' + v.description + '</td>\n\
								<td>' + v.action + '</td>\n\
								<td>' + v.status + '</td>\n\
								<td>' + v.created_at + '</td>\n\
								<td>\n\
									<a style="margin:0 10px; cursor: pointer" class="form_edit" ><i class="fa iconfont icon-bianji"></i></a>\n\
									<a style="margin:0 10px; cursor: pointer" class="form_view" >view</a>\n\
									<a class="delv"><i class="fa iconfont icon-delete mr5"></i></a>\n\
								</td>\n\
							</tr>';
					});
					$('.a-table').html(_div);
				} else {
					$('.a-table').html('<tr>\n\
							<th>表单标题<div class="fr">|</div></th>\n\
							<th>表单分类<div class="fr">|</div></th>\n\
							<th>展示地址<div class="fr">|</div></th>\n\
							<th>表单状态<div class="fr">|</div></th>\n\
							<th>创建时间<div class="fr">|</div></th>\n\
							<th>操作</th>\n\
						</tr>\n\
						<tr class="sapces"></tr>');
				}
			});
			$('.delv').click(function () {
				deleteForm($(this));
			});
			$('.form_edit').click(function () {
				editForm($(this));
			});
			$('.form_view').click(function () {
				viewForm($(this));
			});
		});
	}
	//===创建表单===
	function createForm() {
		var form_name = $('[name="form_name"]').val();
		var platform = '';
		$('[name="platform"]:checked').each(function () {
			if (platform === '') {
				platform += $(this).val();
			} else {
				platform += ',' + $(this).val();
			}
		});
		$.ajax({
			type: 'post',
			url: '../form-create',
			data: {
				name: form_name,
				platform: platform
			},
			dataType: 'json',
			success: function (json) {
				//===跳转到表单编辑页面===
				if (json.err === 0) {
					location.href = "#/addform?form_id=" + json.data;
				} else {
					alert("添加失败");
				}
			}
		});
	}
	//===取消创建===
	function cancelForm() {
		$('[name="form_name"]').val('');
		$("[name='platform']").removeAttr("checked");
		$('#bomb-box').hide();
	}

	//===删除表单===
	function deleteForm(_this) {
		var form_id = _this.parents('tr').attr('data-id');
		//===弹窗确认===
		(function column_delete(del_num) {
			if (del_num === undefined) {
				var warningbox = new WarningBox(column_delete);
				warningbox.ng_fuc();
			} else {
				if (del_num) {
					//===数据库删除===
					$http.post('../form-delete', {form_id: form_id}).success(function (json) {
						checkJSON(json, function (json) {
							_this.parents('tr').remove();
							var hint_box = new Hint_box('删除成功');
							hint_box;
						});
					});
				}
			}
		})();
	}

	//===编辑表单===
	function editForm(_this) {
		var form_id = _this.parents('tr').attr('data-id');
		location.href = "#/addform?form_id=" + form_id;
	}

	//===浏览表单===
	function viewForm(_this) {
		console.log(_this);
		var form_id = _this.parents('tr').attr('data-id');
		window.open("#/viewform?form_id=" + form_id);
	}

}