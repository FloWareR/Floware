<?php

namespace App\Controllers;

use App\Models\OneTimeCode;
use App\Controllers\Helper;
use Exception;


class OneTimeCodeController extends Controller{

    private $model;


    public function __construct() {
        $this->model = new OneTimeCode();
        parent::__construct($this->model, 'OneTimeCode');
    }


    public function get($data) {
      $response = $this->model->read($data);
      return $response;
    }

    public function create($data) {
      $data['code'] = Helper::generateSignupCode(2);
      $response = $this->model->create($data);
      return $response;
    }

    public function update($data) {
      $response = $this->model->update($data);
    }

} 