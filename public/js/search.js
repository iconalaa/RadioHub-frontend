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

$(document).ready(function() {
    $('#searchForm').submit(function(event) {
        event.preventDefault(); // Prevent form submission

        var searchTerm = $('#searchInput').val();

        $.ajax({
            url: '/gratification/search',
            method: 'GET',
            data: { search: searchTerm },
            success: function(response) {
                $('#gratificationTable tbody').empty(); // Clear previous results
                
                response.gratifications.forEach(function(gratification) {
                    var row = '<tr>' +
                              '<td>' + gratification.id + '</td>' +
                              '<td>' + gratification.date + '</td>' +
                              '<td>' + gratification.title + '</td>' +
                              '<td>' + gratification.description + '</td>' +
                              '<td>' + gratification.type + '</td>' +
                              '<td>' + gratification.montant + '</td>' +
                              '<td>' + gratification.iddonateur + '</td>' +
                              '<td>' +
                              '<a href="/gratification/' + gratification.id + '" class="btn btn-primary">Show</a>' +
                              '<a href="/gratification/' + gratification.id + '/edit" class="btn btn-secondary">Edit</a>' +
                          '</td>' +
                          '</tr>';
                              
                              '</tr>';
                    $('#gratificationTable tbody').append(row);
                });
            },
            error: function(xhr, status, error) {
                console.error(error); // Handle errors
            }
        });
    });
});





