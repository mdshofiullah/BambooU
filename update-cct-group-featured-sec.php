<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.1/css/swiper.min.css"/>

<?php
$bp_ids = bp_get_pin_posts_ids();
// var_dump($bp_ids);
$ids = $bp_ids;

// Modified By Ls Start

$group_id = bp_get_current_group_id();
// var_dump($group_id);



// Modified By Ls End

if (!empty($ids)):
    $ids_array = $ids;

// Modified by ls start
    $show_slider = false;

    // Check if any of the pinned posts have a matching group ID
    foreach ($ids_array as $id) {
        $activity = bp_activity_get_specific(array(
            'activity_ids' => $id,
        ));
        $activity = $activity['activities'][0];
        if ($activity->item_id == $group_id) {
            $show_slider = true;
            break;
        }
    }

    if ($show_slider):

// modified by ls end

//     var_dump($ids_array);
        ?>
        <style>
            .inner-activity-link .comb {
                display: inline-block;
                background: var(--bb-primary-color);
                color: #fff;
                text-align: center;
            }

            .pinPostImg.comb {
                background: #dad7c4;
            }

            .c-featuredSec .feturedPostsSlider-wrap .singlePinPost .pinPostText {
                background-color: #dad7c4;
                color: #000;
                justify-content: flex-start;
            }

            .c-feturedPostsSlider img.avatar.user--avatar.avatar-150.photo {
                width: 50px;
            }

            .activity-avatar.item-avatar img {
                max-width: 40px;
            }
            .activity-avatar.item-avatar a {
                display: flex;
            }

            .swiper-wrapper img.avatar.group-80-avatar.avatar-20.photo {
                max-width: 22px;
            }

            .swiper-wrapper .bp-activity-head {
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                margin-bottom: 15px;
                background: #dad7c4;
                color: #000;
            }

            .swiper-wrapper a.view.activity-time-since {
                display: block !important;
            }

            .swiper-wrapper .activity-header * {
                color: #3f3f3f !important;
            }

            .swiper-wrapper .content {
                color: #585858;
            }
            .swiper-wrapper .hashtag{
                color: #585858!important;
            }
            h3.display-name {
                text-transform: capitalize;
                padding: 10px;
                margin: 0!important;
                font-size: 18px;
            }
            .activity-header {
                display: flex;
                flex-wrap: wrap;
            }
            .activity-header span {
                margin: 5px;
            }
            .swiper-wrapper .bp-activity-head {
                flex-direction: column;
                width: 100%;
            }
            a.activity-image {
                overflow: hidden;
            }
            .pinPostText a {
                overflow: hidden;
            }
        </style>
        <div class="c-featuredSec">
            <div class="c-secTitle"><h3>Featured</h3></div>
            <div class="feturedPostsSlider-wrap">
                <div class="swiper-button-next swiperButton"></div>
                <div class="swiper-button-prev swiperButton"></div>

                <div class="c-feturedPostsSlider">
                    <div class="swiper-wrapper">


                        <?php
                        $comma = implode(',', $ids_array);
                        ?>
                        <?php if (bp_has_activities(bp_ajax_querystring('activity') . "&include=$comma")) : ?>

                            <?php
                            while (bp_activities()) :
                                bp_the_activity();
                                $activity_id = bp_get_activity_id();
// 					Modified By Ls Start
                                if (bp_get_activity_item_id() == $group_id):
