<template>
    <div class="p-4 d-flex root">
        <div class="text-center w-25px flex-shrink-0">
            <fa-icon class="color--white" icon="street-view" />
        </div>
        <div class="ps-3">
            <div>
                <strong>Trauer um {{ reason.text }}</strong>
            </div>
            <table>
                <tr v-if="reason.death !== null && reason.death !== ''">
                    <td v-if="reason.death.startsWith('Tod ')" class="pe-2">
                        {{ reason.death }}
                    </td>
                    <td v-else class="pe-2">
                        <b>Todesart:&nbsp;</b>{{ reason.death }}
                    </td>
                </tr>
                <tr>
                    <td v-if="reason.year" class="pe-2">
                        Verstorben:&nbsp;{{ reason.year }}
                    </td>
                    <td v-else class="pe-2">
                        Verstorben:&nbsp;Datum unbekannt
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>
<script>
export default {
    name: "ForeignProfileReason",
    props: ["reason"],
    computed: {
        toggleReasonDeath() {
            return "death" in this.reason;
        },
        toggleReasonDate() {
            return "year" in this.reason;
        },
    },
};
</script>

<style scoped></style>
