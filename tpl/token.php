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
    <form id="tokenForm" onclick="event.preventDefault()" enctype="multipart/form-data">
        inn: <input type="text" name="inn" />
        <br><br>
        organization: <input type="text" name="organization" />
        <br><br>
        contacts: <input type="text" name="contacts" />
        <br><br>
        <input type="submit" id="addToken" value="create token" />
    </form>
    <div id="token"></div>

    <script>
        (async _ => {
            await fetch('/gsp/tokens')
                .then(async response => {
                    if (response.ok) {
                        const res = await response.json();
                        // console.log(res);
                        for (const r of res) {
                            token.insertAdjacentHTML('afterbegin', makeRow(r));
                        }
                    } else {
                        throw (new Error(`http response status ${response.status}`))
                    }
                });
        })();

        function delToken(event) {
            if (!confirm("delete this token?")) return;

            fetch(`/gsp/token/${event.target.parentNode.dataset.id}`, {
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

            await fetch('/gsp/token', {
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
            return `<div data-id="${data.id}">Организация: ${data.organization} <br> 
            Контакты: ${data.contacts} <br> 
            Token: <b>${data.token}</b> <small onclick="delToken(event)">delete</small><br><hr></div>`;
        }
    </script>
</body>