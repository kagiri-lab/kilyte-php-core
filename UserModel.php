<?php

namespace kilyte\core;

use kilyte\core\db\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}