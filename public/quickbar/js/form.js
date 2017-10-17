$(function () {

    var scripts = document.getElementsByTagName("script");
    var CustomerName = '';
    for (var i = 0; i < scripts.length; i++) {
        if (scripts[i].src.indexOf('form.js') >= 0) {
            target = scripts[i];
        }
    }
    if (target.src.indexOf('?') == -1 || target.src.substring(target.src.indexOf('?') + 1).indexOf('debug') == -1) {
        if (target.src.indexOf('?') !== -1) {
            var param = target.src.substring(target.src.indexOf('?') + 1);
            CustomerName = param.match(/name=(.*)/)['1'];
        }
    }

    /**
     * 读取表单数据
     */
    // $.ajax({
    //     url: '/formdata.json',
    //     dataType: json,
    //     success: function (json) {
    //         var bindweb = json.website;
    //         var forminfo = json.forminfo;
    //         $.each(bindweb, function (k, v) { //===遍历定位
    //             var div = v.bind_div.div;
    //             var form_id = v.bind_form.form_id;
    //             var formdata = forminfo[form_id];
    //             var n = $('#' + div + ' form [data-type="inset"]').length;
    //             $('#' + div + ' form').attr('action', 'http://swap.5067.org/userdata/' + form_id);
    //             $('#' + div + ' [data-type="title"]').html(formdata.title);
    //             $('#' + div + ' [data-type="description"]').html(formdata.description);
    //             $('#' + div + ' form').append('<input type="hidden" name="form_id" value="' + formdata.id + '">');
    //             $('#' + div + ' form').append('<input type="hidden" name="action_type" value="' + formdata.action_type + '">');
    //             $('#' + div + ' form').append('<input type="hidden" name="action_text" value="' + formdata.action_text + '">');
    //             for (var i = 0; i < n; i++) {
    //                 $('#' + div + ' form [data-type="inset"]:eq(' + i + ')').attr('name', formdata.data[i].title);
    //                 $('#' + div + ' form [data-type="inset"]:eq(' + i + ')').attr('data-id', formdata.data[i].id);
    //                 $('#' + div + ' form [data-type="inset"]:eq(' + i + ')').attr('placeholder', formdata.data[i].description);
    //             }
    //         });
    //     },
    // });

    $.getJSON(('/formdata.json'), function (json) {
        flag = false;
        var bindweb = json.website;
        var forminfo = json.forminfo;
        $.each(bindweb, function (k, v) { //===遍历定位
            var div = v.bind_div.div;
            var form_id = v.bind_form.form_id;
            var formdata = forminfo[form_id];
            var n = $('#' + div + ' form [data-type="inset"]').length;
            $('#' + div + ' form').attr('action', 'http://swap.5067.org/userdata/' + form_id);
            $('#' + div + ' [data-type="title"]').html(formdata.title);
            $('#' + div + ' [data-type="description"]').html(formdata.description);
            $('#' + div + ' form').append('<input type="hidden" name="form_id" value="' + formdata.id + '">');
            $('#' + div + ' form').append('<input type="hidden" name="action_type" value="' + formdata.action_type + '">');
            $('#' + div + ' form').append('<input type="hidden" name="action_text" value="' + formdata.action_text + '">');
            for (var i = 0; i < n; i++) {
                $('#' + div + ' [data-type="subgroup"]:eq(' + i + ')').html(formdata.data[i].title);
                $('#' + div + ' form [data-type="inset"]:eq(' + i + ')').attr('name', formdata.data[i].title);
                $('#' + div + ' form [data-type="inset"]:eq(' + i + ')').attr('data-id', formdata.data[i].id);
                $('#' + div + ' form [data-type="inset"]:eq(' + i + ')').attr('placeholder', formdata.data[i].description);
            }
        });
    });

});

