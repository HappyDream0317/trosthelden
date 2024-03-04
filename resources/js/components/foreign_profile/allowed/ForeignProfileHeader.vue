<template>
    <div class="row">
        <div class="col-12 pagetitle">
            <p class="color--primary font-weight-not-bold h6">
                Profilansicht: {{ nickname }}
            </p>
        </div>
    </div>
</template>

<script>
export default {
    name: "ForeignProfileHeader",
    props: {
        nickname: String,
    },
};
</script>

<style scoped></style>
