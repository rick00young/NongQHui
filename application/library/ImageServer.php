<?php

class ImageServer{
	private $imgServerInfo;
	static public  $mThis;

	function __construct() {
		$this->imgServerInfo = HelperConfig::get('image');
	}
	
	/**
	 * @return ImageServer
	 */
	public static function getInstance() {
		if(empty(ImageServer::$mThis)) {
			ImageServer::$mThis = new ImageServer();
		}
		return ImageServer::$mThis;
	}

	//二维码URL读取
	public function getQrImageUrl($md5){
		$urlPre = @$this->imgServerInfo[$md5{0}]['rUrl'].QR_FOLDER;
		return ImageServer::getImagePath($urlPre, $md5, 'png');
	}

	//图片读取调用如下接口：
	public function getImageUrl($md5, $ext) {
	    if (strlen($md5) !== 32) {
	        return '';
	    }
    	$urlPre = @$this->imgServerInfo[$md5{0}]['rUrl'].WATER_FOLDER;
		return ImageServer::getImagePath($urlPre, $md5, $ext);
	}

	public function get360ImageUrl($md5, $ext) {
		if (strlen($md5) !== 32) {
			return '';
		}
		$urlPre = @$this->imgServerInfo[$md5{0}]['rUrl'].W360_FOLDER;
		return ImageServer::getImagePath($urlPre, $md5, $ext);
	}

	//图片读取调用如下接口：
	// public function getImageUrlByRunTime($md5, $ext, $runtime) {
	//     if (strlen($md5) !== 32) {
	//         return '';
	//     }
 //    	$urlPre = @$this->imgServerInfo[$md5{0}]['rUrl'].WATER_FOLDER;
	// 	return ImageServer::getImagePath($urlPre, $md5, $ext);
	// }

	public function getRawImageUrl($md5, $ext) {
	    if (strlen($md5) !== 32) {
	        return '';
	    }
    	$urlPre = $this->imgServerInfo[$md5{0}]['rUrl'].RAW_FOLDER;
		return ImageServer::getImagePath($urlPre, $md5, $ext);
	}

	public function getPreRawImageUrl($md5, $ext) {
	    if (strlen($md5) !== 32) {
	        return '';
	    }
    	$urlPre = $this->imgServerInfo[$md5{0}]['rUrl'].PRERAW_FOLDER;
		return ImageServer::getImagePath($urlPre, $md5, $ext);
	}

	public function getThumbUrl($md5, $ext, $x, $y, $type, $quality = 100) {
		if ($type === TYPE_NO_BLANK) {
			$thumbFolder = THUMB_NO_BLANK_FOLDER;
		} else if ($type === TYPE_NO_DEFORM){
			$thumbFolder = THUMB_NO_DEFORM_FOLDER;
		} else if ($type === TYPE_FIX_WIDTH){
			$thumbFolder = THUMB_FIX_WIDTH_FOLDER;
		} else if ($type === TYPE_SPECIAL) {
			$thumbFolder = TYPE_SPECIAL_FOLDER;
		} else {
			$thumbFolder = "";
		}
		$urlPre = '';
		if (strlen($md5) > 0 && isset($this->imgServerInfo[$md5{0}]['rUrl'])) {
			if($type === TYPE_FIX_WIDTH){
				$urlPre = $this->imgServerInfo[$md5{0}]['rUrl'].$thumbFolder.$x;
			} else {
		    	$urlPre = $this->imgServerInfo[$md5{0}]['rUrl'].$thumbFolder.$x."x".$y;
			}
		}

		if (intval($quality) === 100) {
			$urlPre .= "/";
		} else {
			$urlPre .= "x$quality/";
		}
		return ImageServer::getImagePath($urlPre, $md5, $ext);
	}

	public function getThumbUrlByRunTime($md5, $ext, $x, $y, $type, $runtime, $folder = null) {
		if ($type === TYPE_NO_BLANK) {
			$thumbFolder = THUMB_NO_BLANK_FOLDER;
		} else if ($type === TYPE_NO_DEFORM){
			$thumbFolder = THUMB_NO_DEFORM_FOLDER;
		} else if ($type === TYPE_FIX_WIDTH){
			$thumbFolder = THUMB_FIX_WIDTH_FOLDER;
		} else if ($type === TYPE_SPECIAL) {
			$thumbFolder = TYPE_SPECIAL_FOLDER;
		} else {
			$thumbFolder = "";
		}
		$urlPre = '';
		$imgServer = HelperConfig::get('image');
		if (strlen($md5) > 0 && isset($this->imgServerInfo[$md5{0}]['rUrl'])) {
			if($type === TYPE_FIX_WIDTH){
				$urlPre = $imgServer[$runtime][$md5{0}]['rUrl'].$thumbFolder.$x."/";
			} else {
		    	$urlPre = $imgServer[$runtime][$md5{0}]['rUrl'].$thumbFolder.$x."x".$y."/";
			}
		}

		if (!is_null($folder)) {
			$urlPre .= "$folder/";
		}

		return ImageServer::getImagePath($urlPre, $md5, $ext);
	}



