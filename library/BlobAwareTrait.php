<?php
/**
 * Image Manager
 *
 * @link        https://github.com/ripaclub/imgman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace ImgMan;

/**
 * Class BlobAwareTrait
 */
trait BlobAwareTrait
{
    public $blob;

    /**
     * @param string $blob
     */
    public function setBlob($blob)
    {
        $this->blob = $blob;
    }

    /**
     * @return BlobInterface
     */
    public function getBlob()
    {
        return $this->blob;
    }
}
