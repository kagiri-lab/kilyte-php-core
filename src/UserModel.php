<?php

namespace kilyte;

use kilyte\db\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}