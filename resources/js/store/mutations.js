export default {

    firstMutation() {
        console.log("first Mutation")
    }
    , setTopicDetails(state, topicDetails) {


        state.topicDetails[topicDetails.identifier.toLowerCase()] = topicDetails;
    },
    setMe(state, me) {

        state.me = me;


    }

}