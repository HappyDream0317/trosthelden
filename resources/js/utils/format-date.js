import moment from "moment";
moment.locale("de_DE");

const formatDate = function (date) {
    let ts = new moment(date).local();
    let daysDiff = new moment().diff(ts, "days");

    if (daysDiff < 2) {
        let now = new moment();
        return (ts > now)? now.fromNow() : ts.fromNow();
    } else if (daysDiff < 7) {
        return ts.format("dddd, HH:mm");
    } else {
        return ts.format("DD.MM.YYYY, HH:mm");
    }
};

export default formatDate;