	/**
	 * 图片写入（上传、Resize、删除）调用如下接口：
	 * 统一返回结构：MJsonRespond
	 */
	public function GenerateQrImage($strValue){
		$md5 = md5($strValue);
		$url = $this->imgServerInfo[$md5{0}]['wUrl'].'gen-qrcode-image';
		$query = 'qrvalue='.urlencode($strValue);
		$html = HelperHttp::post($url, $query);
		$json = json_decode($html);
		return isset($json->data) ? $json->data : '';

	}
	public function uploadImage($imgPath){
		if(!is_file($imgPath)) {
			return false;
		}
		$imgMd5 = md5_file($imgPath);
		/*
		$url = $this->imgServerInfo[$imgMd5{0}]['wUrl'].'upload';
		由于目前只一台服务器,所以上传的图片在这里直接存到/static/upload下,以后有了多台服务器后,会有专门的服务进行上传图片
		 */
		//return HelperHttp::uploadFileByHttp($url, $imgPath);
		
		$result['code']  =  E_FAIL;
		$result['info'] = 'I do not know what happened..';
		do{
			if(is_uploaded_file($imgPath)){
				$conf = Yaf_Registry::get('config')->upload->path;
				$path = $conf . '/' . $imgMd5{0} . $imgMd5{1} . $imgMd5{2};
				
				$ext = self::getImageExt($imgPath);
				if($ext == null) {
		    		$result['code']  =  101;
		    		$result['info'] = 'the file is not an image file, please check it,ext='.$ext.', tmpPath='.$imgPath;
		    		break;
	    		}

				if(self::mkdirEx($path)){
					$destination = $path . '/' . $imgMd5 .'.'. $ext;
					if( !(is_file($destination) || move_uploaded_file($imgPath, $destination)) ) {
						$result['code']  =  103;
						$result['info'] = 'move file failed.';
						break;
					}
				}else{
					$result['code']  =  104;
					$result['info'] = 'make dir failed.';
				}
			}
			//TODO 将图片数据写入数据库
			$result = $this->recordToDb($destination,  $imgMd5, $ext);
			if(!$result){
				$result['code']  =  105;
				$result['info'] = 'recordToDb failed.';
			}
			$result['code'] = 0;
			$result['info'] = 'uplode success!';
			$result['md5'] = $imgMd5;
			$result['ext'] = $ext;
            $result['dir'] = $imgMd5[0] . $imgMd5[1] . $imgMd5[2];
		}while(false);
		return $result;
	}

	public static function recordToDb($destination, $imgMd5, $ext){
        $result['code']  =  S_OK;
        $result['info'] = $imgMd5;

		list($width, $height, $type, $attr) = getimagesize($destination);
		$filesize = filesize($destination);
		
		//TODO 图片来源
		
		$table = 'imginfo';

		$sql = sprintf("SELECT * FROM `%s` WHERE imgmd5 = '%s'", $table, $imgMd5);

        $imgRes = DB::getOne($sql);

        $saveData = array();
    	$saveData['imgmd5'] = $imgMd5;
    	$saveData['ext'] = $ext;
    	$saveData['width'] = $width;
    	$saveData['height'] = $height;
    	$saveData['filesize'] = $filesize;
        
        if($imgRes){
            $res = DB::update($saveData, $table, $imgRes['id']);
            if(false == $res) {
                $result['code']  =  E_UPDATE_FAIL;
                $result['info'] = 'update db failed, the sql is: '.$sql;
            }
        }else{
        	$saveData['create_time'] = date('Y-m-d H:i:s');
            $res = DB::insert($saveData, $table);

            if(false == $res) {
                @unlink($destination);//delete the file
                $result['code']  =  E_INSERT_FAIL;
                $result['info'] = 'insert db failed, the sql is: '.$sql;
            }
        }

        return $result;
		/*
		$result['code']  =  S_OK;
		$result['info'] = $md5;

		$rsQuery = $this->dbProxy->rs2rowline("SELECT imgkey, refcount FROM imginfo WHERE imgmd5='".$md5."'");
		if(!empty($rsQuery)) {
			$refCount = $rsQuery['refcount'];
			$refCount = $refCount < 0 ? 1 : $refCount+1;
			$sql = "UPDATE imginfo set refcount=".$refCount." WHERE imgkey=".$rsQuery['imgkey'];
			$rs = $this->dbProxy->doUpdate($sql);
			if(false == $rs) {
				$result['code']  =  E_UPDATE_FAIL;
				$result['info'] = 'update db failed, the sql is: '.$sql;
				break;
			}
		} else {
			$px = $this->getPx($destination);
			$width=$px[0];
			$height=$px[1];
			$sql = "INSERT INTO imginfo(imgmd5, refcount, width, height, ext) VALUES('".$md5."',1, ".$width.", ".$height.", '".$this->dbProxy->realEscapeString($ext)."')";
			$rs = $this->dbProxy->doInsert($sql);
			if(false == $rs) {
				@unlink($destination);//delete the file
				$result['code']  =  E_INSERT_FAIL;
				$result['info'] = 'insert db failed, the sql is: '.$sql;
				break;
			}
		}

		return $result;
		*/
	}

