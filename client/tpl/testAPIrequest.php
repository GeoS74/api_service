    <?php
    // try {
    //     $db = new DataBase();

    //     $query = "INSERT INTO orders (
    //         uid_order, 
    //         inn_customer, 
    //         order_num, 
    //         garage_num,
    //         invent_num,
    //         car_type, 
    //         car, 
    //         vin_code, 
    //         year_issue,
    //         mileage, 
    //         reg_num, 
    //         problems, 
    //         contact,
    //         basis,
    //         comments) 
    //         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // $problems = [
    //     "течь топливного бака",
    //     "повышенный люфт рулевого колеса",
    // ];

    // $data = [
    //     "sfs4ff334f3fgjfJU&7jtjt7jtjt3t3",
    //     "741259687451",
    //     "100500/сгк",
    //     "115127",
    //     "11451",
    //     "Бортовой автомобиль с КМУ",
    //     "Урал 4320 с БАКМ",
    //     "sdfs4w4wcwfwfwvf3",
    //     "2001",
    //     "18 095",
    //     "C158ОВ165RUS",
    //     serialize($problems),
    //     "Сидоров Павел Александрович",
    //     "г. Новый Уренгой",
    //     "УКПГ-16",
    // ];

    //     // $query = "UPDATE orders SET date_start_repair=DEFAULT WHERE id='1'";


    //     // $db -> mysql_qw($query, $data);
    //     echo 'ok';
    // }
    // catch(Exception $error) {
    //     echo $error -> getMessage();
    // }



    // $db = new DataBase();
    // $result = $db -> mysql_qw("SELECT * FROM orders");
    // if($result -> num_rows) {
    //     echo '<pre>';
    //     while($row = $result -> fetch_assoc()) {
    //         $row['problems'] = unserialize($row['problems']);
    //         print_r($row);
    //     }
    //     echo '</pre>';
    // }
    // else {
    //     echo 'not data for print...';
    // }
    // exit;
    ?>












<script>
    const data = {
        name: 'GeoS',
        // value: [1, 3, 5]
    };

    (async _ => {
        await fetch('/gsp/api/service/orders', {
                // mode: 'cors',
                // cache: 'no-cache',
                headers: {
                    // 'Content-Type': 'multipart/form-data',
                    // 'Content-Type': 'application/x-www-form-urlencoded',
                    // 'Content-Type': 'application/json',
                    // 'Connection': 'keep-alive',
                    // 'Origin': 'weed.ru'
                    'Authorization': 'Basic 1132f97ab8ed7fe7a8aeea34e90d6d8cf250ebe508fd42cd3e'
                },
                method: 'GET',
                // body: JSON.stringify(data)
            })
            .then(async response => {
                console.log('response');
                console.log(response.status);
                console.log(await response.text());
                // console.log(await response.json());
            })
            .catch(async error => {
                console.log('error');
                console.log(error);
            })
    })();
</script>