<?php

namespace Kfirba;

class QueryGenerator
{
    /**
     * Generates a QueryObject with the SQL query and the bindings.
     *
     * @param       $table
     * @param       $rows
     * @param array $exclude
     *
     * @return QueryObject
     */
    public function generate($table, $rows, array $exclude = [])
    {
        $columns = array_keys($rows[0]);
        $columnsString = implode('`,`', $columns);
        $values = $this->buildSQLValuesStringFrom($rows);
        $updates = $this->buildSQLUpdatesStringFrom($columns, $exclude);

        $query = vsprintf('insert into `%s` (`%s`) values %s on duplicate key update %s', [
            $table, $columnsString, $values, $updates,
        ]);

        return new QueryObject($query, $this->extractBindingsFrom($rows));
    }

    /**
     * Build the SQL "values()" string.
     *
     * @param $rows
     *
     * @return string
     */
    protected function buildSQLValuesStringFrom($rows)
    {
        return rtrim(array_reduce($rows, function ($values, $row) {
            return $values . '(' . rtrim(str_repeat('?,', count($row)), ',') . '),';
        }, ''), ',');
    }

    /**
     * Build the SQL "on duplicate key update" string.
     *
     * @param $rows
     * @param $exclude
     *
     * @return string
     */
    protected function buildSQLUpdatesStringFrom($rows, $exclude)
    {
        return trim(array_reduce(array_filter($rows, function ($column) use ($exclude) {
            return ! in_array($column, $exclude);
        }), function ($updates, $column) {
            return $updates . "`{$column}`=VALUES(`{$column}`),";
        }, ''), ',');
    }

    /**
     * Flatten the given array one level deep to extract the bindings.
     *
     * @param $rows
     *
     * @return mixed
     */
    protected function extractBindingsFrom($rows)
    {
        return array_reduce($rows, function ($result, $item) {
            return array_merge($result, array_values($item));
        }, []);
    }
}
