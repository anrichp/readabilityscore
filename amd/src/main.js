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

    var scanButton = document.getElementById('scan-button');
    if (scanButton) {
        // eslint-disable-next-line no-console
        console.log('scanButton found:', scanButton);

        scanButton.addEventListener('click', function() {
            // eslint-disable-next-line no-console
            console.log('Scan button clicked');

            var selectedTextContainer = document.getElementById('selected-text-container');
            if (selectedTextContainer) {
                // eslint-disable-next-line no-console
                console.log('selectedTextContainer found:', selectedTextContainer);
                selectedTextContainer.textContent = 'Selected Text: ';
            } else {
                // eslint-disable-next-line no-console
                console.log('selectedTextContainer not found');
            }

            alert('Please select text on the page.');

            var textSelectionHandler = function() {
                var selectedText = window.getSelection().toString().trim();
                // eslint-disable-next-line no-console
                console.log('Selected text:', selectedText);

                if (selectedText !== '') {
                    // eslint-disable-next-line no-console
                    console.log('Selected text is not empty:', selectedText);
                    if (selectedTextContainer) {
                        selectedTextContainer.textContent += selectedText;
                    }

                    processText(selectedText).then(response => {
                        alert('Readability Score: ' + response.readabilityscore);
                    }).catch(error => {
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
        });
    } else {
        // eslint-disable-next-line no-console
        console.log('scanButton not found');
    }
};
