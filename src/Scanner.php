<?php

    namespace PN13\FluentJSON;

    use JsonStreamingParser\Listener\ListenerInterface;

    class Scanner implements ListenerInterface
    {
        /** @var array */
        private $items;

        public function __construct(string $containerTag, string $countTag = null)
        {
            // this is where the fun starts
        }

        public function startDocument(): void
        {
            $this->items = [];
        }

        public function endDocument(): void
        {
            // TODO: Implement endDocument() method.
        }

        public function startObject(): void
        {
            // TODO: Implement startObject() method.
        }

        public function endObject(): void
        {
            // TODO: Implement endObject() method.
        }

        public function startArray(): void
        {
            // TODO: Implement startArray() method.
        }

        public function endArray(): void
        {
            // TODO: Implement endArray() method.
        }

        public function key(string $key): void
        {
            // TODO: Implement key() method.
        }

        /**
         * @param mixed $value the value as read from the parser, it may be a string, integer, boolean, etc
         */
        public function value($value)
        {
            // TODO: Implement value() method.
        }

        public function whitespace(string $whitespace): void
        {
            // TODO: Implement whitespace() method.
        }
    }