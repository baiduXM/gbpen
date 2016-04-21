<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>用户表单数据详情</title>
		<style>
		</style>
	</head>
	<body>
		<h1 >{{$form_data->title}}</h1>
		<h2 class="description">{{$form_data->description}}</h2>
		<table>
			<thead>
				<!--表格头部-->
			</thead>
			<tbody>
				@foreach ($column_data as $column)
				<?php $to = 'col_' . $column->id; ?>
				<tr>
					<td>{{$column->title}}</td>
					@if(isset($user_data[$to]))
					<td>{{$user_data[$to]}}</td>
					@else
					<td>null</td>
					@endif
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<!--表格底部-->
			</tfoot>
		</table>
	</body>
</html>
