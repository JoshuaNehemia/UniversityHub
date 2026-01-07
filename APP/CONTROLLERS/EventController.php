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
            throw new Exception("Data is incomplete need group id");
        $event = $this->mapToEvent($data);
        return $this->service->createEvent($data['idgrup'], $event);
    }

    public function getEvent($data)
    {
        $this->assertKeysExistAndNotNull($data, array("idgrup", "keyword", "limit", "page"));
        return $this->service->getGroupEvent($data['idgrup'], $data['keyword'], $data['limit'], $data['page']);
    }

    public function updateEvent($data)
    {
        if(!isset($data)) throw new Exception("Data is incomplete need event id");
        $event = $this->mapToEvent($data);
        return $this->service->updateEvent($event);
    }

    public function deleteEvent($data)
    {
        return $this->service->deleteEvent($data['id']);
    }

    private function assertKeysExistAndNotNull(
        array $data,
        array $keys,
        string $context = 'Mapper'
    ): void {
        $invalid = [];

        foreach ($keys as $key) {
            if (!array_key_exists($key, $data) || $data[$key] === null) {
                $invalid[] = $key;
            }
        }

        if ($invalid) {
            throw new Exception(
                $context . ' missing or null keys: ' . implode(', ', $invalid)
            );
        }
    }

    private function mapToEvent(array $data): Event
    {
        $this->assertKeysExistAndNotNull(
            $data,
            [
                'judul',
                'tanggal',
                'keterangan',
                'jenis',
                'poster_extention'
            ],
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
        $event->setPosterExtension($data['poster_extention']);

        return $event;
    }


}
