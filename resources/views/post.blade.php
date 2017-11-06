<!DOCTYPE html>
<html>
<head>
	<title>发布文章</title>
	<form id="poster" method="POST" action="/essay/post">
		{{ csrf_field() }}
		<input class="title" type="text" name="title" placeholder="输入文章标题">
		<input class="author" type="text" name="author" placeholder="输入文章作者">
		<div class="content" id="editor">
			<textarea name="content"></textarea>
		</div>
		<a class="btn" href="javascript:$('#poster').submit();">发布</a>
	</form>
	<link rel="stylesheet" type="text/css" href="/js/editor.md-master/css/editormd.min.css">
	<link rel="stylesheet" type="text/css" href="/css/app.css">
	<style type="text/css">
		#poster {
			width: 900px;
			margin: 0 auto;
		}
		#poster input {
			box-sizing: border-box;
			display: block;
			width: 100%;
			margin-bottom: 10px;
			outline: none;
			padding: 6px 8px;
			border: 1px solid #e3e3e3;
		}
	</style>
</head>
<body>

</body>
<script type="text/javascript" src="/js/zepto.min.js"></script>
<script type="text/javascript" src="/js/editor.md-master/editormd.min.js"></script>
<script type="text/javascript">
	$(function(){
		var editor = editormd('editor', {
			path: '../js/editor.md-master/lib/',
			height: 800,
			placeholder: '输入文章内容',
			watch: false,
		});
	});
</script>
</html>