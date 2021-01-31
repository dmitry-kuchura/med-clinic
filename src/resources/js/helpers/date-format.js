export function formatDate(date) {
    let d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear(),
        hour = '' + d.getHours(),
        minutes = '' + d.getMinutes();

    if (month.length < 2) {
        month = '0' + month;
    }

    if (day.length < 2) {
        day = '0' + day;
    }

    if (hour.length < 2) {
        hour = '0' + hour;
    }

    return [hour, minutes].join(':') + ' ' + [day, month, year].join('/');
}
