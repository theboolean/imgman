<?php
/**
 * Image Manager
 *
 * @link        https://github.com/ripaclub/imgman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace ImgManTest\Storage\Image;

use ImgMan\Storage\Image\AbstractImageContainer;
use ImgManTest\ImageManagerTestCase;

/**
 * Class AbstractContainerTest
 */
class AbstractContainerTest extends ImageManagerTestCase
{
    /**
     * @var AbstractImageContainer
     */
    protected $container;

    public function setUp()
    {
        $this->container = $this->getMockForAbstractClass('ImgMan\Storage\Image\AbstractImageContainer');
    }

    public function testAbstractContainerTestBlob()
    {
        $this->container->setBlob('test');
        $this->assertSame('test', $this->container->getBlob());
    }

    public function testAbstractContainerTestMimeType()
    {
        $this->container->setMimeType('test');
        $this->assertSame('test', $this->container->getMimeType());
    }
}
