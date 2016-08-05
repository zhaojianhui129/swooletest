<?php
//设置定时器
//每隔20000ms出发一次
swoole_timer_tick(2000, function($timmerID){
    echo "tick-20000ms\n";
});

//30000秒后执行此函数
swoole_timer_after(3000, function () {
    echo "after 3000ms.\n";
});