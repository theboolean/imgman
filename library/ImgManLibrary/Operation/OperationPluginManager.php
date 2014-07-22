<?php

namespace ImgManLibrary\Operation;

use ImgManLibrary\Core\CoreAwareInterface;
use ImgManLibrary\Core\CoreAwareTrait;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceManager;

class OperationPluginManager extends AbstractPluginManager implements CoreAwareInterface
{
    use CoreAwareTrait;
    /**
     * Default set of helpers
     *
     * @var array
     */
    protected $invokableClasses = array(
        'fitIn'         => 'ImgManLibrary\Operation\Helper\FitIn',
        'fitOut'        => 'ImgManLibrary\Operation\Helper\FitOut',
        'resize'        => 'ImgManLibrary\Operation\Helper\Resize',
        'scaleToHeight' => 'ImgManLibrary\Operation\Helper\ScaleToHeight',
        'scaleToWidth'  => 'ImgManLibrary\Operation\Helper\ScaleToWidth',
        'crop'          => 'ImgManLibrary\Operation\Helper\Crop',
        'format'        => 'ImgManLibrary\Operation\Helper\Format',
        'compression'   => 'ImgManLibrary\Operation\Helper\Compression',
        'rotate'        => 'ImgManLibrary\Operation\Helper\Rotate',
    );

    public function __construct(ConfigInterface $configuration = null)
    {
        parent::__construct($configuration);
        // FIXME settarlo da configurazione e non nel costruttore
        foreach ($this->invokableClasses as $key => $value) {
            $this->setInvokableClass($key, $value);
        }

        $this->addInitializer(array($this, 'injectAdapter'));
    }

    /**
     * @param $helper
     */
    public function injectAdapter($helper)
    {
        /* @var ImgManLibrary\Operation\Helper\HelperInterface $helper */
        if ($this->getAdapter()) {
            $helper->setAdapter($this->getAdapter());
            return;
        }
    }

    /**
     * @param mixed $plugin
     * @throws Exception\InvalidHelperException
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof Helper\HelperInterface) {
            return;
        }

        throw new Exception\InvalidHelperException(sprintf(
            'Plugin of type %s is invalid; must implement %s\Helper\HelperInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }

} 