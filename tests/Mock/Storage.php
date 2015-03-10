<?php

namespace BehEh\Flaps\Mock;

use BehEh\Flaps\StorageInterface;

class Storage implements StorageInterface
{

    protected $values;
    protected $timestamps;
    protected $expires;

    public function __construct()
    {
        $this->values = array();
        $this->timestamps = array();
    }

    public function setValue($key, $value)
    {
        $this->values[$key] = $value;
    }

    public function getValue($key)
    {
        return isset($this->values[$key]) ? $this->values[$key] : 0;
    }

    public function setTimestamp($key, $timestamp)
    {
        $this->timestamps[$key] = $timestamp;
    }

    public function getTimestamp($key)
    {
        return isset($this->timestamps[$key]) ? $this->timestamps[$key] : 0;
    }

    public function expire($key)
    {
        unset($this->values[$key]);
        unset($this->timestamps[$key]);
        unset($this->expires[$key]);
    }

    public function expireIn($key, $seconds)
    {
        $this->expires[$key] = $seconds;
    }

}
