<?php



add_action( 'wp_ajax_rm_post_pagination', 'rm_post_paginate' );
add_action( 'wp_ajax_nopriv_rm_post_pagination', 'rm_post_paginate' );

function rm_post_paginate(){

    if ( check_ajax_referer( 'rm-pagination-obj', 'nonce' ) ) {

      

        $paged = ( $_GET['paged'] ) ? $_GET['paged'] : 1;
        $post_per_page = $_GET['post_per_page'];
       



      
        
    if( filter_var($paged, FILTER_VALIDATE_INT ) && filter_var($post_per_page, FILTER_VALIDATE_INT ) ) {
       
        $args = array(
            'post_type' => 'post',
            'paged' => $paged,
            'posts_per_page' => $post_per_page,
            'post_status' =>'publish'
        );

        $query = new WP_Query( $args );

        $post_count = $query->found_posts;
   
       
       
        $max_num_pages = $query->max_num_pages;
       
           
        if( $query->have_posts() ) :

            $html = '<div class="grid-two-cols gap-medium">';

            while( $query->have_posts() ) :    $query->the_post();
             
                        
                $categories = get_the_category();

                $category = ($categories) ? $categories[0]->cat_name: '';
                $category_link = ($categories) ?   get_category_link($categories[0]->term_id) : '';

                $img = (get_the_post_thumbnail_url()) ? '<img decoding="async" src="'.get_the_post_thumbnail_url().'">' : '';
                
                $html .= '<div class="rm-article">

                                <div class="article-image-container">
                                    '.$img.'
                                </div>

                                <div class="article-content-container">
                                    <h3 class="article-title">
                                        '.get_the_title().'
                                    </h3>
                                    <span class="article-meta">'.get_the_author_meta('nickname').' | '.get_the_date('F j, Y').' | <a href="'.$category_link.'" target="_blank">'.$category.'</a></span>
                                    <div class="article-content">
                                        '.get_the_excerpt().'
                                    </div>
                                        <a href="'.get_the_permalink().'" class="article-read-more">Read the Article →</a>

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