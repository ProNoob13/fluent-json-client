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
            return $this->attributes[$name];
        }

        /**
         * @param string|null $name
         * @param Result|int|float|string|bool $value
         */
        protected function set(?string $name, $value): void
        {
            if(!$name) {
                throw new RuntimeException('Item attributes should have a name');
            }

            $this->attributes[$name] = $value;
        }
    }