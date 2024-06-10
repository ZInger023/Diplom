<?php
//массив, у которого ключи – это регулярка для адреса, а значение – это массив с двумя значениями – именем контроллера и названием метода.

return [
    '~^tickets/(\d+)$~' => [\TechSupport\Controllers\TicketController::class, 'view'],
    '~^tickets/(\d+)/setManager$~' => [\TechSupport\Controllers\TicketController::class, 'setManager'],
    '~^tickets/insert$~' => [\TechSupport\Controllers\TicketController::class, 'insert'],
    '~^tickets/(\d+)/edit$~' => [\TechSupport\Controllers\TicketController::class, 'edit'],
    '~^tickets/(\d+)/delete$~' => [\TechSupport\Controllers\TicketController::class, 'delete'],
    '~^$~' => [\TechSupport\Controllers\MainController::class, 'main'],
    '~^signUp$~' => [\TechSupport\Controllers\UsersController::class, 'signUp'],
    '~^validateRegistration$~' => [\TechSupport\Controllers\UsersController::class, 'validateRegistration'],
    '~^users/login$~' => [\TechSupport\Controllers\UsersController::class, 'login'],
    '~^tickets/addToChat/(\d+)$~' => [\TechSupport\Controllers\ChatController::class, 'insert'],
    '~^tickets/getChatMessages/(\d+)$~' => [\TechSupport\Controllers\ChatController::class, 'getChatMessages'],
    '~^users/account/(\d+)$~' => [\TechSupport\Controllers\UsersController::class, 'myAccount'],
    '~^users/editAccount$~' => [\TechSupport\Controllers\UsersController::class, 'editAccount'],
];