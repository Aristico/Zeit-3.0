<template>
        <div class="row">
            <div v-if="singleday.active" class="col-sm-2">
                <p>{{singleday.name_of_day}}</p>
            </div>

            <input type="hidden" :name="arrayForScheduleData('day')" :value="singleday.day">
            <input type="hidden" :name="arrayForScheduleData('user_id')" :value="singleday.user_id">
            <input type="hidden" :name="arrayForScheduleData('version')" :value="singleday.version">

            <div v-if="singleday.active" class="form-group col-sm-4">
                <label :for="arrayForScheduleData('begin')" class="sr-only">Beginn:</label>
                <input title="begin" class="form-control" type="time"
                    :name="arrayForScheduleData('begin')"
                    v-model="dayBegin"
                    :id="arrayForScheduleData('begin')"
                    :class="invalidClass(validationResult.begin)"
                    :readonly="validationSuccess"
                >

            </div>
            <input v-if="!singleday.active" type="hidden" :name="arrayForScheduleData('begin')" :value="null">

            <div v-if="singleday.active" class="form-group  col-sm-4">
                <label :for="arrayForScheduleData('end')" class="sr-only">Ende:</label>
                <input title="end" class="form-control" type="time"
                    :name="arrayForScheduleData('end')"
                    v-model="dayEnd"
                    :id="arrayForScheduleData('end')"
                    :class="invalidClass(validationResult.end)"
                    :readonly="validationSuccess"
                >
            </div>
            <input v-if="!singleday.active" type="hidden" :name="arrayForScheduleData('end')" :value="null">

            <div v-if="singleday.active" class="form-group col-sm-2">
                <label :for="arrayForScheduleData('break')" class="sr-only">Pause</label>
                <input title="break" class="form-control" type="number"
                    :name="arrayForScheduleData('break')"
                    v-model="dayBreak"
                    :id="arrayForScheduleData('end')"
                    :class="invalidClass(validationResult.break)"
                    :readonly="validationSuccess"
                >
            </div>
            <input v-if="!singleday.active" type="hidden" :name="arrayForScheduleData('break')" :value="null">

        </div>
</template>

<script>
export default {
    props: ['singleday', 'validate', 'validationSuccess'],
    data () {
        return {
            dayActive: this.singleday.active,
            dayBegin: this.singleday.begin,
            dayEnd: this.singleday.end,
            dayBreak: this.singleday.break,
            validationMessages: [],
            workingHours: 0,
            validationResult: {
                'break': true,
                'begin': true,
                'end': true
            }
        }
    },/*
    created () {
        this.day = this.copyPropObject(this.singleday);
    },*/
    watch: {
        validate () {
            if (this.validate === true && this.dayActive) {
                this.validateInputs();
                this.setValidationMessages();
                this.workingHours = this.dayWorkingHours;
                this.$emit('validationResult',
                    {   'day': this.dayInformation,
                        'validationMessages' : this.validationMessages });
                    }
        },
        'singleday.active' : function () {
            console.log('this.singleday.active');
        }

    },
    computed: {
        dayWorkingHours () {
            if (this.validationResult.begin && this.validationResult.end && this.validationResult.break) {
                return this.calculateWorkingHours(this.dayInformation);
            } else {
                return null;
            }

        },
        dayInformation () {
            return {'begin': this.dayBegin,
                    'end': this.dayEnd,
                    'break': this.dayBreak,
                    'workingHours': this.workingHours,
                    'day': this.singleday.day,
                    'active': this.singleday.active}
        }
    },
    methods: {
        validateInputs () {
            let checkTimeRelation = this.createDate(this.dayEnd) - this.createDate(this.dayBegin) > 1000*60*60;
            let checkBreak = (this.calculateWorkingHours(this.dayInformation) > 6 && this.dayBreak >= 30)
                             || (this.calculateWorkingHours(this.dayInformation) <= 6 && this.dayBreak >= 0)

            this.validationResult.break = checkBreak && this.isValid(this.dayBreak),
            this.validationResult.begin = checkTimeRelation && this.isValid(this.dayBegin),
            this.validationResult.end = checkTimeRelation && this.isValid(this.dayEnd)

        },
        valitdateBeginBeforeEnd () {
            if (this.createDate(this.dayEnd) - this.createDate(this.dayBegin) > 1000*60*60)
            { return true;}
                else
            { return false;}
        },
        validateBreak () {
            if ((this.calculateWorkingHours(this.dayInformation) > 6 && this.dayBreak >= 30)
                || (this.calculateWorkingHours(this.dayInformation) <= 6 && this.dayBreak >= 0))
                {
                    return true;
                } else {
                    return false;
                }

        },
        setValidationMessages () {
            this.validationMessages = [];
            if (!this.valitdateBeginBeforeEnd() && this.isValid(this.dayBegin) && this.isValid(this.dayEnd)) {
                this.validationMessages.push(`Am ${this.singleday.name_of_day} liegt der Begin der Arbeitszeit nicht Mindestens eine Stunde vor dem Ende`);
            }
            if (!this.validateBreak() && this.isValid(this.dayBreak)) {
                console.log('works');
                this.validationMessages.push(`Am ${this.singleday.name_of_day} muss die Pause mindestens 30 Minute betragen`);
            }
            if (!this.isValid(this.dayBegin)) {
                this.validationMessages.push(`Am ${this.singleday.name_of_day} ist der Anfang nicht vollständig gefüllt`);
            }
            if (!this.isValid(this.dayEnd)) {
                this.validationMessages.push(`Am ${this.singleday.name_of_day} ist das Ende nicht vollständig gefüllt`);
            }
            if (!this.isValid(this.dayBreak)) {
                this.validationMessages.push(`Am ${this.singleday.name_of_day} ist die Pause nicht vollständig gefüllt`);
            }
        },
        arrayForScheduleData ( field ) {
            return `day[${this.singleday.day}][${field}]`;
        },
        isValid (value) {
            return value != "";
        },
        invalidClass (value) {
            return {
                'is-invalid' : !value
            }
        },
        createDate (time) {
            return new Date('2000-01-01 ' + time);
        },
        calculateWorkingHours (currentDay) {
            return Math.round((this.createDate(currentDay.end) - this.createDate(currentDay.begin) - currentDay.break*60*1000)/1000/60/60*4)/4;
        },
        copyPropObject(src) {
            return Object.assign({}, src);
        }
    }


}
</script>
