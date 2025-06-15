<?php

namespace App\Models;

use App\Core\Model;
use DateTimeImmutable;

class User extends Model
{

    protected string $table;

    private int $id;
    private string $username;
    private string $email;
    private string $password_hash;
    private DateTimeImmutable $created_at;

    public function __construct($data)
    {
        $this->table = $_ENV['DB_TABLE_U'] ?? 'users';
        Parent::__construct();

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $data->$key = $value;
            }
        }
    }

    public function toArray(): array
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
            'created_at' => $this->created_at
        ];
    }

    //Getter 

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getHashedPassword()
    {
        return $this->password_hash;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    //Setter
    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
    }

    public function setCreatedAt($createdAt)
    {
        if ($createdAt instanceof DateTimeImmutable) {
            $this->created_at = $createdAt;
        } elseif (is_string($createdAt)) {
            $this->created_at = new DateTimeImmutable($createdAt);
        } else {
            throw new \InvalidArgumentException('createdAt must be a DateTimeImmutable or date string');
        }
    }

    // Verify Password

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password_hash);
    }
}
