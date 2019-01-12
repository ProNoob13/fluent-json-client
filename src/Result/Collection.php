<?php

    namespace PN13\FluentJSON\Result;

    use IteratorAggregate;
    use Traversable;

    /**
     * Class Collection
     * @package PN13\FluentJSON
     */
    class Collection extends Result implements IteratorAggregate
    {
        /** @var CollectionIterator|null */
        protected $iterator = null;

        /**
         * @param string|null $name
         * @param Result|int|float|string|bool $value
         */
        protected function set(?string $name, $value): void
        {
            if($this->iterator) {
                $this->iterator->enqueue($value);
            }
        }

        public function proceed(): void
        {
            parent::proceed();
        }

        /**
         * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
         * @return Traversable
         */
        public function getIterator()
        {
            if(!$this->iterator) {
                $this->iterator = new CollectionIterator($this);
            }

            return $this->iterator;
        }
    }