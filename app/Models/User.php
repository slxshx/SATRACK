<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;
use DateTimeImmutable;
use PDO;
use Exception;

class User extends Model
{

    protected string $table;

    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private DateTimeImmutable $created_at;

    public function __construct($data)
    {
        $this->table = $_ENV['DB_TABLE_U'] ?? 'users';
        parent::__construct();

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                if ($key === 'created_at' && is_string($value)) {
                    $this->created_at = new DateTimeImmutable($value);
                } else {
                    $this->$key = $value;
                }
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

    // Prüfen ob Nutzerdaten korrekt sind
    public static function findByEmail($email): ?self
    {
        $table = $_ENV['DB_TABLE_U'] ?? 'users';
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM {$table} WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $user = new self($data);

        // Sicherstellen, dass $data['password_hash'] gesetzt ist
        if (!isset($user->password)) {
            throw new Exception('Password hash ist nicht gesetzt.');
        }
        return $user;
    }

    public static function findByUsername($username): ?self
    {
        $table = $_ENV['DB_TABLE_U'] ?? 'users';
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM {$table} WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $user = new self($data);

        // Sicherstellen, dass $data['password_hash'] gesetzt ist
        if (!isset($user->password)) {
            throw new Exception('Password hash ist nicht gesetzt.');
        }
        return $user;
    }

    // Verify Password

    public function verifyPassword(string $password): bool
    {
        if (empty($this->password)) {
            throw new Exception('Password hash is empty.');
        }

        return password_verify($password, $this->password);
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
        return $this->password;
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
        $this->password = password_hash($password, PASSWORD_DEFAULT);
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
}
