<?php

namespace kilyte\form;

use kilyte\database\Model;

class InputField extends BaseField
{
    const TYPE_TEXT = 'text';
    const TYPE_PASSWORD = 'password';
    const TYPE_FILE = 'file';
    const TYPE_EMAIL = 'email';
    const IS_REQUIRED = '';


    public function __construct(Model $model, string $attribute, $options)
    {
        $this->type = self::TYPE_TEXT;
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
            '<input type="%s" class="form-control%s" name="%s" value="%s" %s %s>',
            $this->type,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->attribute,
            $this->model->{$this->attribute},
            $this->isRequired,
            implode(" ", $attributes),
        );
    }

    public function passwordField()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function isRequired()
    {
        $this->isRequired = 'required="true"';
        return $this;
    }

    public function fileField()
    {
        $this->type = self::TYPE_FILE;
        return $this;
    }

    public function emailField()
    {
        $this->type = self::TYPE_EMAIL;
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
