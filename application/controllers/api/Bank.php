<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Bank extends RestController {

    public function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {
        $jsonPath = APPPATH . 'data/bank.json';
        if (!file_exists($jsonPath)) {
            $this->response([
                'status' => false,
                'message' => 'Data bank tidak ditemukan'
            ], RestController::HTTP_NOT_FOUND);
            return;
        }
        $json = file_get_contents($jsonPath);
        $data = json_decode($json, true);
        $this->response([
            'status' => true,
            'data' => $data
        ], RestController::HTTP_OK);
    }
}