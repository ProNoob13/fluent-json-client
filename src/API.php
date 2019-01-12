<?php

    namespace PN13\FluentJSON;

    use JsonStreamingParser\Parser;
    use PN13\FluentJSON\Result\Item;

    /**
     * Class API
     * @package PN13\FluentJSON
     */
    class API extends Endpoint
    {
        const METHOD_GET = 'GET';
        const METHOD_POST = 'POST';
        const METHOD_PUT = 'PUT';
        const METHOD_DELETE = 'DELETE';

        /** @var string|null */
        protected static $userAgent = null;

        /** @var string */
        private $baseURL;

        /** @var string[] */
        protected $headers = ['Accept' => 'application/json'];

        /** @var boolean switch to allow usage of a URL-encoded body rather than a JSON one */
        protected $encode = false;

        /** @var boolean switch to disable the caching of the call result in a temporary file */
        protected $caching = true;

        public function __construct(string $baseURL)
        {
            parent::__construct();
            $this->baseURL = rtrim($baseURL, '/') . '/';
        }

        protected function execute(string $URI, string $method = self::METHOD_GET, array $data = [])
        {
            $method = strtoupper($method);

            if(!defined(__CLASS__ . '::METHOD_' . $method)) {
                throw new \LogicException(__CLASS__ . ' only supports GET, POST, PUT and DELETE requests');
            }

            $URL = $this->baseURL . ltrim($URI, '/');

            $options = [
                'http' => [
                    'method' => $method,
                    'headers' => '',
                ]
            ];

            foreach ($this->headers as $key => $value) {
                if(is_string($key)) {
                    $key = ucwords(preg_replace('/[^A-Za-z0-9]+/', '-', $key), '-');
                    $options['http']['headers'] .= $key . ': ' . $value . "\r\n";
                } else {
                    throw new \OutOfRangeException('Headers should be in key => value format');
                }
            }

            if(self::$userAgent) {
                $options['http']['user_agent'] = self::$userAgent;
            }

            if($data) {
                if($method === self::METHOD_GET || $method === self::METHOD_DELETE) {
                    $URL .= '?' . http_build_query($data);
                } else {
                    if($this->encode) {
                        $options['http']['content'] = json_encode($data);
                    } else {
                        $options['http']['content'] = http_build_str($data);
                    }
                }
            }

            $stream = @fopen($URL, false, stream_context_create($options));

            if(!is_resource($stream)) {
                $error = error_get_last();
                throw new \RuntimeException($error['message'], $error['code']);
            }

            if($this->caching) {
                $cache = tmpfile();

                stream_copy_to_stream($stream, $cache);
                fseek($cache, 0);
                fclose($stream);

                $stream = $cache;
            }

            $result = new Item();
            $parser = new Parser($stream, $result);
        }
    }