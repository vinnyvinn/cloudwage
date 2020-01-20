<?php

namespace Payroll\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DeductionSlab extends Model
{
    protected $guarded = [];

    const MODULE_ID = 10;

    public function deduction()
    {
        return $this->belongsTo(Deduction::class);
    }

    /**
     * Save a new model and return the instance.
     *
     * @param  array $attributes
     *
     * @return static
     */
    public static function bulkCreate(array $attributes = [])
    {
        $slabs = self::createSlabs($attributes);
        $sortedSlabs = self::sortSlabs($slabs);

        return parent::insert($sortedSlabs);
    }

    private static function createSlabs($attributes)
    {
        $slabs = [];

        $currentDate = Carbon::now();

        for ($i = 1; $i <= $attributes['total_rows']; $i++) {
            $slabs [] = [
                'deduction_id' => $attributes['deduction_id'],
                'slab_number' => '1',
                'min_amount' => $attributes['min_amount' . $i],
                'max_amount' => $attributes['max_amount' . $i],
                'rate' => $attributes['rate' . $i],
                'created_at' => $currentDate,
                'updated_at' => $currentDate
            ];
        }

        return $slabs;
    }

    private static function sortSlabs($slabs)
    {
        $slabs = collect($slabs)->sortBy('min_amount');

        $number = 0;
        $newSlab = array();
        foreach ($slabs->values()->all() as $slab) {
            $number++;
            $slab['slab_number'] = $number;
            $newSlab [] = $slab;
        }

        return $newSlab;
    }
}
