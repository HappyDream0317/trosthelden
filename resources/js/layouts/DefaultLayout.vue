<template>
    <div class="default-layout">
        <page-header></page-header>
        <main class="container">
            <modal></modal>
            <slot></slot>
        </main>
        <page-footer></page-footer>
    </div>
</template>

<script>
import PageFooter from "../components/PageFooter";
import PageHeader from "../components/PageHeader";
import Modal from "../components/Modal";

export default {
    name: "DefaultLayout",
    components: { Modal, PageFooter, PageHeader },
};
</script>

<style scoped>
div.default-layout {
    min-height: 100%;
    display: flex;
    flex-direction: column;
}
main {
    flex: 1;
}
</style>
