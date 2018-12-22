<?php

    namespace PN13\FluentJSON\Result;

    use JsonStreamingParser\Listener\ListenerInterface;

    /**
     * Class Result
     * @package PN13\FluentJSON
     */
    abstract class Result implements ListenerInterface
    {
        /** @var Result|null */
        protected $parent;

        /** @var int */
        protected $level;

        /** @var int */
        private $nested = 0;

        /** @var string|null */
        private $key = null;

        /** @var Result|null */
        private $child = null;

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

        public function startDocument(): void
        {
            // ignore unless overridden in child class
        }

        public function endDocument(): void
        {
            // ignore unless overridden in child class
        }

        public function startObject(): void
        {
            if($this->child) {
                $this->nested++;
                $this->child->startObject();
            } else {
                $this->child = new Item($this, $this->level + 1);
            }
        }

        public function endObject(): void
        {
            if($this->nested) {
                $this->child->endObject();
                $this->nested--;
            } else {
                $this->endChild();
            }
        }

        public function startArray(): void
        {
            if($this->child) {
                $this->nested++;
                $this->child->startArray();
            } else {
                $this->child = new Collection($this, $this->level + 1);
            }
        }

        public function endArray(): void
        {
            if($this->nested) {
                $this->child->endArray();
                $this->nested--;
            } else {
                $this->endChild();
            }
        }

        private function endChild(): void
        {
            $value = $this->child;
            $this->child = null;

            $this->set($this->key, $value);
        }

        public function whitespace(string $whitespace): void
        {
            // just ignore it
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

        /**
         * @param string|null $name
         * @param Result|int|float|string $value
         */
        protected abstract function set(?string $name, $value): void;
    }