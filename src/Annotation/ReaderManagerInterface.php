<?php

namespace Climb\Annotation;

interface ReaderManagerInterface
{
    /**
     * returns an annotation reader instance for a class
     *
     * @param $class
     *
     * @return ReaderInterface
     */
    public function getReader($class);
}
