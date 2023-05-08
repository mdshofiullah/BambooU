<div class="featured_block">

    <div class="mainActiveFeedBlock white-pad">
        <div class="groupFeedList">

            <div class="displayGroupFeedList">
                <div class="">
                    <?php
                    $bp_ids = bp_get_pin_posts_ids();
                    // var_dump($bp_ids);
                    $ids = $bp_ids;
                    if (!empty($ids)):
                        $ids_array = $ids;
//var_dump($ids_array);
                        foreach ($ids_array as $activity_id)
                        {
                            $activity = bp_activity_get_specific( array(
                                'activity_ids' => $activity_id,
                            ) );
                            $activity = $activity['activities'][0];

                            $activity_meta = bp_activity_get_meta($activity_id);
                            $user_id = $activity->user_id;
                            $user = get_userdata( $user_id );
                            $full_name = $user->first_name . ' ' . $user->last_name;
                            $avatar = bp_core_fetch_avatar( array( 'item_id' => $user_id ) );
                            $username = bp_core_get_username( $user_id );
                            $post_time = $activity->date_recorded;
                            $formatted_post_time = date( 'F j, Y, g:i a', strtotime( $post_time ) );
                            $time_diff = human_time_diff( strtotime( $post_time ), current_time( 'timestamp' ) );

                            if(array_key_exists("bp_media_ids",$activity_meta))
                            {
                                $bp_photoids = $activity_meta['bp_media_ids'][0];  // get 1st photo id
                                $bp_getimage = bp_media_get_specific (array ('media_ids' => $bp_photoids) );
                                $photourl = $bp_getimage["medias"][0]->attachment_data->thumb;
                            }else{
                                $photourl = '';
                            }


                            if(array_key_exists("bp_video_ids",$activity_meta)){

                                $bp_video_ids = $activity_meta['bp_video_ids'][0]; // this is video id
                                $video_attachment_id_array = BP_Media::get( $args = array(
                                    'in'=> $bp_video_ids,
                                    'video'=> true,
                                ) );
                                $video_attachment_id = $video_attachment_id_array["medias"][0]->attachment_id;
                                $bp_video_url = wp_get_attachment_url($video_attachment_id);

                            }else{
                                $bp_video_url = '';
                            }

                            $activitycontent    = $activity->content;

                            ?>

                            <div class="c-singleFeedWrap">
                                <div class="c-feedUserInfo">
                                    <div class="c-userpic">
                                        <?=$avatar;?>
                                    </div>
                                    <div class="c-usernickname"><h4><?=$full_name;?> <span>@<?=$username;?> . <?=$time_diff;?> ago</span></h4></div>
                                </div>
                                <div class="displayFeedPost">

                                    <?php
                                    if($photourl != null){
                                        ?>
                                        <img src="<?=$photourl;?>" alt="" title="">
                                    <?php } ?>

                                    <?=$activitycontent;?>

                                    <?php
                                    if($bp_video_url != null){
                                        ?>
                                        <video width="100%" height="99%" controls="controls" type="video/mp4" preload="none">
                                            <source src="<?=$bp_video_url;?>" autostart="false">
                                            Your browser does not support the video tag.
                                        </video>
                                    <?php } ?>

                                </div>
                            </div>

                        <?php }

                    endif;
                    ?>

                </div>
            </div>

        </div>
    </div>




    <div class="groupMainRight">
        <div class="singleWidgetBlock widgetsList white-pad">
            <div class="c-secTitle">
                <h3>ABOUT</h3>
            </div>
            <p>
                This FBB Community Group will be the hub of all the activities in relation to your FBB course. Here, the Bamboo U Team will post any important information, announcements and achievements which relate to the course. Students are encouraged to post their questions (#ASK), queries (#TeamBambooU) and wins (#WIN) on this group’s page.

                This group will become a powerful tool throughout the course’s duration and then beyond! So make sure to engage and participate here so that you can start to build your Bamboo Community.
            </p>
        </div>


        <div class="singleWidgetBlock">
            <div class="imgWidget">
                <a href="#">
                    <img src="/wp-content/uploads/2023/03/pinpostslide.png">
                    <span>RETURN TO COURSE</span>
                </a>
            </div>
        </div>



    </div>

    <div style="clear:both"></div>
</div>

<div style="clear:both"></div>