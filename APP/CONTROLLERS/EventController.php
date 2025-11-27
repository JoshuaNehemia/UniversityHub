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
        $event->setSlug($event['slug']);
        $event->setTanggal($event['tanggal']);
        $event->setKeterangan($event['keterangan']);
        $event->setJenis($event['jenis']);
        $event->setPosterExtension($event['poster_extension']);
        return $event->create($idgroup);
    }

    public function getUserEvent($username)
    {
        $list = Event::getAllUserEvent($username);
        foreach ($list as $key => $mhs) {
            $list[$key] = $mhs->getArray();
        }
        return $list;
    }

    public function getGroupEvent($groupid)
    {
        $list = Event::getAllGroupEvent($groupid);
        foreach ($list as $key => $mhs) {
            $list[$key] = $mhs->getArray();
        }
        return $list;
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
