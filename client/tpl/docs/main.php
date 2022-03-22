<!DOCTYPE HTML>
<html lang="ru">

<head>
    <title>MAGNUS | Документация API</title>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link href="<?php echo BASE ?>/client/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!--https://bootswatch.com/cosmo/-->
    <!--https://icons.getbootstrap.com/-->
    <style>
        #left_nav {
            position: fixed;
            width: 180px;
            height: 100%;
            background-color: #e9ecef;
        }

        #main {
            max-width: 700px;
            margin: 25px 0 0 210px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary" style="z-index:100">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">MAGNUS<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lightning-charge" viewBox="0 0 16 16">
                    <path d="M11.251.068a.5.5 0 0 1 .227.58L9.677 6.5H13a.5.5 0 0 1 .364.843l-8 8.5a.5.5 0 0 1-.842-.49L6.323 9.5H3a.5.5 0 0 1-.364-.843l8-8.5a.5.5 0 0 1 .615-.09zM4.157 8.5H7a.5.5 0 0 1 .478.647L6.11 13.59l5.732-6.09H9a.5.5 0 0 1-.478-.647L9.89 2.41 4.157 8.5z" />
                </svg></a>
            <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo BASE ?>">Документация API
                            <span class="visually-hidden">(current)</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <ul class="nav nav-pills flex-column" id="left_nav">
        <li class="nav-item mt-4">
            <a class="nav-link" href="description">Описание сервиса</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="token">Получение токена</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="order_query">Направление заявки на ремонт</a>
        </li>
    </ul>
    <div id="main"></div>
    <script>
        document.querySelector('#left_nav').addEventListener('mouseover', event => {
            if (event.target.closest('a')) event.target.classList.toggle('active');
        });
        document.querySelector('#left_nav').addEventListener('mouseout', event => {
            if (event.target.closest('a')) event.target.classList.toggle('active');
        });
        //load text
        document.querySelector('#left_nav').addEventListener('click', event => {
            if (!event.target.closest('a')) return;
            event.preventDefault();
            loadData(event.target.getAttribute('href'));
        });

        loadData('description');

        function loadData(thema) {
            fetch(`<?php echo BASE ?>/api/service/docs/${thema}`)
                .then(async response => {
                    main.innerHTML = await response.text();
                })
                .catch(error => console.log(error))
        }
    </script>
</body>