	public static function getImageExt($filePath) {
		$size = getimagesize($filePath);
		$ext = null;
		switch ($size[2]) {
			case 1:
				$ext = 'gif';
				break;
			case 2:
				$ext = 'jpg';
				break;
			case 3:
				$ext = 'png';
				break;
		}
		return $ext;
	}

	public function deleteImage($imgMd5){
		$url = $this->imgServerInfo[$imgMd5{0}]['wUrl'].'delete?imgmd5='.$imgMd5;
		return  HelperHttp::getHttps($url);
	}

	public function resizeImage($imgMd5, $x, $y,$type, $setWater=1){
	    $url = '';
	    if (strlen($imgMd5) > 0) {
	        $url = $this->imgServerInfo[$imgMd5{0}]['pwUrl'].'resize?imgmd5='.$imgMd5.'&x='.$x.'&y='.$y.'&t='.$type.'&water='.$setWater;
	    }
		return HelperHttp::getHttps($url);
	}

	public function cropImage($runtime, $imgMd5, $x, $y, $sw, $sh, $dw, $dh, $type, $folder)
	{
		$imgServer = Config::configForKeyPath('imageserver');
		$url = $imgServer[$runtime][$imgMd5{0}]['pwUrl']."cut-image?imgmd5=$imgMd5&x=$x&y=$y&sw=$sw&sh=$sh&dw=$dw&dh=$dh&type=$type&folder=$folder";
		return HelperHttp::getHttps($url);
	}

	public function cropImageEx($imgMd5, $x, $y, $w, $h)
	{
		$url = $this->imgServerInfo[$imgMd5{0}]['pwUrl'].'crop?imgmd5='.$imgMd5.'&x='.$x.'&y='.$y."&w=".$w."&h=".$h;
		return HelperHttp::getHttps($url);
	}

	public function setWater($imgMd5, $setWater=1)
	{
        $url = '';
        if (strlen($imgMd5) > 0) {
            $url = $this->imgServerInfo[$imgMd5{0}]['pwUrl'].'setwater?imgmd5='.$imgMd5.'&water='.$setWater;
        }
        return HelperHttp::getHttps($url);
	}

	//在meta目录下新建文件，防止缩略图打水印
	public function genMeta($md5, $ext) {
		$url = $this->imgServerInfo[$md5{0}]['pwUrl'].'gen-meta-file?md5='.$md5.'&ext='.$ext;
		return HelperHttp::getHttps($url);
	}

	public function isExist($imgMd5, $x, $y, $type, $folder = null) {
        $url = '';
        if (strlen($imgMd5) > 0) {
            $url = $this->imgServerInfo[$imgMd5{0}]['wUrl'].'exist?imgmd5='.$imgMd5.'&x='.$x.'&y='.$y."&type=".$type;
        }
        if (strlen($url) > 0 && !is_null($folder)) {
        	$url .= "&folder=$folder";
        }
        return HelperHttp::getHttps($url);
	}

	//图片服务器调用如下接口, 业务服务器请勿调用：
	public function getQrImagePath($root, $md5, $ext='png') {
		if (strlen($md5) < 2) {
			return '';
		}
		$strStorePath = $root.$md5{0}.$md5{1};
		if(!is_dir($strStorePath)){
			$this->mkdirEx($strStorePath);
		}
		$destination = $strStorePath."/".$md5.".".$ext;
		return $destination;
	}

