<?php

namespace CONTROLLERS;

#region REQUIRE
require_once(__DIR__ . "/../MODELS/Event.php");
require_once(__DIR__ . "/../SERVICE/EventService.php");
require_once(__DIR__ . "/../config.php");
#endregion

#region USE
use MODELS\Event;
use SERVICE\EventService;
use Exception;
#endregion

class EventController
{
    #region FIELDS
    private $service;
    #endregion

    #region CONSTRUCTOR
    public function __construct()
    {
        $this->service = new EventService();
    }
    #endregion

    public function createEvent($data): bool
    {
        if (!isset($data['idgrup']))
            throw new Exception("Data incomplete need group id");

        if (isset($data['poster_file'])) {
            $file = $data['poster_file'];
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
                throw new Exception("Format file tidak didukung. Gunakan JPG, JPEG, atau PNG");
            }
            
            $data['poster_extention'] = $extension;

            $targetDir = __DIR__ . "/../DATABASE/POSTER/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $tempName = uniqid('poster_') . '.' . $extension;
            $tempPath = $targetDir . $tempName;
            
            if (!move_uploaded_file($file['tmp_name'], $tempPath)) {
                throw new Exception("Gagal menyimpan file poster");
            }
            
            $data['temp_poster_path'] = $tempPath;
            $data['temp_poster_name'] = $tempName;
        } else {
            $data['poster_extention'] = '';
        }
        
        $event = $this->mapToEvent($data);
        $result = $this->service->createEvent($data['idgrup'], $event);

        if ($result && isset($data['temp_poster_path'])) {
            $eventId = $this->service->getLastInsertedId();
            $extension = $data['poster_extention'];
            $targetDir = __DIR__ . "/../DATABASE/POSTER/";
            $finalName = $eventId . '.' . $extension;
            $finalPath = $targetDir . $finalName;
            
            rename($data['temp_poster_path'], $finalPath);
        }
        
        return $result;
    }

    public function getEvent($data)
    {
        if(!isset($data['idgrup'])) return [];
        
        $keyword = $data['keyword'] ?? '';
        $limit = $data['limit'] ?? 10;
        $page = $data['page'] ?? 0;

        return $this->service->getGroupEvent($data['idgrup'], $keyword, $limit, $page);
    }

    public function updateEvent($data)
    {
        if(!isset($data)) throw new Exception("Data is incomplete");
        $event = $this->mapToEvent($data);
        return $this->service->updateEvent($event);
    }

    public function deleteEvent($data)
    {
        return $this->service->deleteEvent($data['id']);
    }

    private function assertKeysExistAndNotNull(array $data, array $keys, string $context = 'Mapper'): void {
        $invalid = [];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data) || $data[$key] === null) {
                $invalid[] = $key;
            }
        }
        if ($invalid) {
            throw new Exception($context . ' missing or null keys: ' . implode(', ', $invalid));
        }
    }

    private function mapToEvent(array $data): Event
    {
        $this->assertKeysExistAndNotNull(
            $data,
            ['judul', 'tanggal', 'keterangan', 'jenis'],
            'Event mapper'
        );

        $event = new Event();

        if (array_key_exists('id', $data)) {
            $event->setId((int) $data['id']);
        }

        $event->setJudul($data['judul']);
        $event->setSlug();
        $event->setTanggal($data['tanggal']);
        $event->setKeterangan($data['keterangan']);
        $event->setJenis($data['jenis']);
        
        $posterExt = $data['poster_extention'] ?? ''; 
        $event->setPosterExtension($posterExt);

        return $event;
    }
}