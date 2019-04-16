<template>
    <div>
        <!--
        <form @submit.prevent="submitForm">
            -->
            <div class="row">
                <p class="col-sm-4 offset-2">Anfang</p>
                <p class="col-sm-4">Ende</p>
                <p class="col-sm-2">Pause</p>
            </div>

            <schedule-inputs v-for="(day, key) in schedule" :singleday="day" :key="key"></schedule-inputs>
            <div class="row">
            <h3>Schritt 1</h3>
                           <button class="btn btn-primary" value="false" name="ready">Ändern</button>
            </div>
            <div class="row">
            <h3>Schritt 2</h3>
                           <button class="btn btn-primary" value="true" name="ready">Speichern</button>
                           <button class="btn btn-secondary" value="false" name="ready">Ändern</button>
            </div>
       <!-- </form>
        -->
    </div>
</template>

<script>
export default {
    props: ['csrf', 'schedule'],
    data () {
        return {
            currentSchedule: this.schedule
        }
    },
    computed: {
        activeSchedule () {
            return this.currentSchedule.filter((value) => {
                return value.active;
            });
        }
    },
    created () {
        this.currentSchedule.forEach(element => {
            if (element.begin === null) {
                element.active = false;
            } else {
                element.active = true;
            }
        });
    }
}
</script>

