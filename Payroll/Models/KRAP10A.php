<?php
/**
 * @category PHP
 * @author   David Mjomba <smodavprivate@gmail.com>
 */

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class KRAP10A extends Model
{
    const PERMISSIONS = [
        'Create'    => 'krap10a.create',
        'Read'      => 'krap10a.read',
        'Update'    => 'krap10a.update',
        'Delete'    => 'krap10a.delete'
    ];
}
