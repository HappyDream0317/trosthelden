<template>
    <div class="p-4">
        <h2>Kontakt zum chatten auswählen:</h2>
        <div>
            <contacts
                @selected-contact="$emit('close')"
                :contacts="contacts"
            ></contacts>
        </div>
    </div>
</template>

<script>
import Contacts from "./chat/Contacts";
import { mapGetters } from "vuex";

export default {
    name: "SelectContactModal",
    components: { Contacts },
    computed: {
        ...mapGetters("partner", {
            contacts: "allPartners",
        }),
    },
};
</script>

<style lang="scss" scoped></style>
