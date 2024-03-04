<template>
    <div>
        <div>
            {{ options.description }}
        </div>
        <div>
            <DialogOption
                :option="options.options.a.option"
                :action="options.options.a.action"
                @action="handleAction"
            ></DialogOption>
            <DialogOption
                :option="options.options.b.option"
                :action="options.options.b.action"
                @action="handleAction"
            ></DialogOption>
        </div>
    </div>
</template>

<script>
import DialogOption from "./DialogOption";
export default {
    name: "DialogOptionChoice",
    components: { DialogOption },
    props: {
        options: Object,
    },
    methods: {
        handleAction(action) {
            this.$emit("action", action);
        },
    },
};
</script>

<style scoped></style>
