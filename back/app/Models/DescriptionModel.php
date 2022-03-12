<?php

namespace App\Models;

use CodeIgniter\Model;

class DescriptionModel extends Model
{
    protected $table         = 'descriptions';
    protected $allowedFields = [
        'descriptions','medicine_id',
    ];
    protected $returnType    = \App\Entities\Description::class;
    protected $useTimestamps = true;
}