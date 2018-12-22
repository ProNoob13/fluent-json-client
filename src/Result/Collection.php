<?php

    namespace PN13\FluentJSON\Result;

    use Iterator;

    /**
     * Class Collection
     * @package PN13\FluentJSON
     */
    class Collection extends Result implements Iterator
    {
        protected $iterator = 0;

        protected $current = null;

        /**
         * @param string|null $name
         * @param Result|int|float|string $value
         */
        protected function set(?string $name, $value): void
        {
            $this->current = $value;
        }

        /**
         * Return the current element
         * @link https://php.net/manual/en/iterator.current.php
         * @return mixed Can return any type.
         * @since 5.0.0
         */
        public function current()
        {
            // TODO: Implement current() method.
        }

        /**
         * Move forward to next element
         * @link https://php.net/manual/en/iterator.next.php
         * @return void Any returned value is ignored.
         * @since 5.0.0
         */
        public function next()
        {
            // TODO: Implement next() method.
        }

        /**
         * Checks if current position is valid
         * @link https://php.net/manual/en/iterator.valid.php
         * @return boolean The return value will be casted to boolean and then evaluated.
         * Returns true on success or false on failure.
         * @since 5.0.0
         */
        public function valid()
        {
            // TODO: Implement valid() method.
        }

        /**
         * Rewind the Iterator to the first element
         * @link https://php.net/manual/en/iterator.rewind.php
         * @return void Any returned value is ignored.
         * @since 5.0.0
         */
        public function rewind()
        {
            // TODO: Implement rewind() method.
        }
    }