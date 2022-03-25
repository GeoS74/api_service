<!DOCTYPE html>
<head>
    <style>
        div {
            margin: 25px;
        }

        small:hover {
            text-decoration: underline;
            cursor: pointer;
            color: red;
        }
    </style>
</head>
<body>
    <h1>Add token</h1>
    <form id="tokenForm" onsubmit="event.preventDefault()" enctype="multipart/form-data">
        inn: <input type="text" name="inn" />
        <br><br>
        organization: <input type="text" name="organization" />
        <br><br>
        contacts: <input type="text" name="contacts" />
        <br><br>
        is customer: <input type="checkbox" name="is_customer" />
        <br><br>
        <input type="submit" id="addToken" value="create token" />
    </form>
    <div id="token"></div>

    <script>
        (async _ => {
            await fetch('<?php echo BASE ?>/tokens')
                .then(async response => {
                    if (response.ok) {
                        const res = await response.json();
                        // console.log(res);
                        for (const r of res) {
                            token.insertAdjacentHTML('afterbegin', makeRow(r));
                        }
                    } else {
                        const res = await response.json();
                        throw (new Error(`${res.error}`))
                    }
                })
                .catch(error => console.log(error));;
        })();

        function delToken(event) {
            if (!confirm("delete this token?")) return;

            fetch(`<?php echo BASE ?>/token/${event.target.parentNode.dataset.id}`, {
                    method: 'DELETE'
                })
                .then(async response => {
                    if (response.ok) {
                        const res = await response.json();
                        document.querySelector(`[data-id="${res.id}"]`).remove();
                    } else {
                        throw (new Error(`http response status ${response.status}`))
                    }
                })
                .catch(error => console.log(error));
        }

        addToken.onclick = async _ => {
            const fd = new FormData(tokenForm);
            addToken.disabled = true;

            await fetch('<?php echo BASE ?>/token', {
                    method: 'POST',
                    body: fd
                })
                .then(async response => {
                    if (response.ok) {
                        const res = await response.json();
                        // console.log(res);
                        token.insertAdjacentHTML('afterbegin', makeRow(res));
                        tokenForm.reset();
                    } else {
                        const res = await response.json();
                        throw (new Error(`${res.error}. Response status: ${response.status}`))
                    }
                })
                .catch(error => console.log(error))
                .finally(_ => addToken.disabled = false);
        };

        function makeRow(data) {
            return `<div data-id="${data.id}">
            Организация: ${data.organization || ""} <br> 
            ИНН: ${data.inn || ""} <br> 
            Контакты: ${data.contacts || ""} <br> 
            Заказчик: ${data.is_customer === 'true' ? "да" : "нет"} <br> 
            Token: <b>${data.token}</b> <small onclick="delToken(event)">delete</small><br><hr></div>`;
        }
    </script>
</body>