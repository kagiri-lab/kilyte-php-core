<?php

namespace kilyte\controls;

use kilyte\database\Model;

abstract class BaseBlock
{

    public Model $model;
    public string $attribute;
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
            '<div class="col-12">
                %s
            </div>',
            $this->renderBlock()
        );
    }

    abstract public function renderBlock();
}
