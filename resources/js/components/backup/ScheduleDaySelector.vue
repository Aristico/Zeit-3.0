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
    props: ['dayActive', 'dayNameOf', 'item'],
    data () {
        return {
            active: this.dayActive,
            name: this.dayNameOf,
            itemKey: this.item,
            activeClass: ''
        }
    },
    computed: {
        activityClass () {
            if (this.active === true) {
                return 'badge-primary'
            } else {
                return 'badge-secondary'
            }
        }
    },
    methods: {
        toggleDayActive () {
            this.active = !this.active;
            this.$emit('toggleDay', {
                'key': this.itemKey,
                'active': this.active
            })
        }

    }
}
</script>


