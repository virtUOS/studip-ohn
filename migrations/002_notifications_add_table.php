<?php

class NotificationsAddTable extends Migration
{
    public function description()
    {
        return 'Add DB table for MailNotifications';
    }

    public function up()
    {
        $db = DBManager::get();

        // add db-table
        $db->exec("CREATE TABLE IF NOT EXISTS `mail_notifications` (
            `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `course_id` varchar(32) NOT NULL,
            `response_to` varchar(128) NOT NULL,
            `days_from_start` int(20) NOT NULL,
            `recipient_groups` varchar(128) NOT NULL,
            `mailsubject` mediumtext NOT NULL,
            `mailcontent` mediumtext NOT NULL,
            `sent` varchar(11) NULL
        )");

        SimpleORMap::expireTableScheme();
    }

    public function down()
    {
        $db = DBManager::get();

        $db->exec("DROP TABLE mail_notifications");

        SimpleORMap::expireTableScheme();
    }
}
