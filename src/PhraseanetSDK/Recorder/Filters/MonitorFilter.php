<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Recorder\Filters;

class MonitorFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(array &$data)
    {
        $output = array();

        foreach ($data as $request) {
            if (false === strpos($request['path'], 'api/v1/monitor/')) {
                $output[] = $request;
            }
        }

        $data = $output;
    }
}
