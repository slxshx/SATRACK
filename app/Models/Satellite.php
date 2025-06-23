<?php

namespace App\Models;

use App\Core\Model;
use DateTimeImmutable;
use App\Core\Database;
use PDO;
use PDOException;

class Satellite extends Model
{
    protected string $table;

    private int $id;
    private string $name;
    private int $norad_id;
    private DateTimeImmutable $created_at;

    public function __construct($data)
    {
        $this->table = $_ENV['DB_TABLE_SAT'] ?? 'satellites';
        Parent::__construct();

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                // Wenn 'created_at' ein String ist, dann in DateTimeImmutable umwandeln
                if ($key === 'created_at' && is_string($value)) {
                    $this->$key = new DateTimeImmutable($value);
                } else {
                    $this->$key = $value;
                }
            }
        }
    }


    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'norad_id' => $this->norad_id,
            'created_at' => $this->created_at->format('Y.m.d H:i:s')
        ];
    }

    public static function  findByName(string $name)
    {
        $table = $_ENV['DB_TABLE_SAT'] ?? 'satellites';
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM {$table} WHERE name = :name LIMIT 1");
        $stmt->execute([':name' => $name]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return $satellite = new self($data);
    }

    // Getter

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    // Setter

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
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
