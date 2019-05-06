<template>
    <div>
        <div class="row mb-3 alert alert-info d-flex justify-content-center">
            <schedule-day-selector
                v-for="(singleDay, key) in currentSchedule"
                :key="key"
                :item="key"
                :dayActive="singleDay.active"
                :dayNameOf="singleDay.name_of_day"
                @toggleDay="toggleDay">
            </schedule-day-selector>
        </div>
        <div class="row">
            <p class="col-sm-4 offset-2">Anfang</p>
            <p class="col-sm-4">Ende</p>
            <p class="col-sm-2">Pause</p>
        </div>

        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
                </div>
            </div>
        </div>

        <schedule-inputs
            @validationResult="computeValidationResults"
            v-for="(day, key) in currentSchedule"
            :singleday="day"
            :key="key"
            :validate="validate"
            :validationSuccess="validationsSuccessfull"></schedule-inputs>

        <div v-if="validationFailed" class="alert alert-danger mt-2">
            <p><strong>Bitte korrigieren Sie Ihre Eingabe</strong><br></p>
                <ul>
                    <li v-for="(message,key) in validationMessages" :key="key" >{{ message }}</li>
                </ul>
        </div>

        <div v-if="validationsSuccessfull" class="alert alert-success mt-1">
            Mit den von Ihnen gemachten Angaben ergibt sich eine Wochenarbeitszeit von {{ sumOfWorkingHours }} Stunden.
        </div>

        <button v-if="!validationsSuccessfull" @click.prevent="startValidation" class="btn btn-primary mt-1">Prüfen</button>

        <button v-if="validationsSuccessfull" @click.prevent="resetValidation" class="btn btn-secondary mt-1">Zurück</button>
        <button v-if="validationsSuccessfull" class="btn btn-primary mt-1">Speichern</button>

    </div>
</template>

<script>
export default {
    props: ['csrf', 'schedule'],
    data () {
        return {
            currentSchedule: [],
            sumOfWorkingHours: 0,
            validate: false,
            validationsSuccessfull: false,
            validationMessages: [],
            inputsValid: [],
            inputNeedsValidationMessage: false
        }
    },
    computed: {
        validationFailed () {
            return this.validationMessages.length > 0;
        }
    },
    methods: {
        calculateWorkingHours () {
            let sum = 0;
            this.currentSchedule.forEach(element => {
                sum += element.workingHours;

            });
            return sum;
        },
        startValidation () {
            this.validationMessages = [];
            this.validate = true;
            setTimeout(() => {
                this.validate = false;
                if (this.validationFailed === false) {
                    console.log('if this ' + this.validationFailed);
                    this.validationsSuccessfull = true;
                } else {
                    console.log('else ' + this.validationFailed);
                    this.validationsSuccessfull = false;
                }
            },250)
        },
        resetValidation () {
            this.validationsSuccessfull = false;
        },
        computeValidationResults (values) {
            this.currentSchedule[values.day.day-1].workingHours = values.day.workingHours;
            this.validationMessages.push.apply(this.validationMessages, values.validationMessages);
            this.validationMessages = [...new Set(this.validationMessages)];
            this.sumOfWorkingHours = this.calculateWorkingHours();
        },
        toggleDay(values) {
            this.currentSchedule[values.key].active = values.active;
        },
        copyPropObject(src) {
            return Object.assign({}, src);
        },
        copyPropArray(src) {
            return array.assign([], src);
        }
    },
    created () {
        this.schedule.forEach(element => {
           this.currentSchedule.push(this.copyPropObject(element));
        })

        this.currentSchedule.forEach(element => {
            if (element.begin === null) {
                element.active = false;
            } else {
                element.active = true;
            }
            element.workingHours = 0;
        })
    }
}
</script>

