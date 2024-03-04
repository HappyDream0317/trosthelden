import Home from "../views/Home.vue";
import Register from "../views/Register.vue";
import RemoteVerification from "../views/RemoteVerification.vue";
import Login from "../views/Login.vue";
import ForgotPassword from "../views/ForgotPassword.vue";
import ForgotPasswordReset from "../views/ForgotPasswordReset.vue";
import Logout from "../views/Logout.vue";
import Imprint from "../views/Imprint.vue";
import Privacy from "../views/Privacy.vue";
import Conditions from "../views/Conditions.vue";
import CustomerService from "../views/Customerservice.vue";
import PleaseVerify from "../views/Pleaseverify.vue";
import Faq from "../views/Faq.vue";
import About from "../views/About.vue";
import Revocation from "../views/Revocation.vue";
import Membership from "../views/Membership.vue";
import Dashboard from "../views/Dashboard.vue";
import GroupDetails from "../views/GroupDetails.vue";
import PostDetails from "../views/PostDetails.vue";
import Chat from "../views/Chat.vue";
import Profile from "../views/Profile.vue";
import ForeignProfile from "../views/ForeignProfile.vue";
import IconTestPage from "../views/IconTestPage.vue";
import Verification from "../views/Verification.vue";
import EmptyRouterView from "../components/EmptyRouterView.vue";
import Partner from "../views/Partner.vue";
import Matchings from "../views/Matchings.vue";
import Watchlist from "../views/Watchlist.vue";
import Hidden from "../views/Hidden.vue";
import Settings from "../views/Settings.vue";
import Notifications from "../views/Notifications.vue";
import store from "../store";
import GuidePage from "../views/GuidePage.vue";
import PaymentSucceeded from "../views/PaymentSucceeded.vue";
import PaymentError from "../views/PaymentError.vue";
import Premium from "../views/Premium.vue";
import StoryblokPost from "../cms/storyblok-post.vue";
import StoryblokBlog from "../cms/storyblok-blog.vue";
import PageNotFound from "../views/PageNotFound.vue";
import Checkout from "../views/Checkout.vue";
import CheckoutSucceeded from "../views/CheckoutSucceeded.vue";
import B2BCheckout from "../views/b2b/Checkout.vue";
import B2BDashboard from "../views/b2b/Dashboard.vue";
import B2BPartner from "../views/b2b/Partner.vue";
import B2BVerification from "../views/b2b/Verification.vue";

