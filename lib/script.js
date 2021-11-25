document.addEventListener("DOMContentLoaded", () => {
    const calendarTable = document.querySelector('.fc-scrollgrid-sync-table');
    calendarTable.addEventListener('click', event => {
        const currentDay = event.target.closest('td.fc-daygrid-day[role="gridcell"]');
        const thisDate = currentDay.dataset.date;
        getInfoByDate(thisDate);

        document.body.style.overflow = 'hidden';
        document.querySelector('#calibration').classList.add('showMe');
        document.querySelector('[data-modal-header]').innerText = thisDate;
    })

    document.querySelector('[data-btn-close]').addEventListener('click', () => {
        document.body.style.overflow = 'visible';
        document.querySelector('#calibration').classList.remove('showMe');
    })
});


function getInfoByDate(date) {
    let formData = new FormData();
    formData.append('ACTION', 'getInfoByDate');
    formData.append('DATE', date);

    fetch(window.location.href, {
        method: 'POST',
        body: formData
    }).then(response => response.json())
    .then(data => {
        const IS_WORK = document.querySelector('#calibration form #IS_WORK');
        const REPLACED = document.querySelector('#calibration form #REPLACED');
        if (data['IS_WORK'] && data['IS_WORK'] == 1) {
            IS_WORK.setAttribute('checked', '');
        } else {
            IS_WORK.removeAttribute('checked');
        }
        if (data['IS_WORK'] && data['REPLACED'] == 1) {
            REPLACED.setAttribute('checked', '');
        } else {
            REPLACED.removeAttribute('checked');
        }
    })
    .catch(err => {
        console.warn(err)
    });
}