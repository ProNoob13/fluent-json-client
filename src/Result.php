<?php

    namespace PN13\FluentJSON;

    class Result
    {
        /** @var array */
        private $attributes;

        public function __construct(array $attributes)
        {
            $this->attributes = $attributes;
        }

        public function __isset(string $name): bool
        {
            return isset($this->attributes[$name]);
        }

        public function __unset(string $name): void
        {
            unset($this->attributes[$name]);
        }

        public function __get(string $name)
        {
            return $this->attributes[$name];
        }

        public function __set(string $name, $value)
        {
            return $this->attributes[$name] = $value;
        }
    }