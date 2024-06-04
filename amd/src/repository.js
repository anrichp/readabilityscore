import {call as fetchMany} from 'core/ajax';

export const processText = (selectedtext) => fetchMany([{
    methodname: 'block_readabilityscore_process_text',
    args: {
        selectedtext,
    },
}])[0];
