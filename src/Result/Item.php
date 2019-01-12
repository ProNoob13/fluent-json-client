<?php

    namespace PN13\FluentJSON\Result;

    use http\Exception\RuntimeException;

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
            $result = $this->attributes[$name];
            unset($this->attributes[$name]); // keep the memory footprint small by caching it only until it's consumed

            return $result;
        }

        /**
         * @param string|null $name
         * @param Result|int|float|string|bool $value
         */
        protected function set(?string $name, $value): void
        {
            if(!$name || ctype_digit($name)) {
                throw new RuntimeException('Item attributes should have a name');
            }

            $this->attributes[$name] = $value;

            if(!$value instanceof Collection) {
                // ignoring atrocious APIs, result JSON objects are usually small enough to cache as a whole
                // continue reading until either the object is read in full or an array is encountered
                $this->proceed();
            }
        }
    }