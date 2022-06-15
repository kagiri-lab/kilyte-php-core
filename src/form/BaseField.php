<?php

namespace kilyte\form;

use kilyte\database\Model;

abstract class BaseField
{

    public Model $model;
    public string $attribute;
    public string $type;
    public array $options = [];
    public string $visible = '';


    public function __construct(Model $model, string $attribute, $options = [])
    {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->options = $options;
    }

    public function __toString()
    {
        return sprintf(
            '<div class="col-12 %s">
                <label class="form-label">%s</label>
                %s
                <div class="invalid-feedback">
                    %s
                </div>
            </div>',
            $this->visible,
            $this->model->getLabel($this->attribute),
            $this->renderInput(),
            $this->model->getFirstError($this->attribute)
        );
    }

    abstract public function renderInput();
}
