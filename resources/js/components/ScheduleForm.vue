<template>
    <div>
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

        <schedule-inputs @validationResult="updateWorkingHours" v-for="(day, key) in currentSchedule" :singleday="day" :key="key" :validate="validate"></schedule-inputs>

        <div v-if="inputNeedsValidationMessage" class="alert alert-danger mt-2">
            <p><strong>Bitte korrigieren Sie Ihre Eingabe</strong><br></p>
            <p>
                Bitte prüfen Sie die rot umrandeten Eingabefelder. Folgende Gründe kann diese Meldung haben:
                <ul>
                    <li>Das Feld enthält keinen oder einen unvollständigen Wert.</li>
                    <li>Die Zeit im Feld Anfang liegt nicht mindestens eine Stunde vor der Zeit im Feld Ende.</li>
                    <li>Der Wert im Feld Pause ist unter 30 Minuten und die Arbeitszeit des Tages liegt über 6 Stunden.</li>
                </ul>
            </p>
        </div>
        <button @click.prevent="startValidation" class="btn btn-primary mt-3" name="ready">Ändern</button>

    </div>
</template>

<script>
export default {
    props: ['csrf', 'schedule'],
    data () {
        return {
            currentSchedule: this.schedule,
            sumOfWorkingHours: 0,
            validate: false,
            inputsValid: [],
            inputNeedsValidationMessage: false
        }
    },
    methods: {
        startValidation () {
            this.validate = true;
        },
        createDate (time) {
            return new Date('2000-01-01 ' + time);
        },
        calculateWorkingHours (currentDay) {
            return Math.round((this.createDate(currentDay.end) - this.createDate(currentDay.begin) - currentDay.break*60*1000)/1000/60/60*4)/4;
        },
        updateWorkingHours (values) {
            /*this.currentSchedule[values.day-1].workingHours = values.workingHours;
            this.inputsValid[values.day-1] = values.inputsValid;

            let sum = 0;
            this.currentSchedule.forEach(element => {
                element.workingHours > 0 ? sum += element.workingHours : sum +=0 ;
            });
            this.sumOfWorkingHours = sum;

            this.inputNeedsValidationMessage = false;
            this.inputsValid.forEach(element => {
                if (element === false) {this.inputNeedsValidationMessage = true;}
            })
            */
        }
    },
    created () {
        /*let sum = 0;*/
        this.currentSchedule.forEach(element => {
            if (element.begin === null) {
                element.active = false;
            } else {
                element.active = true;
            }
            element.workingHours = 0; /*this.calculateWorkingHours(element);*/
            /*element.workingHours > 0 ? sum += element.workingHours : sum +=0 ;
            this.sumOfWorkingHours = sum;*/
        })
    }
}
</script>