// 					Modified By Ls End
                                    $user        = get_user_by('id', bp_get_activity_user_id());
                                    //var_dump($user->display_name);
                                    ?>
                                    <div id="swiper-wrapper-<?= $activity_id; ?>" class="singlePinPost swiper-slide">

                                        <?php
                                        $activity = bp_activity_get_specific(array(
                                            'activity_ids' => $activity_id,
                                        ));
                                        $activity = $activity['activities'][0];
                                        /* --------Get the media id or ids by $activity_id -------*/
                                        $activity_meta = bp_activity_get_meta($activity_id);

                                        if (array_key_exists("bp_video_ids", $activity_meta)) {
//                                 modified by ls start
// 									if (array_key_exists("bp_video_ids", $activity_meta)&& in_array($ids, $group_id)) {
// 										Modified by ls end

                                            $bp_video_ids              = $activity_meta['bp_video_ids'][0]; // this is video id
                                            $video_attachment_id_array = BP_Media::get($args = array(
                                                'in'    => $bp_video_ids,
                                                'video' => true,
                                            ));
                                            $video_attachment_id       = $video_attachment_id_array["medias"][0]->attachment_id;
                                            $bp_video_url              = wp_get_attachment_url($video_attachment_id);
                                            ?>
                                            <div class="pinPostImg">
                                                <video width="100%" height="99%" controls="controls" type="video/mp4"
                                                       preload="none">
                                                    <source src="<?= $bp_video_url ?>" autostart="false">
                                                    Your browser does not support the video tag.
                                                </video>


                                            </div>
                                            <?php
                                        } elseif (array_key_exists("bp_media_ids", $activity_meta)) {
//                                 Modified by ls start
// 										} elseif (array_key_exists("bp_media_ids", $activity_meta)&& in_array($ids, $group_id)) {
// 										modified by ls end

                                            $bp_photo_ids = $activity_meta['bp_media_ids'][0];  // get 1st photo id
                                            $bp_get_image = bp_media_get_specific(array('media_ids' => $bp_photo_ids));
                                            $photo_url    = $bp_get_image["medias"][0]->attachment_data->thumb;

                                            ?>
                                            <div class="pinPostImg-- pinPostText comb">
                                                <div class="bp-activity-head">
                                                    <div class="activity-avatar item-avatar">
                                                        <a href="<?php bp_activity_user_link(); ?>" class="active-user-link"><?php bp_activity_avatar(array('type' => 'full')); ?>
                                                            <h3 class="display-name"><?php esc_html_e($user->display_name); ?></h3>
                                                        </a>
                                                    </div>

                                                    <div class="activity-header">
                                                        <p>@<?php echo $user->user_login; ?><span>&#8226;</span></p>
                                                        <a href="<?php echo esc_url(bp_activity_get_permalink(bp_get_activity_id())); ?>"><?php echo bp_core_time_since(bp_get_activity_date_recorded()); ?></a>
                                                    </div>
                                                </div>
                                                <a class="activity-image" href="#activity-<?php bp_activity_id(); ?>">
                                                    <?php if (!empty($activity->content)) { ?>
                                                        <div class="content">
                                                            <p><?php echo wp_trim_words($activity->content , 6); ?></p>
                                                        </div>
                                                    <?php } ?>
                                                    <img src="<?php echo $photo_url ?>" alt="Post Image">
                                                </a>
                                            </div>
                                            <?Php
                                        } elseif (array_key_exists("bp_document_ids", $activity_meta)) {
//                                 modified by ls start
// 										} elseif (array_key_exists("bp_document_ids", $activity_meta)&& in_array($ids, $group_id)) {
// modified by ls end
                                            $bp_document_ids       = $activity_meta['bp_document_ids'];
                                            $bp_doc_atach_id_array = bp_media_get_specific(array('bp_document_ids' => $bp_document_ids));
                                            global $wpdb;
                                            $attachments_id = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}bp_documentWHERE activity_id = {$activity_id}");
                                            foreach ($attachments_id as $attachment) {

                                                $attach_id    = $attachment->attachment_id;
                                                $attach_title = $attachment->title;
                                            }
                                            // $document_url now contains the URL of the attached document
                                            $attach_url = wp_get_attachment_url($attach_id);
                                            ?>
                                            <div class="pinPostText">
                                                <div class="bp-activity-head">
                                                    <div class="activity-avatar item-avatar">
                                                        <a href="<?php bp_activity_user_link(); ?>"><?php bp_activity_avatar(array('type' => 'full')); ?>
                                                            <h3 class="display-name"><?php esc_html_e($user->display_name); ?></h3>
                                                        </a>
                                                    </div>

                                                    <div class="activity-header">
                                                        <p>@<?php echo $user->user_login; ?><span>&#8226;</span></p>
                                                        <a href="<?php echo esc_url(bp_activity_get_permalink(bp_get_activity_id())); ?>"><?php echo bp_core_time_since(bp_get_activity_date_recorded()); ?></a>
                                                    </div>
                                                </div>

                                                <?php echo '<br><b><a href="' . $attach_url . '">' . $attach_title . '</a></b>'; ?>

                                            </div>

                                            <?php
                                        } else { ?>

                                            <div class="pinPostText">
                                                <div class="bp-activity-head">
                                                    <div class="activity-avatar item-avatar">
                                                        <a href="<?php bp_activity_user_link(); ?>"><?php bp_activity_avatar(array('type' => 'full')); ?>
                                                            <h3 class="display-name"><?php esc_html_e($user->display_name); ?></h3>
                                                        </a>
                                                    </div>

                                                    <div class="activity-header">
                                                        <p>@<?php echo $user->user_login; ?><span>&#8226;</span></p>
                                                        <a href="<?php echo esc_url(bp_activity_get_permalink(bp_get_activity_id())); ?>"><?php echo bp_core_time_since(bp_get_activity_date_recorded()); ?></a>
                                                    </div>
                                                </div>
                                                <a href="#activity-<?php bp_activity_id(); ?>">
                                                    <div class="content">
                                                        <p><?php echo $activity->content ?></p>
                                                    </div>
                                                </a>
                                            </div>
                                            <?php
                                        }

                                        ?>
                                    </div>
                                    <!-- Modified by Ls start -->
                                <?php endif; ?>
                                <!-- 			Modified by ls End		 -->
                            <?php endwhile; ?>

                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
        <!-- Modified by ls start -->
    <?php endif; ?>
    <!-- Modified by ls end -->
