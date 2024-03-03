/*document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    searchInput.addEventListener('input', function () {
        const searchTerm = searchInput.value.trim();
        if (searchTerm.length > 0) {
            fetch(`/gratification/search?search=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    // Handle the search results
                    renderSearchResults(data.gratifications);
                })
                .catch(error => {
                    console.error('Error fetching search results:', error);
                });
        } else {
            // Clear the search results if the search term is empty
            searchResults.innerHTML = '';
        }
    });

    function renderSearchResults(gratifications) {
        searchResults.innerHTML = '';
        if (gratifications.length === 0) {
            searchResults.innerHTML = '<p>No results found.</p>';
        } else {
            const ul = document.createElement('ul');
            gratifications.forEach(gratification => {
                const li = document.createElement('li');
                // Update field names to match the keys in your JSON response
                li.textContent = `${gratification.title} - ${gratification.date}`;
                ul.appendChild(li);
            });
            searchResults.appendChild(ul);
        }
    }
});
*/
