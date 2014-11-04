<?php

namespace Kassko\ClassResolver;

/**
 * A container interface for containers implementations used in ClassResolver component.
 *
 * @author kko
 */
interface ContainerInterface
{
    function get($name);
    function has($name);
}
