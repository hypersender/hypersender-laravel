<?php

namespace Hypersender\Hypersender\Support;

use AllowDynamicProperties;
use Hypersender\Hypersender\Traits\WithAttributes;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Validation\ValidationException;

#[AllowDynamicProperties]
abstract class BaseAction implements Arrayable
{
    use Conditionable, WithAttributes;

    protected bool $skipValidation = false;

    /**
     * @return static
     */
    public static function make(array $attributes = [])
    {
        return tap(app(static::class), function ($action) use (&$attributes) {
            $action->attributes = $attributes;
        });
    }

    /**
     * Implement action logic here.
     */
    abstract public function handle();

    /**
     * Run action.
     *
     *
     * @throws ValidationException
     */
    public function run(): mixed
    {
        $this->validateAttributes();

        return $this->handle();
    }

    /**
     * Run action.
     *
     *
     * @throws ValidationException
     */
    public function execute(): mixed
    {
        return $this->run();
    }

    public function skipValidation(): self
    {
        $this->skipValidation = true;

        return $this;
    }

    /**
     * @throws ValidationException
     */
    public function validateAttributes(): void
    {
        if ($this->skipValidation) {
            return;
        }

        if (! empty($this->rules())) {
            $this->merge(validator($this->attributes, $this->rules(), $this->messages())->validate());
        }
    }

    /**
     * Validation rules.
     */
    protected function rules(): array
    {
        return [];
    }

    /**
     * Validation messages.
     */
    protected function messages(): array
    {
        return [];
    }

    /**
     * Merge or update to existing keys.
     *
     * @return $this
     */
    public function merge(array $attributes)
    {
        $this->attributes = array_merge($attributes, $this->attributes);

        return $this;
    }
}
