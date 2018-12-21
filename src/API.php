<?php

    namespace PN13\FluentJSON;

    use JsonStreamingParser\Parser;

    class API extends Endpoint
    {
        const METHOD_GET = 'GET';
        const METHOD_POST = 'POST';
        const METHOD_PUT = 'PUT';
        const METHOD_DELETE = 'DELETE';

        /** @var string|null */
        protected static $userAgent = null;

        /** @var string[] */
        protected $headers = [];

        /** @var string */
        protected $root;

        /** @var bool */
        protected $encode = false;

        /** @var bool */
        protected $caching = true;

        public function __construct(string $URL)
        {
            parent::__construct();
            $this->root = rtrim($URL, '/') . '/';
        }

        protected function execute(string $URI, string $method = self::METHOD_GET, array $data = [])
        {
            $method = strtoupper($method);

            if(!defined(__CLASS__ . '::METHOD_' . $method)) {
                throw new \LogicException(__CLASS__ . ' only supports GET, POST, PUT and DELETE requests');
            }

            $URL = $this->root . ltrim($URI, '/');

            $context = [
                'http' => [
                    'method' => $method,
                    'headers' => "Accept: application/json\r\n",
                ]
            ];

            foreach ($this->headers as $key => $value) {
                if(is_string($key)) {
                    $context['http']['headers'] .= $key . ': ' . $value;
                } else {
                    $context['http']['headers'] .= $value;
                }

                $context['http']['headers'] .= "\r\n";
            }

            if(self::$userAgent) {
                $context['http']['user_agent'] = self::$userAgent;
            }

            if($data) {
                if($method === self::METHOD_GET || $method === self::METHOD_DELETE) {
                    $URL .= '?' . http_build_query($data);
                } else {
                    if($this->encode) {
                        $context['http']['content'] = json_encode($data);
                    } else {
                        $context['http']['content'] = http_build_str($data);
                    }
                }
            }

            $stream = @fopen($URL, false, stream_context_create($context));

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

            $scanner = new Scanner();
            $parser = new Parser($stream, $scanner);
        }
    }