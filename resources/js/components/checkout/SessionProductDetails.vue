<template>
    <div class="form-group mb-0">
        <table class="product w-100 color--primary">
            <tr>
                <td>{{title}}:</td>
                <td>
                    {{ product.InternalName }}
                </td>
            </tr>
            <tr>
                <td>Beschreibung:</td>
                <td class="pre-formatted">
                    {{ product.Description._c }}
                </td>
            </tr>
            <tr>
                <td>Preis:</td>
                <td>{{ product.RecurringFee }}</td>
            </tr>
        </table>
    </div>
</template>

<script>
export default {
    name: "SessionProductDetails",
    props: ["product", "title"],
};
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";

.product {
    border: 2px solid $brand-color-primary;
    border-collapse: unset;

    tr {
        td {
            padding: 0.5rem 0.6rem;
        }

        td:first-child {
            width: 25%;
        }
    }
}

.pre-formatted {
    white-space: pre-line;
}
</style>
