<?php

    namespace PN13\FluentJSON\Result;

    use JsonStreamingParser\Listener\AsyncListenerInterface;

    /**
     * Class Result
     * @package PN13\FluentJSON
     */
    abstract class Result implements AsyncListenerInterface
    {
        const VALUE_TYPE_OBJECT = 'object';
        const VALUE_TYPE_ARRAY = 'array';

        /** @var Result|null */
        protected $parent;

        /** @var int */
        protected $level;

        /** @var int */
        private $depth = 0;

        /** @var string|null */
        private $key = null;

        /** @var Result|null */
        private $child = null;

        /** @var callable */
        private $callback;

        /**
         * Result constructor.
         * @param Result|null $parent
         * @param int $level
         */
        public function __construct(Result $parent = null, int $level = 0)
        {
            $this->parent = $parent;
            $this->level = $level;
        }

        public function setAsyncCallback(callable $callback): void
        {
            $this->callback = $callback;
        }

        public function startObject(): void
        {
            $this->startComplexValue(self::VALUE_TYPE_OBJECT);
        }

        public function endObject(): void
        {
            $this->endComplexValue(self::VALUE_TYPE_OBJECT);
        }

        public function startArray(): void
        {
            $this->startComplexValue(self::VALUE_TYPE_ARRAY);
        }

        public function endArray(): void
        {
            $this->endComplexValue(self::VALUE_TYPE_ARRAY);
        }

        public function startDocument(): void
        {
            // ignore unless overridden by child class
        }

        public function endDocument(): void
        {
            // ignore unless overridden by child class
        }

        public function whitespace(string $whitespace): void
        {
            // ignore unless overridden by child class
        }

        public function key(string $key = null): void
        {
            if($this->child) {
                $this->child->key($key);
            } else {
                $this->key = $key;
            }
        }

        public function value($value): void
        {
            if($this->child) {
                $this->child->value($value);
            } else {
                $this->set($this->key, $value);
            }
        }

        protected function startComplexValue(string $type): void
        {
            if($this->child) {
                $this->depth++;
                $this->child->{'start' . ucfirst($type)}();
            } else {
                if($type === self::VALUE_TYPE_ARRAY) {
                    $class = Collection::class;
                } else {
                    $class = Item::class;
                }

                $this->child = new $class($this, $this->level + 1);
            }
        }

        protected function endComplexValue(string $type): void
        {
            if($this->depth > 0) {
                $this->child->{'end' . ucfirst($type)}();
                $this->depth--;
            } else {
                $value = $this->child;
                $this->child = null;

                $this->set($this->key, $value);
            }
        }

        protected function proceed(): void
        {
            ($this->callback)();
        }

        /**
         * @param string|null $name
         * @param Result|int|float|string|bool $value
         */
        protected abstract function set(?string $name, $value): void;
    }