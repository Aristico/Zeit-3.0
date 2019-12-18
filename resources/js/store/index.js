import Vue from 'vue'
import Vuex from 'vuex'

function createDate (time) {
    return new Date('2000-01-01 ' + time);
}

function calculateWorkingHours (entryOfDay) {

        let hours = Math.round((createDate(entryOfDay.end) - createDate(entryOfDay.begin) - entryOfDay.break*60*1000)/1000/60/60*4)/4;
        if(isNaN(hours)) {
            return null;
        } else {
            return hours;
        }
}

Vue.use(Vuex)
export default new Vuex.Store({
    state: {
        schedule: null
    },
    actions: {

    },
    getters: {
        workingHoursPerWeek (state) {
            let sumOfWorkingHours = 0;

            state.schedule.forEach(entry => {
                sumOfWorkingHours += entry.workingHours;
            })
            return sumOfWorkingHours;
        },
        dayNameOf (state, index) {
            return state.schedule[index].name_of_day
        },
        dayStatusActive (state, index) {
            return state.schedule[index].day_active
        }


    },
    mutations: {
        initSchedule (state, scheduleData) {
            Vue.set(state, 'schedule', scheduleData)
            state.schedule.forEach(element => {
                Vue.set(element, 'workingHours', calculateWorkingHours(element))
            })
        },
        toggleDayActive (state, indexOfDay) {
            state.schedule[indexOfDay].day_active = !state.schedule[indexOfDay].day_active
        }
    }
})

/*

  setItem (state, {item, id, resource}) {
    item['.key'] = id
    Vue.set(state[resource], id, item)
  }

*/


