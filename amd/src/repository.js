import { call as fetchMany } from 'core/ajax';

export const processText = (selectedtext, pageurl) => fetchMany([{
    methodname: 'block_readabilityscore_process_text',
    args: {
        selectedtext,
        pageurl,
    },
}])[0];