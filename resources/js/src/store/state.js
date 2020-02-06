import navbarSearchAndPinList from "@js/common/layouts/components/navbar/navbarSearchAndPinList"
import themeConfig from "@/../themeConfig.js"
import colors from "@/../themeConfig.js"


// /////////////////////////////////////////////
// Variables
// /////////////////////////////////////////////
const user = {
    name: '',
    last_name: '',
}
const token = ''

const transactions = []
const bankAccounts = []

const details = {
    marketing: '',
    notifications: '',
    language: '',
}
// /////////////////////////////////////////////
// State
// /////////////////////////////////////////////

const state = {
    bodyOverlay            : false,
    isVerticalNavMenuActive: true,
    mainLayoutType         : themeConfig.mainLayoutType || "vertical",
    navbarSearchAndPinList : navbarSearchAndPinList,
    reduceButton           : themeConfig.sidebarCollapsed,
    verticalNavMenuWidth   : "default",
    verticalNavMenuItemsMin: false,
    scrollY                : 0,
    starredPages           : navbarSearchAndPinList.data.filter((page) => page.highlightAction),
    theme                  : themeConfig.theme || "light",
    themePrimaryColor      : colors.primary,
    user                   : user,
    token                  : token,
    accounts               : bankAccounts,
    transactions           : transactions,
    details                : details,

    // Can be used to get current window with
    // Note: Above breakpoint state is for internal use of sidebar & navbar component
    windowWidth: null,
    checkAuth  : false,
}

export default state
