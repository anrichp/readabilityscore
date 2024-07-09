import { processText } from './repository';

let initialized = false;

export const init = () => {
    // eslint-disable-next-line no-console
    console.log("The init function was called");

    if (initialized) {
        return;
    }

    initialized = true;
    // eslint-disable-next-line no-console
    console.log('Executing init function logic');

    const scanButton = document.getElementById('scan-button');
    if (scanButton) {
        // eslint-disable-next-line no-console
        console.log('scanButton found:', scanButton);

        scanButton.addEventListener('click', function() {
            // eslint-disable-next-line no-console
            console.log('Scan button clicked');

            const selectedTextContainer = document.getElementById('selected-text');
            if (selectedTextContainer) {
                // eslint-disable-next-line no-console
                console.log('selectedTextContainer found:', selectedTextContainer);
                selectedTextContainer.innerHTML = ''; // Clear existing content

                alert('Please select text on the page.');

                const textSelectionHandler = function() {
                    const selection = window.getSelection();
                    let selectedText = '';

                    // Iterate through the selected ranges
                    for (let i = 0; i < selection.rangeCount; i++) {
                        const range = selection.getRangeAt(i);
                        const container = document.createElement('div');
                        container.appendChild(range.cloneContents());
                        selectedText += container.innerText || container.textContent;
                    }

                    selectedText = selectedText.replace(/\s+/g, ' ').trim();

                    // eslint-disable-next-line no-console
                    console.log('Selected text:', selectedText);

                    if (selectedText !== '') {
                        // eslint-disable-next-line no-console
                        console.log('Selected text is not empty:', selectedText);

                        const paragraph = document.createElement('p'); // Create a new paragraph element
                        paragraph.textContent = selectedText; // Set the text content of the paragraph
                        selectedTextContainer.appendChild(paragraph); // Append paragraph to selectedTextContainer

                        const pageUrl = window.location.href; // Ensure pageUrl is correctly set

                        processText(selectedText, pageUrl)
                            .then(response => {
                                alert('Readability Score: ' + response.readabilityscore);
                            })
                            .catch(error => {
                                // eslint-disable-next-line no-console
                                console.error('Error:', error);
                            });

                        document.removeEventListener('mouseup', textSelectionHandler);
                    } else {
                        // eslint-disable-next-line no-console
                        console.log('No text selected');
                    }
                };

                document.addEventListener('mouseup', textSelectionHandler);
                // eslint-disable-next-line no-console
                console.log('textSelectionHandler added');
            } else {
                // eslint-disable-next-line no-console
                console.log('selectedTextContainer not found');
            }
        });
    } else {
        // eslint-disable-next-line no-console
        console.log('scanButton not found');
    }
};