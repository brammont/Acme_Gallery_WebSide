document.addEventListener('DOMContentLoaded', function() {
    fetch('controllers/PaintingController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'action=getAllPaintings'
    })
    .then(response => response.json())
    .then(data => {
        const tableBody = document.querySelector('#paintingsTable tbody');
        data.forEach(painting => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${painting.title}</td>
                <td>${painting.artist}</td>
                <td>${painting.year}</td>
                <td><img src="assets/img/${painting.image}" alt="${painting.title}" width="100"></td>
            `;
            tableBody.appendChild(row);
        });
    });
});
