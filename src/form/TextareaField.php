<?php

namespace kilyte\form;

class TextareaField extends BaseField
{
    public function renderInput()
    {
        $attributes = [];
        foreach ($this->options as $key => $value) {
            $attributes[] = "$key=\"$value\"";
        }
        return sprintf(
            '<textarea class="form-control%s" name="%s">%s</textarea>',
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->attribute,
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
