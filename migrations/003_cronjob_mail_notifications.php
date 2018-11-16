<?php

/**
 * 003_cronjob_mail_notifications.php
 *
 * @author Annelene Sudau <asudau@uos.de>
 */
class CronjobMailNotifications extends Migration
{

    const FILENAME = 'public/plugins_packages/virtUOS/OHNLayout/cronjobs/info_mail.php';

    public function description()
    {
        return 'add cronjob for sending defined mailnotifications';
    }

    public function up()
    {
        $task_id = CronjobScheduler::registerTask(self::FILENAME, true);

        // Schedule job to run every day at 23:59
        if ($task_id) {
            CronjobScheduler::schedulePeriodic($task_id, -1);  // negative value means "every x minutes"
        }
    }

    function down()
    {
        if ($task_id = CronjobTask::findByFilename(self::FILENAME)->task_id) {
            CronjobScheduler::unregisterTask($task_id);
        }
    }
}
