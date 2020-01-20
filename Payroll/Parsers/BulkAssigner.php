<?php

namespace Payroll\Parsers;

use BadMethodCallException;
use Cartalyst\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Class BulkAssigner
 *
 *
 * Example:
 *  $employees->each(function ($item, $key) use ($employees) {
 *        $employees[$key] = collect($item)->only([
 *           'id', 'payroll_number', 'first_name', 'last_name', 'identification_number'
 *      ]);
 *  });
 *
 *  return $assigner->withRows($employees)
 *      ->withRequiredFields($requiredFields)
 *      ->withAssignTo($allowanceId)
 *      ->withFormAction(route('employee-allowances.store'))
 *      ->getForm();
 *
 * @category PHP
 * @package  Payroll\Parsers
 * @author   David Mjomba <smodavprivate@gmail.com>
 */

class BulkAssigner
{
    private $rows;

    private $createView = 'smodav.bulk.create';

    private $showView = 'smodav.bulk.show';

    private $formAction;

    private $requiredFields = array();

    private $assignTo;

    private $columns;


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

        throw new BadMethodCallException("Method [$method] does not exist on view.");
    }
    
    /**
     * Add a piece of data to the view.
     *
     * @param  string|array $key
     * @param  mixed        $value
     *
     * @return $this
     */
    public function with($key, $value = null)
    {
        $set = 'set' . $key;
        $this->$set($value);

        return $this;
    }

    /**
     * @param Collection $rows
     *
     * @return $this
     *
     */
    public function setRows($rows)
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * @param string $createView
     *
     * @return $this
     */
    public function setCreateView($createView)
    {
        $this->createView = $createView;

        return $this;
    }

    /**
     * @param string $showView
     *
     * @return $this
     */
    public function setShowView($showView)
    {
        $this->showView = $showView;

        return $this;
    }

    /**
     * @param mixed $formAction
     *
     * @return $this
     */
    public function setFormAction($formAction)
    {
        $this->formAction = $formAction;

        return $this;
    }

    /**
     * @param array $requiredFields
     *
     * @return $this
     */
    public function setRequiredFields($requiredFields)
    {
        $this->requiredFields = $requiredFields;

        return $this;
    }

    /**
     * @param int $assignTo
     *
     * @return $this
     */
    public function setAssignTo($assignTo)
    {
        $this->assignTo = $assignTo;

        return $this;
    }

    /**
     * @param mixed $columns
     *
     * @return $this
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Get the available fields from the database table
     *
     * @return $this
     */
    public function getColumns()
    {
        if (! is_null($this->columns)) {
            return collect($this->columns);
        }

        return $this->columns = collect($this->rows->first())
            ->except(['created_at', 'updated_at', 'deleted_at', 'id'])
            ->keys();
    }

    public function getForm($allowCopy = false)
    {
        return view($this->createView)
            ->with('formAction', $this->formAction)
            ->withColumns($this->getColumns())
            ->withRows($this->rows)
            ->with('requiredFields', $this->requiredFields)
            ->with('assignToId', $this->assignTo)
            ->with('allowCopy', $allowCopy);
    }
}
