<?php

    namespace PN13\FluentJSON\Result;

    use Iterator;

    /**
     * Class CollectionIterator
     * @package PN13\FluentJSON\Result
     */
    class CollectionIterator implements Iterator
    {
        /** @var Collection */
        protected $parent;

        /** @var int */
        protected $offset = 0;

        /** @var Result|int|float|string|bool|null */
        protected $current;

        /** @var Result|int|float|string|bool|null */
        protected $next = null;

        /** @var bool */
        protected $hasNext = false;

        /** @var bool */
        protected $finished = false;

        public function __construct(Collection $parent)
        {
            $this->parent = $parent;
        }

        /**
         * @param Result|int|float|string|bool|null $value
         */
        public function enqueue($value): void
        {
            $this->next = $value;
            $this->hasNext = true;
        }

        /**
         * @link https://php.net/manual/en/iterator.rewind.php
         * @return void
         */
        public function rewind()
        {
            $this->finished = $this->hasNext = false;
            $this->offset = null;
        }

        /**
         * @link https://php.net/manual/en/iterator.next.php
         * @return void
         */
        public function next()
        {
            // proceeding should trigger enqueue, giving us the next value if the array hasn't ended
            $this->parent->proceed();

            if($this->hasNext) {
                $this->current = $this->next;
                $this->next = null;

                $this->hasNext = false;
            } else {
                $this->finished = true;
            }

            $this->offset++;
        }

        /**
         * @link https://php.net/manual/en/iterator.valid.php
         * @return boolean
         */
        public function valid()
        {
            return !$this->finished;
        }

        /**
         * @link https://php.net/manual/en/iterator.key.php
         * @return int|null
         */
        public function key()
        {
            return $this->offset;
        }

        /**
         * @link https://php.net/manual/en/iterator.current.php
         * @return Result|int|float|string|bool
         */
        public function current()
        {
            return $this->current;
        }
    }