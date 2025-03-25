<?php
class AdminDanhMuc
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

   
}