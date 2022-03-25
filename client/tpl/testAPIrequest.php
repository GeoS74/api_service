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

//     $str = "Data too long for column 'vin_code' at row 1";

//     $res = preg_match("/Dataa too long for column '([\w]+)'/", $str, $foo);
//     echo $res;
// print_r($foo);
    // exit;
    ?>








    // $data = [
    // "sfs4ff334f3fgjfJU&7jtjt7jtjt3t3",
    // "741259687451",
    // "100500/сгк",
    // "115127",
    // "11451",
    // "Бортовой автомобиль с КМУ",
    // "Урал 4320 с БАКМ",
    // "sdfs4w4wcwfwfwvf3",
    // "2001",
    // "18 095",
    // "C158ОВ165RUS",
    // serialize($problems),
    // "Сидоров Павел Александрович",
    // "г. Новый Уренгой",
    // "УКПГ-16",
    // ];


    // uid,
    // order_num,
    // garage_num,
    // invent_num,
    // reg_num,
    // vin_code,
    // car_model,

    // car_type,
    // year_issue,
    // mileage,
    // problems,
    // contact,
    // basis,
    // comments

    // inn_customer,


    <script>



        const data = {
            uid: 'dgfvi49y84um39395',
            order_num: 'C2KA-039837',
            garage_num: '115853',
            invent_num: '11527',
            reg_num: 'O931BK199',
            vin_code: 'X980980N3AABS5078',
            // car_model: 'УРАЛ 4320 с БАКМ 0980N',
            car_model: '         gaz              ',

            car_type: 'Бортовой автомобиль с КМУ',
            year_issue: '2008',
            mileage: '21 968',
            problems: [
                "плановое ТО",    
                "нестабильные обороты на холостых",   
            ],
            contact: 'Иванов Иван Иванович',
            basis: 'г. Новый Уренгой',
            comments: 'Здесь может быть какой-нибудь комментарий',
        };

        const upd = {
            accept_start_repair: 'true'
        };
        (async _ => {
            await fetch('<?php echo BASE ?>/api/service/orders?only_new=true', {
            // await fetch('/gsp/api/service/order/dgfvi49y84um19395', {
            // await fetch('/gsp/api/service/order/7810443290_15', {
            // await fetch('/gsp/api/service/order/7810443290_10', {
                    // mode: 'cors',
                    // cache: 'no-cache',
                    headers: {
                        // 'Content-Type': 'multipart/form-data',
                        // 'Content-Type': 'application/x-www-form-urlencoded',
                        'Content-Type': 'application/json',
                        // 'Connection': 'keep-alive',
                        // 'Origin': 'weed.ru'
                        'Authorization': 'Basic 640aec714b14bb856621c593ca8cd88a873763e06dc655dfa2'
                    },
                    method: 'GET',
                    // body: JSON.stringify(data)
                    // body: JSON.stringify(upd)
                })
                .then(async response => {
                    if(response.ok){
                        console.log(await response.json());
                    }
                    else if(response.status == 400) {
                        console.log(JSON.parse(await response.text()));
                    }
                    else {
                        console.log(await response.text());
                    }
                })
                .catch(async error => {
                    console.log('error');
                    console.log(error);
                })
        })();
    </script>