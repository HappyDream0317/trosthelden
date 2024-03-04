<template>
    <div class="d-flex justify-content-center align-items-center p-3">
        <div>
            Dein Profil ist zu
            <br />
            <span class="color--primary">{{ progress_total }}% vollständig</span>
        </div>
        <div v-if="tooltip_text" class="icon--info">
            <div class="tooltip_hover">
                <span class="tooltip">{{ tooltip_text }}</span>
                <i class="fas fa-info-circle me-2"> </i>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ProfileProgress.vue",
    props: ["progress_total", "tooltip_text"],
};
</script>

<style scoped>
.tooltip {
    visibility: hidden;
    width: 120px;
    background-color: #555;
    color: #fff;
    text-align: center;
    padding: 5px 0;
    border-radius: 6px;

    /* Position the tooltip text */
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -60px;

    /* Fade in tooltip */
    opacity: 0;
    transition: opacity 0.3s;
}

.tooltip::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #555 transparent transparent transparent;
}

.tooltip_hover:hover .tooltip {
    visibility: visible;
    opacity: 1;
}
</style>
