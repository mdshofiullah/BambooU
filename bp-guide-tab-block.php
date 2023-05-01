<div class="row guide-row">
    <div class="col col-md-3 left">
        <div class="first-sec">
            <h4>Guides</h4>



            <?php
            $user = wp_get_current_user();
            $allowed_roles = array( 'group_leader', 'administrator');
            //var_dump($user);
            if ( array_intersect( $allowed_roles, $user->roles ) || !is_user_logged_in())
            {
                ?>
                <a href="?task=newguide">
                    <button class="cpostfeed">NEW GUIDE</button>
                </a>
                <?php
            }
            ?>

            <?php

            $total_complete = bp_get_totalcomplete_posts_ids();
            $total_post = bp_get_totalpostguide_posts_ids();

            $percentage = ($total_complete / $total_post) * 100;

            ?>


            <?=$total_complete;?>/<?=$total_post;?> required guides completed
            <div class="progress">
                <div class="progress-bar" style="width: <?=$percentage;?>%;" role="progressbar" aria-valuenow="<?=$percentage;?>" aria-valuemin="0" aria-valuemax="<?=$total_post;?>"></div>
            </div>




        </div>



        <?php
        $categories = get_categories( array(
            'post_type' => 'fundamentals-guides',
            'hide_empty' => false, // Display empty categories
            'taxonomy' => 'category',
        ) );

        foreach ( $categories as $category ) {
        $args = array(
            'post_type' => 'fundamentals-guides',
            'posts_per_page' => -1, // Display all posts
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'slug',
                    'terms' => $category->slug,
                ),
            ),
        );

        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
        ?>
        <div class="second-sec">
            <?=$category->description;?>
            <h5><?=$category->name;?></h5>

            <?php
            // Modified By LS Start
            $count = 0;
            // Modified by LS End
            while ( $query->have_posts() )
            {
            $query->the_post();

            $excerpt = wp_strip_all_tags(get_the_excerpt());
            $limit = 50; // Set the maximum length of the excerpt
            $short_description = substr($excerpt, 0, $limit); // Limit the excerpt to the specified length
            if (strlen($excerpt) > $limit) {
                $short_description .= '...'; // Add ellipsis if the excerpt is longer than the limit
            }

            // Modified By LS Start
            $count++;
            // Modified by LS End
            ?>

            <!--  Modified By LS Start -->
        <?php
        if($count > 5) { ?>

            <div class="row guide-1" style="display:none">
                <?php } else { ?>
                <div class="row guide-1">
                    <?php } ?>

                    <!-- 						 Modified by LS End -->
                    <!-- 		<div class="row guide-1"> -->
                    <div class="column-left mt-1 groupicon ">
                        <a href="#<?php echo get_the_ID(); ?>"><img src="/wp-content/uploads/2023/03/octicon_note-24.svg" class="img-fluid img-responsive cat-image" /></a>
                    </div>
                    <div class="column-right mt-1">
                        <a href="#<?php echo get_the_ID(); ?>"><h5 class="mb-0"><?php echo the_title(); ?></h5></a>
                        <p class="text-justify text-truncate para mb-0">
                            <?php
                            if (!empty($short_description))
                            {
                                echo $short_description;
                            }
                            ?>
                        </p>
                    </div>
                </div>



                <?php
                }
                // 		Modified by LS Start
                // Check if there are more than 5 posts and display "See More" button if true
                $post_count = $query->post_count;
                if ($count > 5) { ?>
                    <div class="see-more">
                        <button class="see-more-btn">See More</button>
                        <script>
                            var seeMoreBtns = document.querySelectorAll('.see-more-btn');
                            seeMoreBtns.forEach(function(btn) {
                                var allPosts = btn.closest('.second-sec').querySelectorAll('.guide-1');
                                var showPosts = 5;
                                btn.addEventListener('click', function() {
                                    if (this.innerHTML === 'See More') {
                                        for (var i = showPosts; i < allPosts.length; i++) {
                                            allPosts[i].style.display = 'block';
                                        }
                                        this.innerHTML = 'Less Posts';
                                    } else {
                                        for (var i = showPosts; i < allPosts.length; i++) {
                                            allPosts[i].style.display = 'none';
                                        }
                                        this.innerHTML = 'See More';
                                    }
                                });
                            });
                        </script>
                    </div>
                <?php }

                // 		Modified by LS End
                ?>


            </div>


            <?php

            }


            wp_reset_postdata(); // Reset post data for each category
            }




            ?>



        </div>



        <div class="col col-md-9">



            <?php
            if (isset($_GET['task']) && ($_GET['task']=='newguide') )
            {
                $nonce = wp_create_nonce("bp_guide_post_action_nonce");
                ?>
                <div class="guide-details">
                    <div class="postGroupActivity">
                        <form method="post" action="" class="post-form" name="cat">
                            <input type="hidden" name="nonce" value="<?=$nonce;?>">
                            <div class="c-postfeedField">
                                <input type="text" class="text-field" name="cat_name" id="cat_name" placeholder="What’s the subject of this guide?" required>
                            </div>
                            <div class="c-postfeedField-options">
                                <ul>
                                    <li></li>
                                </ul>
                                <input type="submit" name="submit_cat" value="SAVE" class="cpostfeed">
                            </div>

                        </form>
                    </div>

                    <?php

                    if (isset($_POST['submit_cat'])) {

                        if ( !wp_verify_nonce( $_POST['nonce'], "bp_guide_post_action_nonce")) {
                            exit("No naughty business please");
                        }


                        $cat_name = sanitize_text_field($_POST['cat_name']);

                        $term = wp_insert_term(
                            $cat_name,
                            'category',
                            array(
                                'description'=> $cat_name,
                                'slug' => '',
                                'parent' => 0
                            )
                        );

                        if (is_wp_error($term)) {
                            echo 'There was an error creating the Guides.';
                        } else {
                            echo 'Guide Category created successfully.';
                        }

                    }
                    ?>

                </div>
                <?php
            }
            ?>



            <?php
            $categories = get_categories( array(
                'post_type' => 'fundamentals-guides',
                'hide_empty' => false, // Display empty categories
                'taxonomy' => 'category',
            ) );

            foreach ( $categories as $category ) {
                $args = array(
                    'post_type' => 'fundamentals-guides',
                    'posts_per_page' => -1, // Display all posts
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'category',
                            'field' => 'slug',
                            'terms' => $category->slug,
                        ),
                    ),
                );

                ?>

                <div class="guide-details" id="<?=$category->term_id;?>">
                    <?=$category->description;?>



                    <?php

                    $user = wp_get_current_user();
                    $allowed_roles = array( 'group_leader', 'administrator');
                    //var_dump($user);
                    if ( array_intersect( $allowed_roles, $user->roles ) || !is_user_logged_in())
                    {
                        $nonce = wp_create_nonce("bp_guide_post_action_nonce");
                        ?>

                        <div class="postGroupActivity">
                            <form method="post" action="" class="post-form" name="guide-post<?=$category->term_id;?>">
                                <input type="hidden" name="term_id" value="<?=$category->term_id;?>">
                                <input type="hidden" name="nonce" value="<?=$nonce;?>">

                                <div class="c-postfeedField">
                                    <input type="text" class="text-field" name="post_title" id="post_title" placeholder="Give your post a title" required>
                                </div>
                                <div class="c-postfeedField">
                                    <textarea class="cat-post-area" name="post_content" placeholder="Write something..." required></textarea>
                                </div>
                                <div class="c-postfeedField-options">
                                    <ul>
                                        <li></li>
                                    </ul>
                                    <input type="submit" name="submit_post" value="POST" class="cpostfeed mt-1">
                                </div>

                            </form>
                        </div>
                        <?php

                        $term_id = $category->term_id;

                        if ( isset($_POST['submit_post']) &&  ($term_id==$_POST['term_id']) )
                        {

                            if ( !wp_verify_nonce( $_POST['nonce'], "bp_guide_post_action_nonce")) {
                                exit("No naughty business please");
                            }


                            $post_title = sanitize_text_field($_POST['post_title']);
                            $post_content = sanitize_text_field($_POST['post_content']);
                            $term_id = sanitize_text_field($_POST['term_id']);

                            $new_post = array(
                                'post_title' => $post_title,
                                'post_content' => $post_content,
                                'post_excerpt' => '',
                                'post_status' => 'publish',
                                'post_author' => get_current_user_id(), // Replace with the ID of the logged-in user or use wp_get_current_user()->ID
                                'post_type' => 'fundamentals-guides',
                                'tax_input'    => array(
                                    "category" => $term_id //Video Cateogry is Taxnmony Name and being used as key of array.
                                ),
                            );

                            $post_id = wp_insert_post( $new_post );

                            if ( $post_id ) {
                                echo 'Guide post created successfully.';
                            } else {
                                echo 'There was an error creating the post.';
                            }
                        }


                    }
                    ?>






                    <h5><?=$category->name;?></h5>

                    <?php

                    $cat_id = $category->term_id;
                    $total_complete = bp_get_total_guide_posts_ids($cat_id);
                    $total_post = bp_get_totalpost_guide_posts_ids($cat_id);

                    $percentage = ($total_complete / $total_post) * 100;

                    ?>


                    <?=$total_complete;?>/<?=$total_post;?> posts completed
                    <div class="progress">
                        <div class="progress-bar" style="width: <?=$percentage;?>%;" role="progressbar" aria-valuenow="<?=$percentage;?>" aria-valuemin="0" aria-valuemax="<?=$total_post;?>"></div>
                    </div>



                    <?php
                    $query = new WP_Query( $args );

                    if ( $query->have_posts() ) {

                        while ( $query->have_posts() )
                        {
                            $query->the_post();
                            ?>
                            <div class="recent-guide" id="<?php echo get_the_ID(); ?>">




                                <div class="row ">
                                    <div class="column-right mt-1">
                                        <h6><?php echo the_title(); ?></h6>
                                    </div>
                                    <div class="column-left mt-1 text-right">
                                        <?php




                                        $nonce = wp_create_nonce("bp_guide_post_action_nonce");
                                        $post_id =  get_the_ID();
                                        $cat_id = $category->term_id;
                                        $user_id = get_current_user_id();

                                        $data_check = bp_check_guide_id_already_stricky($cat_id, $post_id, $user_id);

                                        $bp_guide_link = admin_url('admin-ajax.php?action=bp_guide_post_action&post_id='.$post_id.'&cat_id='.$cat_id.'&bp_id=1&nonce='.$nonce);

                                        if($data_check==0){
                                            ?>

                                            <form method="post" action="#<?php echo get_the_ID(); ?> " class="post-form" name="complete-post-<?php echo get_the_ID(); ?>">
                                                <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
                                                <input type="hidden" name="cat_id" value="<?=$category->term_id;?>">
                                                <input type="hidden" name="bp_id" value="1">
                                                <input type="hidden" name="nonce" value="<?=$nonce;?>">
                                                <input type="hidden" name="user_id" value="<?=$user_id;?>">

                                                <button name="submit_post_done" value="Done" type="submit" class="submit_button"><i class="fa fa-check" aria-hidden="true"></i> Done</button>
                                            </form>
                                            <?php
                                        }else{
                                            ?>
                                            <button class="done_button">Done</button>
                                            <?php
                                        }
                                        ?>



                                    </div>
                                </div>




                                <?php echo the_content(); ?>
                            </div>


                            <?php
                        }
                    }

                    ?>
                </div>
                <?php
                wp_reset_postdata(); // Reset post data for each category
            }
            ?>






        </div>
    </div>



