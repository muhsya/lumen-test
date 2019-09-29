<?php

namespace App\Models;

use App\Models\Template;
use Illuminate\Database\Eloquent\Model;

class ChecklistTemplate extends Model {
    protected $fillable = [
        'template_id',
        'description',
        'due_interval',
        'due_unit'
    ];

    protected $visible = ['description', 'due_interval', 'due_unit'];

    public function template() {
        return $this->belongsTo(Template::class, 'template_id');
    }
}