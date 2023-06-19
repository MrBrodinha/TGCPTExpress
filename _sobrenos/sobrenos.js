var audio = new Audio('Musica/Truckin.mp3');

audio.volume = 0.01;

function playMusic() {
    audio.play();
    document.getElementById("musica").setAttribute("value", "Pause");
    document.getElementById("musica").setAttribute("onclick", "pauseMusic()");
    document.getElementById("icon").setAttribute("class", "fa fa-pause");
}

function pauseMusic() {
    audio.pause();
    document.getElementById("musica").setAttribute("value", "Play");
    document.getElementById("musica").setAttribute("onclick", "playMusic()");
    document.getElementById("icon").setAttribute("class", "fa fa-play");
}

function generatePDF ()
{
    // Create a new jsPDF instance
    var doc = new jsPDF();

    // Get the table element
    var table = document.getElementById("membros");

    // Get the table rows
    var rows = Array.from(table.getElementsByTagName("tr"));

    // Exclude the last row
    rows.pop();

    // Set the table headers
    var headers = Array.from(rows.shift().getElementsByTagName("td")).map(function (cell)
    {
        return cell.textContent.trim();
    });

    // Set the table data
    var data = rows.map(function (row)
    {
        return Array.from(row.getElementsByTagName("td")).map(function (cell)
        {
            return cell.textContent.trim();
        });
    });

    // Set the x and y coordinates for the table
    var x = 10;
    var y = 20;

    // Set the column widths
    var columnWidths = [ 80, 80 ];

    // Draw the table headers
    headers.forEach(function (header, index)
    {
        doc.text(x, y, header);
        x += columnWidths[ index ];
    });

    // Reset the x coordinate
    x = 10;

    // Draw the table data
    data.forEach(function (row)
    {
        row.forEach(function (cell, index)
        {
            doc.text(x, y + 10, cell);
            x += columnWidths[ index ];
        });
        x = 10;
        y += 10;
    });

    // Save the PDF
    doc.save("membros.pdf");
}




