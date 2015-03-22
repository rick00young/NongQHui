<?php
/**
 * @describe:
 * @author: rick
 * */
class uploadAction extends Yaf_Action_Abstract
{
    public function execute($arg = null)
    {
    	Yaf_Dispatcher::getInstance()->autoRender(FALSE);

    	$fileData = @$_FILES['file'];
        if(empty($fileData)){
            $returnData = HelperResponse::resultFail('无图片上传');
            //$this->jsonReturn($returnData);
            echo json_encode($returnData);
        }
        $allowTypes = array('1','2','3');
        list($width, $height, $type, $attr) = getimagesize($fileData['tmp_name']);
        if(!$fileData['error']){
            if(!in_array($type,$allowTypes)){
                $returnData = HelperResponse::resultFail('图片格式非法!');
                $this->jsonReturn($returnData);
            }
            $imageServer = ImageServer::getInstance();
            $result = $imageServer->uploadImage($fileData['tmp_name']);
            //$result = json_decode($result,true);
            //$imageMd5 = $result['data']['md5'];
            //$imageUrl = $imageServer->getThumbUrl($imageMd5,'jpg',320,320,TYPE_NO_BLANK);
            //$returnData = array('status'=>1,'data'=>array('image_md5'=>$imageMd5,'image_url'=>$imageUrl));
            $returnData = array('status'=>1,'data'=>$result);
            //$this->jsonReturn($returnData);
            echo json_encode($returnData);
        }else{
            $returnData = HelperResponse::resultFail('图片上传失败!');
            //$this->jsonReturn($returnData);
        }
    }
}