	public function getImagePath($root, $md5, $ext) {
	    if (strlen($md5) < 2) {
	        return '';
	    }
		$strStorePath = $root.$md5{0}.$md5{1}.$md5{2};
		if(!is_dir($strStorePath)){
			$this->mkdirEx($strStorePath);
		}
		$destination = $strStorePath."/".$md5.".".$ext;
		return $destination;
	}

	public function getMetaPath($root, $md5, $ext) {
		if (strlen($md5) < 2) {
	        return '';
	    }
		$strStorePath = $root.$md5{0}.$md5{1};
		$destination = $strStorePath."/".$md5.".".$ext;
		return $destination;
	}

	public function getWaterPath($root, $md5, $ext) {
		$strStorePath = $root.$md5{0}.$md5{1};
		if(!is_dir($strStorePath)){
			$this->mkdirEx($strStorePath);
		}
		$destination = $strStorePath."/".$md5.".".$ext;
		return $destination;
	}

	public function getThumbPath($md5, $ext, $x, $y, $type, $quality = 100) {
		assert($type == TYPE_NO_DEFORM || $type == TYPE_NO_BLANK || $type == TYPE_FIX_WIDTH || $type == TYPE_SPECIAL);
		if($type === TYPE_NO_BLANK) {
			$root = IMG_ROOT.THUMB_NO_BLANK_FOLDER;
			$parentPath = $root.$x."x".$y;
		} else if($type === TYPE_NO_DEFORM){
			$root = IMG_ROOT.THUMB_NO_DEFORM_FOLDER;
			$parentPath = $root.$x."x".$y;
		} else if ($type === TYPE_SPECIAL) {
			$root = IMG_ROOT.TYPE_SPECIAL_FOLDER;
			$parentPath = $root.$x."x".$y;
		} else {
			$root = IMG_ROOT.THUMB_FIX_WIDTH_FOLDER;
			$parentPath = $root.$x;
		}

		//如果缩略图下分业务逻辑，则通过folder指定文件夹名称
		if (intval($quality) === 100) {
			$parentPath .= "/";
		} else {
			$parentPath .= "x$quality/";
		}

		if(!is_dir($parentPath)){
			return null;
		}
		$strStorePath = $parentPath.$md5{0}.$md5{1}.$md5{2};


		if(!is_dir($strStorePath)){
			mkdir($strStorePath);
		}
		$destination = $strStorePath."/".$md5.".".$ext;

		return $destination;
	}

	public function getImgServerInfo() {
		return $this->imgServerInfo;
	}

	public function setImgServerInfo($imgServerInfo) {
		$this->imgServerInfo = $imgServerInfo;
	}

	public function getUrlPre($md5)
	{
		return $this->imgServerInfo[$md5{0}]['rUrl'] . RAW_FOLDER;
	}

	//业务服务器调用：图片服务器调用如下接口,
	public function getRealRawImage($md5, $ext)
	{
		$host = Config::runtimeConfigForKeyPath('global.image_api_url');
		$url = $host .'api/get-realraw-image';
		$query = "md5=$md5&ext=$ext";
		$ret = HelperHttp::post($url, $query);
		return $ret;
	}
	//业务服务器调用：图片服务器调用如下接口,
	public function cutImage($runtime, $md5, $ext, $saveType, $x, $y, $sw, $sh, $dw, $dh, $type, $folder)
	{
		$imgServer = Config::configForKeyPath('imageserver');
		$host = Config::runtimeConfigForKeyPath('global.image_api_url');
		$url = $host .'cut-image';
		$query = "md5=$md5&ext=$ext&x=$x&y=$y&sw=$sw&sh=$sh&dw=$dw&dh=$dh&type=$type&folder=$folder&saveType=$saveType";
		$ret = HelperHttp::post($url, $query);
		return $ret;
	}

	//业务服务器调用：图片服务器调用如下接口[new]
	public function cutImageEx($saveType, $md5, $ext, $x, $y, $sw, $sh)
	{
		$host = Config::runtimeConfigForKeyPath('global.image_api_url');
		$url = $host .'api/cut-image';
		$query = "saveType=$saveType&md5=$md5&ext=$ext&x=$x&y=$y&sw=$sw&sh=$sh";
		$ret = HelperHttp::post($url, $query);
		return $ret;
	}

	//创建目录
	private function mkdirEx($pathname) {
		if (file_exists($pathname))
		{
			if (is_dir($pathname))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		$strParentDir = dirname($pathname);
		if ( $strParentDir === $pathname )
		{
			return false;
		}
		$this->mkdirEx($strParentDir);
		$ret = @mkdir($pathname);
		return $ret;
	}

}