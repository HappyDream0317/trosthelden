<template>
    <div class="col-12 rounded px-3 py-2">
        <div class="row mb-2">
            <div class="col-12 my-auto text-center">
                Dein Profil ist zu<br /><span class="color--primary"
                    >{{ progress_total }}% vollständig</span
                >
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "UserInfoPanelProgress",
    data: function () {
        return {
            progress_total: 0,
        };
    },
    mounted: function () {
        axios
            .get("/api/profile/progress")
            .then((response) => {
                this.progress_total = response.data.progress;
            })
            .catch((err) => {
                console.log(err);
            });
    },
};
</script>

<style scoped></style>
