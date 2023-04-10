<?php


namespace kilyte\form;

use kilyte\database\Model;
use kilyte\form\InputField;

class Form
{
    public static function begin($action, $method, $options = [])
    {
        $attributes = [];
        foreach ($options as $key => $value) {
            $attributes[] = "$key=\"$value\"";
        }
        echo sprintf('<form action="%s" method="%s" %s>', $action, $method, implode(" ", $attributes));
        return new Form();
    }

    public static function end()
    {
        echo '</form>';
    }

    public function field(Model $model, $attribute, $options = [])
    {
        return new InputField($model, $attribute, $options);
    }

    public function textAreaField(Model $model, $attribute, $options = [])
    {
        return new TextareaField($model, $attribute, $options);
    }

    public function tinymceTextareaField(Model $model, $attribute, $options = [])
    {
        return new TinymceTextareaField($model, $attribute, $options);
    }
}
