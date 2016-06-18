<?php 
    $data = json_decode($_POST['data']);
        $css = '<link rel="stylesheet" type="text/css" href="http://chanpin.xm12t.com.cn/css/floatadv.css">';
        foreach ((array) $data as $k => $v) {
            if ($v->type == "form") {
                $form_info = $v->form_info;
                $column_info = $v->column_info;
            }
            if ($v->type == "adv") {
                $div = '<div id="popContent" class="float floatAdv' . $k . ' style="';
                if ($v->position == '4') {
                    $div.='right';
                } else {
                    $div.='left';
                }
                $div.=':' . $v->posx . 'px;width:' . $v->posw . 'px;';
                if ($v->position == '3') {
                    $div.='bottom';
                } else {
                    $div.='top';
                }
                $div.=':' . $v->posy . 'px;';
                $div.='<a class="popClose" title="关闭" >关闭</a>';
                $div.='<a href="' . $v->href . '" target="_blank"><img id="float_adv" src="' . $v->url . '"></a>';
                $div.='</div>';
            }
            $js = '<script>';
            $js.='$(".popClose").click(function(){
                    $(this).parent(".float").stop();
                    $(this).parent(".float").slideUp();
                });';
            $js.='</script>';
        }
        echo $css . $div . $js;