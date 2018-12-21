<?php

    namespace PN13\FluentJSON;

    use Countable;
    use JsonStreamingParser\Listener\ListenerInterface;

    class Resultset extends Result implements Countable, ListenerInterface
    {
        /** @var int */
        private $total;

        public function __construct(array $attributes, int $total = null)
        {
            parent::__construct($attributes);
            $this->total = $total;
        }

        public function count()
        {
            if(!is_int($this->total)) {
                throw new \RuntimeException('The fetched JSON doesn\'t contain a item count');
            }

            return $this->total;
        }

        public function startDocument(): void
        {
            // TODO: Implement startDocument() method.
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