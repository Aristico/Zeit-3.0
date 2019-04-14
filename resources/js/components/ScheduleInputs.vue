<template>
        <div class="row">
            <div class="col-sm-2">
                <p>{{singleday.name_of_day}}</p>
            </div>

            <input type="hidden" :name="arrayForScheduleData('day')" :value="singleday.day">
            <input type="hidden" :name="arrayForScheduleData('user_id')" :value="singleday.user_id">
            <input type="hidden" :name="arrayForScheduleData('version')" :value="singleday.version">

            <div class="form-group col-sm-4">
                <label :for="arrayForScheduleData('begin')" class="sr-only">Beginn:</label>
                <input title="begin" class="form-control" type="time"
                    :name="arrayForScheduleData('begin')"
                    v-model="dayBegin"
                    :id="arrayForScheduleData('begin')"
                    :class="invalidClass(dayBegin)"
                >

            </div>
            <div class="form-group  col-sm-4">
                <label :for="arrayForScheduleData('end')" class="sr-only">Ende:</label>
                <input title="end" class="form-control" type="time"
                    :name="arrayForScheduleData('end')"
                    v-model="dayEnd"
                    :id="arrayForScheduleData('end')"
                    :class="invalidClass(dayEnd)"
                >
            </div>
            <div class="form-group col-sm-2">
                <label :for="arrayForScheduleData('break')" class="sr-only">Pause</label>
                <input title="break" class="form-control" type="number"
                    :name="arrayForScheduleData('break')"
                    v-model="dayBreak"
                    :id="arrayForScheduleData('end')"
                    :class="invalidClass(dayBreak)"
                >
            </div>
        </div>
</template>

<script>
export default {
    props: ['singleday'],
    data () {
        return {
            day: this.singleday.day,
            currentbreak: this.singleday.break,
            dayBegin: this.singleday.begin,
            dayEnd: this.singleday.end,
            dayBreak: this.singleday.break
        }
    },
    computed: {
        nameOfDay () {
            return days[index]
        }
    },
    methods: {
        arrayForScheduleData ( field ) {
            return `day[${this.day}][${field}]`;
        },
        isValid (value) {
            return value != "";
        },
        invalidClass (value) {
            return {
                'is-invalid' : !this.isValid(value)
            }
        }
    }

}
</script>
