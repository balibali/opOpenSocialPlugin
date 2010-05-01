<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class opOpenSocialPlugin12_indexApplicationLifecycleEventQueue extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->createForeignKey('application_lifecycle_event_queue', 'application_lifecycle_event_queue_application_id_application_id', array(
         'name' => 'application_lifecycle_event_queue_application_id_application_id',
         'local' => 'application_id',
         'foreign' => 'id',
         'foreignTable' => 'application',
         'onUpdate' => '',
         'onDelete' => 'CASCADE',
         ));
    $this->createForeignKey('application_lifecycle_event_queue', 'application_lifecycle_event_queue_member_id_member_id', array(
         'name' => 'application_lifecycle_event_queue_member_id_member_id',
         'local' => 'member_id',
         'foreign' => 'id',
         'foreignTable' => 'member',
         'onUpdate' => '',
         'onDelete' => 'CASCADE',
         ));
  }

  public function down()
  {
    $this->dropForeignKey('application_lifecycle_event_queue', 'application_lifecycle_event_queue_application_id_application_id');
    $this->dropForeignKey('application_lifecycle_event_queue', 'application_lifecycle_event_queue_member_id_member_id');
  }
}
