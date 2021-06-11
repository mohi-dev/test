<?php

namespace App\Traits;

trait JsonData
{

    public function initializeJsonData()
    {
        foreach ($this->getJsonDataAttributes() as $param) {
            $this->casts[$param] = 'array';
        }
    }

    public function getJsonDataAttributes()
    {
        return isset($this->json_data) ? $this->json_data : [];
    }

    /**
     * @param $key
     * @return mixed|JsonDataAttribute
     */
    public function __get($key)
    {
        if (in_array($key, $this->getJsonDataAttributes())) {
            return JsonDataAttribute::createForModel($this, $key);
        }

        return parent::__get($key);
    }
}
