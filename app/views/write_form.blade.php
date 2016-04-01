<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>万用表单test</title>
		<link href="../../public/admin/css/universal-form.css" rel="stylesheet" type="text/css"/>
		<script src="../../public/admin/js/jquery.min.js" type="text/javascript"></script>
	</head>
	<body>
		<h1>{{$form_data->title}}</h1>
		<h2 class="description">{{$form_data->description}}</h2>
		<form action="./form-view-submit" method="post" id="user_form" name="user_form">
			{{Form::open(array('url' => '../form-view-submit','method'=>'post','files'=>true))}}
			{{Form::hidden('form_id',$form_data->id)}}
			@foreach ($column_data as $column)
			{{Form::hidden('column_id',$column->id)}}
			<?php $config = $column->config; ?>
			<div>
				<!--<p>{{$column->title}}</p>-->
				@if ($column->type == 'text')
				{{Form::label('col_'.$column->id, $column->title)}}
				@if ($config->text_type == 'text')
				{{Form::text('col_'.$column->id)}}
				@elseif ($config->text_type == 'password')
				{{Form::password('col_'.$column->id)}}
				@endif
				@endif

				@if ($column->type == 'textarea')
				{{Form::label('col_'.$column->id, $column->title)}}
				{{Form::textarea('col_'.$column->id)}}
				@endif

				@if ($column->type == 'radio')
				<p>{{$column->title}}</p>
				@for ($i = 0; $i < $config->option_count; $i++)
				<?php $to = 'option_' . $i; ?>
				{{Form::radio('col_'.$column->id, $i)}}
				<span>{{$config->$to}}</span>
				@endfor
				@endif

				@if ($column->type == 'checkbox')
				<p>{{$column->title}}</p>
				@for ($i = 0; $i < $config->option_count; $i++)
				<?php $to = 'option_' . $i; ?>
				{{Form::checkbox('col_'.$column->id, $i)}}
				<span>{{$config->$to}}</span>
				@endfor
				@endif


				@if ($column->type == 'select')
				<p>{{$column->title}}</p>
				@for ($i = 0; $i < $config->option_count; $i++)
				<?php
				$to = 'option_' . $i;
				$array[$i] = $config->$to;
				?>
				@endfor
				{{Form::select('col_'.$column->id, $array)}}
				@endif


				@if ($column->type == 'image')
				{{Form::label('col_'.$column->id, $column->title)}}
				{{Form::file('col_'.$column->id,array('accept'=>'image/*'));}}
				@endif


				@if ($column->type == 'file')
				{{Form::label('col_'.$column->id, $column->title)}}
				{{Form::file('col_'.$column->id);}}
				@endif
			</div>
			@endforeach
			{{Form::submit('Click Me!')}}
			{{Form::close()}}
			<!--===TODO===-->
		</form>
	</body>
	<script>
	$(function(){
		alert(1)
	});
	</script>
</html>
