<?php

namespace Kassko\ClassResolver\Exception;

/**
 * This exception is thrown when an object can't be created
 * from it's class name.
 *
 * @author kko
 */
class NotResolvedClassException extends \RuntimeException
{
    public function __construct($className)
    {
        parent::__construct(sprintf("Can't resolve object from class name \"%s\".", $className));
    }
}