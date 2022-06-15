<?php

namespace kilyte\model;

use kilyte\database\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}