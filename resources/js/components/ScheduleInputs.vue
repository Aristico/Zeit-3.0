<template>
        <div class="row">
            <div v-if="day.active" class="col-sm-2">
                <p>{{day.name_of_day}}</p>
            </div>

            <input type="hidden" :name="arrayForScheduleData('day')" :value="day.day">
            <input type="hidden" :name="arrayForScheduleData('user_id')" :value="day.user_id">
            <input type="hidden" :name="arrayForScheduleData('version')" :value="day.version">

            <div v-if="day.active" class="form-group col-sm-4">
                <label :for="arrayForScheduleData('begin')" class="sr-only">Beginn:</label>
                <input title="begin" class="form-control" type="time"
                    :name="arrayForScheduleData('begin')"
                    v-model="day.begin"
                    :id="arrayForScheduleData('begin')"
                    :class="invalidClass(validateInputs.begin)"
                >

            </div>
            <input v-if="!day.active" type="hidden" :name="arrayForScheduleData('begin')" :value="day.begin">

            <div v-if="day.active" class="form-group  col-sm-4">
                <label :for="arrayForScheduleData('end')" class="sr-only">Ende:</label>
                <input title="end" class="form-control" type="time"
                    :name="arrayForScheduleData('end')"
                    v-model="day.end"
                    :id="arrayForScheduleData('end')"
                    :class="invalidClass(validateInputs.end)"
                >
            </div>
            <input v-if="!day.active" type="hidden" :name="arrayForScheduleData('end')" :value="day.end">

            <div v-if="day.active" class="form-group col-sm-2">
                <label :for="arrayForScheduleData('break')" class="sr-only">Pause</label>
                <input title="break" class="form-control" type="number"
                    :name="arrayForScheduleData('break')"
                    v-model="day.break"
                    :id="arrayForScheduleData('end')"
                    :class="invalidClass(validateInputs.break)"
                >
            </div>
            <input v-if="!day.active" type="hidden" :name="arrayForScheduleData('break')" :value="day.break">

        </div>
</template>

<script>
export default {
    props: ['singleday', 'validate'],
    data () {
        return {
            day: this.singleday,
            validationMessages: []
        }
    },
    watch: {
        validate () {
            if (this.validate === true && this.day.active) {
                this.setValidationMessages();
                this.day.workingHours = this.dayWorkingHours;
                this.$emit('validationResult',
                    {'singleDay': this.day,
                     'inputsValid': this.validateInputs.begin &&
                                    this.validateInputs.end &&
                                    this.validateInputs.break
                    });
            }
        }
    },
    computed: {
        dayWorkingHours () {
            return this.calculateWorkingHours(this.day);
        },
        validateInputs () {
            let checkTimeRelation = this.createDate(this.day.end) - this.createDate(this.day.begin) > 1000*60*60;
            let checkBreak = (this.calculateWorkingHours(this.day) > 6 && this.day.break >= 30)
                             || (this.calculateWorkingHours(this.day) <= 6 && this.day.break >= 0)
            return {
                break: checkBreak && this.isValid(this.day.break),
                begin: checkTimeRelation && this.isValid(this.day.begin),
                end: checkTimeRelation && this.isValid(this.day.end)
            }
        }
    },
    methods: {
        valitdateBeginBeforeEnd () {
            if (this.createDate(this.day.end) - this.createDate(this.day.begin) > 1000*60*60)
            { return true;}
                else
            { return false;}
        },
        validateBreak () {
            if ((this.calculateWorkingHours(this.day) > 6 && this.day.break >= 30)
                || (this.calculateWorkingHours(this.day) <= 6 && this.day.break >= 0))
                {
                    return true;
                } else {
                    return false;
                }

        },
        setValidationMessages () {
            if (!this.valitdateBeginBeforeEnd() && this.isValid(this.day.begin) && this.isValid(this.day.end)) {
                this.validationMessages.push(`Am ${this.day.name_of_day} liegt der Begin der Arbeitszeit nicht Mindestens eine Stunde vor dem Ende`);
            }
            if (!this.validateBreak()) {
                console.log('works');
                this.validationMessages.push(`Am ${this.day.name_of_day} muss die Arbeitszeit mindestens 30 Minute betragen`);
            }
            if (!this.isValid(this.day.begin)) {
                this.validationMessages.push(`Am ${this.day.name_of_day} ist der Anfang nicht vollständig gefüllt`);
            }
            if (!this.isValid(this.day.end)) {
                this.validationMessages.push(`Am ${this.day.name_of_day} ist das Ende nicht vollständig gefüllt`);
            }
            if (!this.isValid(this.day.break)) {
                this.validationMessages.push(`Am ${this.day.name_of_day} ist die Pause nicht vollständig gefüllt`);
            }
        },
        arrayForScheduleData ( field ) {
            return `day[${this.day.day}][${field}]`;
        },
        isValid (value) {
            return value != "";
        },
        invalidClass (value) {
            return {
                'is-invalid' : !value && this.validate
            }
        },
        createDate (time) {
            return new Date('2000-01-01 ' + time);
        },
        calculateWorkingHours (currentDay) {
            return Math.round((this.createDate(currentDay.end) - this.createDate(currentDay.begin) - currentDay.break*60*1000)/1000/60/60*4)/4;
        },
        valueChanged (value) {
            /*
            this.day.workingHours = this.dayWorkingHours;
            this.$emit('valueChanged',
                        {'day': this.day.day,
                         'workingHours': this.day.workingHours,
                         'inputsValid': this.validateInputs.begin &&
                                        this.validateInputs.end &&
                                        this.validateInputs.break
                        });*/
        }
    }


}
</script>
