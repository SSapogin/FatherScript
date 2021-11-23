<?php
include('core/prolog.php');

$workDays = $fatherScript->select('WorkSchedule')->where(['DATE' => CURRENT_YEAR], '>=')->execute(); ?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500&display=swap" rel="stylesheet">

    <link href='/lib/main.css' rel='stylesheet' />
    <script src='/lib/main.js'></script>

    <title>Calendar</title>
</head>
<body>
    <style>
        body {
            margin: 40px 10px;
            padding: 0;
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 14px;
        }
        #calendar {
            max-width: 1100px;
            margin: 0 auto;
        }
    </style>

    <div id='calendar-container'>
        <div id='calendar'></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            <?$green = 'rgb(143, 223, 130)';
            $red = 'rgb(255, 159, 137)';?>

            let calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                firstDay: 1,
                locale: 'ru',
                buttonText: {
                    today: "Сегодня",
                    month: "Месяц",
                    week: "Неделя",
                    day: "День"
                },
                initialDate: '<?=CURRENT_DATE?>',
                navLinks: true,
                businessHours: true,
                editable: true,
                selectable: true,
                events: [
                    <?if(isset($workDays)):?>
                        <?foreach ($workDays as $day):?>
                            {
                                groupId: '<?=$day['DATE']?>',
                                start: '<?=$day['DATE']?>',
                                overlap: false,
                                display: 'background',
                                color: '<?=$day['IS_WORK'] ? $green : $red?>'
                            },
                        <?endforeach;?>
                    <?endif;?>

                    <?if(isset($events)):?>
                        <?foreach ($events as $event):?>
                            {
                                groupId: '<?=$event['DATE']?>',
                                start: '<?=$event['DATE']?>',
                                title: '<?=$event['COMMENTS']?>',
                                color: '<?=$event['CATEGORY']['NAME'] == 'Расходы' ? $red : $green?>'
                            },
                        <?endforeach;?>
                    <?endif;?>
                ]
            });

            calendar.render();
        });
    </script>
</body>
</html>
