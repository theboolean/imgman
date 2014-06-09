<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 26/05/14
 * Time: 12.33
 */

namespace ImgManLibrary\Operation\Helper;

use ImgManLibrary\Core\CoreInterface;
use ImgManLibrary\Operation\Helper\Operation\HeightWidthOptionTrait;

class Resize extends AbstractHelper
{
    use HeightWidthOptionTrait;

    protected $width;

    /**
     * @param $width
     * @param $height
     * @return CoreInterface
     */
    public function __invoke($width, $height)
    {
        return $this->getAdapter()->resize($width,  $height);
    }

    /**
     * @param array $params
     * @return CoreInterface
     */
    public function execute(array $params)
    {
        $this->setFromArray($params);
        return $this->__invoke($this->getWidth(), $this->getHeight());
    }

}