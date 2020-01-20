<?php
/**
 * @category PHP
 * @author   David Mjomba <smodavprivate@gmail.com>
 */

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class KRAP10B extends Model
{
    const PERMISSIONS = [
        'Create'    => 'krap10b.create',
        'Read'      => 'krap10b.read',
        'Update'    => 'krap10b.update',
        'Delete'    => 'krap10b.delete'
    ];
}
