export default {

    firstGetters() {
        return "first firstGetters";
    },
    getBudg: (state) => id => {

        return state.topicDetails[id]
    },
    getExperties(state, getters) {

        try {
            return JSON.parse(state.me.profile.experties)
        } catch (error) {

        }
    },
    authUser(state, getters) {

        try {
            return state.me ? state.me : JSON.parse(localStorage.getItem("authUser"))
        } catch (error) {

        }
    }


}