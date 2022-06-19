<?php


namespace kilyte\controls;

use kilyte\database\Model;

class Block1 extends BaseBlock
{


    public function __construct(Model $model, string $attribute, $options)
    {
        parent::__construct($model, $attribute, $options);
    }


    public function renderBlock()
    {
        return sprintf(
            '<div class="col-12 label">%s</div>
            <div class="col-12">%s</div>
            ',
            $this->model->getLabel($this->attribute),
            $this->model->{$this->attribute}
        );
    }

    public function show()
    {
        echo $this;
    }
}
