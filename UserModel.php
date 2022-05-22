<?php

namespace kilyte\kilytephpcore;

use kilyte\kilytephpcore\db\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}