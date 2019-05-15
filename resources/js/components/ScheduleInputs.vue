<template>
        <div class="row">
            <input type="hidden" :name="arrayForScheduleData('day')" :value="day.day">
            <input type="hidden" :name="arrayForScheduleData('user_id')" :value="day.user_id">
            <input type="hidden" :name="arrayForScheduleData('version')" :value="day.version">

            <template v-if="day.day_active">
                <div class="col-sm-2">
                    <p>{{day.name_of_day}}</p>
                </div>

                <div class="form-group col-sm-4">
                    <label :for="arrayForScheduleData('begin')" class="sr-only">Beginn:</label>
                    <input title="begin" class="form-control" type="time"
                        :name="arrayForScheduleData('begin')"
                        v-model="day.begin"
                        :id="arrayForScheduleData('begin')"
                        :class="invalidClass(validationResult.begin)"
                        :readonly="validationSuccess"
                    >
                </div>
                <div class="form-group  col-sm-4">
                    <label :for="arrayForScheduleData('end')" class="sr-only">Ende:</label>
                    <input title="end" class="form-control" type="time"
                        :name="arrayForScheduleData('end')"
                        v-model="day.end"
                        :id="arrayForScheduleData('end')"
                        :class="invalidClass(validationResult.end)"
                        :readonly="validationSuccess"
                    >
                </div>

                <div class="form-group col-sm-2">
                    <label :for="arrayForScheduleData('break')" class="sr-only">Pause</label>
                    <input title="break" class="form-control" type="number"
                        :name="arrayForScheduleData('break')"
                        v-model="day.break"
                        :id="arrayForScheduleData('end')"
                        :class="invalidClass(validationResult.break)"
                        :readonly="validationSuccess"
                    >
                </div>


            </template>
            <template v-else>
                <input type="hidden" :name="arrayForScheduleData('begin')" :value="null">
                <input type="hidden" :name="arrayForScheduleData('end')" :value="null">
                <input type="hidden" :name="arrayForScheduleData('break')" :value="null">
            </template>

        </div>
</template>

<script>
export default {
    props: ['singleday', 'validate', 'validationSuccess'],
    data () {
        return {
            day: this.singleday,
            validationMessages: [],
            validationResult: {
                'break': true,
                'begin': true,
                'end': true
            }
        }
    },
    watch: {
        validate: function () {
            if (this.validate === true && this.day.day_active) {
                this.validateInputs();
                this.setValidationMessages();
                this.day.workingHours = this.dayWorkingHours;
                this.$emit('validationResult',
                    {   'day': this.day,
                        'validationMessages' : this.validationMessages });
                    }
        }

    },
    computed: {
        dayWorkingHours () {
            if (this.validationResult.begin && this.validationResult.end && this.validationResult.break) {

                let hours = Math.round((this.createDate(this.day.end) - this.createDate(this.day.begin) - this.day.break*60*1000)/1000/60/60*4)/4;
                if(isNaN(hours)) {
                    return null;
                } else {
                    return hours;
                }

                //return this.calculateWorkingHours(this.day);
            } else {
                return null;
            }

        }
    },
    methods: {
        validateInputs () {

            let checkTimeRelation = this.createDate(this.day.end) - this.createDate(this.day.begin) > 1000*60*60;
            let checkBreak = (this.dayWorkingHours > 6 && this.day.break >= 30)
                             || (this.dayWorkingHours <= 6 && this.day.break >= 0)

            this.validationResult.break = checkBreak && this.isValid(this.day.break),
            this.validationResult.begin = checkTimeRelation && this.isValid(this.day.begin),
            this.validationResult.end = checkTimeRelation && this.isValid(this.day.end)

        },
        valitdateBeginBeforeEnd () {
            if (this.createDate(this.day.end) - this.createDate(this.day.begin) > 1000*60*60)
            { return true;}
                else
            { return false;}
        },
        validateBreak () {
            if ((this.dayWorkingHours > 6 && this.day.break >= 30)
                || (this.dayWorkingHours <= 6 && this.day.break >= 0))
                {
                    return true;
                } else {
                    return false;
                }

        },
        setValidationMessages () {
            this.validationMessages = [];
            if (!this.valitdateBeginBeforeEnd() && this.isValid(this.day.begin) && this.isValid(this.day.dnd)) {
                this.validationMessages.push(`Am ${this.singleday.name_of_day} liegt der Begin der Arbeitszeit nicht Mindestens eine Stunde vor dem Ende`);
            }
            if (!this.validateBreak() && this.isValid(this.day.break)) {
                this.validationMessages.push(`Am ${this.singleday.name_of_day} muss die Pause mindestens 30 Minute betragen`);
            }
            if (!this.isValid(this.day.begin)) {
                this.validationMessages.push(`Am ${this.singleday.name_of_day} ist der Anfang nicht vollständig gefüllt`);
            }
            if (!this.isValid(this.day.end)) {
                this.validationMessages.push(`Am ${this.singleday.name_of_day} ist das Ende nicht vollständig gefüllt`);
            }
            if (!this.isValid(this.day.break)) {
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
        }
    }


}
</script>
