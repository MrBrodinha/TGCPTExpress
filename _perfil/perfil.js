function eliminar (idprompt, filename) 
{
    const data = {
        idpub: idprompt
    };

    fetch('http://localhost/PWEB/TGCPTExpress/API/publicacao/delete.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(data =>
        {
            //open in a new window delete-files.php
            window.open('http://localhost/PWEB/TGCPTExpress/_publicacoes/delete-files.php?filename=' + encodeURIComponent(filename), '_blank');

            alert('Publicação eliminada com sucesso!');
            window.location.href = "perfil.php";
        })
        .catch(error =>
        {
            console.error('Error:', error);
        });
}
