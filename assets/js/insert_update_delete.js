function savePainting() {
    const id = document.getElementById('paintingId').value;
    const title = document.getElementById('title').value;
    const artist = document.getElementById('artist').value;
    const year = document.getElementById('year').value;
    const image = document.getElementById('image').value;

    const action = id ? 'updatePainting' : 'insertPainting';
    const data = `action=${action}&id=${id}&title=${title}&artist=${artist}&year=${year}&image=${image}`;

    fetch('controllers/PaintingController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: data
    })
    .then(response => response.json())
    .then(result => {
        if (result) {
            alert('Painting saved successfully!');
            // Optionally, clear the form or redirect to another page
        } else {
            alert('Failed to save painting.');
        }
    });
}

function deletePainting() {
    const id = document.getElementById('paintingId').value;

    if (!id) {
        alert('Please enter a painting ID to delete.');
        return;
    }

    const data = `action=deletePainting&id=${id}`;

    fetch('controllers/PaintingController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: data
    })
    .then(response => response.json())
    .then(result => {
        if (result) {
            alert('Painting deleted successfully!');
            // Optionally, clear the form or redirect to another page
        } else {
            alert('Failed to delete painting.');
        }
    });
}
