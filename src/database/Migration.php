<?php

namespace kilyte\database;

use kilyte\Application;
use kilyte\Controller;

class Migration extends Controller
{

    public function apply()
    {
        Application::$app->db->applyMigrations();
    }
}
