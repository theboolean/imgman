<?php
namespace ImgManLibraryTest\Storage\Adapter\Mongo;

use ImgManLibrary\Storage\Adapter\Mongo\MongoAdapter;
use ImgManLibraryTest\Core\Adapter\TestAsset\Image\Container;
use ImgManLibraryTest\ImageManagerTestCase;

class MongoAdapterTest extends ImageManagerTestCase
{
    /**
     * @var \ImgManLibrary\Storage\Adapter\Mongo\MongoAdapter
     */
    protected $adapter;

    protected $image;

    public function setUp()
    {
        $this->image = new Container(__DIR__ . '/../../../Image/img/test.jpg');

        $mongoCollection = $this->getMockBuilder('MongoCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $mongoCollection->expects($this->any())
            ->method('save')
            ->will($this->returnValue(true));

        $mongoCollection->expects($this->any())
            ->method('remove')
            ->will($this->returnValue(true));

        $mongoCollection->expects($this->any())
            ->method('update')
            ->will($this->returnValue(true));

        $mongoCollection->expects($this->any())
            ->method('findOne')
            ->will($this->returnValue(array('id' => 'fdsfdsfsdf', 'blob' => new \MongoBinData($this->image->getBlob(), \MongoBinData::CUSTOM))));

        $this->adapter = new MongoAdapter();
        $this->adapter->setMongoCollection($mongoCollection);
    }

    public function testMongoAdapterSetGetMongoCollection()
    {
        $mongoCollection = $this->getMockBuilder('MongoCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $adapter = new MongoAdapter();
        $adapter->setMongoCollection($mongoCollection);
        $this->assertSame($mongoCollection, $adapter->getMongoCollection());
    }

    public function testMongoAdapterSave()
    {
        $this->assertTrue($this->adapter->saveImage('id', $this->image));
    }

    public function testMongoAdapterDelete()
    {
        $this->assertTrue($this->adapter->deleteImage('id'));
    }

    public function testMongoAdapterGet()
    {
        $this->assertInstanceOf('ImgManLibrary\Storage\Adapter\Mongo\Image\ImageContainer', $this->adapter->getImage('id'));

        $mongoCollection = $this->getMockBuilder('MongoCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $mongoCollection->expects($this->any())
            ->method('findOne')
            ->will($this->returnValue(false));

        $this->adapter->setMongoCollection($mongoCollection);

        $this->assertNull($this->adapter->getImage('id'));
    }

    public function testMongoAdapterHas()
    {
        $this->assertTrue($this->adapter->hasImage('id'));

        $mongoCollection = $this->getMockBuilder('MongoCollection')
            ->disableOriginalConstructor()
            ->getMock();

        $mongoCollection->expects($this->any())
            ->method('findOne')
            ->will($this->returnValue(false));

        $this->adapter->setMongoCollection($mongoCollection);

        $this->assertFalse($this->adapter->hasImage('id'));
    }

    public function testMongoAdapterUpdate()
    {
        $this->assertTrue($this->adapter->updateImage('id', $this->image));
    }
} 