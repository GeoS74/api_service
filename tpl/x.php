<h1>Hello world</h1>
<input type="submit" id="foo" value="send" />
<script>
    foo.onclick = async _ => {
        const data = {
            name: 'GeoS',
            value: [1, 3, 5]
        };

        await fetch('http://localhost:8888/gsp/test', {
                headers: {
                    'Content-Type': 'application/json',
                },
                method: 'POST',
                body: JSON.stringify(data)
            })
            .then(async response => {
                console.log('response');
                console.log(response.status);
                console.log(await response.json());
            })
            .catch(async error => {
                console.log('error');
                console.log(error);
            })
    };
</script>