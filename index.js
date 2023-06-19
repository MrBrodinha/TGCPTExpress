$(document).ready(function ()
{
        $("#perfil").fadeTo(300, 1, function () {});
        $("#pfp").fadeTo(300, 1, function () {});
        $("#imagem1").fadeTo(300, 1, function ()
        {
                $("#imagem2").fadeTo(300, 1, function ()
                {
                        $("#navegacao").fadeTo(300, 1, function ()
                {
                                $("#conteudo").fadeTo(300, 1);
                        });
                });
        });
});