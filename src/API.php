<?php

    namespace PN13\FluentJSON;

    class API
    {
        const METHOD_GET = 'GET';
        const METHOD_POST = 'POST';
        const METHOD_PUT = 'PUT';
        const METHOD_DELETE = 'DELETE';

        /** @var string */
        protected $root;

        public function __construct(string $URL)
        {
            $this->root = $URL;
        }

        final public function execute(string $URI, string $method = self::METHOD_GET, array $data = [])
        {
            dd($URI, $method, $data);
        }
    }