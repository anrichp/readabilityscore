import { processText } from './repository';

/**
 * Initialize the readability score functionality.
 */
export const init = () => {
    const scanButton = document.getElementById('scan-button');
    if (scanButton) {
        scanButton.addEventListener('click', handleScanButtonClick);
    } else {
        // eslint-disable-next-line no-console
        console.log('scanButton not found');
    }
};

/**
 * Handle the scan button click event.
 */
function handleScanButtonClick() {
    const resultContainer = document.getElementById('readability-result');
    if (resultContainer) {
        resultContainer.innerHTML = '';
        alert('Please select text on the page to analyze its readability.');
        document.addEventListener('mouseup', textSelectionHandler);
    } else {
        // eslint-disable-next-line no-console
        console.log('resultContainer not found');
    }
}

/**
 * Handle the text selection event.
 */
function textSelectionHandler() {
    const selection = window.getSelection();
    const selectedText = getSelectedText(selection);

    if (selectedText !== '') {
        const pageUrl = window.location.href;

        processText(selectedText, pageUrl)
            .then(handleProcessTextResponse)
            .catch((error) => {
                // eslint-disable-next-line no-console
                console.error('Error:', error);
            });

        document.removeEventListener('mouseup', textSelectionHandler);
    }
}

/**
 * Get the selected text from the selection object.
 * @param {Selection} selection - The selection object.
 * @returns {string} The selected text.
 */
function getSelectedText(selection) {
    let selectedText = '';
    for (let i = 0; i < selection.rangeCount; i++) {
        const range = selection.getRangeAt(i);
        const container = document.createElement('div');
        container.appendChild(range.cloneContents());
        selectedText += container.innerText || container.textContent;
    }
    return selectedText.replace(/\s+/g, ' ').trim();
}

/**
 * Handle the response from processing the text.
 * @param {Object} response - The response object from processText.
 */
function handleProcessTextResponse(response) {
    const resultContainer = document.getElementById('readability-result');
    const score = response.readabilityscore;
    const difficultyLevel = getDifficultyLevel(score);
    const resultParagraph = document.createElement('p');
    resultParagraph.innerHTML = `
        <strong>Readability Score:</strong> ${score}<br>
        <strong>Difficulty Level:</strong> ${difficultyLevel}
    `;
    resultContainer.appendChild(resultParagraph);

    // Display remediation suggestions
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
 * Get the difficulty level based on the readability score.
 * @param {number} score - The readability score.
 * @returns {string} The difficulty level description.
 */
function getDifficultyLevel(score) {
    if (score <= 6) { return "Very Easy"; }
    if (score <= 8) { return "Easy"; }
    if (score <= 10) { return "Fairly Easy"; }
    if (score <= 12) { return "Standard"; }
    if (score <= 14) { return "Fairly Difficult"; }
    if (score <= 18) { return "Difficult"; }
    return "Very Difficult";
}