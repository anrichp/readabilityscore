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
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ text: selectedText })
            })
            .then(response => response.json())
            .then(data => {
                // Update the readability score on the page
                document.getElementById('readability-score').innerText = data.score;
            })
            .catch(error => console.error('Error:', error));
        }
    });
});
