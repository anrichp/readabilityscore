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
        console.error('Scan button not found');
    }

    // Create and append the instruction element
    const instructionElement = document.createElement('div');
    instructionElement.id = 'readability-instruction';
    instructionElement.style.display = 'none';
    instructionElement.innerHTML = 'Please select text on the page to analyze its readability.';
    document.body.appendChild(instructionElement);
};

/**
 * Handle the scan button click event.
 * This function prepares the UI for text selection and sets up the mouseup event listener.
 */
function handleScanButtonClick() {
    const resultContainer = document.getElementById('readability-result');
    const instructionElement = document.getElementById('readability-instruction');

    if (resultContainer && instructionElement) {
        // Clear any existing results
        resultContainer.innerHTML = '';

        // Show and position the instruction immediately
        showInstruction(instructionElement);

        // Set up the mouseup event listener to capture text selection
        document.addEventListener('mouseup', textSelectionHandler);

        // Update instruction position on mouse move
        document.addEventListener('mousemove', (e) => positionInstruction(instructionElement, e));
    } else {
        // Log an error if the result container or instruction element is not found
        // eslint-disable-next-line no-console
        console.error('Result container or instruction element not found');
    }
}

/**
 * Show and position the instruction element.
 * @param {HTMLElement} instructionElement - The instruction element.
 */
function showInstruction(instructionElement) {
    instructionElement.style.display = 'block';
    instructionElement.style.position = 'fixed';
    instructionElement.style.zIndex = '100000';

    // Position in the center of the viewport initially
    const viewportWidth = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
    const viewportHeight = Math.max(document.documentElement.clientHeight || 0, window.innerHeight || 0);

    instructionElement.style.left = `${viewportWidth / 2 - instructionElement.offsetWidth / 2}px`;
    instructionElement.style.top = `${viewportHeight / 2 - instructionElement.offsetHeight / 2}px`;
}

/**
 * Position the instruction element near the mouse cursor.
 * @param {HTMLElement} instructionElement - The instruction element.
 * @param {MouseEvent} e - The mouse event object.
 */
function positionInstruction(instructionElement, e) {
    instructionElement.style.left = `${e.clientX + 10}px`;
    instructionElement.style.top = `${e.clientY + 10}px`;
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

        // Remove the mouseup and mousemove event listeners
        document.removeEventListener('mouseup', textSelectionHandler);
        document.removeEventListener('mousemove', (e) =>
            positionInstruction(document.getElementById('readability-instruction'), e)
        );

        // Hide the instruction
        const instructionElement = document.getElementById('readability-instruction');
        if (instructionElement) {
            instructionElement.style.display = 'none';
        }
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
        <strong>Readability Score:</strong> ${score}<br>
        <strong>Difficulty Level:</strong> ${difficultyLevel}
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
 * @param {number} score - The Gunning Fog Index score.
 * @returns {string} The difficulty level description.
 */
function getDifficultyLevel(score) {
    if (score <= 6) { return "Easy - Readable by a 6th grader"; }
    if (score <= 7) { return "Fairly Easy - Readable by a 7th grader"; }
    if (score <= 8) { return "Standard - Readable by an 8th grader"; }
    if (score <= 9) { return "Fairly Difficult - Readable by a high school freshman"; }
    if (score <= 10) { return "Difficult - Readable by a high school sophomore"; }
    if (score <= 11) { return "Difficult - Readable by a high school junior"; }
    if (score <= 12) { return "Very Difficult - Readable by a high school senior"; }
    if (score <= 16) { return "College Level - Readable by college students"; }
    return "Graduate Level - Readable by college graduates";
}