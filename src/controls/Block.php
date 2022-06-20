<?php

namespace kilyte\controls;

use kilyte\database\Model;

class Block
{

    public function divBlock(Model $model, string $attribute, $options = [])
    {
        return new DivBlock($model, $attribute, $options);
    }

    public function cardBlock(Model $model, string $attribute, $options = [])
    {
        return new CardBlock($model, $attribute, $options);
    }
}
