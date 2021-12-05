<?php

namespace App\Model;

class EntityModel
{
    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    protected function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            $method = "set" . $this->dashesToCamelCase($key);
            if (is_callable([$this, $method])) {
                $this->$method($value);
            }
        }
    }

    private function dashesToCamelCase($string, $capitalizeFirstCharacter = true)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }
}
