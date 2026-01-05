<?php

namespace CONTROLLERS;

require_once(__DIR__ . "/../MODELS/Event.php");
require_once(__DIR__ . "/../config.php");

use MODELS\Event;

class EventController
{
    public function __construct() {}

    public function createEvent(array $arr_event,$idgroup)
    {
        $event = new Event();
        $event->setJudul($arr_event['judul']);
        $event->setSlug();
        $event->setTanggal($arr_event['tanggal']);
        $event->setKeterangan($arr_event['keterangan']);
        $event->setJenis($arr_event['jenis']);
        $event->setPosterExtension($arr_event['poster_extention']);
        return $event->create($idgroup)->toArray();
    }

    public function getGroupEvent($groupid,$keyword,int $limit,int $offset)
    {
        $list = Event::getAllGroupEvent($groupid,$keyword,$limit,$offset);
        foreach ($list as $key => $mhs) {
            $list[$key] = $mhs->toArray();
        }
        return $list;
    }

    public function getEventById($eventid){
        return Event::getEvent($eventid)->toArray();
    }

    public function updateEvent(array $arr_event)
    {
        $event = new Event();
        $event->setId($arr_event['idevent']);
        $event->setJudul($arr_event['judul']);
        $event->setSlug();
        $event->setTanggal($arr_event['tanggal']);
        $event->setKeterangan($arr_event['keterangan']);
        $event->setJenis($arr_event['jenis']);
        $event->setPosterExtension($arr_event['poster_extention']);
        return $event->update();
    }

    public function deleteEvent($idevent)
    {
        $event = new Event();
        $event->setId($idevent);
        return $event->delete();
    }
}