export const routes = [
    {
        path: "/",
        name: "home",
        component: Home,
        meta: {
            guest: true
        },
        beforeEnter: (to, from, next) => {
            if (!store.getters["currentUser/isVerified"]) {
                return next({ name: "verify" });
            }
            return next({ name: store.getters["currentUser/isVerified"] ? 'b2b_dashboard' : 'dashboard' });
        },
    },
    {
        path: "/impulse",
        name: "blog",
        component: StoryblokBlog,
    },
    {
        path: "/impulse/*",
        name: "blog-post",
        component: StoryblokPost,
    },
    {
        path: "/remote/verify/:user_id/:hash/:variant_id?",
        name: "remote-verification",
        component: RemoteVerification,
        props: true,
        meta: {
            guest: true,
            analyticsIgnore: true,
            forNotVerified: true,
        },
    },
    {
        path: "/register",
        name: "register",
        component: Register,
        meta: {
            guest: true,
        },
        beforeEnter: (to, from, next) => {
            if (store.getters["currentUser/isLoggedIn"]) {
                return next({ name: "guide" });
            }
            return next();
        },
    },
    {
        path: "/guide",
        name: "guide",
        component: GuidePage,
        meta: {
            forNotVerified: true,
            forBusinessAccount: false,
        },
        beforeEnter: (to, from, next) => {
            if (!store.getters["currentUser/isLoggedIn"]) {
                return next({ name: "login" });
            }
            return next();
        },
    },
    {
        path: "/login",
        name: "login",
        component: Login,
        meta: {
            title: "Login",
            guest: true,
        },
    },
    {
        path: "/forgot-password",
        name: "forgot-password",
        component: ForgotPassword,
        meta: {
            guest: true,
        },
    },
    {
        path: "/password/reset/:hash",
        name: "forgot-password-verification",
        component: ForgotPasswordReset,
        props: true,
        meta: {
            guest: true,
        },
    },
    {
        path: "/logout",
        name: "logout",
        component: Logout,
        meta: {
            guest: true,
        },
    },
    {
        path: "/imprint",
        name: "imprint",
        component: Imprint,
        meta: {
            guest: true,
        },
    },
    {
        path: "/privacy",
        name: "privacy",
        component: Privacy,
        meta: {
            guest: true,
        },
    },
    {
        path: "/conditions",
        name: "conditions",
        component: Conditions,
        meta: {
            guest: true,
        },
    },
    {
        path: "/customerservice",
        name: "customerservice",
        component: CustomerService,
        meta: {
            guest: true,
        },
    },
    {
        path: "/payment-succeeded",
        name: "paymentsucceeded",
        component: PaymentSucceeded,
        meta: {
            guest: true,
            forNotVerified: true,
        },
    },
    {
        path: "/payment-error",
        name: "paymenterror",
        component: PaymentError,
        meta: {
            guest: false,
            forNotVerified: false,
        },
    },
    {
        path: "/premium",
        name: "premium",
        component: Premium,
        meta: {
            guest: false,
            forNotVerified: true,
        },
    },
    {
        path: "/checkout/:variant_id",
        name: "checkout",
        component: Checkout,
        props: true,
        meta: {
            guest: true,
            forNotVerified: true,
        },
    },
    {
        path: "/checkout-succeeded/:variant_id",
        name: "checkoutsucceeded",
        component: CheckoutSucceeded,
        props: true,
        meta: {
            guest: true,
            forNotVerified: true,
        },
    },
    {
        path: "/pleaseverify",
        name: "pleaseverify",
        component: PleaseVerify,
        meta: {
            guest: false,
            forNotVerified: true,
        },
    },
    {
        path: "/faq",
        name: "faq",
        component: Faq,
        meta: {
            guest: true,
        },
    },
    {
        path: "/about",
        name: "about",
        component: About,
        meta: {
            guest: true,
        },
    },
    {
        path: "/revocation",
        name: "revocation",
        component: Revocation,
        meta: {
            guest: true,
        },
    },
    {
        path: "/membership",
        name: "membership",
        component: Membership,
        meta: {
            guest: true,
        },
    },
    {
        path: "/dashboard",
        name: "dashboard",
        component: Dashboard,
        meta: {
            forBusinessAccount: false,
        }
    },
    {
        path: "/group/:id",
        name: "group",
        component: GroupDetails,
        meta: {
            forBusinessAccount: false,
        }
    },
    {
        path: "/post/:id",
        name: "post",
        component: PostDetails,
        meta: {
            forBusinessAccount: false,
        }
    },
    {
        path: "/chat",
        name: "chat",
        component: Chat,
        props: true,
        meta: {
            forBusinessAccount: false,
        }
    },
    {
        path: "/profile",
        name: "profile",
        component: Profile,
        meta: {
            forBusinessAccount: false,
        }
    },
    {
        path: "/profile/:user_id",
        name: "foreignProfile",
        component: ForeignProfile,
        props: true,
        beforeEnter: (to, from, next) => {
            if (to.params.user_id === store.getters["currentUser/getId"]) {
                next({ name: "profile" });
                return;
            }
            next();
        },
        meta: {
            forBusinessAccount: false,
        }
    },

    {
        path: "/iconTestPage/:user_id",
        name: "iconTestPage",
        component: IconTestPage,
        props: true,
        meta: {
            forBusinessAccount: false,
        }
    },
    {
        path: "/email/verify/:user_id/:hash",
        name: "verification",
        component: Verification,
        props: true,
        meta: {
            guest: true,
            analyticsIgnore: true,
            forNotVerified: true,
        },
    },
    {
        path: "/partner",
        name: "partner-category",
        component: EmptyRouterView,
        children: [
            {
                name: "partner",
                path: "",
                component: Partner,
                meta: {
                    forBusinessAccount: false,
                },
            },
            {
                name: "matchings",
                path: "matchings",
                component: Matchings,
                meta: {
                    forBusinessAccount: false,
                },
            },
            {
                name: "watchlist",
                path: "watchlist",
                component: Watchlist,
                meta: {
                    forBusinessAccount: false,
                },
            },
            {
                name: "hidden",
                path: "hidden",
                component: Hidden,
                meta: {
                    forBusinessAccount: false,
                },
            },
        ],
    },
    {
        path: "/settings",
        name: "settings-category",
        component: EmptyRouterView,
        children: [
            {
                name: "settings",
                path: "",
                component: Settings,
                meta: {
                    forBusinessAccount: false,
                },
            },
            {
                path: "/notifications",
                name: "notifications",
                component: Notifications,
                meta: {
                    forBusinessAccount: false,
                },
            },
        ],
    },
    {
        path: "/b2b",
        name: "b2b",
        component: EmptyRouterView,
        meta: {
            forBusinessAccount: true,
        },
        children: [
            {
                path: "email/verify/:user_id/:hash",
                name: "b2b_verification",
                component: B2BVerification,
                props: true,
                meta: {
                    guest: true,
                    analyticsIgnore: true,
                    forNotVerified: true,
                },
            },
            {
                name: "b2b_partner",
                path: "partner/:slug",
                component: B2BPartner,
                props: true,
                meta: {
                    guest: true,
                },
            },
            {
                name: "b2b_checkout",
                path: "checkout/:variant_id",
                component: B2BCheckout,
                props: true,
                meta: {
                    guest: true,
                },
            },
            {
                path: "dashboard",
                name: "b2b_dashboard",
                component: B2BDashboard,
            },
            {
                path: "settings",
                name: "b2b_settings",
                component: Settings,
            },
        ]
    },
    {
        path: "/:catchAll(.*)",
        component: PageNotFound,
    },
];