<?php endif; ?>



<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.1/js/swiper.min.js"></script>
<script type="text/javascript">
    // group page featured slider: Slider
    new Swiper('.c-feturedPostsSlider', {
        slidesPerView: 4,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        paginationClickable: true,
        loop: false,
        autoHeight: true,
        spaceBetween: 15,
        breakpoints: {
            1920: {
                slidesPerView: 4,
                spaceBetween: 15
            },
            1199: {
                slidesPerView: 3,
                spaceBetween: 15
            },
            992: {
                slidesPerView: 3,
                spaceBetween: 10
            },
            767: {
                slidesPerView: 1,
                spaceBetween: 10,
            }
        }
    });
</script>


<script type="text/javascript">

    /*
jQuery(document).ready(function($) {
$( '#bp-pin-post' ).on( 'click', function( event ){
    alert('dusna');

});




jQuery(document).ready(function($) {
    $('.bp-pin-post10313').click(function() {

        alert('dusna');

        jQuery.ajax({
         type: 'POST',
          url: '../wp-admin/admin-ajax.php',
         data: {
          "action": "bp_pin_post_action",
          bp_id : '10311',           // PHP: $_POST['first_name']
          to_do  : 'pin',
          nonce  : '5d67bd8856',
         }, success: function(data){

          jQuery('.inpost-btn').html(data);
        }
    });
    return false;
    }
});
*/
</script>

