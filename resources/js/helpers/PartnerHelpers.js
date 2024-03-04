export const FRIENDREQUEST_SEND = 0;
export const FRIENDREQUEST_RECEIVED = 1;
export const BEFRIENDED = 2;
export const UNKNOWN = 3;

export default {
    reducePartnerList(partners = []) {
        return partners.map((p) => {
            return {
                id: p.id,
                age: p.age,
                avatar: p.avatar,
                friend_status: p.friend_status,
                location: p.location,
                nickname: p.nickname,
                protected_postal_code: p.protected_postal_code,
                sex: p.sex,
                is_blocked: p.is_blocked,
                is_watched: p.is_watched,
            };
        });
    },
    getOtherUserId(currentUserId, userIdAndPartnerId) {
        return Object.values(userIdAndPartnerId).filter(
            (userId) => userId !== currentUserId
        )[0];
    },
    clearPartnerListCache() {
        Object.keys(localStorage).map((key) => {
            if (key.startsWith('partner_list_')) {
                localStorage.removeItem(key);
            }
        });
    }
};
