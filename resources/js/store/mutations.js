export default {

    firstMutation() {
        console.log("first Mutation")
    }
    , setTopicDetails(state, topicDetails) {


        state.topicDetails[topicDetails.identifier.toLowerCase()] = topicDetails;
    },
    setMe(state, me) {

        state.me = me;
        state.meKeywords = me.keywords;
        localStorage.setItem("authUser", JSON.stringify(me));

    },
    setSubjectTitle(state, subjectTitle) {
        state.subjectTitle = subjectTitle;
    },
    setSubjects(state, subjects) {
        if (!subjects) state.subjects = [];
        else state.subjects = subjects;
    },
    setMeTags(state, meTags) {

        state.meTags = meTags;

    },
    setKeywords(state, keywords) {

        state.keywords = keywords;
        //localStorage.setItem("authUser", JSON.stringify(me));

    },
    setTree(state, tree) {
        state.tree = tree;

    },
    setMCalls(state, mCalls) {
        state.mCalls = mCalls;
        console.log({ CALLMUTATIONS: state.mCalls })

    },
    setTreeName(state, treeName) {
        state.treeName = treeName;

    },
    setUpdateFlags(state, updateFlags) {
        state.updateFlags = updateFlags;
    },
    setGroupedKeyWords(state, groupedKeyWords) {

        state.groupedKeyWords = groupedKeyWords;

    },
    setTopicKeywords(state, nodes) {
        // console.log(state.treeName)
        // console.log(state.topics)

        state.topics.find(a => a.name == state.treeName).keywords = nodes
        //state.topics.find(a => a.name == state.treeName).active = true
        //console.log(state.topics)

        //{ treeName: this.treeName, nodes }
    },
    setTopicActive(state, isTopicActive) {


        state.topics.find(a => a.name == state.treeName).active = isTopicActive;
    },
    setMeTopics(state, meTopics) {

        if (!meTopics.includes(",")) return state.topics.find(t => t.name == meTopics).active = true;
        meTopics.split(',').forEach(topic => {

            state.topics.find(t => t.name == topic).active = true;
        });


    },
    setMeKeywords(state, meKeywords) {

        //if (!meKeywords.includes(",")) return state.topics.find(t => t.name == meTopics).active = true;
        state.meKeywords = state.keywords.filter(

            k => meKeywords.includes(k.ccm2_Id)
        );
        // meTopics.split(',').forEach(topic => {
    },
    deleteFromMeKyWords(state, data) {

        try {
            let i = state.meKeywords.findIndex(mk => mk.ccm2_Id == data.ccm2_Id)
            state.meKeywords.splice(i, 1);
        } catch (e) {
            console.error(e)
        }
    },
    pushMeKyWords(state, data) {

        console.log(data.ccm2_Id, state.meKeywords.every(k => k.ccm2_Id != data.ccm2_Id))
        if (state.meKeywords.every(k => k.ccm2_Id != data.ccm2_Id))
            state.meKeywords.push(data);
    }
    // setMeTopicsKeywords(state) {
    //     state.topics.forEach(topic => {
    //         if(topic.active) {

    //           state.meKeywords

    //         }

    //     });
    // }
    //     state.topics.find(t => t.name == topic).active = true;
    // });
    ,
    setAllProgrammes(state, programmes) {
        state.allProgrammes = programmes
    }
    ,
    setMainProgrammes(state, programmes) {
        state.mainProgrammes = programmes
    }

}