<?php
/**
 * 工具类
 * @author lixiuran
 */

class Tools
{

    /**
     * array_column
     * @param unknown $input
     * @param unknown $column_key
     * @param string $index_key
     * @return multitype:|multitype:unknown
     */
    public static function array_column($input, $column_key, $index_key = null)
    {
        if ($index_key !== null) {
            $keys = array();
            $i = 0;
            foreach ($input as $row) {
                if (array_key_exists($index_key, $row)) {
                    if (is_numeric($row[$index_key]) || is_bool($row[$index_key])) {
                        $i = max($i, (int) $row[$index_key] + 1);
                    }
                    $keys[] = $row[$index_key];
                } else {
                    $keys[] = $i++;
                }
            }
        }
        if ($column_key !== null) {
            $values = array();
            $i = 0;
            foreach ($input as $row) {
                if (array_key_exists($column_key, $row)) {
                    $values[] = $row[$column_key];
                    $i++;
                } elseif (isset($keys)) {
                    array_splice($keys, $i, 1);
                }
            }
        } else {
            $values = array_values($input);
        }
        if ($index_key !== null) {
            return array_combine($keys, $values);
        }
        return $values;
    }

}
