<?php
/**
 * @category PHP
 * @author   David Mjomba <smodavprivate@gmail.com>
 */

namespace Payroll\Models;

use Illuminate\Database\Eloquent\Model;

class Coinage extends Model
{
    const PERMISSIONS = [
        'Create'    => 'coinage.create',
        'Read'      => 'coinage.read',
        'Update'    => 'coinage.update',
        'Delete'    => 'coinage.delete'
    ];
}
