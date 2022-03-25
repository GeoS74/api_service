<h1>Подтверждение начала ремонта</h1>
<hr>
<p>Метод предназначен для подтверждения Исполнителем начала ремонта и доступен только для Исполнителя.</p>

<h3 class="mt-4">Запрос метода</h3>
<p>Для использования необходимо отправить <b>POST</b>-запрос на URL: <a href="<?php echo DOMEN.BASE.PREFIX ?>/order/@uid"><?php echo DOMEN.BASE.PREFIX ?>/order/@uid</a>
<br>где <b>@uid</b> - уникальный идентификатор заявки</p>

<h5 class="mt-4">Структура запроса</h5>
<p>Тело запроса необходимо передавать в формате JSON (Content-Type: application/json).</p>
<div class="card bg-light" style="max-width: 100%">
    <div class="card-body">
        <pre>
{
    "accept_start_repair": "true",
}</pre>
    </div>
</div>
<p class="mt-4">Описание параметров:</p>
<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Поле</th>
            <th scope="col">Описание</th>
            <th scope="col">Тип поля</th>
            <th scope="col">Обязат.</th>
        </tr>
    </thead>
    <tbody>
        <tr class="table-light">
            <th scope="row">accept_start_repair</th>
            <td>флаг подтверждения начала ремонта</td>
            <td>string</td>
            <td>Да</td>
        </tr>
    </tbody>
</table>

<p>Если ремонт подтверждён, будет возращен статус страницы <b>200</b> и зафиксирована дата начала ремонта.
В случае, если ремонт был подтверждён ранее, метод вернёт статус <b>400</b> с текстом ошибки <i>"данные не могут быть изменены, ремонт был подтверждён ранее"</i></p>