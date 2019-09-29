<?php

namespace App\Models;

use App\Models\Template;
use Illuminate\Database\Eloquent\Model;

class ItemTemplate extends Model {
    protected $fillable = [
        'template_id',
        'description',
        'urgency',
        'due_interval',
        'due_unit'
    ];

    protected $visible = ['description', 'urgency', 'due_interval', 'due_unit'];

    public function template() {
        return $this->belongsTo(Template::class, 'template_id');
    }
}