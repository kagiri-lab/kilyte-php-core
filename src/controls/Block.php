<?php

namespace kilyte\controls;

use kilyte\database\Model;

class Block
{

    public function block1(Model $model, string $attribute, $options = [])
    {
        return new Block1($model, $attribute, $options);
    }
}
