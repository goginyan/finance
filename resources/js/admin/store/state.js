import navbarSearchAndPinList from "@js/common/layouts/components/navbar/navbarSearchAndPinList"
import themeConfig from "@/../themeConfig.js"
import colors from "@/../themeConfig.js"


// /////////////////////////////////////////////
// Variables
// /////////////////////////////////////////////
const user = {}
const token = ''
const allUsers = []
const accounts = []
const transactions = []
const transactionsUser = []
const commissions = []
// /////////////////////////////////////////////
// State
// /////////////////////////////////////////////

const state = {
    bodyOverlay: false,
    isVerticalNavMenuActive: true,
    mainLayoutType: themeConfig.mainLayoutType || "vertical",
    navbarSearchAndPinList: navbarSearchAndPinList,
    reduceButton: themeConfig.sidebarCollapsed,
    verticalNavMenuWidth: "default",
    verticalNavMenuItemsMin: false,
    scrollY: 0,
    starredPages: navbarSearchAndPinList.data.filter((page) => page.highlightAction),
    theme: themeConfig.theme || "light",
    themePrimaryColor: colors.primary,
    user: user,
    token: token,
    accounts: accounts,
    transactions: transactions,
    transactionsUser: transactionsUser,
    allUsers: allUsers,
    commissions: commissions,

    // Can be used to get current window with
    // Note: Above breakpoint state is for internal use of sidebar & navbar component
    windowWidth: null,
    checkAuth: false,

}

export default state
