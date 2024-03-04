<template>
    <div class="d-flex">
        <select
            v-model="day"
            class="form-control"
            :id="'questionDaySelect_' + questionId"
            :class="'date_day question_' + questionId"
        >
            <option :value="nullValue" disabled>Tag</option>
            <option v-for="optionDay in days" :key="optionDay">
                {{ optionDay }}
            </option>
        </select>
        <select
            v-model="month"
            class=""
            :id="'questionMonthSelect_' + questionId"
            :class="'form-control mx-3 date_month question_' + questionId"
        >
            <option :value="nullValue" disabled>Monat</option>
            <option
                v-for="(optionMonth, index) in months"
                :key="index + 1"
                :value="index + 1"
            >
                {{ optionMonth }}
            </option>
        </select>
        <select
            v-model="year"
            class="form-control"
            :id="'questionYearSelect_' + questionId"
            :class="'date_year question_' + questionId"
        >
            <option :value="nullValue" disabled>Jahr</option>
            <option v-for="yearOption in years" :key="yearOption">
                {{ yearOption }}
            </option>
        </select>
        <tooltip
            class="ms-3"
            v-if="tooltipContent"
            :title="tooltipTitle"
            :content="tooltipContent"
        ></tooltip>
    </div>
</template>

<script>
import Tooltip from "../Tooltip";
const startYear = 1920;
const lastDayOfMonth = 31;

export default {
    name: "AnswerTypeDate",
    emits: ["input"],
    components: { Tooltip },
    props: {
        id: Number,
        questionId: Number,
        value: Object,
        allowFutureDates: {
            type: Boolean,
            default: false,
        },
        minYearsAgo: { type: Number, default: null },
        tooltipTitle: { default: null },
        tooltipContent: { default: null },
    },
    data() {
        return {
            // workaround for null as option in select
            nullValue: null,
        };
    },
    methods: {
        initValue() {
            let value = {};
            value["day"] = null;
            value["month"] = null;
            value["year"] = null;
            return value;
        },
        needsInit() {
            return (
                this.value === null ||
                typeof this.value !== "object" ||
                !this.value.hasOwnProperty("day") ||
                !this.value.hasOwnProperty("month") ||
                !this.value.hasOwnProperty("year")
            );
        },
        getDateProp(propName) {
            if (
                this.value !== null &&
                typeof this.value === "object" &&
                this.value.hasOwnProperty(propName)
            ) {
                return this.value[propName];
            }
            return null;
        },
        setDateProp(propName, val) {
            let value = this.value;
            if (this.needsInit()) {
                value = this.initValue();
            }
            value[propName] = val;
            let now = new Date();
            if (
                !this.allowFutureDates &&
                value.hasOwnProperty("day") &&
                value.day !== null &&
                value.hasOwnProperty("month") &&
                value.month !== null &&
                value.hasOwnProperty("year") &&
                value.year !== null &&
                new Date(value.year, value.month - 1, value.day) > now
            ) {
                value["year"] = now.getFullYear();
                value["month"] = now.getMonth() + 1;
                value["day"] = now.getDate();
            }
            this.$emit("input", value);
        },
    },
    computed: {
        day: {
            get() {
                return this.getDateProp("day");
            },
            set(val) {
                this.setDateProp("day", val);
            },
        },
        month: {
            get() {
                return this.getDateProp("month");
            },
            set(val) {
                this.setDateProp("month", val);
            },
        },
        year: {
            get() {
                return this.getDateProp("year");
            },
            set(val) {
                this.setDateProp("year", val);
            },
        },
        years() {
            let currentYear = new Date().getFullYear();
            if (this.minYearsAgo) {
                currentYear = currentYear - this.minYearsAgo;
            }
            return new Array(currentYear - startYear + 1)
                .fill(startYear)
                .map((start, index) => start + index)
                .reverse();
        },
        months() {
            return [
                "Januar",
                "Februar",
                "März",
                "April",
                "Mai",
                "Juni",
                "Juli",
                "August",
                "September",
                "Oktober",
                "November",
                "Dezember",
            ];
        },
        days() {
            let unselected = this.year === null || this.month === null;
            let lastDay = lastDayOfMonth;
            // If year and month are selected, calculate the max. possible day
            if (!unselected) {
                lastDay = new Date(this.year, this.month, 0).getDate();
                if (lastDay < this.day) {
                    this.day = lastDay;
                }
            }
            return new Array(lastDay)
                .fill(1)
                .map((start, index) => start + index);
        },
    },
};
</script>

<style scoped>
.form-check {
    padding: 0;
}
</style>
