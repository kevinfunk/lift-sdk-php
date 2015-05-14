<?php
/**
 * @file
 * Creates an array of Entities and its conversion to Json.
 */

namespace Acquia\ContentServicesClient;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer;

class Entities extends \ArrayObject {
    /**
     * @param \Acquia\ContentServicesClient\Entity[] $array
     */
    public function __construct(array $array = [])
    {
        $array += [
            'entities' => []
        ];
        parent::__construct($array);
    }

    /**
     * Adds an entity.
     *
     * It overwrites the entity, if it has the same UUID.
     *
     * @param \Acquia\ContentServicesClient\Entity    $new_entity
     * @return \Acquia\ContentServicesClient\Entities $this
     */
    public function addEntity(Entity $new_entity)
    {
        foreach ($this['entities'] as $key => $entity) {
            if ($entity->getUuid() == $new_entity->getUuid()) {
                unset($this['entities'][$key]);
            }
        }
        $this['entities'][] = $new_entity;
    }

    /**
     * Gets an Entity, given the UUID.
     *
     * @param string $uuid
     *
     * @return \Acquia\ContentServicesClient\Entity|bool
     */
    public function getEntity($uuid)
    {
        foreach ($this['entities'] as $entity) {
            if ($entity->getUuid() == $uuid) {
                return $entity;
            }
        }
        return FALSE;
    }

    /**
     * Removes an Entity from the list, given the UUID.
     *
     * @param $uuid
     * @return \Acquia\ContentServicesClient\Entities $this
     */
    public function removeEntity($uuid)
    {
        $entities = $this->getEntities();
        foreach ($entities as $key => $entity) {
            if ($entity->getUuid() == $uuid) {
                unset($entities[$key]);
                $this->setEntities($entities);
                continue;
            }
        }
        return $this;
    }

    /**
     * Returns the list of Entities.
     *
     * @return \Acquia\ContentServicesClient\Entity[]
     */
    public function getEntities() {
        return $this['entities'];
    }

    /**
     * Bulk setting of Entities
     *
     * @param \Acquia\ContentServicesClient\Entity[]  $entities
     * @return \Acquia\ContentServicesClient\Entities $this
     */
    public function setEntities($entities = [])
    {
        if (is_array($entities)) {
            $this['entities'] = $entities;
        }
        return $this;
    }

    /**
     * Returns the json representation of the current object.
     *
     * @return string
     */
    public function json()
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new CustomNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        return $serializer->serialize($this, 'json');
    }
}