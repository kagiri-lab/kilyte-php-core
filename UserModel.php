<?php

namespace kilytecore;

use kilytecore\db\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}