<?php

namespace App\Models;

use App\Core\Model;
use DateTimeImmutable;

class ApiRequest extends Model
{

    // Child-Tabelle
    protected string $table;

    //Propertys
    private int $id;
    private int $satellite_id;
    private int $user_id;
    private float $latitude;
    private float $longitude;
    private float $altitude_km;
    private float $velocity_kmh;
    private int $timestamp;
    private string $raw_response;
    private DateTimeImmutable $created_at;

    public function __construct(array $data = [])
    {
        $this->table = $_ENV['DB_TABLE_API'] ?? 'api_requests';
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
            'satellite_id' => $this->satellite_id,
            'user_id' => $this->user_id,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'altitude_km' => $this->altitude_km,
            'velocity_kmh' => $this->velocity_kmh,
            'timestamp' => $this->timestamp,
            'raw_response' => $this->raw_response,
            'created_at' => $this->created_at->format('Y.m.d H:i:s')
        ];
    }

    // Getter
    public function getId()
    {
        return $this->id;
    }

    public function getSatelliteId()
    {
        return $this->satellite_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getAltitudeKm()
    {
        return $this->altitude_km;
    }

    public function getVelocityKmh()
    {
        return $this->velocity_kmh;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function getRawResponse()
    {
        return $this->raw_response;
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

    public function setSatelliteId($id)
    {
        $this->satellite_id = $id;
    }

    public function setUserId($id)
    {
        $this->user_id = $id;
    }

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    public function setAltitudeKm($altitude)
    {
        $this->altitude_km = $altitude;
    }

    public function setVelocityKmh($velocity)
    {
        $this->velocity_kmh = $velocity;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function setRawResponse($rawResponse)
    {
        $this->raw_response = $rawResponse;
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
