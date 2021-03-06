<?php
/**
 * Image Manager
 *
 * @link        https://github.com/ripaclub/imgman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace ImgMan\Operation\Helper\Options;

use ImgMan\Operation\Exception\BadMethodCallException;
use ImgMan\Operation\Exception\InvalidArgumentException;
use Traversable;
use Zend\Stdlib\AbstractOptions;

/**
 * Trait AbstractOptionTrait
 */
trait AbstractOptionTrait
{
    /**
     * We use the '__' prefix to avoid collisions with properties in
     * user-implementations.
     *
     * @var bool
     */
    protected $__strictMode__ = true;

    /**
     * Constructor
     *
     * @param  array|Traversable|null $options
     */
    public function __construct($options = null)
    {
        if (null !== $options) {
            $this->setFromArray($options);
        }
    }

    /**
     * Set one or more configuration properties
     *
     * @param  array|Traversable|AbstractOptions $options
     * @throws InvalidArgumentException
     * @return AbstractOptions Provides fluent interface
     */
    public function setFromArray($options)
    {

        if ($options instanceof self) {
            $options = $options->toArray();
        }

        if (!is_array($options) && !$options instanceof Traversable) {
            throw new InvalidArgumentException(sprintf(
                'Parameter provided to %s must be an %s, %s or %s',
                __METHOD__,
                'array',
                'Traversable',
                'Zend\Stdlib\AbstractOptions'
            ));
        }

        foreach ($options as $key => $value) {
            $this->__set($key, $value);
        }

        return $this;
    }

    /**
     * Cast to array
     *
     * @return array
     */
    public function toArray()
    {
        $array = [];
        $transform = function ($letters) {
            $letter = array_shift($letters);
            return '_' . strtolower($letter);
        };
        foreach ($this as $key => $value) {
            if ($key === '__strictMode__') {
                continue;
            }
            $normalizedKey = preg_replace_callback('/([A-Z])/', $transform, $key);
            $array[$normalizedKey] = $value;
        }
        return $array;
    }

    /**
     * Set a configuration property
     *
     * @see ParameterObject::__set()
     * @param string $key
     * @param mixed $value
     * @throws BadMethodCallException
     * @return void
     */
    public function __set($key, $value)
    {
        $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
        if ($this->__strictMode__ && !method_exists($this, $setter)) {
            throw new BadMethodCallException(
                'The option "' . $key . '" does not '
                . 'have a matching ' . $setter . ' setter method '
                . 'which must be defined'
            );
        } elseif (!$this->__strictMode__ && !method_exists($this, $setter)) {
            return;
        }
        $this->{$setter}($value);
    }

    /**
     * Get a configuration property
     *
     * @see ParameterObject::__get()
     * @param string $key
     * @throws BadMethodCallException
     * @return mixed
     */
    public function __get($key)
    {
        $getter = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
        if (!method_exists($this, $getter)) {
            throw new BadMethodCallException(
                'The option "' . $key . '" does not '
                . 'have a matching ' . $getter . ' getter method '
                . 'which must be defined'
            );
        }

        return $this->{$getter}();
    }

    /**
     * Test if a configuration property is null
     * @see ParameterObject::__isset()
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return null !== $this->__get($key);
    }

    /**
     * Set a configuration property to NULL
     *
     * @see ParameterObject::__unset()
     * @param string $key
     * @throws InvalidArgumentException
     * @return void
     */
    public function __unset($key)
    {
        try {
            $this->__set($key, null);
        } catch (BadMethodCallException $e) {
            throw new InvalidArgumentException(
                'The class property $' . $key . ' cannot be unset as'
                . ' NULL is an invalid value for it',
                0,
                $e
            );
        }
    }

    /**
     * @param boolean $_strictMode__
     */
    public function setStrictMode($_strictMode__)
    {
        $this->__strictMode__ = $_strictMode__;
    }

    /**
     * @return boolean
     */
    public function getStrictMode()
    {
        return $this->__strictMode__;
    }
}
