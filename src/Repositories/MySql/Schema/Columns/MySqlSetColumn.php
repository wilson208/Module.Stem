<?php

/*
 *	Copyright 2015 RhubarbPHP
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

namespace Rhubarb\Stem\Repositories\MySql\Schema\Columns;

require_once __DIR__ . "/MySqlEnumColumn.php";

class MySqlSetColumn extends MySqlEnumColumn
{
    public $possibleValues = [];

    public function getPhpType()
    {
        return "string[]";
    }

    public function __construct($columnName, $defaultValue, $possibleValues)
    {
        parent::__construct($columnName, $defaultValue, $possibleValues);

        $this->possibleValues = $possibleValues;
    }

    public function getDefinition()
    {
        $possibleString = "'" . implode("','", $this->possibleValues) . "'";

        return "`" . $this->columnName . "` set(" . $possibleString . ") " . $this->getDefaultDefinition();
    }

    public function getTransformIntoRepository()
    {
        return function ($data) {
            if (is_array($data[$this->columnName])) {
                array_walk($data[$this->columnName], function(&$value, $index) {
                    str_replace(',', '-', $value);
                });

                return implode(',', $data[$this->columnName]);
            }

            return $data[$this->columnName];
        };
    }

    public function getTransformFromRepository()
    {
        return function ($data) {
            if (isset($data[$this->columnName])) {
                return explode(',', $data[$this->columnName]);
            }
            return [];
        };
    }
}
