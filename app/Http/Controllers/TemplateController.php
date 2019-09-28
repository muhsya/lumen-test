<?php

namespace App\Http\Controllers;

use App\Models\Template;

class TemplateController extends BaseController {
    public function __construct(Template $model) {
        $this->model = $model;
    }
}