function popupHide()
{
    document.body.style.overflow = 'visible';
    document.querySelector('#calibration').style.marginLeft = '0px';
    document.querySelector('#calibration').classList.remove('showMe');
}


document.addEventListener("DOMContentLoaded", function () {
    const columns = document.querySelectorAll('[role="presentation"] td[data-date]');
    if(columns)
    {
        for (let i = 0; i < columns.length; i++)
        {
            columns[i].addEventListener('click', function ()
            {
                const thisDate = this.getAttribute('data-date');
                getInfoByDate(thisDate);

                const scrollbar = document.body.clientWidth - window.innerWidth + 'px';
                document.body.style.overflow = 'hidden';
                document.querySelector('#calibration').style.marginLeft = scrollbar;
                document.querySelector('#calibration').classList.add('showMe');

                const titlePopup = document.querySelector('.modal-title');
                if (typeof titlePopup.innerText !== 'undefined')
                {
                    titlePopup.innerText = thisDate;
                }
                else
                {
                    titlePopup.textContent = thisDate;
                }

            });
        }
    }
});


function getInfoByDate(date)
{
    let formData = new FormData();
    formData.append('ACTION', 'getInfoByDate');
    formData.append('DATE', date);

    fetch(window.location.href, {
        method: 'POST',
        body: formData
    }).then(response => {
        return response.json();
    }).then(function(data) {
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

    }).catch(function(error) {
        alert('Произошла непредвиденная ошибка!\n' + error);
    });
}