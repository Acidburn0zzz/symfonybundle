<?php
/**
 * Copyright (c) 2017
 *
 * @category  Library
 * @package   Dwoo\SymfonBundle\DataCollector
 * @author    David Sanchez <david38sanchez@gmail.com>
 * @copyright 2017 David Sanchez
 * @license   http://dwoo.org/LICENSE LGPLv3
 * @version   1.0.0
 * @date      2017-03-15
 * @link      http://symfony.dwoo.org/
 */

namespace Dwoo\SymfonyBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class DwooDataCollector
 *
 * @package Dwoo\SymfonyBundle\DataCollector
 */
class DwooDataCollector extends DataCollector
{

    /**
     * Collects data for the given Request and Response.
     *
     * @param Request    $request   A Request instance
     * @param Response   $response  A Response instance
     * @param \Exception $exception An Exception instance
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'method'                   => $request->getMethod(),
            'acceptable_content_types' => $request->getAcceptableContentTypes(),
        ];
    }

    /**
     * Returns the name of the collector.
     *
     * @return string The collector name
     */
    public function getName()
    {
        return 'dwoo';
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        return call_user_func_array($name, $arguments);
    }
}