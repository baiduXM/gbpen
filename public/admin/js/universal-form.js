/**
 * 万用表单公共js
 */
$(function () {
	$(".submit-form").click(function () {
		var form_id = $('[name="form_id"]').val();
		var form_data = $('form[name="box_show"]').serializeArray();
		$.post('../form-userdata-submit', {form_id: form_id, data: form_data}, function (json) {
			console.log(json);
			console.log('form-userdata-submit');
			if (json.err == 0) {
				var data = json.data;
				if (data.action_type == 0) {//显示文字
					alert(data.action_text);
					$('form[name="box_show"]')[0].reset();
				}
				if (data.action_type == 1) {//跳转链接
//					Hint_box(json.msg);
					setTimeout(data.action_text, 2000);
				}
			} else {
				alert(json.msg);
			}
		});
	});
});