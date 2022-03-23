<h1>Направление заявки на ремонт</h1>
<hr>
<p>Метод предназначен для направления в сервисную станцию исполнителя заявки на ремонт.</p>

<h3 class="mt-4">Запрос метода</h3>
<p>Для использования необходимо отправить POST-запрос на URL: <a href="<?php echo DOMEN . BASE ?>/api/service/order"><?php echo DOMEN . BASE ?>/api/service/order</a></p>

<h5 class="mt-4">Структура запроса</h5>
<p>Тело запроса необходимо передавать в формате JSON (Content-Type: application/json).</p>
<div class="card bg-light mb-3" style="max-width: 100%">
    <div class="card-body">
        <pre>
{
    "uid": "c18425c8bcc80255d2b8a55609f4e",
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
<p>Описание параметров:</p>
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
            <th scope="row">uid</th>
            <td>Уникальный идентификатор заявки. Генерируется Заказчиком</td>
            <td>string(50)</td>
            <td>Да</td>
        </tr>
        <tr class="table-light">
            <th scope="row">order_num</th>
            <td>Номер заявки на ремонт</td>
            <td>string(100)</td>
            <td>Да</td>
        </tr>
        <tr class="table-light">
            <th scope="row">garage_num</th>
            <td>Гаражный номер</td>
            <td>string(25)</td>
            <td>Да</td>
        </tr>
        <tr class="table-light">
            <th scope="row">invent_num</th>
            <td>Инвентарный номер</td>
            <td>string(25)</td>
            <td>Да</td>
        </tr>
        <tr class="table-light">
            <th scope="row">reg_num</th>
            <td>Государственный регистрационный знак</td>
            <td>string(25)</td>
            <td>Да</td>
        </tr>
        <tr class="table-light">
            <th scope="row">vin_code</th>
            <td>Серийный номер (VIN)</td>
            <td>string(17)</td>
            <td>Да</td>
        </tr>
        <tr class="table-light">
            <th scope="row">car_model</th>
            <td>Марка, модель</td>
            <td>string(255)</td>
            <td>Да</td>
        </tr>
        <tr class="table-light">
            <th scope="row">car_type</th>
            <td>Вид техники</td>
            <td>string(255)</td>
            <td>Нет</td>
        </tr>
        <tr class="table-light">
            <th scope="row">year_issue</th>
            <td>Год выпуска</td>
            <td>string(4)</td>
            <td>Нет</td>
        </tr>
        <tr class="table-light">
            <th scope="row">mileage</th>
            <td>Пробег, наработка</td>
            <td>string(10)</td>
            <td>Нет</td>
        </tr>
        <tr class="table-light">
            <th scope="row">problems</th>
            <td>Перечень проблем/заявленных работ и т.д.</td>
            <td>array</td>
            <td>Нет</td>
        </tr>
        <tr class="table-light">
            <th scope="row">contact</th>
            <td>Контактное лицо</td>
            <td>string(255)</td>
            <td>Нет</td>
        </tr>
        <tr class="table-light">
            <th scope="row">basis</th>
            <td>Местонахождение</td>
            <td>string(255)</td>
            <td>Нет</td>
        </tr>
        <tr class="table-light">
            <th scope="row">comment</th>
            <td>Комментарий к работам</td>
            <td>string(255)</td>
            <td>Нет</td>
        </tr>
    </tbody>
</table>

<p>Если данные переданы успешно то, будет возращен статус страницы <b>201</b>.</p>