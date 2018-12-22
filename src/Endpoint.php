<?php

    namespace PN13\FluentJSON;

    use ArrayAccess;
    use LogicException;

    /**
     * Class Endpoint
     * @package PN13\FluentJSON
     */
    class Endpoint implements ArrayAccess
    {
        /** @var Endpoint|null */
        protected $parent;

        /** @var string */
        protected $method;

        /** @var string */
        protected $namespace = __NAMESPACE__;

        /** @var string */
        protected $name;

        /** @var array */
        private $data = [];

        protected function __construct(Endpoint $parent = null, string $name = null, string $method = API::METHOD_GET)
        {
            $this->parent = $parent;
            $this->method = $method;
            $this->name = $name;
        }

        final public function __get(string $property): Endpoint
        {
            return $this->instantiate($property);
        }

        final public function __call(string $name, array $arguments)
        {
            $instance = $this->__get($name);
            return $instance->__invoke(...$arguments);
        }

        final public function __invoke(array $data = [])
        {
            return $this->parent->execute($this->name, $this->method, array_merge($this->data, $data));
        }

        /**
         * @param string $name
         * @return Endpoint
         */
        protected function instantiate(string $name): Endpoint
        {
            $name = strtolower(preg_replace('/(?<=[a-z])(?=[A-Z])/', '-', $name));
            return new Endpoint($this, $name, $this->method);
        }

        /**
         * @param string $URI
         * @param string $method
         * @param array $data
         * @return mixed
         */
        protected function execute(string $URI, string $method = API::METHOD_GET, array $data = [])
        {
            if(!$this->parent) {
                throw new LogicException('Endpoint should be a child of API');
            }

            $URI = $this->name . '/' . $URI;
            $data = array_merge($this->data, $data);

            return $this->parent->execute($URI, $method, $data);
        }

        /**
         * @param string $name
         * @return bool
         */
        final public function offsetExists($name)
        {
            return isset($this->data[$name]);
        }

        /**
         * @param string $name
         * @return mixed
         */
        final public function offsetGet($name)
        {
            return $this->data[$name];
        }

        /**
         * @param string $name
         * @param mixed $value
         * @return mixed
         */
        final public function offsetSet($name, $value)
        {
            if(!is_string($name)) {
                throw new LogicException('Endpoint data must have a string name');
            }

            return $this->data[$name] = $value;
        }

        /**
         * @param string $name
         */
        final public function offsetUnset($name)
        {
            unset($this->data[$name]);
        }
    }