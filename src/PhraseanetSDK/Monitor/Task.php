<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Monitor;

class Task
{
    private $id;
    private $name;
    private $state;
    private $pid;
    private $title;
    private $lastExecTime;
    private $autoStart;
    private $runner;
    private $crashCounter;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    public function getPid()
    {
        return $this->pid;
    }

    public function setPid($pid)
    {
        $this->pid = $pid;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getLastExecTime()
    {
        return $this->lastExecTime;
    }

    public function setLastExecTime(\DateTime $lastExecTime = null)
    {
        $this->lastExecTime = $lastExecTime;

        return $this;
    }

    public function getAutoStart()
    {
        return $this->autoStart;
    }

    public function setAutoStart($autoStart)
    {
        $this->autoStart = $autoStart;

        return $this;
    }

    public function getRunner()
    {
        return $this->runner;
    }

    public function setRunner($runner)
    {
        $this->runner = $runner;

        return $this;
    }

    public function getCrashCounter()
    {
        return $this->crashCounter;
    }

    public function setCrashCounter($crashCounter)
    {
        $this->crashCounter = $crashCounter;

        return $this;
    }
}
