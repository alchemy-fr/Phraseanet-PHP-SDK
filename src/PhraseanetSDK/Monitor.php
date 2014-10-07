<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK;

use PhraseanetSDK\Http\APIGuzzleAdapter;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @method \PhraseanetSDK\Monitor getScheduler()
 * @method \PhraseanetSDK\Monitor startTask($task_id)
 * @method \PhraseanetSDK\Monitor stopTask($task_id)
 * @method \PhraseanetSDK\Monitor getTask($task_id)
 * @method \PhraseanetSDK\Monitor getTasks()
 */
class Monitor
{
    private $adapter;
    private static $mappings = array(
        'getScheduler' => array(
            'path'            => 'monitor/scheduler/',
            'entity'          => 'PhraseanetSDK\Monitor\Scheduler',
            'query-keys'      => array(),
            'result-property' => 'scheduler',
            'method'          => 'GET',
        ),
        'getTask'      => array(
            'path'            => 'monitor/task/{task_id}/',
            'entity'          => 'PhraseanetSDK\Monitor\Task',
            'query-keys'      => array('task_id'),
            'result-property' => 'task',
            'method'          => 'GET',
        ),
        'startTask'      => array(
            'path'            => 'monitor/task/{task_id}/start/',
            'entity'          => 'PhraseanetSDK\Monitor\Task',
            'query-keys'      => array('task_id'),
            'result-property' => 'task',
            'method'          => 'POST',
        ),
        'stopTask'      => array(
            'path'            => 'monitor/task/{task_id}/stop/',
            'entity'          => 'PhraseanetSDK\Monitor\Task',
            'query-keys'      => array('task_id'),
            'result-property' => 'task',
            'method'          => 'POST',
        ),
        'getTasks'     => array(
            'path'            => 'monitor/tasks/',
            'entity'          => 'PhraseanetSDK\Monitor\Task',
            'query-keys'      => array(),
            'result-property' => 'tasks',
            'method'          => 'GET',
        ),
    );

    public function __construct(APIGuzzleAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function __call($name, $arguments)
    {
        if (!isset(static::$mappings[$name])) {
            throw new \BadMethodCallException(sprintf('Method "%s::%s" does not exist.', get_class($this), $name));
        }

        return $this->doCall($name, $arguments);
    }

    private function doCall($name, $arguments)
    {
        $parameters = array();

        $n = 0;
        foreach (static::$mappings[$name]['query-keys'] as $key) {
            $parameters[$key] = $arguments[$n];
            $n++;
        }

        $response = $this->adapter->call(static::$mappings[$name]['method'], array(static::$mappings[$name]['path'], $parameters));
        $result = $response->getResult()->{static::$mappings[$name]['result-property']};

        if (is_array($result)) {
            $output = new ArrayCollection();
            foreach ($result as $entityData) {
                $output->add($entity = $this->getEntity($name, $entityData));
            }
        } else {
            $output = $this->getEntity($name, $result);
        }

        return $output;
    }

    private function getEntity($name, $data)
    {
        $entity = new static::$mappings[$name]['entity']();

        array_walk($data, function ($value, $property) use ($entity) {
            $method = 'set' . implode('', array_map(function ($chunk) {
                    return ucfirst($chunk);
                }, preg_split('/[-_]/', $property)));

            $ref = new \ReflectionParameter(array($entity, $method), 0);
            if (null !== $ref->getClass()) {
                if ('DateTime' === $ref->getClass()->name) {
                    $value = \DateTime::createFromFormat(DATE_ATOM, $value) ?: null;
                }
            }

            call_user_func(array($entity, $method), $value);
        });

        return $entity;
    }
}
