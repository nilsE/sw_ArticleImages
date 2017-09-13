<?php
use Shopware\Components\CSRFWhitelistAware;

class Shopware_Controllers_Backend_NetzpArticleImages extends Enlight_Controller_Action
                                                      implements CSRFWhitelistAware
{
    public function getWhitelistedCSRFActions() {

        return array('getImage');
    }

    private function returnEmptyImage()
    {
        $img = imagecreatetruecolor(1, 1);
        $bg = imagecolorallocate ($img, 255, 255, 255);
        imagefilledrectangle($img, 0, 0, 1, 1, $bg);
        imagepng($img);
        imagedestroy($img);
    }

    public function getImageAction()
    {
    	Shopware()->Plugins()->Controller()->ViewRenderer()->setNoRender();
        $articleId = trim($this->Request()->getParam('articleid'));

        if($articleId != '') {
            $sql = 'SELECT CONCAT(img, ".", extension) as image FROM s_articles_img WHERE articleID = ? AND main = 1';
            $image = Shopware()->Db()->fetchOne($sql, $articleId);
        }
        else {
            $image = trim($this->Request()->getParam('imgsrc'));
        }

    	if($image == '') {
            $this->returnEmptyImage();
        }

    	else {
            $thumbSize = '140x140';
        	$pathinfo = pathinfo($image);

        	$filename = $pathinfo['filename'];
        	$sizePos = strrpos($filename, '_' . $thumbSize);
        	if($sizePos !== false) {
        		$filename = substr($filename, 0, $sizePos);
        	}

            $thumbnailDir = Shopware()->DocPath('media_image') . 'thumbnail' . DIRECTORY_SEPARATOR;
            $path = $thumbnailDir . $filename . '_' . $thumbSize . '.' . $pathinfo['extension'];
            $path = str_replace(Shopware()->DocPath(), '', $path);
            if (DIRECTORY_SEPARATOR !== '/') {
                $path = str_replace(DIRECTORY_SEPARATOR, '/', $path);
            }

            $mediaService = Shopware()->Container()->get('shopware_media.media_service');
            try {
                echo $mediaService->read($path);
            }
            catch(Exception $ex) {
                $this->returnEmptyImage();
            }
        }
    }
}