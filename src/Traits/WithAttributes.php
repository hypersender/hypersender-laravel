<?php

namespace Hypersender\Hypersender\Traits;

use Illuminate\Support\Arr;

trait WithAttributes
{
    protected array $attributes = [];

    /**
     * Determine if attribute exist.
     */
    public function has(string $key): bool
    {
        return Arr::has($this->attributes, $key);
    }

    /**
     * Get attribute by key.
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return Arr::get($this->attributes, $key, $default);
    }

    /**
     * Dynamically get attribute.
     *
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->get($key);
    }

    /**
     * Set attribute by key.
     *
     * @param  mixed  $value
     */
    public function set(string $key, $value): self
    {
        Arr::set($this->attributes, $key, $value);

        return $this;
    }

    /**
     * @return void
     */
    public function setAll(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->all());
    }
}
