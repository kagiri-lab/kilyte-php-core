<?php

namespace kilyte\database;

use kilyte\Application;
use kilyte\Controller;
use app\models\ModelController;

class Migration extends Controller
{

    public function apply()
    {
        Application::$app->db->applyMigrations();
    }

    public function generate()
    {
        $models = ModelController::generateMigrate();
        if ($models) {
            foreach ($models as $mds => $md) {
                $table_name = $md->tableName();
                $columns = "";
                foreach ($md as $ms => $m) {
                    if ($ms != "errors") {
                        if (empty($columns)) {
                            if ($ms == "id")
                                $columns = self::createID();
                            else
                                $columns = self::createString($ms);
                        } else {
                            $columntype = $md->columnType();
                            if (isset($columntype[$ms]))
                                $columns .= self::createText($ms);
                            else
                                $columns .= self::createString($ms);
                        }
                    }
                }

                if ($columns) {
                    $model = self::model_template();
                    $model = str_replace("{{table_name}}", $table_name, $model);
                    $model = str_replace("{{table_columns}}", $columns, $model);
                    $dir = Application::$ROOT_DIR . "/migrations";
                    file_put_contents("$dir/m_$table_name.php", $model);
                }
            }
        }
    }

    public static function createString($column)
    {
        return "$column VARCHAR(255) NOT NULL,";
    }

    public static function createText($column)
    {
        return "$column text,";
    }

    public static function createID()
    {
        return "id INT AUTO_INCREMENT PRIMARY KEY,";
    }

    public static function model_template()
    {
        $model = "<?php ";
        $model .= 'use kilyte\Application; ';
        $model .= 'class m_{{table_name}} { ';
        $model .= 'public function up() ';
        $model .= '{';
        $model .= '$db = Application::$app->db; ';
        $model .= '$SQL = "CREATE TABLE {{table_name}} ( ';
        $model .= '{{table_columns}} ';
        $model .= 'updated_at VARCHAR(255), ';
        $model .= 'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ';
        $model .= ' )  ENGINE=INNODB;"; ';
        $model .= ' $db->pdo->exec($SQL); ';
        $model .= '} ';
        $model .= 'public function down() ';
        $model .= '{ ';
        $model .= '$db = Application::$app->db; ';
        $model .= '$SQL = "DROP TABLE {{table_name}};"; ';
        $model .= '$db->pdo->exec($SQL); ';
        $model .= '} ';
        $model .= '}';
        return $model;
    }
}
