<?php

namespace kilyte\kilytephp;

use kilyte\kilytephp\db\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}