<?php
function create_db_bp_guide_posts(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'bp_guide_posts';

    // Check if the table already exists
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
    {
        // If the table doesn't exist, create it
        $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        cat_id INT NOT NULL,
        post_id INT NOT NULL,
        bp_id INT NOT NULL,
        user_id INT NOT NULL,

        PRIMARY KEY (id)
        );";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }



}



if( isset($_POST['submit_post_done']) )
{
    global $wpdb;


    if ( !wp_verify_nonce( $_POST['nonce'], "bp_guide_post_action_nonce")) {
        exit("No naughty business please");
    }

    $cat_id = $_POST["cat_id"];
    $post_id = $_POST["post_id"];
    $bp_id = $_POST["bp_id"];
    $nonce = $_POST["nonce"];


    $user_id = $_POST["user_id"];

    if (isset($cat_id) && isset($post_id) &&  isset($bp_id) &&  isset($nonce) ):
        create_db_bp_guide_posts();

        $data_check = bp_check_guide_id_already_stricky($cat_id,$post_id, $user_id);



        if ($data_check == 0) {
            $table_name = $wpdb->prefix . 'bp_guide_posts';
            $data = array(
                'cat_id' => $cat_id,
                'post_id' => $post_id,
                'bp_id' => $bp_id,
                'user_id' => $user_id

            );

            $wpdb->insert( $table_name, $data );

        }
        else{
            echo 'Guide already Done!';
        }

    endif;

    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $result = json_encode($result);
        echo $result;
        //wp_send_json($result);
    }
    else {
        header("Location: ".$_SERVER["HTTP_REFERER"]);
    }
    die();


}





