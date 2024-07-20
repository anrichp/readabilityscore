// Import the processText function from the repository module
import { processText } from './repository';

/**
 * Initialize the readability score functionality.
 * This function sets up the event listener for the scan button.
 */
export const init = () => {
    const scanButton = document.getElementById('scan-button');
    if (scanButton) {
        scanButton.addEventListener('click', handleScanButtonClick);
    } else {
        // Log an error if the scan button is not found
        // eslint-disable-next-line no-console
        console.log('scanButton not found');
    }
};

/**
 * Handle the scan button click event.
 * This function prepares the UI for text selection and sets up the mouseup event listener.
 */
function handleScanButtonClick() {
    const resultContainer = document.getElementById('readability-result');
    if (resultContainer) {
        // Clear any existing results
        resultContainer.innerHTML = '';
        // Prompt the user to select text
        alert('Please select text on the page to analyze its readability.');
        // Set up the mouseup event listener to capture text selection
        document.addEventListener('mouseup', textSelectionHandler);
    } else {
        // Log an error if the result container is not found
        // eslint-disable-next-line no-console
        console.log('resultContainer not found');
    }
}

/**
 * Handle the text selection event.
 * This function is called when the user releases the mouse button after selecting text.
 */
function textSelectionHandler() {
    const selection = window.getSelection();
    const selectedText = getSelectedText(selection);

    if (selectedText !== '') {
        const pageUrl = window.location.href;

        // Process the selected text
        processText(selectedText, pageUrl)
            .then(handleProcessTextResponse)
            .catch((error) => {
                // Log any errors that occur during processing
                // eslint-disable-next-line no-console
                console.error('Error:', error);
            });

        // Remove the mouseup event listener to prevent multiple selections
        document.removeEventListener('mouseup', textSelectionHandler);
    }
}

/**
 * Get the selected text from the selection object.
 * @param {Selection} selection - The selection object.
 * @returns {string} The selected text, trimmed and with excess whitespace removed.
 */
function getSelectedText(selection) {
    let selectedText = '';
    for (let i = 0; i < selection.rangeCount; i++) {
        const range = selection.getRangeAt(i);
        const container = document.createElement('div');
        container.appendChild(range.cloneContents());
        selectedText += container.innerText || container.textContent;
    }
    // Remove excess whitespace and trim the selected text
    return selectedText.replace(/\s+/g, ' ').trim();
}

/**
 * Handle the response from processing the text.
 * This function displays the readability score, difficulty level, and remediation suggestions.
 * @param {Object} response - The response object from processText.
 */
function handleProcessTextResponse(response) {
    const resultContainer = document.getElementById('readability-result');
    const score = response.readabilityscore;
    const difficultyLevel = getDifficultyLevel(score);

    // Create and populate the result paragraph
    const resultParagraph = document.createElement('p');
    resultParagraph.innerHTML = `
        <strong>Gunning Fog Index:</strong> ${score.toFixed(1)}<br>
        <strong>Reading Level:</strong> ${difficultyLevel}
    `;
    resultContainer.appendChild(resultParagraph);

    // Display remediation suggestions if available
    if (response.remediationSuggestions && response.remediationSuggestions.length > 0) {
        const suggestionsHeader = document.createElement('h4');
        suggestionsHeader.textContent = 'Remediation Suggestions:';
        resultContainer.appendChild(suggestionsHeader);

        const suggestionsList = document.createElement('ul');
        response.remediationSuggestions.forEach(suggestion => {
            const listItem = document.createElement('li');
            listItem.textContent = suggestion;
            suggestionsList.appendChild(listItem);
        });
        resultContainer.appendChild(suggestionsList);
    }
}

/**
 * Get the difficulty level based on the Gunning Fog Index score.
 * @param {number} score - The readability score.
 * @returns {string} The reading level description.
 */
function getDifficultyLevel(score) {
    if (score >= 17) return "College graduate";
    if (score >= 16) return "College senior";
    if (score >= 15) return "College junior";
    if (score >= 14) return "College sophomore";
    if (score >= 13) return "College freshman";
    if (score >= 12) return "High school senior";
    if (score >= 11) return "High school junior";
    if (score >= 10) return "High school sophomore";
    if (score >= 9) return "High school freshman";
    if (score >= 8) return "Eighth grade";
    if (score >= 7) return "Seventh grade";
    if (score >= 6) return "Sixth grade";
    return "Fifth grade or below";
}