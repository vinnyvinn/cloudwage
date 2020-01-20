<?php

namespace Payroll\Parsers;

use BadMethodCallException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class ModelFilter
 *
 * @category PHP
 * @package  Payroll\Parsers
 * @author   David Mjomba <smodavprivate@gmail.com>
 */
class ModelFilter
{

    /**
     * @var Model
     */
    private $filterModel;

    /**
     * @var string
     */
    private $filterKey;

    /**
     * @var Model
     */
    private $filterUsing;

    /**
     * @var string
     */
    private $filterColumn;

    /**
     * @var array
     */
    private $filters = array();

    /**
     * Set the model to be filtered
     *
     * @param Model $model
     *
     * @return $this
     */
    public function filter($model)
    {
        $this->filterModel = $model;

        return $this;
    }

    /**
     * Set the model to be used to filter the other model
     *
     * @param Model $model
     *
     * @return $this
     */
    public function by($model)
    {
        $this->filterUsing = $model;

        return $this;
    }

    /**
     * Set the key to be used as the comparator in the model to be filtered
     *
     * @param $key
     *
     * @return $this
     */
    public function usingKey($key)
    {
        $this->filterKey = $key;

        return $this;
    }

    /**
     * Set the column to be used to filter the model
     *
     * @param $column
     *
     * @return $this
     */
    public function usingColumn($column)
    {
        $this->filterColumn = $column;

        return $this;
    }

    /**
     * Get the final filtered records
     *
     * @return mixed
     */
    public function get()
    {
        $toFilter = collect($this->filterModel->all());
        $filterKey = $this->filterKey;

        $filter = $this->getFilterRecords();
        $filtered = $toFilter->reject(function ($value, $key) use ($filter, $filterKey) {
                return in_array($value->$filterKey, $filter);
        });

        return $filtered;
    }

    /**
     * Get the existing records that need to be filtered
     *
     * @return mixed
     */
    private function getFilterRecords()
    {
        $filterBy = $this->filterUsing;
        $filterColumn = $this->filterColumn;
        foreach ($this->filters as $filter) {
            $filterBy = $filterBy->where($filter['attribute'], $filter['value']);
        }

        $filter = $filterBy->get([$filterColumn]);
        $filter->each(function ($item, $key) use ($filter, $filterColumn) {
                $filter[$key] = $item->$filterColumn;
        });

        $filter = $filter->toArray();

        return $filter;
    }

    /**
     * Dynamically bind parameters to the object.
     *
     * @param $method
     * @param $parameters
     *
     * @return DocumentGenerator
     */
    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'with')) {
            return $this->with(substr($method, 4), $parameters[0]);
        }

        if (Str::startsWith($method, 'where')) {
            return $this->setFilters(snake_case(substr($method, 5)), $parameters[0]);
        }

        throw new BadMethodCallException("Method [$method] does not exist on view.");
    }

    /**
     * Set the filters to be used on the $filterUsing Model
     *
     * @param $filterAttribute
     * @param $filterValue
     *
     * @return $this
     * @internal param array $filters
     *
     */
    public function setFilters($filterAttribute, $filterValue)
    {
        $this->filters [] = [
            'attribute' => $filterAttribute,
            'value' => $filterValue
        ];

        return $this;
    }


}
