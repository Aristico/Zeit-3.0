<template>
    <div>
        <span class="badge mr-1"
            :class="activityClass"
            @click="toggleDayActive"
        >{{ name.substring(0,2)}}</span>
    </div>
</template>

<script>
export default {
    props: ['item'],
    data () {
        return {
            name: this.dayNameOf,
            itemKey: this.item,
            activeClass: ''
        }
    },
    computed: {
        dayActive () {
            return this.$store.getters.dayStatusActive(this.itemKey)
        },
        dayNameOf () {
            return this.$store.getters.dayNameOf(this.itemKey)
        },
        activityClass () {
            if (this.dayActive === true) {
                return 'badge-primary'
            } else {
                return 'badge-secondary'
            }
        }
    },
    methods: {
        toggleDayActive () {
            this.$store.commit('toggleDayActive', this.itemKey)
        }

    }
}
</script>


