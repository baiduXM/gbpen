/**
 * 万用表单公共js
 */
;
function verify(column_data) {
    var column = eval(column_data);
    console.log(column);
    console.log('---column_data---');
    var box_show = $('#box_show').serializeArray();
    console.log(box_show);
    console.log('---form---');
    return false;
    var tt = '';
    var str = '';
    var flagchb = false;
    $.each(column, function (k, v) {
        //判断是否必填
        
        //
        tt = v.title;
        if (v.required == 1) {
            switch (v.type) {
                case 'checkbox':
                    flagchb = false;
                    var chb = box_show[tt];
                    for (i = 0; i < chb.length; i++) {
                        if (chb[i].checked) {
                            flagchb = flagchb || true;
                        }
                    }
                    if (flagchb == false) {
                        str == '' ? str = v.title : str += ',' + v.title;
                    }
                    break;
                default:
                    if (box_show[tt].value == '') {
                        flagchb = flagchb && false;
                    }
                    if (flagchb == false) {
                        str == '' ? str = v.title : str += ',' + v.title;
                    }
                    break;
            }
        }
    });
    
    return flagchb;
    if (flagchb == false) {
        alert('标注*号的为必填项');
        return false;
    }
}