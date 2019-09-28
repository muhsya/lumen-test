<?php

namespace App\Http\Controllers;

use App\Models\Checklist;

class ChecklistController extends BaseController {
    public function __construct(Checklist $model) {
        $this->model = $model;
    }
}