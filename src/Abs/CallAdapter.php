<?php

namespace A2htray\DateFNS\Abs;


class CallAdapter {
    private $target;
    private $source;

    public function __construct($target, $source) {
        $this->target = $target;
        $this->source = $source;
    }

    public function __get($name) {
        $ref = new \ReflectionClass($this->source);
        if ($ref->hasProperty($name)) return $this->source->{$name};
        $ref = new \ReflectionClass($this->target);
        if ($ref->hasProperty($name)) return $this->target->{$name};
        return null;
    }

    public function __call($name, $arguments) {
        $ref = new \ReflectionClass($this->source);
        if ($ref->hasMethod($name))
            return call_user_func_array([$this->source, $name], $arguments);

        $ref = new \ReflectionClass($this->target);
        if ($ref->hasMethod($name))
            return call_user_func_array([$this->target, $name], $arguments);

        return null;
    }
}