<?php

namespace App\Models;

use App\Models\ItemTemplate;
use App\Models\ChecklistTemplate;
use Illuminate\Database\Eloquent\Model;

class Template extends Model {
    protected $fillable = [
        'type',
        'name'
    ];

    protected $visible = ['type', 'name'];

    public function checklist(){
        return $this->hasOne(ChecklistTemplate::class, 'template_id');
    }

    public function items(){
        return $this->hasMany(ItemTemplate::class, 'template_id');
    }

    public function getSelfLink() {
    	return 'checklists/templates/' . $this->id;
    }
}