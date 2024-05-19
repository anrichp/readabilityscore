function getSelectedText() {
    var selectedText = '';
    if (window.getSelection) {
        selectedText = window.getSelection().toString();
    } else if (document.selection && document.selection.type != 'Control') {
        selectedText = document.selection.createRange().text;
    }
    return selectedText;
}
function updateReadabilityScore() {
    var selectedText = getSelectedText();
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo $PAGE->url; ?>', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('selectedtext=' + encodeURIComponent(selectedText));
}
// Call the updateReadabilityScore function when the user selects text
document.addEventListener('selectionchange', updateReadabilityScore);