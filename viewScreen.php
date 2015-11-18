<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>View Screen</title>
	<?php 
		//$contents = '<a title="梅不的" target="_blank" href="UserInfo.aspx?id=184">';
		$url = "http://papayue.com/";
		$contents = file_get_contents($url);
		$response = Array();
		$request = Array();
		//var_dump($contents);
		//.?<img alt=".*" title=".*" src=".*" class="images border="0">
		//
		//$reg = '/(<a title="(\S*?)" target="_blank" href="(.*?)">[\s\S]*<img alt="\S*?" title="\S*?" src=".*?" class="image" border="0"></a>)/i'; // 
		//
		$reg = '/<a title="([.\S]+)" target="_blank" href="([.\S]+)">/i';

		preg_match_all($reg, $contents, $result, PREG_SET_ORDER);

		//var_dump($result);

		$mulitResult = $result;

		$path = "D:\catchimages";

		//半段目录是否存在
		if(is_dir($path)){
			$response["errormsg"][0] = "对不起！目录 " . $path . " 已经存在！";
		}else{
			mkdir($path);
			$response["success"][0] = "创建成功";
		}
		//$mulitResult[0][1]当前用户名------$mulitResult[0][2] 详细页的url参数
		foreach ($mulitResult as $key => $value) { 
			//echo "现在是第" . $key . "个<br>";
			$fileName = $value[1];

			$singleUrl = $url . $value[2];

			//$singlePath = $path . DIRECTORY_SEPARATOR . $fileName;

			//echo $singlePath;

			// if(is_dir($singlePath) && file_exists($singlePath)){
			// 	$response["errormsg"][1] = $fileName . "用户已存在";
			// 	continue;
			// }else{
			// 	//将网页的utf-8格式转换成windows下的gbk格式再创建目录
			// 	if(!@mkdir(iconv("utf-8", "gbk", $singlePath))){
			// 		die();
			// 	};
			// 	$response["success"][1] = "<br>创建用户成功<br>";
			// }
			//echo $singleUrl."<br>";

			$singleContents = file_get_contents($singleUrl);

			$regImg = '/<img src=\'(http:\/\/img.papayue.com\/UserImage\/\S+)\'/i';

			preg_match_all($regImg, $singleContents, $accountResult, PREG_SET_ORDER);

			$request[] = $accountResult;
		 }
		//var_dump($request);
 	?>
	<script type="text/javascript" src="jquery-2.1.0.min.js"></script>
	<script type="text/javascript">
		$(function(){
			
			$("#download").bind("click", function(){
				$.ajax({
					url: 'http://localhost/phpcatch/post.php',
					type: 'POST',
					dataType: 'json',
				})
				.done(function() {
					console.log("success");
				})
				.fail(function() {
					console.log("error");
				})
				.always(function(data) {
					console.log(data);
					var num = new Number(data.hasloaded/35);
					var width = num.toFixed(2) * 100;
					$("#progress").css('width', width + "%");
					$("#progress span").text(width + "%");
					console.log(num.toFixed(2));
				});				
			})
		})
	</script>
	<style type="text/css">
		#viewScreen{ width: 350px; border: 1px solid #3e3e3e; height: 20px; margin-bottom: 5px; }
		#progress{ height:20px; width:0; background: #9FCEF1; text-align: center; }
	</style>
</head>
<body>
	<!-- 35条记录 -->
	<?php echo $request; ?>
	<div id="viewScreen">
		<div id="progress">
			<span style="width:350px; text-align: center; display: block; ">0%</span>
		</div>
	</div>
	<input  id="download" type="button" value="下载" >
</body>
</html>