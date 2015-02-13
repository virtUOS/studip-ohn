<?php

/**
 * Configure the registration form.
 *
 * @author Christian Flothmann <christian.flothmann@uos.de>
 */
class ConfigureRegistrationForm extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function description()
    {
        return 'Configure the registration form.';
    }

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $db = DBManager::get();
        $db->exec(
            'INSERT INTO `datafields` (`datafield_id`, `name`, `object_type`,
                `edit_perms`, `view_perms`, `mkdate`, `chdate`, `type`, `description`)
            VALUES ("f03a8f6ac4f2a7dad41c4a03f70cfcf0", "Vollständiger Name", "user",
                "root", "root", UNIX_TIMESTAMP(NOW()), UNIX_TIMESTAMP(NOW()), "textline",
                "Vollständiger Name eines OHN-Nutzers")'
        );
        $db->exec(
            'INSERT INTO `datafields` (`datafield_id`, `name`, `object_type`,
                `edit_perms`, `view_perms`, `mkdate`, `chdate`, `type`, `description`)
            VALUES ("6dc5b23f67786aa425fef77846b832dd", "Beruf", "user",
                "root", "root", UNIX_TIMESTAMP(NOW()), UNIX_TIMESTAMP(NOW()),
                "textline", "Beruf eines OHN-Nutzers")'
        );
        $db->exec(
            'INSERT INTO `datafields` (`datafield_id`, `name`, `object_type`,
                `edit_perms`, `view_perms`, `mkdate`, `chdate`, `type`, `typeparam`,
                `description`)
            VALUES ("d939ee673d5b9170ac0613a48589c3be", "Bildungsstufe", "user",
                "root", "root", UNIX_TIMESTAMP(NOW()), UNIX_TIMESTAMP(NOW()),
                "selectbox", "Promotion
Master oder gleichwertiger akademischer Bildungsgrad
Bachelor
Allgemeine Hochschulreife
Fachhochschulreife
Realschulabschluss oder erweiterter Sekundarabschluss 1
Hauptschulabschluss oder Sekundarabschluss 1
anderes", "Bildungsstufe eines OHN-Nutzers")'
        );

        $config = Config::get();
        $config->store('MOOC_REGISTRATION_FORM', file_get_contents(__DIR__.'/../fixtures/registration_form'));
        $config->store('MOOC_TERMS_OF_SERVICE', file_get_contents(__DIR__.'/../fixtures/terms_of_service'));
        $config->store('MOOC_PRIVACY_POLICY', file_get_contents(__DIR__.'/../fixtures/privacy_policy'));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $db = DBManager::get();
        $db->exec(
            'DELETE FROM
              datafields
            WHERE
              datafield_id IN(
                "f03a8f6ac4f2a7dad41c4a03f70cfcf0",
                "6dc5b23f67786aa425fef77846b832dd",
                "d939ee673d5b9170ac0613a48589c3be"
              )'
        );
    }
}
