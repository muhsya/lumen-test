<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model {
    protected $fillable = [
        'type',
        'name'
    ];

    protected $visible = ['type', 'name'];

    public function getSelfLink() {
    	return 'checklists/templates/' . $this->id;
    }
}