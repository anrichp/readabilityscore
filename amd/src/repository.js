import { call as fetchMany } from 'core/ajax';

/**
 * Process the selected text and get the readability score.
 * @param {string} selectedtext - The selected text to be processed.
 * @param {string} pageurl - The URL of the page where the text originated from.
 * @returns {Promise} - A promise that resolves to the readability score.
 */
export const processText = (selectedtext, pageurl) => {
    // Make an AJAX call to the Moodle web service.
    return fetchMany([{
        methodname: 'block_readabilityscore_process_text',
        args: {
            selectedtext,
            pageurl,
        },
    }])[0]
    .then(response => {
        // Check if response contains the expected data
        if (response && response.readabilityscore !== undefined) {
            return response;
        } else {
            throw new Error('Unexpected response format');
        }
    })
    .catch(error => {
        // eslint-disable-next-line no-console
        console.error('Error processing text:', error);
        throw error;
    });
};