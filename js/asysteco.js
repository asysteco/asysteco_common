window.setMobileTable = function(selector) {
    const tableEl = document.querySelector(selector);
    const thEls = tableEl.querySelectorAll('thead th');
    const tdLabels = Array.from(thEls).map(el => el.innerText);
    tableEl.querySelectorAll('tbody tr').forEach( tr => {
        Array.from(tr.children).forEach( 
        (td, ndx) =>  td.setAttribute('data-th', tdLabels[ndx])
        );
    });
}