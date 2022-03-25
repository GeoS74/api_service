<h1>Получение заявок на ремонт по uid</h1>
<hr>
<p>Метод предназначен для получения определённой заявки по уникальному идентификатору uid
</p>

<h3 class="mt-4">Запрос метода</h3>
<p>Для получения заявоки необходимо отправить <b>GET</b>-запрос на URL: <a href="<?php echo DOMEN . BASE . PREFIX ?>/order/@uid"><?php echo DOMEN . BASE . PREFIX ?>/order/@uid</a>
<br>где <b>@uid</b> - уникальный идентификатор заявки
</p>

<h5 class="mt-4">Структура ответа</h5>
<div class="card bg-light mt-3" style="max-width: 100%">
    <div class="card-body">
        <pre>
{
    "uid": "c18425c8bcc80255d2b8a55609f4e",
    "date_create": "2022-03-25 08:12:25",
    "date_start_repair": "",
    "accept_start_repair": "false",
    "order_num": "C2KA-039837",
    "garage_num": "115853",
    "invent_num": "11527",
    "reg_num": "O931BK199",
    "vin_code": "X980980N3AABS5078",
    "car_model": "УРАЛ 4320 с БАКМ 0980N",
    "car_type": "Бортовой автомобиль с КМУ",
    "year_issue": "2008",
    "mileage": "21 968",
    "problems": [
        "плановое ТО",    
        "нестабильные обороты на холостых",    
    ],
    "contact": "Иванов Иван Иванович",
    "basis": "г. Новый Уренгой",
    "comment": "Здесь может быть какой-нибудь комментарий",
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
        </tr>
    </thead>
    <tbody>
        <tr class="table-light">
            <th scope="row">uid</th>
            <td>Уникальный идентификатор заявки</td>
            <td>string</td>
        </tr>
        <tr class="table-light">
            <th scope="row">date_create</th>
            <td>Дата создания заявки на ремонт</td>
            <td>string</td>
        </tr>
        <tr class="table-light">
            <th scope="row">date_start_repair</th>
            <td>Дата начала ремонта. Заполняется если поле <b>accept_start_repair=true</b></td>
            <td>string</td>
        </tr>
        <tr class="table-light">
            <th scope="row">accept_start_repair</th>
            <td>флаг подтверждения начала ремонта. Принимает значения: <b>true/false</b></td>
            <td>string</td>
        </tr>
        <tr class="table-light">
            <th scope="row">order_num</th>
            <td>Номер заявки на ремонт</td>
            <td>string</td>
        </tr>
        <tr class="table-light">
            <th scope="row">garage_num</th>
            <td>Гаражный номер</td>
            <td>string</td>
        </tr>
        <tr class="table-light">
            <th scope="row">invent_num</th>
            <td>Инвентарный номер</td>
            <td>string</td>
        </tr>
        <tr class="table-light">
            <th scope="row">reg_num</th>
            <td>Государственный регистрационный знак</td>
            <td>string</td>
        </tr>
        <tr class="table-light">
            <th scope="row">vin_code</th>
            <td>Серийный номер (VIN)</td>
            <td>string</td>
        </tr>
        <tr class="table-light">
            <th scope="row">car_model</th>
            <td>Марка, модель</td>
            <td>string</td>
        </tr>
        <tr class="table-light">
            <th scope="row">car_type</th>
            <td>Вид техники</td>
            <td>string</td>
        </tr>
        <tr class="table-light">
            <th scope="row">year_issue</th>
            <td>Год выпуска</td>
            <td>string</td>
        </tr>
        <tr class="table-light">
            <th scope="row">mileage</th>
            <td>Пробег, наработка</td>
            <td>string</td>
        </tr>
        <tr class="table-light">
            <th scope="row">problems</th>
            <td>Перечень проблем/заявленных работ и т.д.</td>
            <td>array</td>
        </tr>
        <tr class="table-light">
            <th scope="row">contact</th>
            <td>Контактное лицо</td>
            <td>string</td>
        </tr>
        <tr class="table-light">
            <th scope="row">basis</th>
            <td>Местонахождение</td>
            <td>string</td>
        </tr>
        <tr class="table-light">
            <th scope="row">comment</th>
            <td>Комментарий к работам</td>
            <td>string</td>
        </tr>
    </tbody>
</table>

<p>В случае успешного ответа возращает статус страницы <b>200</b>. Если uid не существует, то возвращается <a href="error_description" class="link">ошибка 404</a></p>