<?php

namespace App\Traits;

use ArrayAccess;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use IteratorAggregate;
use JsonSerializable;

class JsonDataAttribute implements ArrayAccess, Arrayable, Countable, IteratorAggregate, Jsonable, JsonSerializable
{
    /** @var \Illuminate\Database\Eloquent\Model */
    protected $model;

    /** @var string */
    protected $key;

    /** @var \Illuminate\Support\Collection */
    protected $collection;

    public function __construct(Model $model, string $key)
    {
        $this->model = $model;

        $this->key = $key;

        $this->collection = new Collection($this->getRawJsonDataAttributes());
    }

    protected function getRawJsonDataAttributes(): array
    {
        $attributes = $this->model->getAttributes()[$this->key] ?? '{}';

        return $attributes == '""' ? [] : $this->model->fromJson($attributes);
    }

    public static function createForModel(Model $model, string $key)
    {
        return new static($model, $key);
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    public function get($key, $default = null)
    {
        return data_get($this->collection, $key, $default);
    }

    public function set($key, $value)
    {
        $items = $this->collection->toArray();

        return $this->override(data_set($items, $key, $value));
    }

    function override(iterable $collection)
    {
        $this->collection = new Collection($collection);
        $this->model->{$this->key} = $this->collection->toArray();

        return $this;
    }

    public function __call($name, $arguments)
    {
        $result = call_user_func_array([$this->collection, $name], $arguments);

        $this->override($this->collection->toArray());

        return $result;
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetExists($offset)
    {
        return $this->collection->offsetExists($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->forget($offset);
    }

    /**
     * @param $keys
     *
     * @return SchemalessAttributes
     * @see Collection::forget()
     *
     */
    public function forget($keys)
    {
        $items = $this->collection->toArray();

        foreach ((array)$keys as $key) {
            Arr::forget($items, $key);
        }

        return $this->override($items);
    }

    public function toArray()
    {
        return $this->collection->toArray();
    }

    public function toJson($options = 0)
    {
        return $this->collection->toJson($options);
    }

    public function jsonSerialize()
    {
        return $this->collection->jsonSerialize();
    }

    public function count()
    {
        return $this->collection->count();
    }

    public function getIterator()
    {
        return $this->collection->getIterator();
    }
}
