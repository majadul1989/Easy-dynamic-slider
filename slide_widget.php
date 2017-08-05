<?php 



/*

  Plugin Name: Dynamic Easy Slider 

  Plugin URI: 

  Description: It is widget base Dynamic Easy Slider by Host Banglo.

  Version: 0.2

  Author: Majadul

  Author URI: https://profiles.wordpress.org/majadul

  License: none

/**

 * Make sure we have jquery available.

 */

function des_style() {

    wp_enqueue_script('jquery');

    wp_enqueue_style('lightslider', plugin_dir_url(__FILE__ ).'style/lightslider.css');

    wp_enqueue_script('widgets', plugin_dir_url(__FILE__ ).'style/lightslider.js');

    wp_enqueue_style('easy_slide_style', plugin_dir_url(__FILE__ ).'style/easy_slide_style.css');

}



/**

 * Enqueue frontend scripts.

 */

add_action('wp_enqueue_scripts', 'des_style');



require_once(dirname(__FILE__).'/slide-functions.php');



// Creating the widget 

class des_widget extends WP_Widget {

    function __construct() {

        parent::__construct(

        // Base ID of your widget

        'easy_widget', 



        // Widget name will appear in UI

        __('Easy Slide Widget', 'easy_widget_domain'), 



        // Widget description

        array( 'description' => __( 'It is widget base Easy Slider for Host Banglo', 'easy_widget_domain' ), )

        );

    }





// Creating widget front-end

// This is where the action happens

public function widget( $args, $instance ) {

// here widget value

$title              = apply_filters( 'widget_title', $instance['title'] );

$select_category    = apply_filters( 'widget_title', $instance['select_category'] );

$slide_number       = apply_filters( 'widget_title', $instance['slide_number'] );

$slide_type         = apply_filters( 'widget_title', $instance['slide_type']);

$css_class          = apply_filters( 'widget_title', $instance['css_class']);

$right_or_left      = apply_filters( 'widget_title', $instance['right_or_left']);

$description        = apply_filters( 'widget_title', $instance['description']);

$description_on_off        = apply_filters( 'widget_title', $instance['description_on_off']);





// before and after widget arguments are defined by themes

echo $args['before_widget'];

echo $args['before_title'] . $title . $args['after_title'];

// echo $select_category;

// This is where you run the code and display the output

 // print_r($instance);

?>

<div class="slide_me">

    <script>

        jQuery(document).ready(function() {

            var autoplaySlider = jQuery("#slide_<?php echo $args['widget_id'] ?>").lightSlider({

                item:1,

                auto:true,

                loop:true,

            <?php if ($right_or_left == 2) {?>

                    rtl:true,

                    

            <?php }else{ ?>

                    rtl:false,

               <?php } ?>

                mode: '<?php if ($slide_type == 1 ) {echo "slide";} ?>',

                pauseOnHover: true,

                pause:5000,

                speed:500,

                onBeforeSlide: function (el) {

                    jQuery('#current').text(el.getCurrentSlideCount());

                }

            });

        });



    </script>

        <ul id="slide_<?php echo $args['widget_id'] ?>" class="content-slider">

        <?php

            // the query



            $Easy_Slide_Query = new WP_Query ('cat='.esc_attr($select_category).'&posts_per_page='.esc_attr( $slide_number)); ?>



            <?php if ( $Easy_Slide_Query->have_posts() ) : ?>



              <!-- the loop -->

              <?php while ( $Easy_Slide_Query->have_posts() ) : $Easy_Slide_Query->the_post(); ?>

                    <li class="slide_li">
                     <?php if($description_on_off == 1){ ?>
                        <div class="slide_postion">




                        <h3><?php the_title ();?></h3>

                        <p>

                        <?php slide_excerpt($description)?><a href="<?php the_permalink (); ?>"><?php _e( 'Read More' ); ?></a>

                        </p>

                        </div><!-- slide_postion -->
                     <?php  }  ?>
                    <?php the_post_thumbnail('thumbnaillll');?>

                    </li>



              <?php endwhile; ?>

              <!-- end of the loop -->



              <!-- pagination here -->



              <?php wp_reset_postdata(); ?>



            <?php else:  ?>

             <li>No Post find</li>

            <?php endif; ?>       

        </ul>

</div><!-- slide_me -->

<?php 

// file include here



echo $args['after_widget'];

}



        

// Widget Backend 

public function form( $instance ) {

    $title              = $instance[ 'title' ];

    $select_category    = $instance[ 'select_category' ];

    $slide_number       = $instance[ 'slide_number' ];

    $slide_type         = $instance[ 'slide_type' ];

    $right_or_left      = $instance[ 'right_or_left' ];

    $description        = $instance[ 'description' ];
    $description_on_off         = $instance[ '$description_on_off'];




// condition 

if ( isset( $instance[ 'title' ] ) ) {

  $title;

  }else {

  $title = __( 'Slide title', 'easy_widget_domain' );

  }



  if (!$instance[ 'description' ] == "") {

     $description;

     }else{

      $description = __( 30, 'easy_widget_domain' );

     }



if (!$instance[ 'slide_number' ] == "") {

   $slide_number;

   }else{

    $slide_number = __( 4, 'easy_widget_domain' );

   }



?>

<!-- // Widget admin form -->

<!-- Widget Title -->

<p>

<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title :' ); ?></label> 

<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />

</p>

<!-- Category Dropdown Function here -->

<p>

<label for="<?php echo $this->get_field_id( 'select_category' ); ?>"><?php _e( 'Select Category :' ); ?></label>

<select class="widefat" name="<?php echo $this->get_field_name( 'select_category' ); ?>" id="<?php echo $this->get_field_name( 'select_category' ); ?>">

    <?php

        $category_ids = get_all_category_ids();

            foreach($category_ids as $select_category):

            $category_name = get_cat_name($select_category); ?>

    <option <?php if ($select_category == $instance[ 'select_category' ] ) { echo "selected";   } ?>  value="<?php echo esc_attr( $select_category ); ?>"><?php echo esc_attr($category_name); ?></option>        

      <?php endforeach; ?>

</select> 

</p>

<!-- 

Number of slide how many do you wana show your home page 

 -->

<p>

    <label for="<?php echo $this->get_field_name('slide_number');?>"><?php _e( 'Number Of Slide :' ); ?></label>

    <input type="text" class="widefat" name="<?php echo $this->get_field_name('slide_number');?>" id="<?php echo $this->get_field_name('slide_number');?>" value="<?php echo esc_attr( $slide_number); ?>">

</p>



<!-- 

Chose you won style like fade or slide

 -->



<p>

    <label for="<?php echo $this->get_field_id( 'slide_type' ); ?>"><?php _e( 'Slide Type :' ); ?></label>

    <select class="widefat" name="<?php echo $this->get_field_name( 'slide_type' ); ?>" id="<?php echo $this->get_field_name( 'slide_type' ); ?>">

        <option <?php if ($instance[ 'slide_type' ] == 1 ) { echo "selected";} ?>  value="1">Slide</option>

        <option <?php if ($instance[ 'slide_type' ] == 2) { echo "selected"; } ?> value="2">Fade</option>

    </select>

</p>

<!-- 

Chose you won style like Left or right

 -->

<p>

    <label for="<?php echo $this->get_field_id( 'right_or_left' ); ?>"><?php _e( 'Select Right or Left' ); ?></label><br>

    <label for="<?php echo $this->get_field_id( 'right_or_left' ); ?>"><?php _e( 'Ritgh to Left' ); ?></label>

    <input id="<?php echo $this->get_field_name( 'right_or_left' ); ?>" <?php if ($instance['right_or_left'] == 1 || $instance['right_or_left'] == "" ){ echo "checked";} ?> type="radio" value="1" name="<?php echo $this->get_field_name( 'right_or_left' ); ?>">



  <label for="<?php echo $this->get_field_id( 'right_or_left' ); ?>"><?php _e( 'Right to Left' ); ?></label>

  <input id="<?php echo $this->get_field_name( 'right_or_left' ); ?>" type="radio" <?php if ($instance['right_or_left'] == 2){ echo "checked";} ?> value="2" name="<?php echo $this->get_field_name( 'right_or_left' ); ?>">

</p>

<!-- 

Chose you won description word like 20,30,50 also your wish.

 -->

    <p>

        <label for="<?php echo $this->get_field_id( 'select_description' ); ?>"><?php _e( 'Select Description ON or OFF' ); ?></label><br>

        <label for="<?php echo $this->get_field_id( 'on' ); ?>"><?php _e( 'Description ON' ); ?></label>

        <input id="<?php echo $this->get_field_name( 'description_on_off' ); ?>" <?php if ($instance['description_on_off'] == 1 || $instance['description_on_off'] == "" ){ echo "checked";} ?> type="radio" value="1" name="<?php echo $this->get_field_name( 'description_on_off' ); ?>">



        <label for="<?php echo $this->get_field_id( 'off' ); ?>"><?php _e( 'Description OFF' ); ?></label>

        <input id="<?php echo $this->get_field_name( 'description_on_off' ); ?>" type="radio" <?php if ($instance['description_on_off'] == 2){ echo "checked";} ?> value="2" name="<?php echo $this->get_field_name( 'description_on_off' ); ?>">

    </p>

<p>
    <label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Number of word :' ); ?></label>

    <input class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" type="text" value="<?php echo esc_attr( $description); ?>">

</p>



<?php 

}





// Updating widget replacing old instances with new

public function update( $new_instance, $old_instance ) {

    $instance = $old_instance;
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['select_category'] = ( ! empty( $new_instance['select_category'] ) ) ? strip_tags( $new_instance['select_category'] ) : '';
    $instance['slide_number'] = ( ! empty( $new_instance['slide_number'] ) ) ? strip_tags( $new_instance['slide_number'] ) : '';
    $instance['slide_type'] = ( ! empty( $new_instance['slide_type'] ) ) ? strip_tags( $new_instance['slide_type'] ) : '';
    $instance['css_class'] = ( ! empty( $new_instance['css_class'] ) ) ? strip_tags( $new_instance['css_class'] ) : '';
    $instance['right_or_left'] = ( ! empty( $new_instance['right_or_left'] ) ) ? strip_tags( $new_instance['right_or_left'] ) : '';
    $instance['description_on_off'] = ( ! empty( $new_instance['right_or_left'] ) ) ? strip_tags( $new_instance['description_on_off'] ) : '';
    $instance['description'] = ( ! empty( $new_instance['description'] ) ) ? strip_tags( $new_instance['description'] ) : '';

return $instance;

}   

} // Class easy_widget ends here



// Register and load the widget

function easy_load_widget() {

    register_widget( 'des_widget' );

}

add_action( 'widgets_init', 'easy_load_widget' );



?>