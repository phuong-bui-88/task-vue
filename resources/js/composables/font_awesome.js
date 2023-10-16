/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

/* import specific icons */
import { faStar as fasStar } from '@fortawesome/free-solid-svg-icons'
import { faStar as farStar} from '@fortawesome/free-regular-svg-icons'

/* add icons to the library */
library.add(fasStar, farStar)
export default FontAwesomeIcon
