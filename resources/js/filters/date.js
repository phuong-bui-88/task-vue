import moment from "moment";
import GlobalConst from "./../consts/base.js";
export default (value, format = GlobalConst.DATE_FORMAT_DB) => {
    if (value) {
        return moment(value).format(format);
    }

    return value;
};
