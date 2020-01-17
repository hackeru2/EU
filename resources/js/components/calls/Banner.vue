<template>
    <el-row>
        <div
            class="single-page-header margin-bottom-10"
            style="padding: 10px 0;"
            data-background-image="images/call.jpg"
        >
            <div class="dashboard-header" style="z-index: 100; position: relative; padding: 50px;">
                <div class="row">
                    <div class="col-md-12">
                        <el-button>
                            <router-link to="/calls">back</router-link>
                        </el-button>
                        <div class="single-page-header-inner">
                            <div class="left-side" style="align-items: flex-start;">
                                <div class="header-image">
                                    <img
                                        class="programme_logo"
                                        src="https://eucalls.net/template/img/programme/horizon2020.png"
                                    />
                                </div>
                                <div class="header-details">
                                    <h5
                                        class="programme_name"
                                        style="color: #808080; font-weight: 500;"
                                    >Horizon2020</h5>
                                    <h3 class="title">Advanced Functions towards Autonomous Trains</h3>
                                    <ul>
                                        <li>
                                            <i class="icon-line-awesome-hourglass-2"></i> Deadline:
                                            <span
                                                class="deadline"
                                                style="color:#2a41e8;"
                                            >21/04/2020</span>
                                        </li>
                                    </ul>
                                    <div class="task-tags margin-top-10 sector_container">
                                        <span>
                                            <i class="icon-line-awesome-lightbulb-o"></i> Innovation
                                        </span>

                                        <span>
                                            <i class="icon-line-awesome-truck"></i> Transport
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="right-side">
                                <a
                                    class="share_link share-icon"
                                    href="mailto:?Subject=I%20found%20a%20funding%20opportunity%20in%20EUcalls&amp;Body=Hello%2C%0A%0AI%20found%20the%20following%20funding%20opportunity%20in%20EUcalls.net%20that%20might%20interest%20you%3A%0A%0AAdvanced Functions towards Autonomous Trains%0A%0A%0AYou%20can%20view%20it%20here%3A%20%20https://eucalls.net/dashboard/call?id=36939%0A%0AEUcalls%20is%20the%20most%20complete%20directory%20with%20the%20latest%20information%20about%20European%20Union%20funding%20opportunities%20and%20an%20active%20network%20of%20partners%20that%20can%20help%20you%20shape%20a%20consortium.%20You%20can%20subscribe%20for%20free%20in%20www.eucalls.net%0A"
                                    data-tippy-placement="left"
                                    title="Share by Email"
                                ></a>
                                <div class="salary-box">
                                    <div class="salary-amount" style="font-size: 25px;">
                                        <i class="icon-line-awesome-hourglass-2" data-call_id></i>
                                        <span class="date_relative">in 3 months</span>
                                    </div>
                                </div>
                                <span
                                    class="bookmark-icon bookmark-call bookmark_button"
                                    title="Click here to bookmark this call"
                                    data-tippy-placement="left"
                                    data-call_id="36939"
                                ></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="background-image-container"
                style="background-image: url('../images/call.jpg');"
            ></div>
        </div>
    </el-row>
</template>

<script>
export default {};
</script>

<style>
.single-page-header .single-page-header-inner {
    display: flex;
}
</style>