import store from "../../store";
import {
    description as formatDescription,
    price as formatPrice,
} from "../../utils/checkout";

const state = () => ({
    success: () => {
    },
    error: () => {
    },
    signupService: undefined,
    paymentService: undefined,
    urlExternalApp: "",
    countries: [],
    expirationYears: [],
    paymentMethods: [],
});

const actions = {
    async init({commit, dispatch}, {success, error, methods}) {
        const getValueFromEnv = store.getters["env/getValueFromEnv"];

        commit(
            "setUrlExternalApp",
            getValueFromEnv("TROSTHELDEN_URL").value
        );

        commit("setSuccess", success);
        commit("setError", error);

        try {
            await dispatch("loadScript");

            const tag = await dispatch("origin");

            await dispatch("countries");
            await dispatch("expirationYears");
            await dispatch("signupService");
            await dispatch("paymentService", methods);

            return tag;

        } catch (error) {
            return Promise.reject(error);
        }
    },
    loadScript() {
        return new Promise((resolve, reject) => {
            const getValueFromEnv = store.getters["env/getValueFromEnv"];

            let script = document.createElement("script");
            script.src = getValueFromEnv("BILLWERK_ROUTE_SUBSCRIPTION").value;
            script.async = true;
            script.onload = () => resolve();
            script.onerror = () => reject();
            document.head.appendChild(script);
        });
    },
    async product({getters}, productId) {
        try {
            let {data} = await axios.get(`/api/payments/plans/${productId}`);

            data.planVariant.Description._c = formatDescription(
                data.planVariant?.Description._c
            );
            data.planVariant.RecurringFee = formatPrice(
                data.planVariant.RecurringFee
            );

            return data.planVariant;

        } catch (error) {
            return Promise.reject(error);
        }
    },
    async origin({dispatch}) {
        try {
            let queryString = window.location.search;
            let tag = undefined;
            if (queryString !== "") {
                let urlParams = new URLSearchParams(queryString);
                let allowedParams = ["tag", "pk_vid", "partner", "dbg"];
                tag = urlParams.get("tag");
                for (const key of urlParams.keys()) {
                    if (!allowedParams.includes(key)) {
                        await dispatch("finalize");
                        return tag;
                    }
                }
            }
            return tag;
        } catch (error) {
            return Promise.reject(error);
        }
    },
    async finalize({getters}) {
        new SubscriptionJS.finalize(getters.getSuccess, getters.getError);
    },
    async countries({commit}) {
        try {

            let {data} = await axios.post(`/api/payments/countries`);

            const countries = Object.values(data.countries);

            return commit("setCountries", countries);
        } catch (error) {
            return Promise.reject(error);
        }
    },
    async expirationYears({commit}) {
        let years = [];
        let initYear = new Date().getFullYear();

        for (let i = initYear; i < initYear + 11; i++) {
            years.push(i);
        }

        return commit("setExpirationYears", years);
    },
    signupService({commit}) {
        const service = new SubscriptionJS.Signup();
        commit("setSignupService", service);
    },
    paymentService({commit, dispatch, getters}, methods) {

        const getValueFromEnv = store.getters["env/getValueFromEnv"];

        const service = new SubscriptionJS.Payment(
            {
                publicApiKey: getValueFromEnv("BILLWERK_PUBLIC_API_KEY").value,
                providerReturnUrl: window.location.href,
            },
            () => dispatch("paymentMethods", methods),
            (err) => {
                console.error(err);
            }
        );

        commit("setPaymentService", service);
    },
    paymentMethods({commit, getters}, methods) {
        let enums = getters.getPaymentService.getAvailablePaymentMethodEnum();
        let reults = [];

        enums.map((value) => {
            let key = value.split(":")[0];
            if (methods.includes(key))
                reults.push({
                    key,
                    value,
                });
        });

        commit("setPaymentMethods", reults);
    },
    order({getters, dispatch}, {cart, customer, data, customerId, partner}) {
        if (customerId === null) {
            getters.getSignupService.createOrder({
                    Cart: cart,
                    Customer: customer,
                    ContractCustomFields: {
                        Partner: partner
                    }
                },
                (order) => {
                    dispatch("pay", {data, order})
                },
                getters.getError
            );
        } else {
            axios
                .post("/api/payments/order", {
                    customerId,
                    cart,
                    customer,
                    partner
                })
                .then(({ data: { order } }) => dispatch("pay", { data, order }))
                .catch(getters.getError);
        }
    },
    pay({ getters }, { data, order }) {
        getters.getSignupService.paySignupInteractive(
            getters.getPaymentService,
            data,
            order,
            getters.getSuccess,
            getters.getError
        );
    },
    preview({getters}, {cart, customer, callback}) {
        getters.getSignupService.preview(cart, customer, callback, callback);
    },
    async customerSearch({}, {key, value}) {
        try {
            let {data} = await axios.post(`/api/payments/customer/search`, {
                key,
                value
            })
            return data;
        } catch (error) {
            return Promise.reject(error);
        }
    },
    async customerData({}, {userId}) {
        try {
            let {data} = await axios.get(`/api/payments/customer/${userId}`)
            return data;
        } catch (error) {
            return false;
        }
    },
};

const getters = {
    getUrlExternalApp: (state) => {
        return state.urlExternalApp;
    },
    getSuccess: (state) => {
        return state.success;
    },
    getError: (state) => {
        return state.error;
    },
    getCountries: (state) => {
        return state.countries;
    },
    getExpirationYears: (state) => {
        return state.expirationYears;
    },
    getPaymentService: (state) => {
        return state.paymentService;
    },
    getSignupService: (state) => {
        return state.signupService;
    },
    getPaymentMethods: (state) => {
        return state.paymentMethods;
    },
};

const mutations = {
    setUrlExternalApp(state, url) {
        state.urlExternalApp = url;
    },
    setSuccess: (state, success) => {
        state.success = success;
    },
    setError: (state, error) => {
        state.error = error;
    },
    setCountries: (state, countries) => {
        state.countries = countries;
    },
    setExpirationYears: (state, years) => {
        state.expirationYears = years;
    },
    setPaymentService: (state, service) => {
        state.paymentService = service;
    },
    setSignupService: (state, service) => {
        state.signupService = service;
    },
    setPaymentMethods: (state, methods) => {
        state.paymentMethods = methods;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};
