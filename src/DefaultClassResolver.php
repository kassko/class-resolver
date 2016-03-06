<?php

namespace Kassko\ClassResolver;

/**
 * Class-resolver that is used in the chain class resolver chain as fallback
 * when no class resolver can resolve a class.
 *
 * This class resolver has no sense without the class resolver chain.
 *
 * @author kko
 */
class DefaultClassResolver extends AbstractFallbackClassResolver
{
    /**
     * A config in an array.
     *
     * The following keys are handled: 
     *
     *
     * 'be_silent'          : boolean value
     * 'throw_exception'    : boolean value
     *
     *
     * @var array   $config
     */
    private $config;

    /**
     * @param array     $config
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);        
    }

    /**
     * @param array     $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        if (empty($this->config)) {
            $this->config['be_silent'] = true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($className)
    {
        switch (true) {
            case isset($this->config['be_silent']):
                return new $className;
                break;
            case isset($this->config['throw_exception']):
                throw new NotResolvedClassException($className);
                break;
        }

        throw new NotResolvedClassException($className);
    }
}
