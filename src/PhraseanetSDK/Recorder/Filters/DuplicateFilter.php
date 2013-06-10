<?php

namespace PhraseanetSDK\Recorder\Filters;

class DuplicateFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(array &$data)
    {
        $knowns = array();
        $output = array();

        foreach (array_reverse($data) as $key => $request) {
            $md5 = md5(serialize($request));
            if (!isset($knowns[$md5])) {
                array_unshift($output, $request);
                $knowns[$md5] = true;
            }
        }

        $data = $output;
    }
}
