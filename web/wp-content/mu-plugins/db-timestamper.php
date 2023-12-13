<?php

add_filter('cron_schedules','arteric_timestamp_every_5_minutes');

function arteric_timestamp_every_5_minutes($schedules){
    if(!isset($schedules["5min"])){
        $schedules["5min"] = array(
            'interval' => 5*60,
            'display' => __('Once every 5 minutes'));
    }

    return $schedules;
}

add_action('wp', 'arteric_schedule_db_timestamp');

function arteric_schedule_db_timestamp(){
    if(!wp_next_scheduled('arteric_hook_db_timestamp')){
        wp_schedule_event(time(), '5min', 'arteric_hook_db_timestamp');
    }
}

add_action('arteric_hook_db_timestamp', 'arteric_timestamp_db');

function arteric_timestamp_db(){

    $arteric_date_stamp = new \DateTime('now', new \DateTimeZone(date_default_timezone_get()));
    $arteric_date_stamp->setTimeZone(new \DateTimeZone('America/New_York'));
    
    update_option(getenv("timestampOptionName")?: '300_heartbeat', $arteric_date_stamp->format('Y-m-d h:i:s'));
}