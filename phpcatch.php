<?php 
	header("Content-type: text/html; charset=utf-8");

	//$contents = '<a title="梅不的" target="_blank" href="UserInfo.aspx?id=184">';
	$url = "http://papayue.com/";
	$contents = file_get_contents($url);
	$response = Array();

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

		$singlePath = $path . DIRECTORY_SEPARATOR . $fileName;

		echo $singlePath;

		if(is_dir($singlePath) && file_exists($singlePath)){
			$response["errormsg"][1] = $fileName . "用户已存在";
			continue;
		}else{
			//将网页的utf-8格式转换成windows下的gbk格式再创建目录
			if(!mkdir(iconv("utf-8", "gbk", $singlePath))){
				die();
			};
			$response["success"][1] = "<br>创建用户成功<br>";
		}
		echo $singleUrl."<br>";

		$singleContents = file_get_contents($singleUrl);

		$regImg = '/<img src=\'(http:\/\/img.papayue.com\/UserImage\/\S+)\'/i';

		preg_match_all($regImg, $singleContents, $accountResult, PREG_SET_ORDER);

		//var_dump($accountResult);

		for ($i=0; $i < count($accountResult) ; $i++) { 
			$img = $accountResult[$i][1];
			$imgData = @file_get_contents($img);
			$imgPath = iconv("utf-8", "gbk", $singlePath) . DIRECTORY_SEPARATOR . iconv("utf-8", "gbk", $fileName) ."-".$i.".jpg";
			file_put_contents($imgPath, $imgData);
		}
		
		$response["hasloaded"] = $key + 1;

		echo json_encode($response);
	 }
 ?>