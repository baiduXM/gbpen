<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>用户表单数据列表</title>
		<link rel="stylesheet" href="css/themes.css">
		<link rel="stylesheet" href="css/laydate.css">
		<script src="js/colorpicker.min.js"></script>
		<style>

		</style>
	</head>
	<body>
		<table border="1">
			<thead>
				<!--表头-->
				<tr>
					<th>ID</th>
					<th>用户ID</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<!--内容-->
				@foreach ($list as $item)
				<tr data-form-id="{{$form_id}}" data-id="{{$item->id}}" data-cus-id="{{$item->cus_id}}" >
					<td>{{$item->id}}</td>
					<td>{{$item->cus_id}}</td>
					<td>
						<a href="./form-view-detail?form_id={{$form_id}}&id={{$item->id}}"><button name="view">查看</button></a>
						<a href="./form-delete-detail?form_id={{$form_id}}&id={{$item->id}}"><button name="delete">删除</button></a>
					</td>
				</tr>
				@endforeach

			</tbody>
			<tfoot>
				<!--页码-->
			</tfoot>
		</table>
	</body>


</html>

