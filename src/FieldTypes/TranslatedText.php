<?php

namespace GetCandy\FieldTypes;

use GetCandy\Base\FieldType;
use GetCandy\Exceptions\FieldTypeException;
use Illuminate\Database\Eloquent\Collection;

class TranslatedText implements FieldType
{
    /**
     * @var Illuminate\Database\Eloquent\Collection
     */
    protected $value;

    /**
     * Create a new instance of TranslatedText field type.
     *
     * @param  Illuminate\Database\Eloquent\Collection  $value
     */
    public function __construct($value = null)
    {
        if ($value) {
            $this->setValue($value);
        } else {
            $this->value = new Collection;
        }
    }

    /**
     * Return the value of this field.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value of this field.
     *
     * @param  Illuminate\Database\Eloquent\Collection  $value
     */
    public function setValue($value)
    {
        if (is_array($value)) {
            $value = collect($value);
        }

        if (! $value instanceof \Illuminate\Support\Collection) {
            throw new FieldTypeException(self::class.' value must be a collection.');
        }

        foreach ($value as $key => $item) {
            if (is_string($item)) {
                $item = new Text($item);
                $value[$key] = $item;
            }
            if ($item && (Text::class !== get_class($item))) {
                throw new FieldTypeException(self::class.' only supports '.Text::class.' field types.');
            }
        }

        $this->value = $value;
    }
}
