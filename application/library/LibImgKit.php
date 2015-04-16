<?php
//require_once(CONFIG.'ipWhitelist.conf.php');

define('MAX_WATER_WIDTH', 1570);
define('MAX_WATER_HEIGHT', 1110);

class LibImgKit
{
	public static function resizeImage($md5, $x, $y, $type, $setWater = null, $quality = 100)
	{
		$result['code'] = S_OK;
		$result['info'] = "";
    	do {

	    	$ext = self::getImageExt($md5);

	    	if(empty($ext)) {
	    		$result['code']  =  E_FILE_NOT_EXIST;
	    		$result['info'] = 'there is not a img whose md5='.$md5.', what are you doing?';
	    		break;
	    	}
    		$srcPath = ImageServer::getInstance()->getImagePath(IMG_RAW, $md5, $ext);
//var_export("$srcPath----srcPath----\n");
			if( !is_file($srcPath) ) {
				$result['code']  =  E_FILE_NOT_EXIST;
				$result['info'] = 'there is record in db, but I can not find img on the disk, sorry..';
				//TODO log('resize file error, the db has record, but no file on disk, md5='.$md5);
				break;
			}
			$dstPath = ImageServer::getInstance()->getThumbPath($md5, $ext, $x, $y, $type, $quality);
//var_export("$dstPath ----dstPath-----\n");
			if(empty($dstPath)){
				$result['code']  =  E_ERROR;
				$result['info'] = 'this size of img is not permmited to create';
				break;
			}

			//如果没有主动的设置水印，按照meta中是否存在文件的方式判断是否设置水印
			if (is_null($setWater)) {
				$setWater = self::needSetWater($md5, $ext);
			}

			$resizeRs = self::scaleImage($srcPath, $dstPath, $x, $y, $type, $setWater, $quality);
			$result['code'] = $resizeRs['code'];
			$result['info'] = $resizeRs['info'];
    	}while(false);
//var_export("result*******$result\n");
    	return $result;
	}

	public static function haveRootPermission(){
		$runtime = Config::configForKeyPath('global.runtime');
		$ipArray = Config::configForKeyPath('ipwhitelist.'.$runtime);
		$curIp = getUserClientIp();
		return in_array($curIp, $ipArray);
	}

	public static function setWaterMaskForImage($md5, $ext, $setWater, $desFile = null){
        $result['code'] = S_OK;
        $result['info'] = "";
//var_export($ext);
		$srcFile = ImageServer::getInstance()->getImagePath(IMG_RAW, $md5, $ext);
//var_export($srcFile);
		if(is_file($srcFile) != true){
            $result['code'] = E_FAIL;
            $result['info'] = "no file exist";
			return $result;
		}
		if(empty($desFile)){
			$desFile = ImageServer::getInstance()->getImagePath(IMG_WATER, $md5, $ext);
		}
//var_export($desFile);
		$ret = ImageKit::scaleImageNoDeform($srcFile, $desFile, MAX_WATER_WIDTH, MAX_WATER_HEIGHT, $setWater);
        $result['info'] = $ret['info'];

        $ret['code']===S_OK ? $result['code'] = S_OK : $result['code'] = E_FAIL;

		return $result;
	}

    /*
	public static function ProduceQrImage($qrValue){
		$md5 = md5($qrValue);
		$imgService = MImageService::getInstance();

		$desFile = $imgService->getQrImagePath(IMG_QRCODE, $md5);
		QRcode::png($qrValue, $desFile);

		$result = MResult::result(MResult::FAIL, 'generate qr file failed');
		if(is_file($desFile)){
			$result = MResult::result(MResult::SUCCESS, $md5);
		}
		return $result;
	}
    */

	private static function scaleImage($srcPath, $dstPath, $x, $y, $type, $setWater=1, $quality = 100){
//var_export("type######$type\n");
		if($type === TYPE_NO_BLANK) {
			$resizeRs = ImageKit::scaleImageNoBlank($srcPath, $dstPath, $x, $y, $setWater, $quality);
		} else if($type === TYPE_NO_DEFORM){
			$resizeRs = ImageKit::scaleImageNoDeform($srcPath, $dstPath, $x, $y, $setWater, $quality);
		} else if($type === TYPE_FIX_WIDTH){
			$resizeRs = ImageKit::scaleImageFixWidth($srcPath, $dstPath, $x, $setWater, $quality);
		} else {
			$resizeRs = null;
		}
		return $resizeRs;
	}

	//判断是否需要设置水印
	public static function needSetWater($md5, $ext) {
		$path = ImageServer::getInstance()->getMetaPath(IMG_META, $md5, $ext);
		return file_exists($path)?C_NOT_SET_WATER:C_SET_WATER;
	}

	public static  function getImageExt($md5){
		$table = 'imginfo';
		$sql = sprintf("SELECT * FROM `%s` WHERE imgmd5 = '%s'", $table, $md5);

        $rsQuery = DB::getOne($sql);

		//if(empty($rsQuery) || $rsQuery['refcount']<=0) {
        if(empty($rsQuery)) {
			return null;
		}
		return $rsQuery['ext'];
	}
}
