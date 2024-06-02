document.addEventListener('DOMContentLoaded', function() {
    // Add event listener to the scan button
    document.getElementById('scan-button').addEventListener('click', function() {
        // Prompt the user to select text
        alert('Please select the text you want to analyze.');
    });

    // Event listener to capture the selected text when the user releases the mouse button
    document.addEventListener('mouseup', function() {
        var selectedText = window.getSelection().toString();
        if (selectedText) {
            // Output the selected text to the browser console for debugging
            console.log('Selected Text:', selectedText);

            // Send selected text to the server for analysis
            fetch(M.cfg.wwwroot + '/blocks/readabilityscore/scan.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ text: selectedText })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text(); // Get response as text
            })
            .then(data => {
                console.log('Server Response:', data); // Log the response received from the server
                // Update the readability score on the page (assuming data is the score)
                document.getElementById('readability-score').innerText = data;
            })
            .catch(error => console.error('Error:', error));
        }
    });
});
