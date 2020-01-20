<?php

namespace Payroll\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Schema;

/**
 * Class PaymentMethod
 *
 * @category PHP
 * @package  Payroll\Models
 * @author   David Mjomba <smodavprivate@gmail.com>
 */
class PaymentMethod extends Model
{
    const MODULE_ID = 27;
    
    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'has_udf', 'coinage'];


    const PERMISSIONS = [
        'Create'    => 'payment_method.create',
        'Read'      => 'payment_method.read',
        'Update'    => 'payment_method.update',
        'Delete'    => 'payment_method.delete'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function udfs()
    {
        return $this->morphMany(UDF::class, 'udfable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employees()
    {
        return $this->hasMany(EmployeePaymentMethods::class);
    }

    /**
     * Add a new payment method
     *
     * @param $paymentData
     *
     * @return $this
     */
    public function addPaymentMethod($paymentData)
    {
        $paymentMethod = $this->create(
            [
                'name' => $paymentData['name'],
                'description' => $paymentData['description'],
                'has_udf' => true,
                'coinage' => $paymentData['coinage']
            ]
        );

        $this->createPaymentUDFs($paymentData, $paymentMethod);

        return $this;
    }

    /**
     * Create the user defined fields and modify the Employee Payment Methods table
     *
     * @param $paymentData
     * @param $paymentMethod
     *
     * @return $this
     */
    private function createPaymentUDFs($paymentData, $paymentMethod)
    {
        $fields = array();

        $now = Carbon::now();

        $rowNumber = $paymentData['total_rows'];
        for ($i = 1; $i <= $rowNumber; $i++) {
            $slug = makeSlug($paymentData['field_name' . $i], 'field_name', new UDF());
            $fields [] = [
                'udfable_id' => $paymentMethod->id,
                'udfable_type' => PaymentMethod::class,
                'field_title' => $paymentData['field_name' . $i],
                'field_name' => $slug,
                'created_at' => $now,
                'updated_at' => $now
            ];
            $paymentData['field_name_slug' . $i] = $slug;
        }

        $this->insertNewColumns($paymentData, $rowNumber);

        UDF::insert($fields);

        return $this;
    }

    /**
     * Delete payment method with its udfs and remove the added columns in employee_payment_methods table
     *
     * @param $methodId
     *
     * @return bool
     */
    public function dropPaymentMethod($methodId)
    {
        $method = $this->findOrFail($methodId);
        if (count($method->employees) > 0) {
            return false;
        }
        $udfs = $method->udfs;
        $columns = array();
        foreach ($udfs as $udf) {
            $columns [] = $udf->field_name;
        }
        EmployeePaymentMethods::dropColumns($columns);
        $method->udfs()->delete();
        $method->delete();

        return true;
    }

    /**
     * Add new column to existing employee_payment_methods table
     *
     * @param $paymentData
     * @param $rowNumber
     */
    private function insertNewColumns($paymentData, $rowNumber)
    {
        Schema::table('employee_payment_methods', function ($table) use ($rowNumber, $paymentData) {
            for ($i = 1; $i <= $rowNumber; $i++) {
                $dataType = $paymentData['field_type' . $i] == 'integer' ? 'bigInteger' : $paymentData['field_type' . $i];
                $default = $paymentData['field_default' . $i];
                if ($default == '') {
                    $table->$dataType($paymentData['field_name_slug' . $i])
                        ->nullable();
                } else {
                    $table->$dataType($paymentData['field_name_slug' . $i])
                        ->nullable()->defaul($default);
                }
            }
        });
    }
}