<script type="application/javascript">
    jQuery(document).ready(function ($) {
        $(document).on('click', '.bp-pin-post', function (event) {
            event.preventDefault();

            var button = $(this);
            var bp_id = button.data('bp_id');
            var to_do = button.data('to_do');
            var nonce = button.data('nonce');
            var data = {
                'action': 'bp_pin_post_action',
                'bp_id': bp_id,
                'to_do': to_do,
                'nonce': nonce,
                'cache_bust': Date.now()
            };
            $.post(ajaxurl, data, function (response) {


                var pin_data = JSON.parse(response);
                var response_pin = pin_data.response_pin;
                var photo_url = pin_data.photo_url;
                var bp_content = pin_data.bp_content;
                var bp_video_url = pin_data.bp_video_url;


                if (response_pin === 'pinned') {
                    button.html('<b class="inpost-btn"><span class="dashicons dashicons-sticky" style="color:red;"></span>Unpin Featured</b>');
                    button.data('to_do', 'unpin');
                    button.show();

                    //var latestPosts = <?php echo json_encode(get_latestposts($bp_id)); ?>;
                    var sliderContainer = $('.swiper-wrapper');

                    var sliderItems = '';
                    //$.each(latestPosts, function(index, post) {
                    sliderItems += '<div id="swiper-wrapper-' + bp_id + '" class="singlePinPost swiper-slide" style="width: 256.25px; margin-right: 15px;">';


                    if (bp_video_url != null) {
                        sliderItems += '<div class="pinPostImg"><video width="100%" height="99%" controls="controls" type="video/mp4" preload="none"><source src="' + bp_video_url + '" autostart="false">Your browser does not support the video tag.</video></div>';
                    } else if (photo_url != null) {
                        sliderItems += '<div class="pinPostImg"><img src="' + photo_url + '" alt=""></div>';
                    } else {
                        sliderItems += '<div class="pinPostText">' + bp_content + '</div>';
                    }


                    sliderItems += '</div>';

                    //});

                    // Add the slider items to the slider container
                    sliderContainer.prepend(sliderItems);


                } else if (response_pin === 'unpinned') {
                    button.html('<span class="dashicons dashicons-sticky" style="color:green;"></span>Pin to Featured');
                    button.data('to_do', 'pin');
                    button.show();

                    var sliderContainer = $('#swiper-wrapper-' + bp_id + '');


                    $(sliderContainer).remove();

                } else {
                    console.log('Error: ' + response);
                }
            });
        });
    });

    /*

    var bp_id = '<?php echo $bp_id; ?>';
        var data_nonce = '<?php echo $nonce; ?>';

        jQuery( document ).ready( function() {
        jQuery( '.bp-pin-post-<?php echo $bp_id; ?>' ).click( function( e ) {



            $.ajax({
              type: "POST",
              url: '/wp-admin/admin-ajax.php',
              data: {
                  action: 'get_latestposts',
                  bp_id : '<?php echo $bp_id; ?>',
                  to_do  : 'pin',
                  nonce  : '<?php echo $nonce; ?>',
                 },
              dataType: "json",
              encode: true,
            }).done(function (data) {
              console.log(data);
            });

            $(".bp-pin-post-<?php echo $bp_id; ?>").hide();
            $(".inpost-btn-<?php echo $bp_id; ?>").show();

            //event.preventDefault();




        });
    });

    */


</script>
<?php
function get_latestposts($bp_id) {
    $bp_id = $_POST['bp_id'];

    /*
    global $bp, $wpdb;

    $args = array(
         'post_type' => 'post',
         'post_id' => $post_id,
         'orderby' => 'date',
         'order' => 'DESC'
     );
     $query = new WP_Query($args);
     return $query->posts;
     */

    $activity_to_reshare = bp_activity_get_specific('activity_ids=' . $bp_id);
    $activitys           = $activity_to_reshare['activities'][0];


    $activitymeta = bp_activity_get_meta($bp_id);

    if (array_key_exists("bp_media_ids", $activitymeta)) {
        $bp_photoids = $activitymeta['bp_media_ids'][0];  // get 1st photo id
        $bp_getimage = bp_media_get_specific(array('media_ids' => $bp_photoids));
        $photourl    = $bp_getimage["medias"][0]->attachment_data->thumb;
    }


    if (array_key_exists("bp_video_ids", $activitymeta)) {

        $bp_video_ids              = $activitymeta['bp_video_ids'][0]; // this is video id
        $video_attachment_id_array = BP_Media::get($args = array(
            'in'    => $bp_video_ids,
            'video' => true,
        ));
        $video_attachment_id       = $video_attachment_id_array["medias"][0]->attachment_id;
        $bp_video_url              = wp_get_attachment_url($video_attachment_id);

    }


    $activitycontent = $activitys->content;

    $data = array(
        'bp_url'       => $photo_url,
        'bp_content'   => $activitycontent,
        'bp_video_url' => $bp_video_url
    );
    //return json_encode($activity_content);
    //return array('data' => $data);

    //wp_reset_postdata();
    echo $bp_id;
}

?>