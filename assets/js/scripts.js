// Function to sort the table based on the selected criteria
function sortTable(tableId, sortCriteria) {
    const table = document.getElementById(tableId);
    const tbody = table.tBodies[0];
    const rows = Array.from(tbody.rows);

    rows.sort((a, b) => {
        let aText, bText;

        switch (sortCriteria) {
            case 'title':
                aText = a.cells[0].textContent.toLowerCase();
                bText = b.cells[0].textContent.toLowerCase();
                break;
            case 'artist':
                aText = a.cells[1].textContent.toLowerCase();
                bText = b.cells[1].textContent.toLowerCase();
                break;
            case 'year':
                aText = parseInt(a.cells[3].textContent);
                bText = parseInt(b.cells[3].textContent);
                return aText - bText; // Numeric sort for years
        }
        return aText.localeCompare(bText); // String sort for title and artist
    });

    // Clear existing rows and append sorted rows
    tbody.innerHTML = "";
    rows.forEach(row => tbody.appendChild(row));
}
