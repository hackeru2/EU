
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
        console.log(axios.defaults.baseURL)
        if (me.profile.topics) commit('setMeTopics', me.profile.topics);
        if (me.tags && me.tags.length) commit('setMeTags', me.tags);
        return me;

    },
    async meTagsAct({ state, getters, commit, dispatch }) {
        let { data: me
        } = await axios.get(`me`);
        if (me.tags && me.tags.length) commit('setMeTags', me.tags);
        return me;
    },
    async callProgram({ state, getters, commit, dispatch }) {
        let { data: callProgram
        } = await axios.get(`programmes`);
        commit('setAllProgrammes', callProgram.all);
        commit('setMainProgrammes', callProgram.level2);
        // console.log(axios.defaults.baseURL)
        // if (me.profile.topics) commit('setMeTopics', me.profile.topics);


    },

    async keywordsAct({ state, getters, commit, dispatch }, text) {

        let { data: keywords
        } = await axios.get(`keywords`);
        await commit('setKeywords', keywords);
        if (getters.authUser.profile)
            if (getters.authUser.profile.keywords_ccm2_Ids)
                await commit('setMeKeywords', getters.authUser.profile.keywords_ccm2_Ids);

    },
    async getBigJsonAct() {
        let { data: bigJson } = await axios.get("big-json");

        return bigJson;
    },
    async getTags({ dispatch }) {
        return await dispatch('getBigJsonAct').then(r =>
            r.filter(r => r.status.abbreviation != "Closed" && r.status.description != "Closed")
                .map(a => a.tags).filter(a => a).flat())


    },

    async tagsUnique({ dispatch }) {
        let gtu = await dispatch('getTags').then(r => { return [...new Set([...r])]; })
        return gtu.map((a, i) => { return { name: a, id: i, checked: false } });
    },
    async insertTags(context, data) {
        let { data: tags } = await axios.post("tags", data);
        console.log({ tags })
    },
    async  Tags() {
        let { data: tags } = await axios.get("tags");
        // console.log({ tags })
        return tags;
    }
    , async inserUserProfileTags({ state, commit, getters, dispatch }, data) {

        let { data: tags } = await axios.post("insert-user-tags", data);
        console.log({ data });

    }
    , async saveTagAct({ state, commit, getters, dispatch }, payload) {

        let { data: tags } = await axios.post("call", payload);
        console.log({ tags });

    },
    async getSubjects({ state, commit }) {
        let { data: subjects } = await axios.get('subject');
        commit('setSubjects', subjects);
        return subjects;
    },
    async pushNewHeader({ state, commit }, payload) {

        let { data: subjects } = await axios.post('subject', payload);
        commit('setSubjects', subjects);
        return subjects;
    }
}