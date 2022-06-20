<?php


namespace kilyte\controls;

use kilyte\database\Model;

class CardBlock extends BaseBlock
{


    public function __construct(Model $model, string $attribute, $options)
    {
        parent::__construct($model, $attribute, $options);
    }


    public function renderBlock()
    {
        return sprintf(
            "<h1 class='card-title'>%s</h1> <p>%s</p>",
            $this->model->getLabel($this->attribute),
            $this->model->{$this->attribute}
        );
    }

    public function show()
    {
        echo $this;
    }
}
