export default {
    isB2BProduct(variantId) {
        return axios.post(`/api/payments/plans/${variantId}/is-b2b`).then(({ data }) => data);
    },
    isFlatrateProduct(variantId) {
        return axios.post(`/api/payments/plans/${variantId}/is-flatrate`).then(({ data }) => data);
    },
    isStandardProduct(variantId) {
        return axios.post(`/api/payments/plans/${variantId}/is-standard`).then(({ data }) => data);
    },
    isCouponProduct(variantId) {
        return axios.post(`/api/payments/plans/${variantId}/is-coupon`).then(({ data }) => data);
    }
};