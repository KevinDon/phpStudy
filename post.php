<?php 

	header("Content-type:text/html; charset=utf-8");
	$response = Array();
	//echo $_POST["url"];
	$path = "D:\catchimages";

	$img =$_GET["url"];
	//http://img.papayue.com/UserImage/278/2015_10_24_16_38_461.jpg
	//
	//echo $img;

	$regImg = '/UserImage\/.+\/(.*)\.jpg/i';

	//echo $img;

	$imgData = @file_get_contents($img);
	//var_dump($imgData);
	//
	preg_match($regImg, $img, $id);

	//var_dump($id);

	$imgPath =$path . DIRECTORY_SEPARATOR . $id[1] . ".jpg";

	if(!file_put_contents($imgPath, $imgData)){
		$response["errormsg"] = "fail";
	}else{
		$response["success"] = "success";
	};

	echo json_encode($response);
 ?>