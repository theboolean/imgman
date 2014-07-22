<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 26/05/14
 * Time: 12.33
 */

namespace ImgManLibrary\Operation\Helper;

use ImgManLibrary\Operation\Helper\Options\HeightWidthAllowupBackgroundOptionTrait;

class FitIn extends AbstractHelper
{
    use HeightWidthAllowupBackgroundOptionTrait;

    /**
     * @param $width
     * @param $height
     * @param bool $allowUpsample
     * @param null $backgroundColor
     * @return bool
     */
    public function __invoke($width, $height, $allowUpsample = false, $backgroundColor = null)
    {
        $oldWidth = $this->getAdapter()->getWidth();
        $oldHeight = $this->getAdapter()->getHeight();

        $divX = $oldWidth / $width;
        $divY = $oldHeight / $height;

        $ratio = $this->getAdapter()->getRatio();

        if ($oldWidth >= $width || $oldHeight >= $height) {
            if ($divX > $divY) {
                $newWidth = $width;
                $newHeight = $newWidth / $ratio;
            } else {
                $newHeight = $height;
                $newWidth = $newHeight * $ratio;
            }
        } elseif ($allowUpsample && $divX > $divY) {
            $newWidth = $width;
            $newHeight = $newWidth / $ratio;
        } elseif ($allowUpsample) {
            $newHeight = $height;
            $newWidth = $newHeight * $ratio;
        } else {
            $newWidth = $oldWidth;
            $newHeight = $oldHeight;
        }

        $newWidth  = round($newWidth);
        $newHeight = round($newHeight);

        $result = $this->getAdapter()->resize($newWidth, $newHeight);

        if ($width != $newWidth || $height != $newHeight) {

            $imageBackground = $this->getAdapter()->create($width, $newWidth, $this->getAdapter()->getFormat());
            $shiftHeight = round(($height - $newHeight) / 2);
            $shiftWidth  = round(($width - $newWidth) / 2);
            $result = $this->getAdapter()->compose($imageBackground, $shiftWidth, $shiftHeight);
        }

        return $result;
    }

    /**
     * @param array $params
     * @return bool
     */
    public function execute(array $params)
    {
        $this->setFromArray($params);
        return $this->__invoke($this->getWidth(), $this->getHeight(), $this->getAllowUpsample(), $this->getBackgroundColor());
    }
}