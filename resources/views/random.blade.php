<!DOCTYPE html>
<html>
<head>
	<title>7 Reading</title>
	<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width">
	<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="/css/app.css">
</head>
<style type="text/css">
	body {
		background-color: rgba(0,0,0,.05);
		font-family: '宋体';
	}
	.essay {
		max-width: 1000px;
		margin: 20px auto;
	}
	.essay .title, .essay .author {
		text-align: center;
		color: #333;
	}
	.essay .title {
		font-weight: normal;
	}
	.essay .content {
		line-height: 2;
		color: #444;
	}
	.next-essay {
		display: block;
		width: 120px;
		margin: 20px auto;
	}
	#markdown-content {
		display: none;
	}
</style>
<body>

<div class="essay">
	<h2 class="title">{{ $essay->title }}</h2>
	<p class="author">{{ $essay->author }}</p>
	<div class="content" id="content"></div>
	<textarea id="markdown-content">{!! $essay->content !!}</textarea>
	<a class="next-essay btn" href="/essay">下一篇</a>
	<script type="text/javascript" src="/js/zepto.min.js"></script>
	<script type="text/javascript" src="/js/editor.md-master/lib/marked.min.js"></script>
	<script type="text/javascript">
		$('#content').html(marked($('#markdown-content').val()));
	</script>
</div>

</body>
</html>