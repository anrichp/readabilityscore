document.addEventListener('DOMContentLoaded', function() {
    // Get the scan button element
    var scanButton = document.getElementById('scan-button');

    // Add click event listener to the scan button
    scanButton.addEventListener('click', function() {
        // Clear the selected text container
        var selectedTextContainer = document.getElementById('selected-text-container');
        if (selectedTextContainer) {
            selectedTextContainer.textContent = 'Selected Text: ';
        }

        // Prompt the user to select text
        alert('Please select text on the page.');

        // Add event listener for text selection
        var textSelectionHandler = function() {
            var selectedText = window.getSelection().toString().trim();

            if (selectedText !== '') {
                // Output selected text to the browser console
                console.log('Selected text:', selectedText);

                // Update the selected text container in the block
                selectedTextContainer.textContent += selectedText;

                // Remove the event listener to prevent further text selection
                document.removeEventListener('mouseup', textSelectionHandler);
            }
        };

        document.addEventListener('mouseup', textSelectionHandler);
    });
});
