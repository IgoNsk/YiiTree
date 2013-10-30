<?php

return array(
  'guest' => array(
    'type' => CAuthItem::TYPE_ROLE,
    'description' => 'Guest',
    'bizRule' => null,
    'data' => null
  ),
  'moderator' => array(
    'type' => CAuthItem::TYPE_ROLE,
    'description' => 'Модератор',
    'children' => array(
        'guest',
    ),
    'bizRule' => null,
    'data' => null
  ),
  'administrator' => array(
    'type' => CAuthItem::TYPE_ROLE,
    'description' => 'Администратор',
    'children' => array(
        'moderator',         // позволим админу всё, что позволено модератору
    ),
    'bizRule' => null,
    'data' => null
  ),
);
