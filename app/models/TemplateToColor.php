<?php

class TemplateToColor extends Eloquent{
    protected $table = 'template_to_color';
    public  $timestamps = false;

    public function getColorByTemplateId($id=''){
        $result=array();
        $colors = $this->leftJoin('color','color_id','=','color.id')->where($this->table.'.template_id',$id)->select('color','color_en','color_code')->get();
        $i = 0;
        foreach($colors as $key => $color){
        	$result[$i]['id'] = $color->color_en;
        	$result[$i]['value'] = $color->color_code;
        	$result[$i]['description'] = $color->color;
        	$i++;
        }
        return $result;
    }
}
