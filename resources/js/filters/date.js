import moment from 'moment'
import GlobalConst from './../consts/base.js'
export default (value) => {
    if (value) {
        return moment(value).format(GlobalConst.DATE_FORMAT)
    }

    return value
}
