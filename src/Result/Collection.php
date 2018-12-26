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
                $this->iterator->add($value);
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
            return $this->iterator = $this->iterator ?? new CollectionIterator($this);
        }
    }