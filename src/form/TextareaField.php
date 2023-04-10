<?php

namespace kilyte\form;

use kilyte\database\Model;

class TextareaField extends BaseField
{

    const IS_REQUIRED = '';

    public function __construct(Model $model, string $attribute, $options)
    {
        $this->isRequired = self::IS_REQUIRED;
        parent::__construct($model, $attribute, $options);
    }

    public function renderInput()
    {
        $attributes = [];
        foreach ($this->options as $key => $value) {
            $attributes[] = "$key=\"$value\"";
        }
        return sprintf(
            '<textarea class="form-control%s" name="%s" %s %s>%s</textarea>',
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->attribute,
            implode(" ", $attributes),
            $this->isRequired,
            $this->model->{$this->attribute},
        );
    }

    public function isRequired()
    {
        $this->isRequired = 'required="true"';
        return $this;
    }

    public function show()
    {
        $this->visible = '';
        echo $this;
    }

    public function hidden()
    {
        $this->visible = 'd-none';
        echo $this;
    }
}
