document.addEventListener("DOMContentLoaded", () => {
    const calendarTable = document.querySelector('.fc-scrollgrid-sync-table');
    calendarTable.addEventListener('click', event => {
        const currentDay = event.target.closest('td.fc-daygrid-day[role="gridcell"]');
        const thisDate = currentDay.dataset.date;
        const currentDateData = window.globalData.days.find(day => day['DATE'] === thisDate);
        if (currentDateData) {
            console.log(currentDateData)
        }
        else {
            console.log('Empty')
        }

        document.body.style.overflow = 'hidden';
        document.querySelector('#calibration').classList.add('showMe');
        document.querySelector('[data-modal-header]').innerText = thisDate;
    })

    document.querySelector('[data-btn-close]').addEventListener('click', () => {
        document.body.style.overflow = 'visible';
        document.querySelector('#calibration').classList.remove('showMe');
    })
});