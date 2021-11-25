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
    <link href='/lib/style.css' rel='stylesheet' />
    <script src='/lib/main.js'></script>

    <title>Calendar</title>
</head>
<body>
    <div id='calendar-container'>
        <div id='calendar'></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            <?$green = 'rgb(143, 223, 130)';
            $red = 'rgb(255, 159, 137)';
            $blue = '#3788d8';?>

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
                                color: '<? if($day['REPLACED'] == 1 && $day['IS_WORK'] == 1){echo $blue;} elseif ($day['IS_WORK']){echo $green;} else {echo $red;}?>'
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

    <div id="calibration" class="modal">
        <div class="modal-content">
            <h2 class="modal-header">
                <span class="modal-header__text" data-modal-header>Калибровка графика</span>
                <button type="button" title="Закрыть" class="modal-header__close" data-btn-close>
                    <span class="modal-header__close_text">×</span>
                </button>
            </h2>
            <div class="modal-body">
                <form action="/" method="post">
                    <div>
                        <label for="IS_WORK">Рабочий день?</label>
                        <input id="IS_WORK" name="IS_WORK" type="checkbox">
                    </div>
                    <div>
                        <label for="REPLACED">Подменяешь?</label>
                        <input id="REPLACED" name="REPLACED" type="checkbox">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src='/lib/script.js'></script>
</body>
</html>
