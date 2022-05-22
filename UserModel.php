<?php

namespace kilytecorecore;

use kilytecorecore\db\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}