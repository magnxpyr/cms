<?php 
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Tools\Builder\Mvc\Model\Migration;

class CategoryMigration_101 extends Migration
{
    public function up()
    {
        $this->morphTable(
            'category',
            [
                'columns' => [
                    new Column('id', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'unsigned' => true,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'first' => true
                    ]),
                    new Column('title', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'id'
                    ]),
                    new Column('alias', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'title'
                    ]),
                    new Column('description', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'alias'
                    ]),
                    new Column('metadata', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 2048,
                        'after' => 'description'
                    ]),
                    new Column('hits', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 11,
                        'default' => 0,
                        'after' => 'metadata'
                    ]),
                    new Column('status', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'hits'
                    ]),
                    new Column('view_level', [
                        'type' => 'TINYINT',
                        'notNull' => true,
                        'unsigned' => true,
                        'size' => 2,
                        'after' => 'status'
                    ]),
                    new Column('parent_id', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 5,
                        'default' => 0,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'view_level'
                    ]),
                    new Column('level', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 2,
                        'default' => 0,
                        'notNull' => true,
                        'after' => 'parent_id'
                    ]),
                    new Column('lft', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 5,
                        'default' => 0,
                        'notNull' => true,
                        'after' => 'level'
                    ]),
                    new Column('rgt', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 5,
                        'default' => 0,
                        'notNull' => true,
                        'after' => 'lft'
                    ]),
                    new Column('created_at', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 11,
                        'after' => 'rgt'
                    ]),
                    new Column('created_by', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'unsigned' => true,
                        'size' => 11,
                        'after' => 'created_at'
                    ]),
                    new Column('modified_at', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'after' => 'created_by'
                    ]),
                    new Column('modified_by', [
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'size' => 11,
                        'after' => 'modified_at'
                    ]),
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id']),
                    new Index('UNIQUE', ['alias']),
                    new Index('INDEX', ['created_by'])
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ]
            ]
        );
    }
}
