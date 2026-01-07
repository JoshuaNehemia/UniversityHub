<?php

namespace SERVICE;

#region REQUIRE
require_once(__DIR__ . "/../MODELS/Event.php");
require_once(__DIR__ . "/../REPOSITORY/RepoEvent.php");
#endregion

#region USE
use MODELS\Event;
use REPOSITORY\RepoEvent;
#endregion

class EventService
{
    #region FIELDS
    private $repo;
    #endregion

    #region CONSTRUCTOR
    public function __construct()
    {
        $this->repo = new RepoEvent();
    }
    #endregion

    #region FUNCTION
    public function createEvent($group_id, $event): bool
    {
        return $this->repo->createEvent($group_id, $event);
    }
    public function getGroupEvent($group_id, $keyword, $limit, $page)
    {
        $arr =  $this->repo->findAllEventByGroupId($group_id, $keyword, $limit, $page);
        $res = [];
        foreach( $arr as $event ){
            $res[] = $event->toArray();
        }
        return $res;
    }
    public function updateEvent($event)
    {
        return $this->repo->updateEvent($event);
    }
    public function deleteEvent($event_id)
    {
        return $this->repo->delete($event_id);
    }
    #endregion
}