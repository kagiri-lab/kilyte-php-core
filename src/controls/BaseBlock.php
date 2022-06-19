<?php

namespace kilyte\controls;

use kilyte\database\Model;

abstract class BaseBlock
{

    public Model $model;
    public string $attribute;
    public array $options = [];
    public string $visible = '';
    public int $size = 12;


    public function __construct(Model $model, string $attribute, $options = [])
    {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->options = $options;
    }

    public function __toString()
    {
        if (isset($this->options['size']))
            $this->size = $this->options['size'];
        return sprintf(
            "<div class='col-$this->size'>
                %s
            </div>",
            $this->renderBlock()
        );
    }

    abstract public function renderBlock();
}
