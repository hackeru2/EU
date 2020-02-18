
export default {
    firstAction() {
        console.log("first action")
    },
    async saveProfileAct({ state, getters, commit, dispatch }) {
        let { data: profile
        } = await axios.put(`profile/1`, {
            type: 'saveProfileFirstPart',
            pic: "",
            name: 'litz',
            category: 4,
            country: 81,
            city: 'tel aviv',
            legalForm: 2,
            employee: 2,
            turnover: 0,
            description: 'security firm',
        });
    },
    async saveOrganizationAct({ state, getters, commit, dispatch }, experties) {

        let { data: profile_experties
        } = await axios.put(`profile/1`, {
            experties
        });
    },

    async getTopicDetails({ state, getters, commit, dispatch }, identifier) {
        let { data: topicDetails
        } = await axios.get(`get-topic-details/${identifier}`);
        //console.log({ topicDetails });
        //        console.log({ commit });

        commit('setTopicDetails', topicDetails);
        return topicDetails
        // state.topicDetails[identifier] = topicDetails;
        //https://ec.europa.eu/info/funding-tenders/opportunities/data/topicDetails/dt-tds-01-2019.json
        //alert(identifier)
    },
    async meAct({ state, getters, commit, dispatch }) {
        let { data: me
        } = await axios.get(`me`);
        commit('setMe', me);

        if (me.profile.topics) commit('setMeTopics', me.profile.topics);


    },
    async keywordsAct({ state, getters, commit, dispatch }, text) {

        let { data: keywords
        } = await axios.get(`keywords`);
        await commit('setKeywords', keywords);
        if (getters.authUser.profile)
            if (getters.authUser.profile.keywords_ccm2_Ids)
                await commit('setMeKeywords', getters.authUser.profile.keywords_ccm2_Ids);

    }

}