export const TYPE_TEXT = "text";
export const TYPE_SCALE = "skala";
export const TYPE_META = "meta";
export const TYPE_COLLAPSE_CHECKBOX = "collapse_checkbox";
export const TYPE_COLLAPSE_SELECT = "collapse_select";
export const TYPE_COLLAPSE_RADIO = "collapse_radio";
export const TYPE_COLLAPSE_MIXED = "collapse_mixed";
export const TYPE_RADIO = "radio";
export const TYPE_CHECKBOX = "checkbox";
export const TYPE_CHECKBOX_TEXT = "checkbox_text";
export const TYPE_DATE = "date";
export const TYPE_SELECT = "select";

export default class AnswerModel {
    constructor(question, answerObj, parentAnswerObj) {
        this.question = question;
        for (let prop in answerObj) {
            this[prop] = answerObj[prop];
        }

        //inherit answertype from parent if this is a child
        if (typeof parentAnswerObj !== "undefined") {
            this.different_answer_type = parentAnswerObj.different_answer_type;
        }
    }

    isScalar() {
        return (
            this.isAnswerType(TYPE_TEXT) ||
            this.isAnswerType(TYPE_SCALE) ||
            this.isAnswerType(TYPE_RADIO) ||
            this.isAnswerType(TYPE_CHECKBOX) ||
            this.isAnswerType(TYPE_DATE)
        );
    }

    isAnswerType(type) {
        let questionType = this.question.answer_type || { name: null };
        let answerType = this.different_answer_type || { name: null };
        let actualTypeName = answerType.name || questionType.name;
        return actualTypeName === type;
    }

    isRadio() {
        return (
            this.isAnswerType(TYPE_RADIO) ||
            this.isAnswerType(TYPE_COLLAPSE_RADIO)
        );
    }

    getChildren() {
        if (!Array.isArray(this.parent)) {
            return [];
        }
        return this.parent.map((child) => {
            return new AnswerModel(this.question, child);
        });
    }
}
