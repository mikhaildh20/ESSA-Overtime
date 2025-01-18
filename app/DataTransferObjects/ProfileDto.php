<?php

namespace App\DataTransferObjects;

class ProfileDto
{
    public $full_name;
    public $role;
    public $email;
    public $level;
    public $username;

    public function __construct($full_name, $role, $email, $level, $username)
    {
        $this->full_name = $full_name;
        $this->role = $role;
        $this->email = $email;
        $this->level = $level;
        $this->username = $username;
    }
}