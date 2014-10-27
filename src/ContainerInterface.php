<?php

namespace Kassko\ClassResolver;

/**
 * A container interface for containers implementations.
 *
 * @author kko
 */
interface ContainerInterface
{
    function get($name);
}