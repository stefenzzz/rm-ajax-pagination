<?php



add_action( 'wp_ajax_rm_video_pagination', 'rm_videos_paginate' );
add_action( 'wp_ajax_nopriv_rm_video_pagination', 'rm_videos_paginate' );

function rm_videos_paginate(){

    global $post;

    if ( check_ajax_referer( 'rm-pagination-obj', 'nonce' ) ) {

      

        $paged = ( $_GET['paged'] ) ? $_GET['paged'] : 1;
        $post_per_page = $_GET['post_per_page'];
        



      
        
    if( filter_var($paged, FILTER_VALIDATE_INT ) && filter_var($post_per_page, FILTER_VALIDATE_INT ) ) {
       
        $args = array(
            'post_type' => 'video',
            'paged' => $paged,
            'posts_per_page' => $post_per_page,
            'orderby'=>'date',
            'order'=>'DESC',
            'post_status' =>'publish'
        );

        $query = new WP_Query( $args );

        $post_count = $query->found_posts;
   
       
       
        $max_num_pages = $query->max_num_pages;
       
           
        if( $query->have_posts() ) :

            $html = '<div class="grid-three-cols gap-medium">';

            while( $query->have_posts() ) :    $query->the_post();
             
              
            $categories = get_the_category();

            $category = ($categories) ? $categories[0]->cat_name: '';
            
       

                
                $html .= '<div class="video-player">
    
                            <div class="video-container">
                                <div class="lazy-loading-content image-container">
                        
                                <img src="'.fetch_highest_res(get_post_meta($post->ID, '_rm_youtube_id', true)).'" class="video-thumbnail" loading="lazy"/>
                                <div class="play-button-circle"></div>
                                </div>
                            </div>
                
                            <div class="video-content">
                                <h3 class="video-title">'.get_the_title().'</h3>
                                <span class="meta">'.get_the_author_meta('nickname').' | '.get_the_date('F j, Y').' | '.$category.'</span>
                            </div>   

                        </div>';
            endwhile;
            wp_reset_postdata();

            $html .='</div>';

       

       

               
                $big = 99999999;
                $html .='<div class="rm-pagination">'; 
              
                $html .=  paginate_links( array(
                    'base' => str_replace( $big, '%#%', html_entity_decode( esc_url(get_pagenum_link( $big )) ) ),
                    'format' => '?paged=%#%',
                    'current' => $paged,
                    'total' => $max_num_pages, //$q is your custom query
                    'prev_text'    => __('<span class="rm-pagination-prev"></span>'),
                    'next_text'    => __('<span class="rm-pagination-next"></span>'),
                    ) );
                $html .= '</div>';    
                
            wp_reset_query();
                  
     
          
        endif;
      
    }
    



        wp_send_json(
            [
                'post' => $html,
                
            ]
        
        );
    }
   
}



