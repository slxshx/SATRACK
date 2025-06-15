<?php

namespace App\Models;

use App\Core\Model;
use DateTimeImmutable;

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
                $this->$key = $value;
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
