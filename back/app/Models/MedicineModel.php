<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicineModel extends Model
{
    protected $table         = 'medicine';
    protected $allowedFields = [
        'name',
    ];
    protected $returnType    = \App\Entities\Medicine::class;
    protected $useTimestamps = true;
}