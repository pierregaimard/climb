<?php

namespace Framework3\ClassFinder;

interface FinderInterface
{
    /**
     * returns an array of fully qualified namespaced classes from a given namespace
     *
     * Arguments:
     *
     *  -   $namespace:
     *      given namespace to scan
     *
     *  -   $getSubNamespaceClasses:
     *      If this option is set to true, the method should scan the sub directories,
     *      and also retrieves the sub-classes.
     *
     *  -   $suffix:
     *      if a suffix is given, the method should just retrieves the classes who contains the given suffix.
     *      e.g. $suffix = "Controller" Returns for example `Controller\AdminController`
     *      but not `Controller\User`
     *
     * @param string      $namespace  Given namespace to scan
     * @param bool        $scanSubDir Allow to retrieve classes from sub directories.
     * @param string|null $suffix     Class suffix
     *
     * @return array|null
     */
    public function getClassesList(string $namespace, $scanSubDir = true, string $suffix = null);
}