function bp_check_guide_id_already_stricky($cat_id,$post_id, $user_id){

    global $wpdb;
    $table_name = $wpdb->prefix . 'bp_guide_posts';
    $record_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE cat_id = %s AND post_id=%s AND user_id=%s", $cat_id, $post_id, $user_id) );
    if ( $record_count > 0 )
    {
        $result = 1;
    } else {
        $result = 0;
    }
    return $result;

}




function bp_guide_post_security() {
    echo "Sorry! You are unauthorized.";
    die();
}



function bp_get_total_guide_posts_ids($cat_id){
    global $wpdb;
    $user_id = get_current_user_id();
    $table_name = $wpdb->prefix . 'bp_guide_posts';
    $record_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE cat_id = %s AND bp_id=1 AND user_id=%s", $cat_id, $user_id) );
    if ( $record_count > 0 )
    {
        return $record_count;
    } else {
        return 0;
    }



}


function bp_get_totalcomplete_posts_ids(){
    global $wpdb;

    $user_id = get_current_user_id();
    $table_name = $wpdb->prefix . 'bp_guide_posts';
    $record_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE bp_id=1 AND user_id=%s", $user_id) );

    if ( $record_count > 0 )
    {
        return $record_count;
    } else {
        return 0;
    }

}



function bp_get_totalpost_guide_posts_ids($cat_id){
    global $wpdb;

    $args = array(
        'post_type' => 'fundamentals-guides',
        'cat' => $cat_id, // replace $category_id with the category ID you want to count
        'posts_per_page' => -1, // set to -1 to get all posts
        'post_status' => 'publish'
    );

    $query = new WP_Query($args);
    $total_posts = $query->found_posts;
    return $total_posts;

}


function bp_get_totalpostguide_posts_ids(){
    global $wpdb;

    $args = array(
        'post_type' => 'fundamentals-guides',
        'posts_per_page' => -1, // set to -1 to get all posts
        'post_status' => 'publish'
    );

    $query = new WP_Query($args);
    $total_posts = $query->found_posts;
    return $total_posts;

}





?>