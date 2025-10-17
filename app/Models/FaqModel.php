<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqModel extends Model
{
    use HasFactory;

    protected $table = 'm_faq';
    protected $primaryKey = 'faq_id';

    protected $fillable = [
        'pertanyaan',
        'jawaban',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
