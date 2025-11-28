<?php

namespace CONTROLLERS;

require_once(__DIR__ . "/../MODELS/Event.php");
require_once(__DIR__ . "/../config.php");

use MODELS\Event;

class EventController
{
    public function __construct() {}

    public function createEvent(array $event,$idgroup)
    {
        $event = new Event();
        $event->setId($event['id']);
        $event->setJudul($event['judul']);
        $event->setSlug();
        $event->setTanggal($event['tanggal']);
        $event->setKeterangan($event['keterangan']);
        $event->setJenis($event['jenis']);
        $event->setPosterExtension($event['poster_extension']);
        return $event->create($idgroup)->getArray();
    }

    public function getGroupEvent($groupid,$keyword,$limit,$offset)
    {
        $list = Event::getAllGroupEvent($groupid,$keyword,$limit,$offset);
        foreach ($list as $key => $mhs) {
            $list[$key] = $mhs->getArray();
        }
        return $list;
    }

    public function getEventById($eventid){
        return Event::getEvent($eventid)->getArray();
    }

    public function updateEvent(array $event)
    {
        $event = new Event();
        $event->setId($event['id']);
        $event->setJudul($event['judul']);
        $event->setSlug($event['slug']);
        $event->setTanggal($event['tanggal']);
        $event->setKeterangan($event['keterangan']);
        $event->setJenis($event['jenis']);
        $event->setPosterExtension($event['poster_extension']);
        return $event->update();
    }

    public function deleteEvent($idevent)
    {
        $event = new Event();
        $event->setId($idevent);
        return $event->delete();
    }
}
