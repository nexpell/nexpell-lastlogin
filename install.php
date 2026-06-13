<?php

if (!function_exists('safe_query')) {
    die('Access denied');
}

global $_database, $plugin;

$modulname = 'lastlogin';
$version = isset($plugin['version']) ? (string)$plugin['version'] : ($version ?? '1.0.0');
$pluginName = 'LastLogin';
$pluginPath = 'includes/plugins/lastlogin/';

if (!function_exists('lastlogin_sql')) {
    function lastlogin_sql($value): string
    {
        return escape((string)$value);
    }
}

## SYSTEM #######################################################################

$pluginRes = safe_query("SELECT pluginID FROM settings_plugins WHERE modulname = 'lastlogin' LIMIT 1");
if ($pluginRes && ($pluginRow = mysqli_fetch_assoc($pluginRes))) {
    safe_query("UPDATE settings_plugins SET
        admin_file = 'admin_lastlogin',
        activate = 1,
        author = 'T-Seven',
        website = 'https://www.nexpell.de',
        index_link = '',
        hiddenfiles = '',
        version = '" . lastlogin_sql($version) . "',
        path = '" . lastlogin_sql($pluginPath) . "',
        status_display = 1,
        plugin_display = 1,
        widget_display = 1,
        delete_display = 1,
        sidebar = 'deactivated'
        WHERE pluginID = " . (int)$pluginRow['pluginID'] . "
    ");
} else {
    safe_query("INSERT INTO settings_plugins
        (modulname, admin_file, activate, author, website, index_link, hiddenfiles, version, path, status_display, plugin_display, widget_display, delete_display, sidebar)
    VALUES
        ('lastlogin', 'admin_lastlogin', 1, 'T-Seven', 'https://www.nexpell.de', '', '', '" . lastlogin_sql($version) . "', '" . lastlogin_sql($pluginPath) . "', 1, 1, 1, 1, 'deactivated')
    ");
}

safe_query("
    INSERT INTO settings_plugins_lang
        (content_key, language, content, modulname, updated_at)
    VALUES
        ('plugin_name_lastlogin', 'de', 'Lastlogin', 'lastlogin', NOW()),
        ('plugin_name_lastlogin', 'en', 'Lastlogin', 'lastlogin', NOW()),
        ('plugin_name_lastlogin', 'it', 'Lastlogin', 'lastlogin', NOW()),
        ('plugin_info_lastlogin', 'de', 'Mit diesem Plugin ist es moeglich die Aktivitaet der User und Mitglieder zu ueberpruefen.', 'lastlogin', NOW()),
        ('plugin_info_lastlogin', 'en', 'With this plugin it is possible to check the activity of the users and members.', 'lastlogin', NOW()),
        ('plugin_info_lastlogin', 'it', 'Con questo plugin e possibile controllare l''attivita degli utenti e dei membri.', 'lastlogin', NOW())
    ON DUPLICATE KEY UPDATE
        content = VALUES(content),
        modulname = VALUES(modulname),
        updated_at = VALUES(updated_at)
");

## NAVIGATION ###################################################################

$linkID = 0;
$linkRes = safe_query("
    SELECT linkID FROM navigation_dashboard_links
    WHERE modulname = 'lastlogin'
    ORDER BY linkID ASC LIMIT 1
");
if ($linkRes && ($linkRow = mysqli_fetch_assoc($linkRes))) {
    $linkID = (int)($linkRow['linkID'] ?? 0);
    safe_query("
        UPDATE navigation_dashboard_links SET
            catID = 3,
            url = 'admincenter.php?site=admin_lastlogin',
            sort = 2
        WHERE linkID = " . $linkID . "
    ");
} else {
    safe_query("
        INSERT INTO navigation_dashboard_links
            (catID, modulname, url, sort)
        VALUES
            (3, 'lastlogin', 'admincenter.php?site=admin_lastlogin', 2)
    ");
    $linkID = (int)mysqli_insert_id($_database);
}

if ($linkID > 0) {
    safe_query("
        INSERT INTO navigation_dashboard_lang
            (content_key, language, content, modulname, updated_at)
        VALUES
            ('nav_link_{$linkID}', 'de', 'Letzte Anmeldung', 'lastlogin', NOW()),
            ('nav_link_{$linkID}', 'en', 'Last Login', 'lastlogin', NOW()),
            ('nav_link_{$linkID}', 'it', 'Ultimi Login', 'lastlogin', NOW())
        ON DUPLICATE KEY UPDATE
            content = VALUES(content),
            modulname = VALUES(modulname),
            updated_at = VALUES(updated_at)
    ");
}

safe_query("
    INSERT IGNORE INTO user_role_admin_navi_rights
        (id, roleID, type, modulname)
    VALUES
        ('', 1, 'link', 'lastlogin')
");
