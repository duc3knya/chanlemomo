<?php

/**
 *File name : constant.php / Date: 10/26/2021 - 9:51 PM
 */
define("STATUS_ACTIVE", 1);
define("STATUS_DE_ACTIVE", 0);
define("STATUS_MAINTENANCE", 2);

define("TURN_ON_SETTING", 1);
define("TURN_OFF_SETTING", 2);

define("TIME_EACH_ATTENDANCE_SESSION", 600);
define("TIME_REFRESH_LOAD_DATA_AFTER", 45);

define("CONFIG_LIMIT_LAN_BANK", 190);

define("TIME_START_ATTENDANCE", "07:00");
define("TIME_END_ATTENDANCE", "23:59");

define("MONEY_MIN_WIN_ATTENDANCE", 5000);
define("MONEY_MAX_WIN_ATTENDANCE", 100000);

define("TIME_CACHE_LOAD_DATA", 20);

define("ATTENDANCE_WIN_RATE_DEFAULT", 4);

define("STATUS_LSMOMO_CHUA_THANH_TOAN", 4);
define("STATUS_LSMOMO_TAM_THOI", 4);

define("CONFIG_ALL_GAME", 0);
define("CONFIG_CHAN_LE", 1);
define("CONFIG_TAI_XIU", 2);
define("CONFIG_CHAN_LE_TAI_XIU_2", 3);
define("CONFIG_GAP_3", 4);
define("CONFIG_TONG_3_SO", 5);
define("CONFIG_1_PHAN_3", 6);
define("CONFIG_GAME_LO", 7);

define('CONFIG_MAX_SUM_TIEN_CUOC', 27000000);
define('CONFIG_MAX_COUNT_BANK', 180);

define('CONFIG_COMPARE_TIEN_CUOC_MIN', 20000);
return [
    'list_game' => [
        CONFIG_ALL_GAME          => "Tất cả",
        CONFIG_CHAN_LE           => "Chẵn lẻ",
        CONFIG_TAI_XIU           => "Tài xỉu",
        CONFIG_CHAN_LE_TAI_XIU_2 => "Chẵn lẻ tài xỉu 2",
        CONFIG_GAP_3             => "Gấp 3",
        CONFIG_TONG_3_SO         => "Tổng 3 số",
        CONFIG_1_PHAN_3          => "1 phần 3",
        CONFIG_GAME_LO           => "Lô",
    ],
];