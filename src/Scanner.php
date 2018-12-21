<?php

    namespace PN13\FluentJSON;

    use JsonStreamingParser\Listener\ListenerInterface;

    class Scanner implements ListenerInterface
    {
        /** @var string[] */
        protected $path = [];

        /** @var array */
        protected $attributes = [];

        /** @var string */
        protected $itemsKey;

        /** @var string|null */
        protected $countKey;

        /** @var string|null */
        protected $currentKey = null;

        /** @var int|null */
        protected $count = null;

        /** @var Result|null */
        protected $result = null;

        public function __construct(string $itemsKey, string $countKey = null)
        {
            $this->itemsKey = $itemsKey;
            $this->countKey = $countKey;
        }

        public function startDocument(): void
        {
            $this->count = null;
            $this->result = null;
        }

        public function endDocument(): void
        {
            $this->currentKey = null;
            $this->path = [];
        }

        public function startObject(): void
        {

        }

        public function endObject(): void
        {

        }

        public function startArray(): void
        {
            if($this->currentKey === $this->itemsKey) {
                $this->result = new Resultset($this->attributes)
            }
        }

        public function endArray(): void
        {

        }

        public function key(string $key): void
        {
            $this->currentKey = $key;
        }

        /**
         * @param mixed $value the value as read from the parser, it may be a string, integer, boolean, etc
         */
        public function value($value)
        {
            if(count($this->path) === 0 && $this->currentKey === $this->countKey) {
                $this->count = $value;
            } else {
                $this->attributes[$this->currentKey] = $value;
            }
        }

        public function whitespace(string $whitespace): void
        {
            // ignore whitespace
        }
    }