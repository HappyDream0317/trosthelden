<template>
    <div class="p-4 d-flex root">
        <div class="text-center w-25px flex-shrink-0">
            <fa-icon class="color--white" icon="street-view" />
        </div>
        <div class="ps-3" v-if="reason !== 'SUPPORTER'">
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
        <div class="ps-3" v-else>
            <div>
                <strong>Trauerhelfer</strong>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ProfileReason",
    props: ["reason"],
};
</script>

<style scoped>
.root {
    vertical-align: top;
}
</style>
