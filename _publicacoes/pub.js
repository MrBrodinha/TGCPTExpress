function eliminar(idprompt, filename) 
    {
        const data = {
            idpub: idprompt
        };
    
        const data2 = {
        idpub: idprompt,
        idperfil: -1
        };
    
    const data3 = {
        idpub: idprompt,
        idperfil: -1,
        idcom: -1
    }
    //deletes comments on that has idpub == idprompt
    fetch('http://localhost/PWEB/TGCPTExpress/API/comentarios/delete.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data3)
    })

       //deletes likes on that has idpub == idprompt
        fetch('http://localhost/PWEB/TGCPTExpress/API/likes/delete.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data2)

        })

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
                window.location.href = "pub.php";
            })
            .catch(error =>
            {
                console.error('Error:', error);
            });
}
    
function eliminarComentario(idprompt) 
    {
        const data = {
            idcom: idprompt,
            idperfil: -1,
            idpub: -1
        };
    
        fetch('http://localhost/PWEB/TGCPTExpress/API/comentarios/delete.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)

        })
            .then(response => response.json())
            .then(data =>
            {
                alert('Comentário eliminado com sucesso!');
                location.reload();
            })
            .catch(error =>
            {
                console.error('Error:', error);
            });
}

document.getElementById("publicar").addEventListener("click", openDialog);

function openDialog ()
{
    var dialog = document.getElementById("publicacao");
    dialog.showModal();
}

document.getElementById("2").addEventListener("click", closeDialog);

function closeDialog ()
{
    var dialog = document.getElementById("publicacao");
    dialog.close();
}

function toggleButton (button, idperfil, idpub)
{
    var buttons = document.getElementsByClassName('toggle-button');
    for (var i = 0; i < buttons.length; i++)
    {
        if (buttons[ i ] === button)
        {
            buttons[ i ].classList.toggle('select');
        } else
        {
            buttons[ i ].classList.remove('select');
        }
    }

    // Check which button is enabled and set the value accordingly
    var likeButton = document.getElementById('like');
    var dislikeButton = document.getElementById('dislike');

    if (likeButton.classList.contains('select'))
    {
        likeValue = 1;
    } else if (dislikeButton.classList.contains('select'))
    {
        likeValue = -1;
    } else
    {
        likeValue = 0;
    }

    const data = {
        idpub: idpub,
        idperfil: idperfil,
        gosto: likeValue
    };

    fetch('http://localhost/PWEB/TGCPTExpress/API/likes/update.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)

    })
        .then(response => response.json())
        .then(data =>
        {
            console.log('Success:', data);
        }
    )
        .catch(error =>
        {
            console.error('Error:', error);
        }
    );

    //waits 3 sec and then resets page
    setTimeout(function ()
    {
        location.reload();
    }
        , 500);

}
