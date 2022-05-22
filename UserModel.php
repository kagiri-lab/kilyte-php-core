<?php

namespace shark\kilytephp;

use shark\kilytephp\db\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}