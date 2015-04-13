<?php
/**
 * @describe:
 * @author: Rick Yang
 * */

/* vi:set ts=4 sw=4 et fdm=marker: */

class SourceController extends Yaf_Controller_Abstract {

	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/vips_web/index/index/index/name/rick 的时候, 你就会发现不同
     */

    /*
     * thb thb2 thb3下面的缩略图尺寸需要手动创刊尺寸目录如100x100 200x350,用什么样的图片就城要创建什么样的尺寸目录
     *
     ***/
	public function imagesAction(){
        //$this->getView()->setLayout(null);
		Yaf_Dispatcher::getInstance()->autoRender(FALSE);
		$uri = $_SERVER['REQUEST_URI'];
		if(empty($uri)){
			return self::outputNoFileExist();
		}
        $filePath = str_replace('/source/images/', '', $uri);
        $filePath = IMG_ROOT. $filePath;
        if(file_exists($filePath)){
            $uri = str_replace('/source/', '/public/', $uri);
            //header("Content-Type: application/octet-stream");
            header("Content-Type: image/png");
            header("X-Accel-Redirect: $uri");
            return true;
        }

		$paths = explode('/', $uri);
        //var_export($paths);
		/*
		array (
		  0 => '',
		  1 => 'public',
		  2 => 'images',
		  3 => 'thb2',
		  4 => '600x320',
		  5 => '8f2',
		  6 => '8f26a9a134ac3370f94f107b881d573b.jpg',
		)
		 */

        $path_parts = pathinfo($uri);
        /*
		if(7 !=count($paths)){
			return $this->outputNoFileExist();
		}
        */
		$ret = false;
		$thbType = $paths[3];
		$imgInfo = $paths[4] . '/' . $paths[5] . '/' . $path_parts['filename'];

    	if($paths[3] === 'water'){
            $imgInfo =$paths[4] . '/' . $path_parts['filename'];
    		$ret = $this->produceWaterImg($imgInfo, $path_parts['extension']);
    	} else if($paths[3] === 'thb'){
    		$ret = $this->produceThbImg($imgInfo);
    	} else if($paths[3] === 'thb2'){
    		$ret = $this->produceThb2Img($imgInfo);
    	} else if($paths[3] === 'thb3'){
    		$ret = $this->produceThb3Img($imgInfo);
    	}

    	if($ret !== true) {
    		//echo 'xxxxxxx';
    		//die;
    		self::outputNoFileExist();
    	}
 		//header("Content-Type: application/octet-stream");
        header("Content-Type: image/png");
 		//for nginx
 		//$url = "/public/images/".$imgInfo[0]."/".$imgInfo[1].".".$imgInfo[2];

        $uri = str_replace('/source/', '/public/', $uri);
        //var_export($uri);

 		header("X-Accel-Redirect: $uri");
	}

	public function display404() {
        // if(class_exists("ErrorController")){
        //     $action = new ErrorController();
        //     $action->error();
        // }else{
        //     header('HTTP/1.1 404 Not Found', true,404);
        // }
        header('HTTP/1.1 404 Not Found', true,404);
        exit();
    }
    protected function outputNoFileExist(){
    	self::display404();
    }

    protected function produceWaterImg($imgInfo, $ext){
    	$ret = preg_match("/[a-fA-F0-9]{3,3}\/([a-fA-F0-9]{32,32})$/", $imgInfo, $md5Array);

    	if($ret <= 0){
    		return false;
    	}
    	$imgMd5 = $md5Array[1];
//var_export($imgMd5);
        if(empty($ext)){
            return false;
        }
    	$result = LibImgKit::setWaterMaskForImage($imgMd5, $ext, LibImgKit::needSetWater($imgMd5, $ext));
//var_export($ret);
    	return $result['code']===S_OK ? true : false;
    }

    protected function produceThbImg($imgInfo){
        $infoArray = array();
        $pattern1 = "/(\d+)x(\d+)x(\d+)\/[a-fA-F0-9]{3,3}\/([a-fA-F0-9]{32,32})$/"; //图片质量问题
        $ret = preg_match($pattern1, $imgInfo, $infoArray);
        if($ret > 0 && count($infoArray) === 5){
            return $this->resizeProxy((int)$infoArray[1], (int)$infoArray[2], $infoArray[4], TYPE_NO_DEFORM, $infoArray[3]);
        }

        $infoArray = array();
        $pattern2 = "/(\d+)x(\d+)\/[a-fA-F0-9]{3,3}\/([a-fA-F0-9]{32,32})$/"; //常规缩略图
    	$ret = preg_match($pattern2, $imgInfo, $infoArray);

//        var_export($imgInfo);
//        var_export($infoArray);
//        var_export($ret);

    	if($ret > 0 && count($infoArray) === 4){
    		return $this->resizeProxy((int)$infoArray[1], (int)$infoArray[2], $infoArray[3], TYPE_NO_DEFORM);
    	}

    	return false;
    }

    protected function produceThb2Img($imgInfo){
        $infoArray = array();
        $pattern1 = "/(\d+)x(\d+)x(\d+)\/[a-fA-F0-9]{3,3}\/([a-fA-F0-9]{32,32})$/"; //图片质量问题
        $ret = preg_match($pattern1, $imgInfo, $infoArray);
        if($ret > 0 && count($infoArray) === 5){
            return $this->resizeProxy((int)$infoArray[1], (int)$infoArray[2], $infoArray[4], TYPE_NO_BLANK, $infoArray[3]);
        }

        $infoArray = array();
        $pattern2 = "/(\d+)x(\d+)\/[a-fA-F0-9]{3,3}\/([a-fA-F0-9]{32,32})$/"; //常规缩略图
        $ret = preg_match($pattern2, $imgInfo, $infoArray);

//        var_export($imgInfo);
//        var_export($infoArray);
//        var_export($ret);

        if($ret > 0 && count($infoArray) === 4){
            return $this->resizeProxy((int)$infoArray[1], (int)$infoArray[2], $infoArray[3], TYPE_NO_BLANK);
        }

        return false;
    }

    protected function produceThb3Img($imgInfo){
    	$infoArray = array();
        $pattern1 = "/(\d+)x(\d+)\/[a-fA-F0-9]{3,3}\/([a-fA-F0-9]{32,32})$/";
        $ret = preg_match($pattern1, $imgInfo, $infoArray);
        if ($ret > 0 && count($infoArray) === 4) {
            return $this->resizeProxy((int)$infoArray[1], 0, $infoArray[3], TYPE_FIX_WIDTH, $infoArray[2]);
        }

        $infoArray = array();
        $pattern2 = "/(\d+)\/[a-fA-F0-9]{3,3}\/([a-fA-F0-9]{32,32})$/";
    	$ret = preg_match($pattern2, $imgInfo, $infoArray);
//        var_export($imgInfo);
//       var_export($infoArray);
//        var_export($ret);
    	if ($ret > 0 && count($infoArray) === 3) {
            return $this->resizeProxy((int)$infoArray[1], 0, $infoArray[2], TYPE_FIX_WIDTH);
    	}

        return false;
    }

    protected function resizeProxy($x, $y, $md5, $type, $quality = 100) {
        $result = LibImgKit::resizeImage($md5, $x, $y, $type, null, $quality);
        return $result['code'] === S_OK ? true : false;
    }
}
