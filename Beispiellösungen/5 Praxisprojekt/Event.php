<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mapping\EventsTable_V1 as Table;
use Illuminate\Support\Collection;

class Event extends Model
{
    use HasFactory;

    const ID = Table::COLUMN_ID;
    const ENTITY = Table::COLUMN_ENTITY;
    const RECORD_ID = Table::COLUMN_RECORD_ID;
    const PARENT_ID = Table::COLUMN_PARENT_ID;
    const ACTION_ID = Table::COLUMN_ACTION_ID;
    const ACTION_TYPE = Table::COLUMN_ACTION_TYPE;
    const DATA = Table::COLUMN_DATA;

    public $timestamps = false;

    protected $table = Table::TABLE_NAME;

    protected $fillable = [self::ENTITY, self::RECORD_ID, self::PARENT_ID, self::ACTION_ID, self::ACTION_TYPE, self::DATA];

    public static function getEntityEvents(Model $entity, int $actionId = -1, $operator = "=") {
        $result = self::where(self::ENTITY, self::getEntityName(get_class($entity)))->where(self::RECORD_ID, $entity->id);

        if ($actionId >= 0)
            $result = $result->where(self::ACTION_ID, $operator, $actionId);

        return $result->get();
    }

    public static function getListEvents(string $typeName, int $parentId, int $actionId = -1, $operator = "=") {
        $result = self::where(self::ENTITY, self::getEntityName($typeName))->where(self::PARENT_ID, $parentId);

        if ($actionId >= 0)
            $result = $result->where(self::ACTION_ID, $operator, $actionId);

        return $result->get();
    }

    public static function getEntityWithState(Model $entity, int $actionId) {
        $result = self::getEntityEvents($entity, $actionId, "<=")->reduce(
            function($agg, $item) {
                $data = json_decode($item->data);

                return self::applyEventData($agg, $data);
            }, clone($entity));

        return $result;
    }

    public static function getListWithState(string $typeName, Collection $list, int $parentId, int $actionId) {
        $result = clone($list);
        self::getListEvents($typeName, $parentId, $actionId, "<=")->map(function($event) use($typeName, $result) {
            $data = json_decode($event->data);

            switch($event->action_type) {
                case EventActionType::Added->value:
                    $item = new $typeName();

                    $result->push(self::applyEventData($item, $data));

                    break;
                case EventActionType::Updated->value:
                    $item = $result->find($event->record_id);

                    self::applyEventData($item, $data);

                    break;
                case EventActionType::Deleted->value:
                    $index = $result->search(function($item) use($event) {
                        return $item->id == $event->record_id;
                    });

                    $result->pull($index);

                    break;
            }
        });

        return $result;
    }

    public static function applyEventData($entity, $data) {
        if (!empty($data)) {
            // Set original and current values to enable proper change tracking when entity is updated later
            foreach (get_object_vars($data) as $key => $value) {
                $entity->original[$key] = $value;
                $entity->$key = $value;
            }
        }

        return $entity;
    }

    public static function addListEvent(string $typeName, int $parentId, int $actionId, Collection $collection, Collection|Array $current) {
        if (is_array($current))
            $current = collect($current);

        $createdEntities = $current->diff($collection);
        $createdEntities->map(function($el) use($typeName, $parentId, $actionId) {
                self::addCreateEvent($typeName, $el->id, $actionId, $el, $parentId);
            });

        $deletedEntities = $collection->diff($current);
        $deletedEntities->map(function($el) use($typeName, $parentId, $actionId) {
                self::addDeleteEvent($typeName, $el->id, $actionId, $el, $parentId);
            });

        $updatedEntities = $collection->intersect($current)->filter(function($el) { return $el->isDirty(); });
        $updatedEntities->map(function($el) use($typeName, $parentId, $actionId) {
                self::addUpdateEvent($typeName, $el->id, $actionId, $el, $el->getDirty(), $parentId);
             });

        return $createdEntities->count() + $deletedEntities->count() + $updatedEntities->count() > 0;
    }

    public static function addCreateEvent(string $typeName, int $recordId, int $actionId, array|object $data, int $parentId = null) {
        if ($data instanceof Model || $data instanceof Collection)
            $data = $data->toArray();

        self::create([
            Event::ENTITY => self::getEntityName($typeName),
            Event::RECORD_ID => $recordId,
            Event::PARENT_ID => $parentId,
            Event::ACTION_ID => $actionId,
            Event::ACTION_TYPE => EventActionType::Added->value,
            Event::DATA => json_encode($data)
        ]);

        return true;
    }

    public static function addUpdateEvent(string $typeName, int $recordId, int $actionId, Model $entity, array $updates, int $parentId = null) {
        // Apply updates to support change tracking
        foreach ($entity->getAttributes() as $key => $value) {
            $entity->$key = array_key_exists($key, $updates) ? $updates[$key] : $value;
        }

        if ($entity->isDirty()) {
            self::create([
                Event::ENTITY => self::getEntityName($typeName),
                Event::RECORD_ID => $recordId,
                Event::PARENT_ID => $parentId,
                Event::ACTION_ID => $actionId,
                Event::ACTION_TYPE => EventActionType::Updated->value,
                Event::DATA => json_encode($entity->getDirty())
            ]);

            return true;
        }

        return false;
    }

    public static function addDeleteEvent(string $typeName, int $recordId, int $actionId, array|object $data, int $parentId = null) {
        if ($data instanceof Model || $data instanceof Collection)
            $data = $data->toArray();

        self::create([
            Event::ENTITY => self::getEntityName($typeName),
            Event::RECORD_ID => $recordId,
            Event::PARENT_ID => $parentId,
            Event::ACTION_ID => $actionId,
            Event::ACTION_TYPE => EventActionType::Deleted->value,
            Event::DATA => json_encode($data)
        ]);

        return true;
    }

    private static function getEntityName(string $typeName) {
        return substr($typeName, strrpos($typeName, '\\') + 1);
    }

    private static function getTableName(string $typename) {
        return self::getEntityName($typename) . "s";
    }
}
