<template>
    <div>
        <form>
            <div class="row">
                <p class="col-sm-4 offset-2">Anfang</p>
                <p class="col-sm-4">Ende</p>
                <p class="col-sm-2">Pause</p>
            </div>

            <schedule-inputs v-for="(day, key) in schedule" :singleday="day" :key="key"></schedule-inputs>

        </form>
        @if(session('success'))
            <button class="btn btn-primary" value="true" name="ready">Speichern</button>
            <button class="btn btn-secondary" value="false" name="ready">Ändern</button>
        @else
            <button class="btn btn-primary" value="false" name="ready">Ändern</button>
        @endif
    </div>
</template>

<script>
export default {
    props: ['schedule']
}
</script>

