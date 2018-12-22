<?php

    namespace PN13\FluentJSON\Result;

    /**
     * Class Item
     * @package PN13\FluentJSON
     */
    class Item extends Result
    {
        /** @var array */
        private $attributes;

        public function __isset(string $name): bool
        {
            return isset($this->attributes[$name]);
        }

        public function __get(string $name)
        {
            return $this->attributes[$name];
        }

        /**
         * @param string|null $name
         * @param Result|int|float|string $value
         */
        protected function set(?string $name, $value): void
        {
            if(!$name) {
                // should throw a error
            }

            $this->attributes[$name] = $value;
        }
    }